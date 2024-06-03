document.addEventListener('DOMContentLoaded', function() {
    console.log("スクリプトが読み込まれました");

    function confirmDelete(event) {
        event.preventDefault(); // フォームのデフォルトの送信をキャンセル
        console.log("削除ボタンがクリックされました");

        const confirmed = confirm("本当にこの商品を削除しますか？");
        console.log("削除確認ダイアログが表示されました");

        if (confirmed) {
            // ユーザーが確認した場合、フォームを送信
            console.log("ユーザーが確認しました");
            event.target.submit();
        } else {
            // ユーザーがキャンセルした場合、何もしない
            console.log("ユーザーがキャンセルしました");
            alert("削除がキャンセルされました。");
        }
    }

    // 各削除フォームにイベントリスナーを追加
    document.querySelectorAll('form.confirm-delete').forEach(function(form) {
        console.log("イベントリスナーを追加しました", form);
        form.addEventListener('submit', confirmDelete);
    });
});

