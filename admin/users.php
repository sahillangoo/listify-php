<?php
// include functions file
require_once __DIR__ . '/../functions/functions.php';
//  check if the user is logged in and is an admin
if (!isAuthenticated() || !isAdmin()) {
  redirect('signin.php');
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
              // Fetch all users from the database, sorted by role
              try {
                $sql = "SELECT * FROM users ORDER BY role DESC";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $users = $stmt->fetchAll();
              } catch (PDOException $e) {
                echo $e->getMessage();
              } finally {
                $stmt = null;
              }

              // Output the users in a Bootstrap table
              echo <<<HTML
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">User</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Role</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">User Since</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Active</th>
                    </tr>
                  </thead>
                  <tbody>
              HTML;

              foreach ($users as $row) {
                $active = $row['status'] == 'active' ? 'checked' : '';
                echo <<<HTML
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="{$row['profile_image']}" alt="Profile Image" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{$row['username']} [{$row['id']}]</h6>
                          <p class="text-xs text-secondary mb-0">{$row['email']}</p>
                        </div>
                      </div>
                    </td>
                    <td><p class="badge badge-xsm bg-gradient-primary text-xsm font-weight-bold mb-0">{$row['role']}</p></td>
                    <td><span class="text-secondary text-sm font-weight-bold">{$row['user_since']}</span></td>
                    <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active-{$row['id']}" name="active" {$active} onchange="toggleActive({$row['id']})">
                        <label class="form-check-label" for="active-{$row['id']}"></label>
                      </div>
                    </td>
                  </tr>
              HTML;
              }

              echo <<<HTML
                  </tbody>
                </table>
              </div>
              HTML;

              // Close the database connection
              $db = null;
              ?>

              <script>
              function toggleActive(id) {
                const checkbox = document.getElementById(`active-${id}`);
                const value = checkbox.checked ? 'active' : 'inactive';
                fetch(`./functions/toggle_active_user.php?id=${id}&value=${value}`)
                  .then(response => response.json())
                  .then(data => {
                    if (data.success) {
                      console.log(`User ${id} active status updated to ${value}`);
                    } else {
                      console.error(`Failed to update active status for user ${id}`);
                    }
                  })
                  .catch(error => {
                    console.error(`Failed to update active status for user ${id}: ${error}`);
                  });
              }
              </script>
            </div>
          </div>
        </div>
      </div>
      <?php include './includes/_footer.php'; ?>
    </div>
  </main>

  <!-- include footer file -->
  <?php include './includes/_scripts.php'; ?>

</body>

</html>
