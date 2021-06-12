<!-- 主要處理 login.php 到後端部分操作 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
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
    /**
     *  產生 session id (token)
     *  把 username 寫入檔案
     *  set-cookie: session-id
     */
    $_SESSION['username'] = $username;
    header('Location: index.php');
  } else {
    // echo $username;
    header('Location: login.php?errCode=2');
  }
} else {
  die('code' . $conn->errno);
}
