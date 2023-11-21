<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form_name']) && $_POST['form_name'] == 'loginform')
{
   $success_page = '';
   if (isset($_SESSION['referrer']))
   {
      $success_page = $_SESSION['referrer'];
   }
   $error_page = basename(__FILE__);
   $database = './usersdb.php';
   $crypt_pass = md5($_POST['password']);
   $found = false;
   $db_email = '';
   $db_fullname = '';
   $db_username = '';
   $session_timeout = 100000;
   $log_date = date("Y-m-d");
   $log_time = date("G:i:s");
   $log_ip_address = $_SERVER['REMOTE_ADDR'];
   $log_user_agent = $_SERVER['HTTP_USER_AGENT'];
   $log_referrer = (isset($_SESSION['referrer']) ? $_SESSION['referrer'] : $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
   if (filesize($database) > 0)
   {
      $items = file($database, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      foreach ($items as $line)
      {
         list($username, $password, $email, $name, $active) = explode('|', trim($line));
         if (($username == $_POST['username'] || $email == $_POST['username']) && $active != "0" && $password == $crypt_pass)
         {
            $found = true;
            $db_email = $email;
            $db_fullname = $name;
            $db_username = $username;
            break;
         }
      }
   }
   if ($found == false)
   {
      header('Location: '.$error_page);
      exit;
   }
   else
   {
      $_SESSION['email'] = $db_email;
      $_SESSION['fullname'] = $db_fullname;
      $_SESSION['username'] = $db_username;
      $_SESSION['expires_by'] = time() + $session_timeout;
      $_SESSION['expires_timeout'] = $session_timeout;
      $rememberme = isset($_POST['rememberme']) ? true : false;
      if ($rememberme)
      {
         setcookie('username', $db_username, time() + 3600*24*30);
         setcookie('password', $_POST['password'], time() + 3600*24*30);
      }
      header('Location: '.$success_page);
      exit;
   }
}
$username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
$password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Вход</title>
<meta name="generator" content="WYSIWYG Web Builder 18 - https://www.wysiwygwebbuilder.com">
<link href="Банк.css" rel="stylesheet">
<link href="Вход.css" rel="stylesheet">
</head>
<body>
<div id="space"><br></div>
<div id="container">
<div id="wb_Login1" style="position:absolute;left:20px;top:70px;width:427px;height:257px;z-index:0;">
<form name="loginform" method="post" accept-charset="UTF-8" action="<?php echo basename(__FILE__); ?>" id="loginform">
<input type="hidden" name="form_name" value="loginform">
<table id="Login1">
<tr>
   <td class="header Heading 1 <h1>">ВХОД</td>
</tr>
<tr>
   <td class="label"><label for="username">Почта или имя пользователя</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="username" type="text" id="username" value="<?php echo $username; ?>"></td>
</tr>
<tr>
   <td class="label"><label for="password">Пароль</label></td>
</tr>
<tr>
   <td class="row"><input class="input" name="password" type="password" id="password" value="<?php echo $password; ?>"></td>
</tr>
<tr>
   <td class="row"><input id="rememberme" type="checkbox" name="rememberme"><label for="rememberme">Запомнить меня</label></td>
</tr>
<tr>
   <td style="text-align:center;vertical-align:bottom"><input class="button" type="submit" name="login" value="ВХОД" id="login"></td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>