<!-- 主要處理更新 nickname 操作 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");

// 拿到用戶輸入新的暱稱
$nickname = $_POST['nickname'];

// 若有欄位為空值，回傳錯誤代碼
if (empty($nickname)) {
  header("Location: register.php?errCode=1");
  die();
}
$username = $_SESSION['username'];

// 根據 username 找到要更新的 nickname
$sql = "UPDATE users SET nickname=? WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nickname, $username);
$res = $stmt->execute();

// 成功返回 index.php
if ($res) {
  header('Location: index.php');
  die();
} else {
  die('code' . $conn->errno);
}
