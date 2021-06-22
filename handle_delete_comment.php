<!-- 主要處理刪除 comment 操作 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");

// 拿到用戶輸入新的暱稱
$id = $_GET['id'];
// $content =$_POST['content'];

// 若有欄位為空值，回傳錯誤代碼
if (empty($id)) {
  header("Location: index.php");
  die();
}

// 根據 id 找到要刪除的留言內容
// $sql = "UPDATE comments SET content=? WHERE id=?";
// $sql = "DELETE FROM comments WHERE id=?";
$sql = "UPDATE comments SET is_deleted=1 WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$res = $stmt->execute();

// 成功返回 index.php
if ($res) {
  header('Location: index.php');
  die();
} else {
  die('code' . $conn->errno);
}
