<!-- 負責處理用戶登出部分 -->
<!-- 主要刪除該用戶的 token -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION

if (!isset($_SESSION)) {
  session_start();
}
session_destroy();
header('Location: index.php');
?>