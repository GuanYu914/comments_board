<!-- 主要處理新增留言操作 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");
require_once("utils.php");

// 拿到留言內容，如果為空則導引到原先畫面並印出錯誤訊息
$content = $_POST['content'];
if (empty($content)) {
  header("Location: index.php?errCode=1");
  die();
}

// 透過 SESSION 拿到 username 之後，再拿到相對應 user 資訊
$user = getUserFromUsername($_SESSION['username']);

// 拿到 nickname 跟留言內容之後，新增到 comments table 裡面
$sql = "INSERT INTO comments(nickname, content) VALUE(?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user['nickname'], $content);
$res = $stmt->execute();
if (!$res) {
  die($conn->error);
}
// 操作成功，導引到原先畫面
header('Location: index.php');
?>