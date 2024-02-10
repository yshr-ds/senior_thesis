<!DOCTYPE html>
<html>
  <head>
    <style>
      body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
    }

    h2 {
        color: #333;
        text-align: center;
        margin-top: 50px;
    }

    form {
        width: 300px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
        color: #666;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
        box-sizing: border-box;
    }

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

    </style>
  </head>
<body>

<h2>社員情報ログインフォーム</h2>

<form action="login.php" method="post">
  <label for="id">社員ID:</label><br>
  <input type="text" id="id" name="id"><br>
  <label for="password">社員PW:</label><br>
  <input type="text" id="password" name="password"><br>
  <input type="submit" value="ログイン">
</form>
<?php
$host = 'localhost';
$db   = 'yasu';
$user = 'yasu';
$pass = 'MCbKHL68';


$dbconn = pg_connect("host=$host dbname=$db user=$user password=$pass");

$id = $_POST['id'];
$pd = $_POST['password'];

$sql = "SELECT * FROM member WHERE id = $1 and password = $2";
$result = pg_query_params($dbconn, $sql, array($id, $pd));
$user = pg_fetch_assoc($result);

if ($user) {
    session_start();
    $_SESSION['id'] = $user['id'];
    echo "ログイン成功";
    header('Location: edit.php');
} else {
    echo "ログイン失敗";
}
?>


</body>
</html>
