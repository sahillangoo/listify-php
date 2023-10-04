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
              <h6>Listings</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <?php
              // Fetch all users from the database
              try {
                $sql = "SELECT * FROM listings";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $listings = $stmt->fetchAll();
              } catch (PDOException $e) {
                echo $e->getMessage();
              } finally {
                $stmt = null;
              }

              // Output the Listings in a Bootstrap table
              echo <<<HTML
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Listings</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Category</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">City</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Listing Since</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Featured</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Active</th>
                      <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">View Listing</th>
                    </tr>
                  </thead>
                  <tbody>
              HTML;

              foreach ($listings as $row) {
                $featured = $row['featured'] ? 'checked' : '';
                $active = $row['active'] ? 'checked' : '';
                echo <<<HTML
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="../uploads/business_images/{$row['displayImage']}" alt="Profile Image" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{$row['businessName']}</h6>
                          <p class="text-xs text-secondary mb-0">{$row['email']}</p>
                        </div>
                      </div>
                    </td>
                    <td><p class="badge badge-xsm bg-gradient-info text-xsm font-weight-bold mb-0">{$row['category']}</p></td>
                    <td><p class="badge badge-xsm bg-gradient-primary text-xsm font-weight-bold mb-0">{$row['city']}</p></td>
                    <td><span class="text-secondary text-sm font-weight-bold">{$row['createdAt']}</span></td>

                    <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="featured-{$row['id']}" name="featured" {$featured} onchange="toggleFeatured({$row['id']})">
                        <label class="form-check-label" for="featured-{$row['id']}"></label>
                      </div>
                    </td>
                    <td>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active-{$row['id']}" name="active" {$active} onchange="toggleActive({$row['id']})">
                        <label class="form-check-label" for="active-{$row['id']}"></label>
                      </div>
                    </td>
                    <td><span class="text-secondary text-sm font-weight-bold">
                    <a href="../listing.php?listing={$row['id']}" target="_blank" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Manage user">
                      View
                    </a>
                    </span>
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
                function toggleFeatured(id) {
                  const checkbox = document.getElementById(`featured-${id}`);
                  const value = checkbox.checked ? 1 : 0;
                  fetch(`./functions/toggle_featured.php?id=${id}&value=${value}`)
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        console.log(`Listing ${id} featured status updated to ${value}`);
                      } else {
                        console.error(`Failed to update featured status for listing ${id}`);
                      }
                    })
                    .catch(error => {
                      console.error(`Failed to update featured status for listing ${id}: ${error}`);
                    });
                }

                function toggleActive(id) {
                  const checkbox = document.getElementById(`active-${id}`);
                  const value = checkbox.checked ? 1 : 0;
                  fetch(`./functions/toggle_active.php?id=${id}&value=${value}`)
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        console.log(`Listing ${id} active status updated to ${value}`);
                      } else {
                        console.error(`Failed to update active status for listing ${id}`);
                      }
                    })
                    .catch(error => {
                      console.error(`Failed to update active status for listing ${id}: ${error}`);
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
