<!-- 放一些專案當中從用的 function -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");

function generateToken()
{
  $s = '';
  for ($i = 0; $i < 64; $i++) {
    $s .= chr(rand(65, 90));
  }
  return $s;
}

// 因為使用 SESSION 所以改變 function 用法
function getUserFromUsername($username)
{
  // 若要在 function 裡面使用 global 變數，需要加上 global keyword
  global $conn;
  
  $sql = sprintf("SELECT * FROM users WHERE username='%s'", $username);
  $res = $conn->query($sql);
  if (!$res) {
    die($conn->error);
  }
  $row = $res->fetch_assoc();
  
  return $row;
}

// 使用內建函式防止 XSS 攻擊
function escape ($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}