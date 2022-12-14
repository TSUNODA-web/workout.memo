<?php
session_start();
require('library.php');

if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
  $member_id = $_SESSION['id'];
  $name = $_SESSION['name'];
} else {
  header('Location: login.php');
  exit();
}

$db = dbconnect2();
//ログインしているユーザーのメモの件数を取得
try {
  $stmt = $db->prepare('select count(*) as cnt from posts WHERE member_id =:member_id');
  $stmt->bindValue(':member_id', (int)$member_id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch();
} catch (PDOException $_e) {
  echo '不具合です' . $e->getMessage();
  $db->rollBack();
  exit();
}
//変数に何も入ってこなければ１を代入
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT);
$page = ($page ?: 1);
$start = ($page - 1) * 6;
$max_page = floor(($result['cnt'] + 1) / 6 + 1);


if ($page > $max_page) {
  header('Location:index.php');
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
  </header>
  <?php
  $db = dbconnect2();
  $stmt = $db->prepare('select p.id, p.member_id, p.created, p.part, p.picture from posts p where p.member_id=:member_id order by p.id desc limit :start,6');
  $stmt->bindValue(':member_id', (int)$member_id, PDO::PARAM_INT);
  $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  ?>

  <main>
    <div class="caption">
      <h1>過去の自分を越えよう</h1>
    </div>
    <?php if (!$result) : ?>
      <div class="thanks">
        <h1>投稿がありません</h1>
      </div>
    <?php endif; ?>
    <div class="bl_media_container">
      <?php foreach ($result as $memo) { ?>
        <div class="bl_media_itemWrapper">
          <?php if ($memo['picture']) : ?>
            <div class="bl_media_item"><a href="edit.php?id=<?php echo $memo['id']; ?>">
                <p class="img"><img src="picture/<?php echo h($memo['picture']); ?>" alt=""></p>
              </a>
            <?php else : ?>
              <div class="bl_media_item"><a href="edit.php?id=<?php echo h($memo['id']); ?>">
                  <p class="img"><img src="empty_image/20200501_noimage.jpg" alt=""></p>
                </a>
              <?php endif; ?>
              </div>
              <P>[部位]<?php echo h($memo['part']); ?></P>
              <p>[投稿日]<?php echo h($memo['created']); ?></p>
            </div>
          <?php } ?>
        </div>
    </div>

    <div class="btn-area">
      <div class="pagination">
        <?php if ($page > 1) : ?>
          <a href="index.php?page=<?php echo $page - 1; ?>"><?php echo $page - 1; ?>ページ目へ</a>
        <?php endif ?>
        <?php if ($page < $max_page) : ?>
          <a href="index.php?page=<?php echo $page + 1; ?>"><?php echo $page + 1; ?>ページ目へ</a>
        <?php endif ?>
      </div>

      </section>
  </main>




</body>

</html>
