<?php
// This file contains the functions that will be used in the application
// Start the session if it has not already started
$sessionStarted = session_start();
if (!$sessionStarted) {
  throw new \RuntimeException('Session could not be started');
}
// Include DB file
require_once __DIR__ . '/../functions/db_connect.php';
// Site URL
const BASE_URL = 'http://localhost:3000/';
// Function to set location
function redirect($url)
{
  header('Location: ' . BASE_URL . $url);
}
function sanitize($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return strip_tags($data);
}
// Generate a CSRF token and store it in the user's session
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
function hashPassword($password): string
{
  $options = [
    'cost' => 12,
    'memory_cost' => 2048,
    'time_cost' => 4,
  ];
  return password_hash($password, PASSWORD_ARGON2ID, $options);
}
// check https function
function check_https()
{
  if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    return false;
  }
  return true;
}

// Function to check if the user is logged in
function isAuthenticated(): bool
{
  return !empty($_SESSION["authenticated"]) && $_SESSION["authenticated"] === true;
}
// function to check if the user role is admin
function isAdmin(): bool
{
  return isset($_SESSION["role"]) && $_SESSION["role"] === 'admin';
}
function displayListing($listing)
{
  echo <<<HTML
          <div class="col-md-3 col-lg-3 mb-4">
            <div class="card card-frame">
              <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                <a href="./listing.php?listing={$listing['id']}" class="d-block">
                  <img src="./uploads/business_images/{$listing['displayImage']}" class="img-fluid border-radius-lg move-on-hover" alt="{$listing['businessName']}" loading="lazy">
                </a>
              </div>
              <div class="card-body pt-2">
                <div class="d-flex justify-content-between align-items-center my-2">
                  <span class="text-uppercase text-xxs font-weight-bold"><i class="fa-solid fa-shop"></i> {$listing['category']}</span>
                  <span class="text-uppercase text-xxs font-weight-bold "><i class="fa-solid fa-location-dot"></i> {$listing['city']}</span>
                </div>
                <div class="d-flex justify-content-between ">
                  <a href="./listing.php?listing={$listing['id']}" class="card-title h6 d-block text-gradient text-primary font-weight-bold ">{$listing['businessName']}</a>
                  <span class="text-gradient text-warning text-uppercase text-xs mt-1"><i class="fa-solid fa-star"></i> {$listing['avg_rating']} ({$listing['reviews_count']})</span>
                </div>
                <p class="card-description text-sm mb-3" id="truncate" >{$listing['description']}</p>
                <p class="mb-2 text-xxs font-weight-bolder text-warning text-gradient text-uppercase"><span>By―</span> {$listing['username']}</p>
                <div class="d-flex justify-content-start my-2">
                  <a href="./listing.php?listing={$listing['id']}" class="text-primary text-sm icon-move-right">View details <i class="fas fa-arrow-right text-sm" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        HTML;
}
