<!-- 留言板首頁 -->
<?php
// 確認沒有設置 SESSION 再啟用 SESSION
if (!isset($_SESSION)) {
  session_start();
}
require_once("conn.php");
require_once("utils.php");

/**
 * 從 cookie 裡面讀取 PHPSESSID(token)
 * 從檔案裏面讀取 session id 內容
 * 放到 $_SESSION
 */
$username = null;
if (!empty($_SESSION['username'])) {
  $username = $_SESSION['username'];
  $nickname = getUserFromUsername($username)['nickname'];
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
        <h2 class="board__user__nickname">歡迎回來～<?php echo escape($nickname) ?></h2>
        <div class="board__user__btn">
          <a class="board__user__btn__edit_nickname">編輯暱稱</a>
          <a href="logout.php">登出</a>
        </div>
      </div>
      <form class="hidden board__nickname__form" action="handle_update_user.php" method="POST">
        修改暱稱：<input type="text" name="nickname" placeholder="輸入您的新暱稱">
        <input class="board__submit-btn" type="submit" value="送出">
      </form>
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
      // 根據 GET 拿到頁數
      $page = 1;
      if (!empty($_GET['page'])) {
        $page = intval($_GET['page']);
      }
      // 每一頁有多少筆資料
      $items_per_page = 10;
      // 計算出每一頁初始資料的偏移量，就是 (分頁數 - 1) * 每頁有多少筆資料
      $offset = ($page - 1) * $items_per_page;
      // 為了使修改過後的 nickname 能夠同步到之前的留言
      // 根據 comments 的 username 找出 users 的 nickname
      $sql =
        "SELECT C.id as comment_id, C.created_at as created_at, U.nickname as nickname, U.username as username, C.content as content " .
        "FROM comments as C LEFT JOIN users as U " .
        "ON C.username = U.username " .
        "WHERE C.is_deleted is NULL " .
        "ORDER BY C.created_at DESC " .
        "limit ? offset ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ii", $items_per_page, $offset);
      $res = $stmt->execute();
      if (!$res) {
        die($conn->error);
      }
      $res = $stmt->get_result();
      ?>
      <!-- 將 nickname、content 做 XSS 處理 -->
      <?php while ($row = $res->fetch_assoc()) { ?>
        <div class="card">
          <div class="card__avatar"></div>
          <div class="card__body">
            <div class="card__info">
              <span class="card__info__author"><?php echo escape($row['nickname']) ?>
                (@<?php echo escape($row['username']) ?>)</span>
              <span class="card__info__time"><?php echo escape($row['created_at']) ?></span>
              <?php if ($row["username"] === $username) { ?>
                <a class="card__info__edit" href="update_comment.php?id=<?php echo escape($row['comment_id']) ?>">編輯留言</a>
                <a class="card__info__edit" href="handle_delete_comment.php?id=<?php echo escape($row['comment_id']) ?>">刪除留言</a>
              <?php } ?>
            </div>
            <p class="card__content"><?php echo escape($row['content']) ?></p>
          </div>
        </div>
      <?php } ?>
    </section>
    <div class="board__hr"></div>
    <?php
    $sql =
      "SELECT COUNT(id) as count FROM comments WHERE is_deleted is NULL";
    $stmt = $conn->prepare($sql);
    // $stmt->bind_param("ii", $items_per_page, $offset);
    $res = $stmt->execute();
    if (!$res) {
      die($conn->error);
    }
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $count = $row['count'];
    // ceil 回傳是 float 型態，因為之後要跟 int 比較，所以用 intval 轉成 int 型態
    $total_pages = intval(ceil($count / $items_per_page));
    ?>
    <div class="page">
      <span class="page__info">總共有 <?php echo $count ?> 筆留言，目前分頁為 <?php echo $page ?> / <?php echo $total_pages ?></span>
      <div class="page__paginator">
        <?php if ($page !== 1) { ?>
          <a href="index.php?page=1">第一頁</a>
          <a href="index.php?page=<?php echo $page - 1 ?>">上一頁</a>
        <?php } ?>
        <?php if ($page !== $total_pages) { ?>
          <a href="index.php?page=<?php echo $page + 1 ?>">下一頁</a>
          <a href="index.php?page=<?php echo $total_pages ?>">最後一頁</a>
        <?php } ?>
      </div>
    </div>
  </main>
  <script src="index.js"></script>
</body>

</html>