<!-- ========== Start Navbar ========== -->
<div class="container position-sticky z-index-sticky top-0">
  <div class="row">
    <div class="col-12">
      <nav class="navbar navbar-expand-lg  blur blur-rounded top-0 z-index-fixed shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
        <div class="container-fluid">
          <a class="navbar-brand font-weight-bolder ms-sm-3" href="./index.php">
            <img src="./assets/img/listify.png" alt="listify" height="26px"> Listify
          </a>
          <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon mt-2">
              <span class="navbar-toggler-bar bar1"></span>
              <span class="navbar-toggler-bar bar2"></span>
              <span class="navbar-toggler-bar bar3"></span>
            </span>
          </button>

          <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0" id="navigation">
            <ul class="navbar-nav navbar-nav-hover ms-lg-12 ps-lg-5 w-100">
              <li class="nav-item ms-lg-auto my-auto">
                <a class="nav-link nav-link-icon me-2" href="https://github.com/sahillangoo/listify-php" target="_blank">
                  <i class="fa-brands fa-square-github"></i>
                  <p class="d-inline text-sm z-index-1 font-weight-bold text-uppercase" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Star us on Github">Github</p>
                </a>
              </li>
              <?php if (!isAuthenticated()) : ?>
                <li class="nav-item ms-lg-auto my-auto ms-3 ms-lg-0">
                  <a href="./signin.php" class="btn btn-sm bg-gradient-primary btn-round mb-0 me-1 mt-2 mt-md-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SignUp or SignIn to Listify">SignUp / SignIn</a>
                </li>
              <?php endif; ?>

              <?php if (isAuthenticated()) : ?>
                <li class="nav-item dropdown dropdown-hover mx-2">
                  <a class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center" id="dropdownMenuPages" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>
                      <img src="<?php echo $_SESSION['profile_image']; ?>" class="avatar avatar-sm rounded-circle" alt="<?php echo $_SESSION['username']; ?>">
                    </span>
                    <?php echo $_SESSION['username']; ?>
                    <img src="./assets/img/down-arrow-dark.svg" alt="down-arrow" class="arrow ms-1">
                  </a>
                  <div class="dropdown-menu dropdown-menu-animation dropdown-md p-3 border-radius-lg mt-0 mt-lg-3" aria-labelledby="dropdownMenuPages">
                    <div class="d-none d-lg-block">
                      <a href="./account.php" class="dropdown-item border-radius-md">
                        My Profile
                      </a>
                      <a href="./add-listing.php" class="dropdown-item border-radius-md">
                        Create Listing
                      </a>
                      <a href="../functions/account/signout_function.php" class="dropdown-item border-radius-md">
                        Sign Out
                      </a>
                    </div>

                    <div class="d-lg-none">
                      <a href="./account.php" class="dropdown-item border-radius-md">
                        My Profile
                      </a>
                      <a href="./add-listing.php" class="dropdown-item border-radius-md">
                        Create Listing
                      </a>
                      <a href="../functions/account/signout_function.php" class="dropdown-item border-radius-md">
                        Sign Out
                      </a>
                    </div>

                  </div>
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
<!-- ========== End Navbar ========== -->
