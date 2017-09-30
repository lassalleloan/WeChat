<?php
require_once('Authentication.php');

Authentication::getInstance()->toHome();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Login</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body style="background-image: url(../fond2.jpg)">
        <h1 class="title" align = "center">Login</h1>
        <br>
		<form method="post" action="login.php">
			<table>
                <?php
                if (isset($_SESSION['logged']) && !$_SESSION['logged']) {
                    echo '<tr style="position: absolute ; top: 26%; left: 45%">
                          <td colspan="2" align="center" style="text-align:center; color:red; font-weight:bold">
                          Incorrect username or password
                          </td>
                          </tr>';
                }
                ?>
				<tr style="position: absolute ; top: 14%; left: 35%">
                    <td style="font-weight: bold ">Username</td>
                    <td>
                        <input type="text" name="username" size="50" minlength="1" maxlength="50" required="">
                    </td>
				<tr style="position: absolute ; top: 18%; left: 35%">
					<td style="font-weight: bold ">Password </td>
					<td>
						<input type="password" name="password" size="50" minlength="8" maxlength="50" required/>
					</td>
				</tr>
				<tr  style="position: absolute ; top: 21%; left: 47%; font-weight: bold">
					<td colspan="2" align="right">
                Remember Me <input type="checkbox" name="rememberMe" value="1">
					</td>
				</tr>
				<tr  style="position: absolute ; top: 24%; left: 49%"">
					<td colspan="2" align="right">
						<input type="submit" value="Login"/>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>
