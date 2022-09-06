<?php
session_start();
require('library.php');
if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
  header('Location: login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = dbconnect();




  $stmt = $db->prepare('UPDATE members SET name = :name, email = :email WHERE members id = :id');
  if (!$stmt) {
    die($db->error);
  }
  $success = $stmt->execute(array(':name' => $_POST['name'], ':email' => $_POST['email'],));
  if (!$success) {
    die($db->error);
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="reset.css" />
  <link rel="stylesheet" href="style.css" />
  <title>更新完了</title>
</head>

<body>
  <h1 class="headline"><a href="">筋トレメモ</a>
  </h1>
  <ul class="nav-list">
    <li class="nav-list-item">
      <a href=" mypage.php?id=<?php echo h($id); ?>"><?php echo h($name); ?>様</a>
    </li>
    <li class="nav-list-item">
      <a href="logout.php">ログアウト</a>
    </li>
  </ul>
  </header>
  <div class="form-title">フォーム</div>
  <p class="thanks">更新が完了しました</p>
  <div class="content">
    <a href="index.php" class="button">戻る</a>
  </div>

</body>

</html>
