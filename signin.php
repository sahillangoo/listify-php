<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
  <title>Sigin/Sigup to Listify</title>
  <?php
  // include config file
  include_once './includes/_config.php';
  // include the head file
  include_once './includes/_head.php';

  //  check if the user is logged in or not
  if (isLoggedIn()) {
    redirect('index.php');
    exit;
  }
  ?>
</head>

<body class="sign-in-illustration">
  <section>
    <div class="page-header min-vh-100">
      <div class="container">
        <div class="row">
          <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
            <div class="card card-plain">
              <div class="card-header pb-0 text-left">
                <!-- dialog -->
                <h4 class="font-weight-bolder text-primary text-gradient">Welcome back</h4>
                <?php include_once('./functions/dialog.php'); ?>
                <div class="nav-wrapper position-center end-0">
                  <ul class="nav nav-pills nav-fill p-1" id="account" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link mb-0 px-0 py-1 active" id="signin-tab" data-bs-toggle="tab" data-bs-target="#signin-tab-pane" role="tab" aria-controls="signin-tab-pane" aria-selected="true">Sign In</a>
                    </li>

                    <li class="nav-item" role="presentation">
                      <a class="nav-link mb-0 px-0 py-1" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup-tab-pane" role="tab" aria-controls="signup-tab-pane" aria-selected="false">Sign Up</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="tab-content" id="signin">
              <div class="tab-pane fade show active" id="signin-tab-pane" role="tabpanel" aria-labelledby="signin-tab" tabindex="0">
                <div class="card-body">

                  <form action="./functions/auth/signin_function.php" method="post" name="signin" id="signin" class="needs-validation" novalidate autocomplete="on">
                    <legend class="my-3 small">Enter your email and password to sign in</legend>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="email" required class="form-control form-control-lg" name="email" placeholder="Email" id="signin-email" aria-label="Email" aria-describedby="email-addon">
                        <div class="valid-feedback">
                          Looks good!
                        </div>
                        <div class="invalid-feedback">Please enter valid E-mail.</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="password" class="form-control form-control-lg" id="signin-password" name="password" required placeholder="Password" aria-label="Password" aria-describedby="password-addon">
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

                    <a class="small" href="javascript:;">
                      Lost your password?
                    </a>

                    <div class="form-group text-center">
                      <button type="submit" name="signin" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">
                        <i class="fas fa-lock"></i> SignIn
                      </button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="my-4 text-primary text-gradient text-sm mx-auto">
                    Don't have an account Try SignUp!
                  </p>
                </div>
              </div>
            </div>

            <!-- signup -->
            <div class="tab-content" id="signup">
              <div class="tab-pane fade show" id="signup-tab-pane" role="tabpanel" aria-labelledby="signup-tab" tabindex="0">
                <div class="card-body">
                  <form action="./functions/auth/signup_function.php" method="POST" name="signup" id="signup" class="needs-validation" novalidate autocomplete="on">
                    <legend class="my-3 small">Enter your details to SignUp</legend>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="text" required class="form-control form-control-lg" placeholder="Username" id="username" name="username" aria-label="username" aria-describedby="username-addon">
                        <div class="valid-feedback">
                          Looks good!
                        </div>
                        <div class="invalid-feedback">Please choose a unique and valid username.</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="email" required class="form-control form-control-lg" placeholder="Email" id="email" name="email" aria-label="Email" aria-describedby="email-addon">
                        <div class="valid-feedback">
                          Looks good!
                        </div>
                        <div class="invalid-feedback">Please enter valid E-mail.</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="tel" required class="form-control form-control-lg" placeholder="Phone" id="phone" name="phone" aria-label="phone" aria-describedby="phone-addon">
                        <div class="valid-feedback">
                          Looks good!
                        </div>
                        <div class="invalid-feedback">Please enter valid Phone.</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="mb-3 has-validation">
                        <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="Password" aria-label="Password" aria-describedby="password-addon">
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
                      <button type="submit" name="signup" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">
                        <i class="fas fa-lock"></i> SignUp
                      </button>
                    </div>
                  </form>
                </div>

                <!-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="my-4 text-sm mx-auto">
                  </p>
                </div> -->
              </div>
            </div>

          </div>
          <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center">
              <img src="../assets/img/shapes/pattern-lines.svg" alt="pattern-lines" class="position-absolute opacity-4 start-0">
              <div class="position-relative">
                <img class="max-width-500 w-100 position-relative z-index-2" src="../assets/img/illustrations/chat.png">
              </div>
              <h4 class="mt-5 text-white font-weight-bolder">"Attention is the new currency"</h4>
              <p class="text-white">The more effortless the writing looks, the more effort the writer actually put into the process.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>



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
