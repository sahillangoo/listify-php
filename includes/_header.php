  <!-- Navbar -->
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-lg  blur blur-rounded top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid px-0">
            <a class="navbar-brand font-weight-bolder ms-sm-3" href="#" rel="tooltip" title="A Comprehensive Listing Web App" data-placement="bottom">
              Listify
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
              <ul class="navbar-nav navbar-nav-hover ms-lg-12 ps-lg-5 w-100">
                <li class="nav-item ms-lg-auto">
                  <a class="nav-link nav-link-icon me-2" href="https://github.com/sahillangoo/listify-php" target="_blank">
                    <i class="fa fa-github me-1"></i>
                    <p class="d-inline text-sm z-index-1 font-weight-bold text-uppercase" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Star us on Github">Github</p>
                  </a>
                </li>
                <!-- siginout form if user is signed in -->
                <?php if (isLoggedIn()) : ?>
                  <li class="nav-item my-auto ms-3 ms-lg-0">
                    <form action="./../functions/auth/auth_functions.php" method="post">
                      <button href="./signin.php" type="submit" name="signout" value="Sign Out" class="btn btn-sm bg-gradient-primary btn-round mb-0 me-1 mt-2 mt-md-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Sign Out">Sign Out</button>
                    </form>
                  <?php endif; ?>
                  <?php if (!isLoggedIn()) : ?>
                  <li class="nav-item my-auto ms-3 ms-lg-0">
                    <a href="./signin.php" class="btn btn-sm  bg-gradient-primary  btn-round mb-0 me-1 mt-2 mt-md-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SigUp or SignIn to Listify">SignUp / SigIn</a>
                  </li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
