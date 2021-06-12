<!-- 主要處理 login.php 到後端部分操作 -->
<?php
require_once("conn.php");
require_once("utils.php");

// 拿到用戶輸入的帳密
$username = $_POST['username'];
$password = $_POST['password'];

// 若有空值，則導引至原先畫面並回傳錯誤代碼
if (empty($username) || empty($password)) {
  header("Location: login.php?errCode=1");
  die();
}

// 找尋資料庫是否有相同的帳密資訊
$sql = sprintf("SELECT * FROM users WHERE username='%s' and password='%s'", $username, $password);
$res = $conn->query($sql);

if ($res) {
  // 資料庫裏面有找到資訊
  if ($res->num_rows) {
    // 回傳登入時相對應的 token，用來代表目前登入的用戶
    $token = generateToken();
    // 將 token & username 匹配資訊存放在 tokens table
    $sql = sprintf("INSERT INTO tokens(token, username) VALUES('%s', '%s')", $token, $username);
    $res = $conn->query($sql);
    if (!$res) {
      die($conn->error);
    }
    // 設定 Cookie，並限制期限為一天
    // 3600 * 24 -> 一天
    $expire = 3600 * 24;
    setcookie("token", $token, time() + $expire);
    header('Location: index.php');
  } else {
    // 沒有找到相對應帳密，回傳錯誤代碼
    header('Location: login.php?errCode=2');
  }
} else {
  die('code' . $conn->errno);
}
