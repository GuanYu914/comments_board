<!-- 用戶註冊頁面 -->
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
    <h1 class="board__title">註冊</h1>
    <!-- 根據後端回傳的代碼，顯示錯誤訊息 -->
    <?php
    if (!empty($_GET['errCode'])) {
      if ($_GET['errCode'] === '1') {
        $msg = "錯誤：資料不齊全";
        echo "<h2 class='error'>$msg</h2>";
      } else if ($_GET['errCode'] === '2') {
        $msg = "錯誤：帳號已被使用，請使用另外一個帳號名稱";
        echo "<h2 class='error'>$msg</h2>";
      } else {
        $msg = "錯誤：不合法的錯誤代碼";
        echo "<h2 class='error'>$msg</h2>";
      }
    }
    ?>
    <form class="board__form" method="POST" action="handle_register.php">
      <div class="board__register">
        <div class="board__register__nickname">
          <span>暱稱：</span>
          <input type="text" name="nickname" />
        </div>
        <div class="board__register__btn">
          <a href="index.php">回留言板</a>
          <a href="login.php">登入</a>
        </div>
      </div>
      <div class="board__register">
        <div class="board__register__username">
          <span>帳號：</span>
          <input type="text" name="username" />
        </div>
      </div>
      <div class="board__register">
        <div class="board__register__password">
          <span>密碼：</span>
          <input type="password" name="password" />
        </div>
        <input class="board__submit-btn" type="submit" value="送出">
    </form>
  </main>
</body>

</html>