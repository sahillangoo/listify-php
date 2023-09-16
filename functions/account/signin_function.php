<?php
/**
 * Authentication file for signIn for the Webapp
 *
 * Author: SahilLangoo
 * Last modified: 23/8/2023
 */

require_once __DIR__ . '/../functions.php';

if (isset($_POST['signin'])) {
  // Use CSRF protection on this form
  $csrf_token = $_POST['csrf_token'] ?? '';
  try {
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
      // Reset the CSRF token
      unset($_SESSION['csrf_token']);
      throw new Exception('CSRF token validation failed.');
    }

    // Get the form data
    $username = clean($_POST['username'] ?? '');
    $password = clean($_POST['password'] ?? '');
    $remember_me = isset($_POST['remember_me']) && $_POST['remember_me'] === 'on';
    if (empty($username) || empty($password)) {
      throw new Exception('All fields are necessary.');
    }

    // Check if the username is valid only letters and numbers
    if (!preg_match("/^(?<username>[a-z0-9._-]{6,20})$/", $username)) {
      throw new Exception('Invalid username format or length.');
    }

    // If all checks pass, SignIn
    signIn($username, $password, $remember_me);
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  }
}

/**
 * Signs in a user with the given username and password.
 *
 * @param string $username The username of the user to sign in.
 * @param string $password The password of the user to sign in.
 * @param bool $remember_me Whether to remember the user's session.
 * @return void
 */
function signIn(string $username, string $password, bool $remember_me): void
{
  try {
    global $db;

    // Check if the username is registered
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
      throw new Exception('Incorrect username or password.');
    }

    // Check if the password matches
    if (!password_verify($password, $user['password'])) {
      throw new Exception('Incorrect username or password.');
    }

    // Password is correct, so start a new session
    session_start();
    // Store data in session variables
    $_SESSION["authenticated"] = true;
    $_SESSION["status"] = $user['status'];
    $_SESSION["user_id"] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION["email"] = $user['email'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['profile_image'] = $user['profile_image'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['user_since'] = $user['user_since'];

    // Check if the user already has an active session
    $sql = "SELECT * FROM sessions WHERE user_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$user['id']]);
    $existing_session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_session) {
      // Reuse the existing session token and expiration time
      $session_token = $existing_session['session_token'];
      $expires_at = $existing_session['expires_at'];
    } else {
      // Generate a new session token and expiration time
      $session_token = bin2hex(random_bytes(16));
      $expires_at = date('Y-m-d H:i:s', strtotime('+30 days'));

      // Store the session token in the database
      $sql = "INSERT INTO sessions (user_id, session_token, expires_at) VALUES (?, ?, ?)";
      $stmt = $db->prepare($sql);
      $stmt->execute([$user['id'], $session_token, $expires_at]);
    }

    // Set the session token cookie securely
    $cookie_options = [
      'expires' => strtotime($expires_at),
      'path' => '/',
      'secure' => true, // Only send cookie over HTTPS
      'httponly' => true, // Prevent JavaScript from accessing the cookie
      'samesite' => 'Strict' // Prevent cross-site request forgery attacks
    ];
    setcookie('session_token', $session_token, $cookie_options);

    // Set a long-lived cookie if "Remember Me" is checked
    if ($remember_me) {
      $cookie_name = "remember_me";
      $cookie_value = $user['id'] . ':' . bin2hex(random_bytes(32));
      setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/", "", true, true); // 30 days, secure, httponly
    }

    // Check if the password needs to be rehashed
    if (password_needs_rehash($user['password'], PASSWORD_ARGON2ID)) {
      // Set the new password
      $new_hashed_password = hashPassword($password);
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
  } catch (PDOException $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  } catch (Exception $e) {
    $_SESSION['errorsession'] = $e->getMessage();
    redirect('signin.php');
    exit();
  } finally {
    $db = null;
  }
}
