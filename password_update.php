<?php
session_start();
require('library.php');
if (!isset($_SESSION["form"])) {
  header('Location: login.php');
  exit();
}


$form = $_SESSION['form'];

$db = dbconnect2();
$db->beginTransaction();
try {
  $stmt = $db->prepare('update members SET password=:password where id=:id;');
  $password = password_hash($form['new_password'], PASSWORD_DEFAULT);
  $id = $form['id'];
  $stmt->bindValue(':password', $password, PDO::PARAM_STR);
  $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
  $stmt->execute();
  $db->commit();
} catch (PDOException $e) {
  echo '不具合です' . $e->getMessage();
  $db->rollBack();
  exit();
}
$_SESSION = array();
session_destroy();

?>



<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="reset.css" />
  <link rel="stylesheet" href="style.css" />

  <title>筋トレメモ</title>
</head>

<body>
  <header id="header">
    <div class="wrapper">
      <p class="logo"><a href="top.php">筋トレメモ</a></p>
      <div class="hamburger-menu">
        <input type="checkbox" id="menu-btn-check">
        <label for="menu-btn-check" class="menu-btn"><span></span></label>
        <div class="menu-content">
          <ul>
            <li><a href="memo/post.php?id">メモする</a></li>
            <li><a href="index.php">投稿一覧</a></li>
            <li><a href="mypage.php">登録情報</a></li>
            <li><a href="logout.php">ログアウト</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>

  <p class=" thanks">編集が完了しました<br>再度ログインしてください</p>
  <div class="content">
    <a href="login.php" class="button">ログイン</a>
  </div>
</body>

</html>
