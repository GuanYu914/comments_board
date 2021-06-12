<!-- 主要處理 register.php 到後端部分操作 -->
<?php
require_once("conn.php");

// 拿到用戶輸入的註冊資訊
$nickname = $_POST['nickname'];
$username = $_POST['username'];
$password = $_POST['password'];

// 若有欄位為空值，回傳錯誤代碼
if (empty($nickname) || empty($username) || empty($password)) {
  header("Location: register.php?errCode=1");
  die();
}

// 新增資訊到 users table
$sql = sprintf("INSERT INTO users(nickname, username, password) VALUE('%s', '%s', '%s')", $nickname, $username, $password);
$res = $conn->query($sql);

// 成功返回 index.php
if ($res) {
  header('Location: index.php');
} else {
  // 若 username 重複，回傳錯誤代碼
  if ($conn->errno === 1062) {
    header("Location: register.php?errCode=2");
  }
  die('code' . $conn->errno);
}
