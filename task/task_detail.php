<?php
// PostgreSQLデータベースの接続情報
$host = 'localhost';
$db   = 'yasu';
$user = 'yasu';
$pass = 'MCbKHL68';

// pg_connect関数を使用してデータベースに接続
$dbconn = pg_connect("host=$host dbname=$db user=$user password=$pass");

if (!$dbconn) {
    echo "データベースへの接続に失敗しました。\n";
    exit;
}

// タスクIDを取得
$taskId = $_GET['id'];

// タスクを取得
$sql = "SELECT * FROM tasks WHERE id = $1";
$result = pg_query_params($dbconn, $sql, array($taskId));
$task = pg_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<body>

<h2>タスク詳細</h2>

<p>タスク名: <?php echo htmlspecialchars($task['task_name']); ?></p>
<p>詳細: <?php echo htmlspecialchars($task['task_detail']); ?></p>
<p>管理者: <?php echo htmlspecialchars($task['task_manager']); ?></p>
<p>ステータス: <?php echo htmlspecialchars($task['task_status']); ?></p>
<p>依頼日: <?php echo htmlspecialchars($task['request_date']); ?></p>
<p>開始日: <?php echo htmlspecialchars($task['start_date']); ?></p>
<p>終了日: <?php echo htmlspecialchars($task['end_date']); ?></p>
<p>タスクレベル: <?php echo htmlspecialchars($task['task_level']); ?></p>

<!-- ホームページに移動するボタン -->
<a href="index.php">タスクボードに戻る</a>

</body>
</html>
