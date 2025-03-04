<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>応用情報技術者用単語帳</title>
    </head>
<?php
session_start();
if(isset($_SESSION['userid'])){
?>
    <body>
        エラーが発生しました下のリンクからログイン画面に戻っていください<br>
        <a href="wordbook_login.php">ログイン画面に戻る</a>
    </body>
<?php
}else{
//dbに接続
//権限の付与に戸惑った
require_once "/home/alfto/worddb_create.php";
$pdo = worddb_connect();
$chapterName = ["基礎理論", "コンピュータ構成要素", "システム構成要素", "ソフトウェアとハードウェア",
"ヒューマンインタフェースとマルチメディア", "データベース", "ネットワーク", "セキュリティ", "システム開発技術",
"ソフトウェア開発管理技術", "マネジメント", "ストラテジ"];
try{
    //dbの名前はあとで変える
    $sql = "SELECT chapter FROM worddb_main_table ORDER BY chapter DESC LIMIT 1";
    $stmh = $pdo->query($sql);
    $maxChapterNum = $stmh->fetch(PDO::FETCH_ASSOC)['chapter'];
    $stmh->closeCursor();
    $maxSectionNum = [];
    for($i = 1; $i <= $maxChapterNum; $i++){
        $sql = "SELECT section FROM worddb_main_table WHERE chapter = :chapterNum ORDER BY section DESC LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(':chapterNum', $i, PDO::PARAM_INT);
        $maxSectionNum[$i-1] = $stmh->fetch(PDO::FETCH_ASSOC)['section1'];
        $stmh->closeCursor();
    }
}catch (PDOException $Exception){
    print "error:" .$Exception->getMessage();
}
?>
    <body>
        <header>
            <h1>応用情報技術者 単語帳 アプリケーション</h1>
            <h2>用語管理</h2>
            <!-- 章、節の選択を設定 機能はjsで実装 -->
            <div>
                <?php
                for($i = 0; $i < $maxChapterNum; $i++){
                    $ii = $i + 1;
                    print "<div>{$ii}章 {$chapterName[$i]}</div>";
                }
                print "<br>";
                ?>
            </div>
        </header>
        <main>
            <?php
            //一覧表（管理版）を作成
            $chapterNum = 1;
            do{
                $str = "<h4>第{$chapterNum}章 {$chapterName[$chapterNum-1]}</h4><br>";
                try{
                    $sql = "SELECT * FROM worddb_main_table WHERE chapter = :chapterNum";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':chapterNum', $chapterNum, PDO::PARAM_INT);
                    $stmh->execute();
                }catch (PDOException $Exception){
                    print "error:" .$Exception->getMessage();
                }
                $str .= "<table border=\"2\">";
                while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
                    try{
                        $sql = "SELECT * FROM userinfo_table WHERE userid = :userid AND chapter = :chapter AND section = :section";
                        $stmh_jm = $pdo->prepare($sql);
                        $stmh_jm->bindValue(':userid', $_SESSION['userid'], PDO::PARAM_INT);
                        $stmh_jm->bindValue(':chapter', $stmh['chapter'], PDO::PARAM_INT);
                        $stmh_jm->bindValue(':section', $stmh['section'], PDO::PARAM_INT);
                        $stmh_jm->execute();
                        if($stmh_jm->rowCount == 1) $row = $stmh_jm->fetch(PDO::FETCH_ASSOC);
                    }catch (PDOException $Exception){
                        print "error:" .$Exception->getMessage();
                    }
                    $str .= "<tr><td>". htmlspecialchars($row["id"], ENT_QUOTES). "</td><td>" .htmlspecialchars($row["chapter"], ENT_QUOTES). "章 - " .htmlspecialchars($row["section"], ENT_QUOTES). "節</td>
                    <td rowspan=\"2\" width=\"55%\">" .htmlspecialchars($row["explanation"], ENT_QUOTES). "</td><td rowspan=\"2\" width=\"10%\"><a href=\"wordbook_set.php?id=" .$row["id"]. "&action=edit&status=set\">変更する</a></td></tr>";
                    $str .= "<tr><td colspan=\"2\">" .htmlspecialchars($row["term"], ENT_QUOTES). "</td></tr>";
                }
                $str .= "<tr><td colspan=\"4\"><a href=\"wordbook_set.php?section=" .$chapterNum. "&action=add&status=set\">項目を追加する</a></td></tr></table><br><br>";
                print $str;
                $chapterNum++;
            }while($chapterNum <= $maxChapterNum);
            ?>
            <a href="wordbook_list.php?">戻る</a>
        </main>
        <footer></footer>
    </body>
<?php
}
?>
</html>