<?php
session_start();
if(isset($_SESSION['userid'])) $_SESSION['userid'] = $_POST['userid'];
require_once "/home/alfto/worddb_create.php";
try{
    $pdo = worddb_connect();
    $sql = "SELECT userid FROM for_user_table WHERE userid = :userid";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(':userid', htmlspecialchars($_POST['userid'], ENT_QUOTES), PDO::PARAM_INT);
    $stmh->execute();
}catch (PDOException $Exception){
    print "error:" .$Exception->getMessage();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>応用情報技術者用単語帳</title>
    </head>
<!--
    追加したい機能
    ・ユーザーページを作って成績を出す
    ・最終ログイン日時から不要なアカウントを見つける
    ・useridをcoockieで自動入力できるようにする
    ・useridの間違った回数によって入力、通信の制御機能
    ・履歴表示(パンくずリスト)
    ・wordbook_setに初期化ボタンを配置
    ・通知ボタンで本体DB（初期値）の変更を通知
    ・footerにコメント送信のフォームを作る
    ・mainDBのtermに同じものがあるかをCHECKできるようにする
-->
<?php
if($stmh->rowCount() == 1){
    if(isset($_SESSION['userid'])) $_SESSION['userid'] = htmlspecialchars($_POST['userid'], ENT_QUOTES);
?>
    <body>
        <h1>応用情報技術者 単語帳 アプリケーション</h1>
        <a href=""><h3>いざ実践</h3></a>
        <a href="wordbook_list.php"><h3>一覧表・用語管理</h3></a>
    </body>
<?php
}else{
?>
    <body>
        <h1>応用情報技術者 単語帳 アプリケーション</h1>
        入力されたuseridが間違っているか登録されていません下のリンクより再度お試しください<br>
        <a href="wordbook_login.php">戻る</a>
    </body>
<?php
}
?>
</html>