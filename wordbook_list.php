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
            <h2>一覧表</h2>
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
            //一覧表を作成
            $chapterNum = 1;
            do{
                try{
                    $str = "<h4>第{$chapterNum}章 {$chapterName[$chapterNum-1]}</h4><br>";
                    $sql = "SELECT * from sample WHERE chapter = :chapterNum";
                    $stmh = $pdo->prepare($sql);
                    $stmh->bindValue(':chapterNum', $chapterNum, PDO::PARAM_INT);
                    $stmh->execute();
                }catch (PDOException $Exception){
                    print "error:" .$Exception->getMessage();
                }
                $chapterNum++;
                $str .= "<table border=\"2\">";
                while($row = $stmh->fetch(PDO::FETCH_ASSOC)){
                    $str .= "<tr><td>". htmlspecialchars($row["id"], ENT_QUOTES). "</td><td>" .htmlspecialchars($row["chapter"], ENT_QUOTES). "章 - " .htmlspecialchars($row["section"], ENT_QUOTES). "節</td><td rowspan=\"2\" width=\"65%\">" .htmlspecialchars($row["explanation"], ENT_QUOTES). "</td></tr>";
                    $str .= "<tr><td colspan=\"2\">" .htmlspecialchars($row["term"], ENT_QUOTES). "</td></tr>";
                }
                $str .= "</table><br><br>";
                print $str;
            }while($chapterNum <= $maxChapterNum);
            ?>
            <a href="wordbook_start.html">戻る</a>
            <?php
            //ハードがPCなら表示
            if(!(preg_match('/iPhone|iPod|iPad/ui', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Android/ui', $_SERVER['HTTP_USER_AGENT']))) print "<a href=\"wordbook_manage.php\">管理画面</a>";
            ?>
        </main>
        <footer></footer>
    </body>
</html>