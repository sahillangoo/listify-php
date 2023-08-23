<?php

/**
 * The function `redirect` is used to redirect the user to a specified URL using the `header` function
 * in PHP.
 *
 * @param url The `url` parameter is the path or route that you want to redirect the user to. It should
 * be a string that represents the relative URL of the destination page.
 */
const BASE_URL = 'http://localhost:3000/';
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
  exit();
}

/**
 * The sanitize function takes a string as input and removes any leading/trailing whitespace,
 * backslashes, and HTML tags, returning the sanitized string.
 *
 * @param data The "data" parameter is the input string that needs to be sanitized.
 *
 * @return string a sanitized string.
 */
function sanitize($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return strip_tags($data);
}

// check https function
function check_https()
{
  if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    return false;
  }
  return true;
}
