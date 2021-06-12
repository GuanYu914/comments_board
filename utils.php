<!-- 放一些專案當中從用的 function -->
<?php
require_once("conn.php");

function generateToken()
{
  $s = '';
  for ($i = 0; $i < 64; $i++) {
    $s .= chr(rand(65, 90));
  }
  return $s;
}

function getUserFromToken($token)
{
  // 若要在 function 裡面使用 global 變數，需要加上 global keyword
  global $conn;
  $sql = sprintf("SELECT username FROM tokens WHERE token='%s'", $token);
  $res = $conn->query($sql);
  if (!$res) {
    die($conn->error);
  }
  $username = $res->fetch_assoc()['username'];
  
  $sql = sprintf("SELECT * FROM users WHERE username='%s'", $username);
  $res = $conn->query($sql);
  if (!$res) {
    die($conn->error);
  }
  $row = $res->fetch_assoc();
  
  return $row;
}
