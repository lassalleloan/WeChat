<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>WeChat - Login</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    </head>
    <body>
        <h1>Login</h1>
        <br>
		<form method="post" action="login.php">
			<table>
                <?php
                if (isset($_SESSION['logged']) && !$_SESSION['logged']) {
                    echo '<tr>
                          <td colspan="2" align="center" style="text-align:center; color:red; font-weight:bold">
                          Incorrect username or password
                          </td>
                          </tr>';
                }
                ?>
				<tr>
					<td>Username</td>
					<td>
						<input type="text" name="username" size="50" minlength="1" maxlength="50" required/>
					</td>
				</tr>
				<tr>
					<td>Password</td>
					<td>
						<input type="password" name="password" size="50" minlength="8" maxlength="50" required/>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
                        Remember Me <input type="checkbox" name="rememberMe" value="1">
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="submit" value="Login"/>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>