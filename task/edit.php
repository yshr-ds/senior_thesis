<?php
session_start();

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

// ユーザーがフォームを送信した場合
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $position = $_POST['position'];

    $sql = "UPDATE member SET name = $1, email = $2, department = $3, year = $4, position = $5 WHERE id = $6";
    $result = pg_query_params($dbconn, $sql, array($name, $email, $department, $year, $position, $_SESSION['id']));

    if (!$result) {
        echo "クエリの実行に失敗しました。\n";
        exit;
    }

    echo "登録内容を更新しました。";
}

// 現在のユーザー情報を取得
$sql = "SELECT * FROM member WHERE id = $1";
$result = pg_query_params($dbconn, $sql, array($_SESSION['id']));
$user = pg_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            input[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                border: none;
                background-color: #007BFF;
                color: #fff;
                border-radius: 4px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #0056b3;
            }
            body {
                font-family: Arial, sans-serif;
            }

            form {
                width: 300px;
                margin: 0 auto;
            }

            label {
                display: block;
                margin-top: 20px;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            input[type="submit"] {
                display: block;
                width: 100%;
                padding: 10px;
                margin-top: 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #45a049;
            }
            /* ホームページに移動するボタンのスタイル */
            .home-button {
                display: block;
                width: 100%;
                padding: 10px;
                margin-top: 20px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
                text-align: center;
            }

            .home-button:hover {
                background-color: #0056b3;
            }

        </style>
    </head>
<body>

<h2>登録内容変更フォーム</h2>

<form action="edit.php" method="post">
  <label for="name">名前:</label><br>
  <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br>
  <label for="email">連絡先:</label><br>
  <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br>
  <label for="department">部署:</label><br>
  <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($user['department']); ?>"><br>
  <label for="year">勤務年数:</label><br>
  <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($user['year']); ?>"><br>
  <label for="position">役職:</label><br>
  <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>"><br>
  <input type="submit" value="更新">
</form>

<!-- ホームに移動するボタン -->
<a href="index.php" class="home-button">ホームに戻る</a>

</body>
</html>