<!-- 編輯既有留言頁面 -->
<?php
require_once("conn.php");
require_once("utils.php");

// 拿到留言的 id
$id = $_GET['id'];
$sql = "SELECT * FROM comments WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
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
      <textarea name="content" rows="5"><?php echo $row['content']?></textarea>
      <input type="hidden" name="id" value="<?php echo $id?>">
      <input class="board__submit-btn" type="submit" value="送出">
    </form>
  </main>
</body>

</html>