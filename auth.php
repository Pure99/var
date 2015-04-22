<?
if (isset($_POST['auth_name'])) {
  $name=$connection->real_escape_string($_POST['auth_name']);
  $pass=$connection->real_escape_string($_POST['auth_pass']);
  $query = "SELECT * FROM users WHERE name='$name' AND pass='$pass'";
  $result = $connection->query($query) ;
  if ($row = $result->fetch_assoc()) {
   // session_start();
    $_SESSION['user_id'] = $row['id'];
	$_SESSION['user_name'] = $row['name'];
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	//echo $_SESSION['ip'];
	return ('вход выполнен');
	//require ('var/Konst/xls.php');
	//header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  }
  else { 
?>
 <div class="jumbotron" ><form class="form-signin" method="POST">
<h2 class="form-signin-heading">Авторизация</h2>
<label for="inputEmail" class="sr-only">Email address</label>
<input type="text" id="inputEmail" class="form-control" placeholder="Имя" required="" autofocus="" name="auth_name">
<label for="inputPassword" class="sr-only">Пароль</label>
<input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="" name="auth_pass">
<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form></div>
  <? }
  exit ('Неверный логин или пароль');
}
//if (isset($_GET['action']) AND $_GET['action']=="logout") {
 // session_start();
 // session_destroy();
 // header("Location: http://".$_SERVER['HTTP_HOST']."/");
 // echo 'logout';
 // exit ('выход');
//}
if (isset($_REQUEST[session_name()])) {
	session_start();
	echo 'request';
}
if (isset($_SESSION['user_id']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
	//echo $_SESSION['user_id'];
	//echo $_SESSION['ip'];
//require ('var/Konst/xls.php');
return ('вход выполнен');
}
else {
?>
<div class="jumbotron" ><form class="form-signin" method="POST">
<h2 class="form-signin-heading">Авторизация</h2>
<label for="inputEmail" class="sr-only">Email address</label>
<input type="text" id="inputEmail" class="form-control" placeholder="Имя" required="" autofocus="" name="auth_name">
<label for="inputPassword" class="sr-only">Пароль</label>
<input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="" name="auth_pass">
<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form></div>

<? 
}
exit ;
?>
