<?php
//dbに接続
//権限の付与に戸惑った
require_once "/home/alfto/worddb_create.php";
$pdo = worddb_connect();
$chapterName = ["基礎理論", "コンピュータ構成要素", "システム構成要素", "ソフトウェアとハードウェア",
"ヒューマンインタフェースとマルチメディア", "データベース", "ネットワーク", "セキュリティ", "システム開発技術",
"ソフトウェア開発管理技術", "マネジメント", "ストラテジ"];
try{
    //dbの名前はあとで変える
    $sql = "SELECT chapter FROM sample ORDER BY chapter DESC LIMIT 1";
    $stmh = $pdo->query($sql);
    $maxChapterNum = $stmh->fetch(PDO::FETCH_ASSOC)['chapter'];
    $stmh->closeCursor();
    $maxSectionNum = [];
    for($i = 1; $i <= $maxChapterNum; $i++){
        $sql = "SELECT section FROM sample WHERE chapter = :chapterNum ORDER BY section DESC LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':chapterNum', $i, PDO::PARAM_INT);
        $maxSectionNum[$i-1] = $stmh->fetch(PDO::FETCH_ASSOC)['section1'];
        $stmh->closeCursor();
    }
}catch (PDOException $Exception){
    print "error:" .$Exception->getMessage();
}
//一覧表を表示する際にユーザー個人での変更を反映させるためにsessionの情報を作る
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>応用情報技術者用単語帳</title>
    </head>
    <body>
        <header>
            <h1>応用情報技術者 単語帳 アプリケーション</h1>
            <h2>用語管理</h2>
        </header>
        <main>
            <form name="confirmForAll" method="git">
                <input type="checkbox" name="yesno[]" value="yes">この修正を全体に反映させるために管理人に知らせますか?
            </form>
<!-- 戻るボタンにはsesstionの情報をのせる必要があるのか要検討 -->
            <a href="wordbook_manage.php">戻る</a>
<!-- 下記リンクでcheckboxの内容をget形式で送る -->
            <a href="wordbook_confirmation.php?">保存</a>
        </main>
        <footer></footer>
    </body>
</html>