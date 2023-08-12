<?php

// Site Url
const BASE_URL = 'http://localhost:3000/';
// function to set location
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
  exit();
}

// sanitize function
function sanitize($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return strip_tags($data);
}
