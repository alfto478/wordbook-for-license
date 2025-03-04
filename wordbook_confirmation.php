        <header>
            <h1>応用情報技術者 単語帳 アプリケーション</h1>
            <h2>用語管理・変更確認画面</h2>
        </header>
        <main>
<!-- 確認ように入力された内容を置く->用語の番号だけでおｋ -->
            <form name="confirmForAll" method="post" action="wordbook_list.php">
                <input type="checkbox" name="yesno" value="yes">この修正を全体に反映させるために管理人に知らせますか?<br>
                <input type="hidden" name="confirmation" value="commit">
                <input type="submit" value="保存">
            </form>
<!-- 戻るボタンにはsesstionの情報をのせる必要があるのか要検討 -->
            <a href="wordbook_set.php">戻る</a>
        </main>