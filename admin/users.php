<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';
//  check if the user is logged in or not
if (!isAuthenticated()) {
  redirect('signin.php');
  exit;
}
// check if user is admin
if (!isAdmin()) {
  redirect('index.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- include head file -->
  <?php
  include_once('./includes/_head.php');
  ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!-- include aside file -->
  <?php include_once('./includes/_aside.php'); ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- include Navbar file -->
    <?php include_once('./includes/_navbar.php'); ?>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Users</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <?php
              // Fetch all users from the database
              try {
                $sql = "SELECT * FROM users";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll();
              } catch (PDOException $e) {
                echo $e->getMessage();
              } finally {
                $stmt = null;
              }
              // Output the users in a Bootstrap table
              echo '<div class="table-responsive p-0">';
              echo '<table class="table align-items-center mb-0">';
              echo '<thead>';
              echo '<tr>';
              echo '<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">User</th>';
              echo '<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Role</th>';
              echo '<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">User Since</th>';
              echo '<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Manage User</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';

              foreach ($users as $row) {
                echo '<tr>';
                echo '<td>
                <div class="d-flex px-2 py-1">
                          <div>
                            <img src="' . $row['profile_image'] . '" alt="Profile Image" class="avatar avatar-sm me-3">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">' . $row['username'] . '</h6>
                            <p class="text-xs text-secondary mb-0">' . $row['email'] . '</p>
                          </div>
                        </div>
                </td>                ';
                echo '<td><p class="badge badge-xsm bg-gradient-primary text-xsm font-weight-bold mb-0">' . $row['role'] . '</p></td>';
                echo '<td><span class="text-secondary text-sm font-weight-bold">' . $row['user_since'] . '</span></td>';
                echo '<th class="align-middle">
                <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Manage user">
                  Manage
                </a></th>';
                echo '</tr>';
              }

              echo '</tbody>';
              echo '</table>';
              echo '</div>';

              // Close the database connection
              $db = null;
              ?>
            </div>
          </div>
        </div>
      </div>
      <?php include './includes/_footer.php'; ?>
    </div>
  </main>


  <!-- include theme config file -->
  <?php include './includes/_theme_config.php'; ?>

  <!-- include footer file -->
  <?php include './includes/_scripts.php'; ?>

</body>

</html>
