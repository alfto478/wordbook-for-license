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
            <form name="textSetting">
                <?php
                $str = "章：<input type=\"text\"";
                if($_GET['action'] == 'add'){
                    $str .= "value=\"" .$_GET['section']. "\">  節：<input type=\"text\"><br>";
                    $str .= "登録する用語<input type=\"text\"><br>";
                    //textareaの大きさ調整忘れずに
                    $str .= "用語に対する解説<input type\"textarea\"><br>";
                }else if($_GET['action' == 'edit']){
                    try{
                        $sql = "SELECT * FROM sample WHERE id = :id";
                        $stmh = $pdo->prepare($sql);
                        $stmh->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $stmh->execute();
                    }catch (PDOException $Exception){
                        print "error:" .$Exception->getMessage();
                    }
                    $row = $stmh->fetch(PDO::FETCH_ASSOC);
                    $str .= "value=\"" .$row['section']. "\">  節：<input type=\"text\" value=\"" .$row['chapter']. "\"><br>";
                    $str .= "用語<input type=\"text\" value=\"" .$row['word']. "\"><br>";
                    //textareaの大きさ調整忘れずに
                    $str .= "用語に対する解説<input type\"textarea\" value=\"" .$row['explanation']. "\"><br>";
                }
                ?>
            </form>
            <a href="wordbook_list.php">戻る</a>
            <?php
            if($_GET['action'] == "edit") $word = "保存";
            else if($_GET['action'] == "add") $word = "追加";
            print "<a href=\"wordbook_confirmation.php\">" .$word. "</a>";
            ?>
        </main>
        <footer></footer>
    </body>
</html>