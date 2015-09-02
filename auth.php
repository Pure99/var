<?php
if (isset($_POST['auth_name'])) {
  $name=$connection->real_escape_string($_POST['auth_name']);
  $pass=$connection->real_escape_string($_POST['auth_pass']);
  $query = "SELECT * FROM users WHERE name='$name' AND pass='$pass'";
  $result = $connection->query($query) ;
  if ($row = $result->fetch_assoc()) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_name'] = $row['name'];
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	echo "<script> document.getElementById('name').value ='".$row['name']."'</script>";
	return ('вход выполнен');
  }
  else { 
?>
<div class="jumbotron"><form class="form-signin" method="POST">
<h2 class="form-signin-heading">Авторизация</h2>
<label for="inputEmail" class="sr-only">Email address</label>
<input type="text" id="inputEmail" class="form-control" placeholder="Имя" required="" autofocus="" name="auth_name">
<label for="inputPassword" class="sr-only">Пароль</label>
<input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required="" name="auth_pass">
<button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
</form></div>
  <?php }
  exit ('Неверный логин или пароль');
}
if (isset($_REQUEST[session_name()])) {
	session_start();
	echo 'request';
}
if (isset($_SESSION['user_id']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
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
<?php 
}
require ('templates/footer.php');
exit;