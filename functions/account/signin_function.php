<?php
/*
* Authentication file for signIn for the Webapp
*/
// include functions file
require_once __DIR__ . '/../functions.php';


// Assuming the form data is submitted via POST
if (isset($_POST['signin'])) {

  // Use CSRF protection on this form
  $csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
  try {
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
      // Reset the CSRF token
      unset($_SESSION['csrf_token']);
      // send message to form page with error message
      $_SESSION['errorsession'] = "CSRF token validation failed.";
      redirect('signin.php');
      exit();
    }
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  }

  // Get the form data
  $email = $_POST['email'];
  $password = $_POST['password'];
  $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === 'on';

  // Check if any field is empty
  if (empty($email) || empty($password)) {
    $_SESSION['errorsession'] = "All Fields are Necessary.";
    redirect('signin.php');
    exit();
  }

  // Check if the email is valid
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errorsession'] = "Check your email.";
    redirect('signin.php');
    exit();
  }

  // If all checks pass, SignIn
  signIn($email, $password, $remember_me);
}

// Function to SignIn the user
function signIn($email, $password, $remember_me): void
{
  try {
    global $db;
    // Check if the email is registered
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      // Email doesn't exist, display a generic error message
      throw new Exception('Incorrect Username or Password');
    } else {
      // Check if the password matches
      if (password_verify($password, $user['password'])) {
        // Password is correct, so start a new session
        session_start();
        // Store data in session variables
        $_SESSION["authenticated"] = true;
        $_SESSION["user_id"] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION["email"] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['profile_image'] = $user['profile_image'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_since'] = $user['user_since'];

        // Generate a random session token
        $session_token = bin2hex(random_bytes(16));

        // Check if a session already exists for the user
        $sql = "SELECT * FROM sessions WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user['id']]);
        $existing_session = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_session) {
          // Update the existing session token and expiration time
          $session_token = $existing_session['session_token'];
          $sql = "UPDATE sessions SET session_token = ?, expires_at = ? WHERE id = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$session_token, date('Y-m-d H:i:s', strtotime('+30 days')), $existing_session['id']]);
        } else {
          // Store the session token in the database
          $sql = "INSERT INTO sessions (user_id, session_token, expires_at) VALUES (?, ?, ?)";
          $stmt = $db->prepare($sql);
          $stmt->execute([$user['id'], $session_token, date('Y-m-d H:i:s', strtotime('+30 days'))]);
        }

        // Set the session token cookie securely
        $cookie_options = array(
          'expires' => time() + (30 * 24 * 60 * 60), // Expires in 30 days
          'path' => '/',
          'secure' => true, // Only send cookie over HTTPS
          'httponly' => true, // Prevent JavaScript from accessing the cookie
          'samesite' => 'Strict' // Prevent cross-site request forgery attacks
        );
        setcookie('session_token', $session_token, $cookie_options);

        // Set a long-lived cookie if "Remember Me" is checked
        if ($remember_me) {
          $cookie_name = "remember_me";
          $cookie_value = $user['id'] . ':' . bin2hex(random_bytes(32));
          setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/", "", true, true); // 30 days, secure, httponly
        }

        // Check if the password needs to be rehashed
        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT)) {
          // Set the new password
          $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);
          // Update the user in the database
          $sql = "UPDATE users SET password = ? WHERE id = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute([$new_hashed_password, $user['id']]);
        }
        // Redirect to the home page if role is user else redirect to the admin dashboard
        if ($_SESSION['role'] === 'user') {
          redirect('index.php');
        } else {
          redirect('admin/dashboard.php');
        }
      } else {
        // Password is not valid, display a generic error message
        throw new Exception('Incorrect Username or Password');
      }
    }
  } catch (Exception $e) {
    // send message to form page with error message
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  } finally {
    $db = null;
  }
}
