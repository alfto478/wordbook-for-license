<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>応用情報技術者用単語帳</title>
    </head>
    <body>
        <h1>応用情報技術者 単語帳 アプリケーション</h1>
        <form name="useridform" method="post" action="wordbook_start.php">
            設定したユーザーidを入力してください:
            <input type="text" name="userid"><br>
            ＊ユーザーidは半角数字4桁です<br>
            <input type="submit" value="次へ">
        </form>
        <a href="wordbook_register.php">新しくユーザーidを登録する</a>
    </body>
</html>