<?php
// include functions file
include_once './functions/functions.php';

//  check if the user is logged in or not
if (isAuthenticated()) {
  redirect('index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en" itemscope itemtype="https://schema.org/WebPage">

<head>
  <title>Sign Up to Listify</title>
  <?php
  // TODO add terms and conditions & privacy policy link || add google recaptcha || validation
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
                  <li class="breadcrumb-item">Sign Up</li>
                </ol>
              </nav>
            </div>
            <div class="card card-plain">
              <div class="card-header pb-0 text-left">
                <!-- dialog -->
                <h3 class="font-weight-bolder text-primary text-gradient text-center">Welcome to Listify</h3>
                <p class="my-2 text-primary text-gradient text-sm text-center">Enter your details to SignUp</p>

              </div>
              <?php include_once('./functions/dialog.php'); ?>
            </div>
            <!-- signup -->
            <div class="card-body pt-2">
              <form action="./functions/account/signup_function.php" method="POST" name="signup" id="signup" class="needs-validation">

                <div class="form-group">
                  <div class="mb-3 has-validation">
                    <input type="text" autocomplete="off" required class="form-control" placeholder="Username" id="username" name="username" aria-label="username" aria-describedby="username" data-bs-toggle="tooltip" data-bs-placement="right" title="Username should only contain letters & numbers, 3-20 Characters">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Username must contain only letters or numbers and be between 3 to 20 characters long.</div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="mb-3 has-validation">
                    <input type="email" required autocomplete="email" class=" form-control " placeholder="Email" id="email" name="email" aria-label="Email" aria-describedby="email-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your email to register.">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Please enter valid E-mail.</div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="mb-3 has-validation">
                    <input type="tel" required autocomplete="tel" class=" form-control" placeholder="Phone" id="phone" name="phone" aria-label="phone" aria-describedby="phone-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your 10 digit phone number.">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Please enter valid Phone.</div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="input-group mb-3 has-validation">
                    <input type="password" autocomplete="new-password" class="form-control" id="password" name="password" required placeholder="Password" aria-label="Password" aria-describedby="password-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Password should be atleast 8 letters (including A-Z, a-z, 0-9, special charaters) and should not be a common password">
                    <div class="btn bg-transparent mb-0 shadow-none">
                      <i class="fa fa-fw fa-eye-slash toggle-password" id="toggle-password" onclick="togglePassword()"></i>
                    </div>
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Please check your Password - must be 8-18 characters long, contain letters, numbers & special characters.</div>
                  </div>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="terms" id="terms" name="terms" required>
                  <label class="form-check-label" for="terms">
                    Agree to terms and conditions
                  </label>
                  <div class="invalid-feedback">
                    You must agree before submitting.
                  </div>
                </div>

                <div class="form-group text-center">
                  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                  <button type="submit" name="signup" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0"> <i class="fa-solid fa-bolt"></i> SignUp</button>
                </div>
              </form>
              <div class="card-footer text-center pt-0 px-lg-2 px-1">
                <p class="my-4 text-sm mx-auto">
                  Already have an account?
                  <a href="./signin.php" class="text-primary text-gradient font-weight-bold">Sign in</a>
                </p>
              </div>
            </div>

          </div>
          <div class="col-md-7 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
              <img src="./assets/img/shapes/pattern-lines.svg" alt="pattern-lines" class="position-absolute opacity-4 start-0">
              <div class="position-relative">
                <img class="max-width-500 w-100 position-relative z-index-2" src="./assets/img/illustrations/chat.png" alt="">
              </div>
              <h4 class="mt-5 text-white font-weight-bolder">"That's the magic of Listify!"</h4>
              <p class="text-white">Business Search on Fingertips</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Signing Section End -->
  <?php include_once './includes/_footer.php';  ?>
</body>

</html>
