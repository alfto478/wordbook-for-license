<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>応用情報技術者用単語帳</title>
    </head>
<?php
session_start();
if(isset($_SESSION['userid']) == false || isset($_GET['status']) == false){
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
        $maxSectionNum[$i-1] = $stmh->fetch(PDO::FETCH_ASSOC)['section'];
        $stmh->closeCursor();
    }
}catch (PDOException $Exception){
    print "error:" .$Exception->getMessage();
}
?>
    <body>
        <header>
            <h1>応用情報技術者 単語帳 アプリケーション</h1>
            <h2>
                <?php
                if($_GET['status'] == 'set'){
                    print "用語管理・変更画面";
                }else if($_GET['status'] == 'confirmation'){
                    print "用語管理・変更確認画面";
                }else{
                    print "エラー";
                }
                ?>
            </h2>
        </header>
        <main>
                <?php
                if($_GET['status'] == 'set'){
                    //*******変更のあるものだけ送りたい
                    if($_GET['action'] == 'add'){
                        $str = "<form method=\"post\" action=\"wordbook_set.php?status=confirmation\">";
                        $str .= "章：<input name=\"chapter\" type=\"text\"value=\"" .$_GET['section']. "\">  節：<input name=\"section\" type=\"text\"><br>";
                        $str .= "登録する用語<input name=\"term\" type=\"text\"><br>";
                        //textareaの大きさ調整忘れずに
                        $str .= "用語に対する解説<input name=\"explanation\" type\"textarea\"><br>";
                        $str .= "<input type=\"submit\" value=\"追加\"><br>";
                        $str .= "<input name=\"id\" type=\"hidden\" value\"Noid\">";
                        $str .= "</form>";
                    }else if($_GET['action'] == 'edit'){
                        try{
                            $sql = "SELECT * FROM worddb_main_table WHERE id = :id";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                            $stmh->execute();
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
                        $row = $stmh->fetch(PDO::FETCH_ASSOC);
                        $str = "<form method=\"post\" action=\"wordbook_set.php?status=confirmation\">";
                        $str .= "章：<input name=\"chapter\" type=\"text\"value=\"" .$row['chapter']. "\">  節：<input name=\"section\" type=\"text\" value=\"" .$row['section']. "\"><br>";
                        $str .= "用語<input name=\"term\" type=\"text\" value=\"" .$row['term']. "\"><br>";
                        //textareaの大きさ調整忘れずに
                        $str .= "用語に対する解説<br><textarea name=\"explanation\" cols=\"100\" rows=\"25\">" .$row['explanation']. "</textarea><br>";
                        $str .= "<input type=\"submit\" value=\"保存\"><br>";
                        $str .= "<input name=\"id\" type=\"hidden\" value\"". $row['id'] ."\">";
                        $str .= "</form>";
                    }else if($_GET['action'] == 'remove'){
                        try{
                            $sql = "SELECT * FROM userinfo_table WHERE userid = :userid AND chapter = :chapter AND section = :section";
                            $stmh = $pdo->prepare($sql);
                            $stmh->bindValue(':userid', $_SESSION['userid'], PDO::PARAM_INT);
                            $stmh->bindValue(':chapter', $stmh['chapter'], PDO::PARAM_INT);
                            $stmh->bindValue(':section', $stmh['section'], PDO::PARAM_INT);
                            $stmh->execute();
                            $row = $stmh->fetch(PDO::FETCH_ASSOC);
                            $str = "<form method=\"post\" action=\"wordbook_set.php?status=confirmation\">";
                            $str .= "章：<input name=\"chapter\" type=\"text\"value=\"" .$row['chapter']. "\">  節：<input name=\"section\" type=\"text\" value=\"" .$row['section']. "\"><br>";
                            $str .= "用語<input name=\"term\" type=\"text\" value=\"" .$row['term']. "\"><br>";
                            //textareaの大きさ調整忘れずに
                            $str .= "用語に対する解説<br><textarea name=\"explanation\" cols=\"100\" rows=\"25\">" .$row['explanation']. "</textarea><br>";
                            $str .= "<input type=\"submit\" value=\"保存\"><br>";
                            $str .= "<input name=\"id\" type=\"hidden\" value\"". $row['id'] ."\">";
                            $str .= "</form>";
                            try{
                                $pdo->beginTransaction();
                                $sql = "DELETE FROM userinfo_table WHRER editid = :editid";
                                $stmh = $pdo->prepare($sql);
                                $stmh->bindValue(':editid', $_GET['editid'], PDO::PARAM_INT);
                                $stmh->execute();
                                $pdo->commit();
                            }catch (PDOException $Exception){
                                $pdo->rollBack();
                                print "error:" .$Exception->getMessage();
                            }
                        }catch (PDOException $Exception){
                            print "error:" .$Exception->getMessage();
                        }
                    }else{
                        $str = "エラーが発生しました下のリンクから用語管理画面に戻っていください<br>";
                    }
                    print $str;
                    ?>
                <a href="wordbook_manage.php">戻る</a>
                <?php
                }else if($_GET['status'] == 'confirmation'){
                ?>
                <main>
                    <?php
                    //*******POSTのどっかかけてたらNUllにする
                    //*******$_POST['id'] == 'Noid'なら仮想的にidを作る->ユーザーごとの方も見る
                    if($_POST['id'] == 'Noid'){}
                    try{
                        $pdo->beginTransaction();
                        $sql = "INERT INTO userinfo_table (userid, id, chapter, section, term, explanation) VALUES(:userid, :id, :chpater, :section, :term, :explanaion)";
                        $stmh = $pdo->prepare($sql);
                        $stmh->bindValue(':userid', $_SESSION['userid'], PDO::PARAM_INT);
                        $stmh->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
                        $stmh->bindValue(':chapter', $_POST['chapter'], PDO::PARAM_INT);
                        $stmh->bindValue(':section', $_POST['section'], PDO::PARAM_INT);
                        $stmh->bindValue(':term', $_POST['term'], PDO::PARAM_STR);
                        $stmh->bindValue(':explanation', $_POST['explanation'], PDO::PARAM_STR);
                        $stmh->execute();
                        $pdo->commit();
                    }catch (PDOException $Exception){
                        $pdo->rollBack();
                        print "error:" .$Exception->getMessage();
                    }
                    //*******$eidの値を取得
                    ?>
                <!-- 確認ように入力された内容を置く->用語の番号だけでおｋ -->
                    <form name="confirmForAll" method="post" action="wordbook_list.php">
                        <input type="checkbox" name="yesno" value="yes">この修正を全体に反映させるために管理人に知らせますか?<br>
                        <input type="hidden" name="confirmation" value="commit">
                        <input type="submit" value="保存">
                    </form>
                    <a href="wordbook_set.php?status=set&action=remove&editid=<?=$eid?>">戻る</a>
                </main>
                <?php
                }else{
                ?>
                エラーが発生しました下のリンクから用語管理画面に戻っていください<br>
                <a href="wordbook_manage.php">戻る</a>
                <?php
                }
                ?>
        </main>
        <footer></footer>
    </body>
<?php
}
?>
</html>