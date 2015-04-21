<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Starter Template for Bootstrap</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-theme.css" rel="stylesheet">
    <link href="bootstrap/bootstrap3-editable-1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
	<link href="bootstrap/css/signin.css" rel="stylesheet">
    <style> body  { padding-top: 50px;}
.starter-template { text-align: center;}
.alternate {background-color: rgba(238, 238, 238, 0.52);}
tr:hover  {background-color: #eee;}
.delete {visibility: hidden; }
.delete:hover {background: #c12e2a; }
tr:hover > .delete {visibility: visible; }

select, option { text-align: left; width: inherit; }
.pole {position:absolute; padding:6px; padding-left:3px !important; padding-right:3px !important;}
</style>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header" >
          
          <a class="navbar-brand" href="../index.php" >Коэффициент <br> вариации</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Конструкционный бетон<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="index.php?viewInfo=1">Фактический</a></li>
                <li><a href="index.php?viewInfo=2">Официальный</a></li>
                <li><a href="index.php?viewInfo=3">Ввести данные</a></li>               
              </ul>
            </li>
            <li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Товарный бетон<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="index.php?viewInfo=4">Фактический</a></li>
                <li><a href="index.php?viewInfo=5">Официальный</a></li>
                <li><a href="index.php?viewInfo=6">Ввести данные</a></li>               
              </ul>
            </li>
            <li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Товарный бетон "ТЕКА" (БОРОК)<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="index.php?viewInfo=7">Фактический</a></li>
                <li><a href="index.php?viewInfo=8">Официальный</a></li>
                <li><a href="index.php?viewInfo=9">Ввести данные</a></li>             
              </ul>
            </li>
          </ul>
		  <form class="navbar-form navbar-right">
            <div class="form-group form-group-sm">
              <input type="text" placeholder="Имя" class="form-control" value="<?php if (isset($_SESSION['user_name'])) echo $_SESSION['user_name']?>">
            </div>
            <!--<div class="form-group form-group-sm">
              <input type="password" placeholder="Пароль" class="form-control" >
            </div>
            <button type="submit" class="btn btn-sm btn-success">Sign in</button>-->
			<button type="submit" name="action" value="logout" class="btn btn-sm btn-success">Выход</button>
          </form>
		  
        </div><!--/.nav-collapse -->
      </div>
    </nav>
