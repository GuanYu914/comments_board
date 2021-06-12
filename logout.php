<!-- 負責處理用戶登出部分 -->
<!-- 主要刪除該用戶的 token -->
<?php
  require_once("conn.php");
  // 刪除資料庫中的 token
  $token = $_COOKIE['token'];
  $sql = sprintf("DELETE FROM tokens WHERE token='%s'", $token);
  $res = $conn->query($sql);
  if (!$res) {
    die($conn->error);
  }
  // 設定 Cookie 為過期狀態
  $expire = 3600 * 24;
  setcookie("token", "", time() - $expire);
  header('Location: index.php');
?>
