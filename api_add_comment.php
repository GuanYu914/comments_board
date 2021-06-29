
<?php
// 透過 api 拿到資料庫的留言資訊
require_once("conn.php");

// 拿到留言內容，如果為空回傳錯誤的 json 資訊a
if (empty($_POST['content'])) {
  $json = array(
    "isSuccessful" => false,
    "Msg" => "empty content"
  );
  $response = json_encode($json);
  echo $response;

  die();
}
$content = $_POST['content'];

$username = $_POST['username'];
// 拿到 username 跟留言內容之後，新增到 comments table 裡面
$sql = "INSERT INTO comments(username, content) VALUE(?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $content);
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

// 如果 sql 成功，回傳成功 json格式的訊息
$json = array(
  "isSuccessful" => true
);
$response = json_encode($json);
echo $response;
header("Content-type:application/json;charset=utf-8");
die();
?>