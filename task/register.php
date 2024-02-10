<!DOCTYPE html>
<html>
  <head>
  <style>
body {
  font-family: Arial, sans-serif;
}

form {
  width: 300px;
  margin: 0 auto;
}

input[type="text"] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  box-sizing: border-box;
}

input[type="submit"] {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

input[type="submit"]:hover {
  opacity: 0.8;
}

a {
  display: block;
  width: 100px;
  height: 25px;
  background: #4CAF50;
  padding: 10px;
  text-align: center;
  border-radius: 5px;
  color: white;
  font-weight: bold;
  line-height: 25px;
  margin: 20px auto;
  text-decoration: none;
}
.heading16 {
	position: relative;
	padding-bottom: 20px;
	font-size: 26px;
	text-align: center;
}

.heading16::after {
	content: '';
	position: absolute;
	bottom: 0;
	left: 50%;
	transform: translateX(-50%);
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 10px 6px 0 6px;
	border-color: #b99a00 rgba(0,0,0,0) rgba(0,0,0,0) rgba(0,0,0,0);
}
</style>
  </head>
<body>

<h2 class="heading16">社員情報登録フォーム</h2>

<form action="register.php" method="post">
  <label for="id">社員id:</label><br>
  <input type="text" id="id" name="id"><br>
  <label for="name">名前:</label><br>
  <input type="text" id="name" name="name"><br>
  <label for="email">連絡先:</label><br>
  <input type="text" id="email" name="email"><br>
  <label for="department">部署:</label><br>
  <input type="text" id="department" name="department"><br>
  <label for="year">勤務年数:</label><br>
  <input type="text" id="year" name="year"><br>
  <label for="position">役職:</label><br>
  <input type="text" id="position" name="position"><br>
  <label for="password">パスワード:</label><br>
  <input type="text" id="password" name="password"><br>
  <input type="submit" value="登録">
</form>
<a href="login.php">ログインページに移動</a>

<?php
// ini_set('display_errors', 1);
$host = 'localhost';
$db   = 'yasu';
$user = 'yasu';
$pass = 'MCbKHL68';

$dbconn = pg_connect("host=$host dbname=$db user=$user password=$pass");

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$department = $_POST['department'];
$year = $_POST['year'];
$position = $_POST['position'];
$password = $_POST['password'];


$sql = "INSERT INTO member (id, name, email, department, year, position, password) VALUES ($1, $2, $3, $4, $5, $6, $7)";
$result = pg_query_params($dbconn, $sql, array($id,$name, $email, $department, $year, $position, $password));
if (!$result) {
  // echo "クエリの実行に失敗しました。\n";
  exit;
}

header('Location: login.php');

?>


</body>
</html>
