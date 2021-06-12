<!-- 留言板首頁 -->
<?php
require_once("conn.php");
require_once("utils.php");

// 根據 cookie 得知目前登入狀態
$username = null;
if (!empty($_COOKIE['token'])) {
  $user = getUserFromToken($_COOKIE['token']);
  if ($user) {
    $username = $user['username'];
    $nickname = $user['nickname'];
  }
}
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
    <h1 class="board__title">Comments</h1>
    <!-- 若拿不到 username，代表處在未登入狀態 -->
    <?php if (!$username) { ?>
      <div class="board__user">
        <h2 class="board__user__nickname">訪客您好，需登入才可留言</h2>
        <div class="board__user__btn">
          <a href="register.php">註冊</a>
          <a href="login.php">登入</a>
        </div>
      </div>
    <!-- 拿到 username，輸出 nickname -->
    <?php } else { ?>
      <div class="board__user">
        <h2 class="board__user__nickname">歡迎回來～<?php echo $nickname ?></h2>
        <div class="board__user__btn">
          <a href="logout.php">登出</a>
        </div>
      </div>
    <?php } ?>
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

    <form class="board__form" method="POST" action="handle_add_comment.php">
      <textarea name="content" rows="5"></textarea>
      <!-- 如果有拿到 username，將 "送出" 的按鈕顯示 -->
      <?php if ($username) { ?>
        <input class="board__submit-btn" type="submit" value="送出">
      <?php } ?>

    </form>
    <div class="board__hr"></div>
    <!-- 根據資料庫拿到相對應留言資訊 -->
    <section>
      <?php
      $sql = sprintf("SELECT * FROM comments ORDER BY id DESC");
      $res = $conn->query($sql);

      if (!$res) {
        die($conn->error);
      }
      ?>
      <?php while ($row = $res->fetch_assoc()) { ?>
        <div class="card">
          <div class="card__avatar"></div>
          <div class="card__body">
            <div class="card__info">
              <span class="card__info__author"><?php echo $row['nickname'] ?></span>
              <span class="card__info__time"><?php echo $row['created_at'] ?></span>
            </div>
            <p class="card__content"><?php echo $row['content'] ?></p>
          </div>
        </div>
      <?php } ?>
    </section>
  </main>
</body>

</html>