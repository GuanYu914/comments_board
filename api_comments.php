
<?php
// 透過 api 拿到資料庫的留言資訊
require_once("conn.php");

$comments = array();

// 根據 GET 拿到頁數
$page = 1;
if (!empty($_GET['page'])) {
  $page = intval($_GET['page']);
}
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

$sql =
  "SELECT C.id as id, C.created_at as created_at, U.nickname as nickname, U.username as username, C.content as content " .
  "FROM comments as C LEFT JOIN users as U " .
  "ON C.username = U.username " .
  "WHERE C.is_deleted is NULL " .
  "ORDER BY C.created_at DESC " .
  "limit ? offset ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$res = $stmt->execute();

// 如果 sql 失敗，回傳失敗 json格式的訊息
if (!$res) {
  $json = array(
    "isSuccessful" => false,
    "Msg" => $conn->errno
  );
  $response = json_encode($json);
  echo $response;

  die();
}
$res = $stmt->get_result();


// 抓取相對應留言資訊
while ($row = $res->fetch_assoc()) {
  array_push($comments, array(
    "id" => $row['id'],
    "username"  => $row['username'],
    "nickname"  => $row['nickname'],
    "content"   => $row['content'],
    "created_at"=> $row['created_at']
  ));
}

$json = array(
  "comments" => $comments
);

// 打包到成 json 格式的 response
$response = json_encode($json);
// 設定回傳格式為 json
header("Content-type:application/json;charset=utf-8");
echo $response;
?>