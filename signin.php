<?php
// include functions file
include_once './functions/functions.php';

//  check if the user is logged in or not
if (isAuthenticated()) {
  redirect('index.php');
  exit;
}
// if cookie is present, log the user in automatically
if (isset($_COOKIE['remember_me'])) {
  $cookie = json_decode($_COOKIE['remember_me'], true);
  signIn($cookie['username'], $cookie['password'], true);
}

?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>SignIn to Listify</title>
  <?php
  // include the head file
  include_once './includes/_head.php';

  ?>
</head>

<body class="sign-in-illustration">
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-md-4 d-flex flex-column mx-lg-0 mx-auto">
            <!-- breadcrumb -->
            <div class="row pt-3 overflow-hidden">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="./index.php">Home</a></li>
                  <li class="breadcrumb-item">Sign In</li>
                </ol>
              </nav>
            </div>
            <div class="card card-plain">
              <div class="card-header pb-0 text-left">
                <!-- dialog -->
                <h2 class="font-weight-bolder text-primary text-gradient text-center">Welcome Back</h2>
                <?php include_once('./functions/dialog.php'); ?>
              </div>
            </div>
            <div class="card-body">

              <form action="./functions/account/signin_function.php" method="post" name="signin" id="signin" class="needs-validation" autocomplete="on">
                <p class="my-2 text-primary text-gradient text-sm mx-auto text-center">Enter your username and password to sign in</p>

                <div class="form-group">
                  <label for="username" class="form-label">Username</label>
                  <div class="mb-3 has-validation">
                    <input type="text" required class=" form-control" placeholder="Username" id="username" name="username" aria-label="username" aria-describedby="username" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your registered username">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Invalid username format or length.</div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group mb-3 has-validation">
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Password" aria-label="password" aria-describedby="password-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your password">
                    <span class="btn bg-transparent mb-0 shadow-none">
                      <i class="fa fa-fw fa-eye-slash toggle-password" id="toggle-password" onclick="togglePassword()"></i>
                    </span>

                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Invalid password format or length.</div>
                  </div>
                </div>

                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked>
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>

                <!-- <a class=" text-primary text-gradient text-sm" href="javascript:;">
                  Lost your password?
                </a> -->

                <div class="form-group text-center">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                  <button type="submit" name="signin" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">
                    <i class="fas fa-lock"></i> SignIn
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center pt-0 px-lg-2 px-1">
              <p class="my-4 text-muted text-sm mx-auto">
                Don't have an account?
                <a href="./signup.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
              </p>
            </div>
          </div>
          <div class="col-md-7 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
              <img src="./assets/img/shapes/pattern-lines.svg" alt="pattern-lines" class="position-absolute opacity-4 start-0">
              <div class="position-relative">
                <img class="max-width-500 w-100 position-relative z-index-2" src="./assets/img/illustrations/chat.png" alt="">
              </div>
              <h4 class="h4 mt-5 text-white font-weight-bolder"> "
                <?php
                $quotes = array(
                  "The best way to predict the future is to create it.",
                  "The best preparation for tomorrow is doing your best today.",
                  "The best and most beautiful things in the world cannot be seen or even touched - they must be felt with the heart.",
                  "The best thing about the future is that it comes one day at a time.",
                );
                $random_quote = array_rand($quotes, 1);
                echo $quotes[$random_quote];
                ?>
                "</h4>
              <p class="text-white">Business Search on Fingertips</p>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Signing Section End -->
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>

</body>

</html>
