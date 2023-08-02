<?php

echo "hello world<br>";
// Function to check if the user is logged in
function isLoggedIn()
{
  if (isset($_SESSION['user_id'])) {
    return true;
  } else {
    return false;
  }
}
// check if the user is logged in echo loggedIn();
if (isLoggedIn()) {
  echo "User is logged in";
} else {
  echo "User is not logged in";
}
