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

// フィルタリングの基準と検索クエリを取得
$filter = $_GET['filter'] ?? 'task_name';
$searchQuery = $_GET['search'] ?? '';

// タスクを取得
if ($searchQuery === '') {
    $sql = "SELECT * FROM tasks";
    $result = pg_query($dbconn, $sql);
} else {
    if ($filter === 'task_level') {
        $sql = "SELECT * FROM tasks WHERE $filter = $1";
        $result = pg_query_params($dbconn, $sql, array((int)$searchQuery));
    } else {
        $sql = "SELECT * FROM tasks WHERE $filter LIKE $1";
        $result = pg_query_params($dbconn, $sql, array('%' . $searchQuery . '%'));
    }
}
$tasks = pg_fetch_all($result);
?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            /* ページ全体のスタイル */
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }

            /* ヘッダーのスタイル */
            .header {
                background-color: #333;
                color: #fff;
                padding: 10px 0;
                text-align: center;
            }

            /* タスクボードのスタイル */
            .task-board {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-around;
                    margin: 20px;
                }

                /* 各タスクのスタイル */
                .task {
                    background-color: #f9f9f9;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
                    margin-bottom: 20px;
                    padding: 20px;
                    text-align: center;
                    width: calc(30% - 40px);
                }

                .task a {
                    color: inherit;
                    text-decoration: none;
                }

                .task h3 {
                    color: #333;
                    font-size: 20px;
                    margin-top: 0;
                }

                .task p {
                    color: #666;
                    font-size: 16px;
                }
            /* ホームページに移動するボタンのスタイル */
            .home-button {
                background-color: #333;
                border: none;
                color: #fff;
                cursor: pointer;
                display: block;
                margin: 20px auto;
                padding: 10px 20px;
                text-decoration: none;
                width: 200px;
            }

            .home-button:hover {
                background-color: #444;
            }
            /* 検索フォームのスタイル */
            .search-form {
                display: flex;
                justify-content: center;
                margin: 20px 0;
            }

            .search-form select, .search-form input[type="text"] {
                border: none;
                padding: 10px;
                margin-right: 10px;
                font-size: 16px;
            }

            .search-form input[type="submit"] {
                background-color: #ff0000;
                color: #fff;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
            }

            .search-form input[type="submit"]:hover {
                background-color: #cc0000;
            }
            /* モーダルウィンドウのスタイル */
            .create-button {
                background-color: #ff0000;
                color: #fff;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .create-button:hover {
                background-color: #cc0000;
            }

            .modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }

            /* モーダルウィンドウの内容のスタイル */
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto; /* 15% from the top and centered */
                padding: 20px;
                border: 1px solid #888;
                width: 80%; /* Could be more or less, depending on screen size */
                border-radius: 10px;
                overflow: hidden;
            }

            /* タスク作成フォームのスタイル */
            .task-form {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .task-form input[type="text"],
            .task-form textarea,
            .task-form input[type="date"],
            .task-form input[type="number"] {
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 16px;
            }

            .task-form input[type="submit"] {
                background-color: #ff0000;
                color: #fff;
                border: none;
                padding: 10px;
                cursor: pointer;
                font-size: 16px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .task-form input[type="submit"]:hover {
                background-color: #cc0000;
            }
            /* 閉じるボタンのスタイル */
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                font-size: 32px;
                transition: color 0.3s ease;
            }

            .close:hover {
                color: #888;
            }
        </style>
    </head>
<body>

<div class="header">
    <h2>タスクボード</h2>
</div>

<!-- 検索フォーム -->
<form action="index.php" method="get" class="search-form">
    <select name="filter">
        <option value="task_name"<?php if ($filter === 'task_name') echo ' selected'; ?>>タスク名</option>
        <option value="task_manager"<?php if ($filter === 'task_manager') echo ' selected'; ?>>管理者</option>
        <option value="task_level"<?php if ($filter === 'task_level') echo ' selected'; ?>>タスクレベル</option>
    </select>
    <input type="text" name="search" placeholder="検索" value="<?php echo htmlspecialchars($searchQuery); ?>">
    <input type="submit" value="検索">
</form>

<div class="task-board">
    <?php foreach ($tasks as $task): ?>
        <div class="task">
            <a href="task_detail.php?id=<?php echo $task['id']; ?>">
                <h3><?php echo htmlspecialchars($task['task_name']); ?></h3>
                <p>管理者: <?php echo htmlspecialchars($task['task_manager']); ?></p>
                <p>ステータス: <?php echo htmlspecialchars($task['task_status']); ?></p>
                <p>タスクレベル: <?php echo htmlspecialchars($task['task_level']); ?></p>
            </a>
        </div>
    <?php endforeach; ?>
</div>

<!-- タスク作成ボタン -->
<button id="createTaskButton" class="create-button">タスクを作成</button>

<!-- タスク作成フォームのモーダルウィンドウ -->
<div id="createTaskModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="create_task.php" method="post" class="task-form">
            <label for="task_name">タスク名:</label><br>
            <input type="text" id="task_name" name="task_name" required><br>
            <label for="task_detail">詳細:</label><br>
            <textarea id="task_detail" name="task_detail" required></textarea><br>
            <label for="task_manager">管理者:</label><br>
            <input type="text" id="task_manager" name="task_manager" required><br>
            <label for="task_status">ステータス:</label><br>
            <input type="text" id="task_status" name="task_status" required><br>
            <label for="start_date">開始日:</label><br>
            <input type="date" id="start_date" name="start_date" required><br>
            <label for="end_date">終了日:</label><br>
            <input type="date" id="end_date" name="end_date" required><br>
            <label for="task_level">タスクレベル:</label><br>
            <input type="number" id="task_level" name="task_level" required><br>
            <input type="submit" value="タスクを作成" class="submit-button">
        </form>
    </div>
</div>

<script>
// モーダルウィンドウとボタンを取得
var modal = document.getElementById("createTaskModal");
var btn = document.getElementById("createTaskButton");

// ボタンをクリックするとモーダルウィンドウを表示
btn.onclick = function() {
    modal.style.display = "block";
}

// モーダルウィンドウの閉じるボタンを取得
var span = document.getElementsByClassName("close")[0];

// 閉じるボタンをクリックするとモーダルウィンドウを閉じる
span.onclick = function() {
    modal.style.display = "none";
}

// ユーザーがモーダルウィンドウの外をクリックするとモーダルウィンドウを閉じる
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>


<a href="edit.php" class="home-button">登録内容の変更はこちら</a>

</body>
</html>

