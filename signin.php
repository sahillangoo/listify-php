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
  <title>Sign to Listify</title>
  <?php
  // include the head file
  include_once './includes/_head.php';

  // todo add terms and conditions & privacy policy link || add google recaptcha || add forgot password || Check if the remember me is sets => cookie || user can login with username, email or phone number || remove template code & content

  ?>
</head>

<body class="sign-in-illustration">
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-4 d-flex flex-column mx-lg-0 mx-auto">
            <div class="card card-plain">
              <div class="card-header pb-0 text-left">
                <!-- dialog -->
                <h2 class="font-weight-bolder text-primary text-gradient text-center">Welcome back</h2>
                <?php include_once('./functions/dialog.php'); ?>
              </div>
            </div>
            <div class="card-body">
              <!--  fix labels and tool tips -->
              <form action="./functions/account/signin_function.php" method="post" name="signin" id="signin" class="needs-validation" novalidate autocomplete="on">
                <p class="my-2 text-primary text-gradient text-sm mx-auto text-center">Enter your email and password to sign in</p>

                <div class="form-group">
                  <div class="mb-3 has-validation">
                    <input type="email" required class=" form-control" name="email" placeholder="Email" id="signin-email" aria-label="Email" aria-describedby="email-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your Registered Email">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Please enter valid E-mail.</div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="mb-3 has-validation">
                    <input type="password" class=" form-control" id="signin-password" name="password" required placeholder="Password" aria-label="Password" aria-describedby="password-addon" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter your password">
                    <div class="valid-feedback">
                      Looks good!
                    </div>
                    <div class="invalid-feedback">Please check your Password.</div>
                  </div>
                </div>

                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" checked>
                  <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>

                <a class=" text-primary text-gradient text-sm" href="javascript:;">
                  Lost your password?
                </a>

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
          <div class="col-7 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
              <img src="./assets/img/shapes/pattern-lines.svg" alt="pattern-lines" class="position-absolute opacity-4 start-0">
              <div class="position-relative">
                <img class="max-width-500 w-100 position-relative z-index-2" src="./assets/img/illustrations/chat.png" alt="">
              </div>
              <h4 class="mt-5 text-white font-weight-bolder"> "That's the magic of Listify!""</h4>
              <p class="text-white">The innovative web app that empowers you to curate, personalize, and share lists effortlessly. </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Signing Section End -->
  <script type="text/javascript">
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
      'use strict'

      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      const forms = document.querySelectorAll('.needs-validation')

      // Loop over them and prevent submission
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
    })()
  </script>
  <?php
  // include the footer file
  include_once './includes/_footer.php';
  ?>

</body>

</html>
