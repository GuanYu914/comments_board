<!-- 主要處理新增留言操作 -->
<?php
require_once("conn.php");
require_once("utils.php");

// 拿到留言內容，如果為空則導引到原先畫面並印出錯誤訊息
$content = $_POST['content'];
if (empty($content)) {
  header("Location: index.php?errCode=1");
  die();
}

// 透過 Token 拿到對應的 username，再找到此 username 在 users table 裡所有資訊
$user = getUserFromToken($_COOKIE['token']);

// 拿到 nickname 跟留言內容之後，新增到 comments table 裡面
$sql = sprintf("INSERT INTO comments(nickname, content) VALUE('%s', '%s')", $user['nickname'], $content);
$res = $conn->query($sql);
if (!$res) {
  die($conn->error);
}
// 操作成功，導引到原先畫面
header('Location: index.php');
?>