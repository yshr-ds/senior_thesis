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

$task_name = $_POST['task_name'];
$task_detail = $_POST['task_detail'];
$task_manager = $_POST['task_manager'];
$task_status = $_POST['task_status'];
$request_date = date('Y-m-d H:i:s'); // 現在の日時を取得
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$task_level = $_POST['task_level'];

// タスクをデータベースに登録
$sql = "INSERT INTO tasks (task_name, task_detail, task_manager, task_status, request_date, start_date, end_date, task_level) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)";
$result = pg_query_params($dbconn, $sql, array($task_name, $task_detail, $task_manager, $task_status, $request_date, $start_date, $end_date, $task_level));

// タスクボードにリダイレクト
header('Location: index.php');
?>
