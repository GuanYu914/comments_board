<!-- 主要處理更新 comment 操作 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");

// 拿到用戶輸入新的暱稱
$id = $_POST['id'];
$content =$_POST['content'];

// 若有欄位為空值，回傳錯誤代碼
if (empty($content)) {
  header("Location: update_comment.php?errCode=1&&id=$id");
  die();
}

// 根據 id 找到要更新的留言內容
$sql = "UPDATE comments SET content=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $content, $id);
$res = $stmt->execute();

// 成功返回 index.php
if ($res) {
  header('Location: index.php');
  die();
} else {
  die('code' . $conn->errno);
}
