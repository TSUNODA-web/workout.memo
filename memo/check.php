<?php
session_start();
require('/Applications/MAMP/htdocs/workout.memo/library.php');

if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
  $id = $_SESSION['id'];
  $form = $_SESSION['form'];
} else {
  header('Location: login.php');
  exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = dbconnect();
  $db->begin_transaction();
  $stmt = $db->prepare('insert into memos(member_id,weight,part,memo) VALUES(?,?,?,?)');
  if (!$stmt) {
    die($db->error);
  }

  $stmt->bind_param('iiss', $form['member_id'], $form['weight'], $form['part'], $form['memo']);
  $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
  header('Location:thanks.php');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../reset.css" />
  <link rel="stylesheet" href="../style.css" />

  <title>Document</title>
</head>


<body>
  <header>
    <h1 class="headline"><a href="">筋トレメモ</a>
    </h1>
    <ul class="nav-list">
      <li class="nav-list-item">
        <a href=" mypage.php?id=<?php echo h($id); ?>">マイページ</a>
      </li>
      <li class="nav-list-item">
        <a href="logout.php">ログアウト</a>
      </li>
    </ul>
  </header>
  <div class="form-title">メモ確認</div>
  <div class="form-content">
    <form action="" method="post">
      <div class="form-list">
        <label>体重</label>
        <p class="check-list"><?php echo h($form['weight']); ?></p>
      </div>
      <div class="form-list">
        <label>部位</label>
        <p class="check-list"><?php echo h($form['part']); ?></p>

      </div>
      <div class="form-list">
        <label>メモ</label>
        <p class="check-list"><?php echo h($form['memo']); ?></p>
      </div>
      <div class="form-list">
        <label>メモ</label>
        <p class="check-list"><?php echo h($form['member_id']); ?></p>
      </div>

      <div class="btn-area">
        <a href="post.php?action=rewrite" class="button">書き直す</a>
        <input type="submit" value="メモする" />
      </div>
    </form>
  </div>

</body>

</html>
