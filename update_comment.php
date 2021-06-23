<!-- 編輯既有留言頁面 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}

require_once("conn.php");
require_once("utils.php");

// 拿到留言的 id
$id = $_GET['id'];
$username = $_SESSION['username'];
$sql = "SELECT * FROM comments WHERE id=? AND username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $id, $username);
$res = $stmt->execute();

if (!$res) {
  die($conn->error);
}
$res = $stmt->get_result();
$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言板</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header class="warning">
    <strong>注意！本站為練習用網站，因教學用途刻意忽略資安問題的實作，註冊時請勿使用任何真實的帳號或密碼。</strong>
  </header>

  <main class="board">
    <h1 class="board__title">編輯留言</h1>
    <!-- 根據 queryString errCode 印出相對應的錯誤訊息 -->
    <?php
    if (!empty($_GET['errCode'])) {
      if ($_GET['errCode'] === '1') {
        $msg = "錯誤：資料不齊全";
        echo "<h3 class='error'>$msg</h3>";
      } else {
        $msg = "錯誤：不合法的錯誤代碼";
        echo "<h3 class='error'>$msg</h3>";
      }
    }
    ?>

    <form class="board__form" method="POST" action="handle_update_comment.php">
      <?php if (!empty($row)) {?>
        <textarea name="content" rows="5"><?php echo $row['content']?></textarea>
      <?php } else {?>
        <textarea name="content" rows="5" readonly>不能編輯他人的留言</textarea>
      <?php }?>
      <input type="hidden" name="id" value="<?php echo $id?>">
      <input class="board__submit-btn" type="submit" value="送出">
    </form>
  </main>
</body>

</html>