<?php

// sanitize function
function sanitize($data): string
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return strip_tags($data);
}
