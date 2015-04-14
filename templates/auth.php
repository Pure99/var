<?
if (isset($_POST['auth_name'])) {
  $name=mysql_real_escape_string($_POST['auth_name']);
  $pass=mysql_real_escape_string($_POST['auth_pass']);
  $query = "SELECT * FROM users WHERE name='$name' AND pass='$pass'";
  $res = mysql_query($query) or trigger_error(mysql_error().$query);
  if ($row = mysql_fetch_assoc($res)) {
    session_start();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
  }
  header("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
  exit;
}
if (isset($_GET['action']) AND $_GET['action']=="logout") {
  session_start();
  session_destroy();
  header("Location: http://".$_SERVER['HTTP_HOST']."/");
  exit;
}
if (isset($_REQUEST[session_name()])) session_start();
if (isset($_SESSION['user_id']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) return;
else {
?>
<form method="POST">
<input type="text" name="auth_name"><br>
<input type="password" name="auth_pass"><br>
<input type="submit"><br>
</form>
<? 
}
exit;
?>
