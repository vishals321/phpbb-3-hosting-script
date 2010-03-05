<?php
class error{
/* If is error while handle with the database*/
	public function output($error_type,$error_message = "",$query = "No Query Executed")
		{
			$error_html = "\t\t\t<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
			<html>
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />
					<title>".$error_type."</title>
					<style type=\"text/css\">
					    	* { font-size: 100%; margin: 0; padding: 0; }
						body { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 75%; margin: 10px; background: #FFFFFF; color: #000000; }
						a:link, a:visited { text-decoration: none; color: #005fa9; background-color: transparent; }
						a:active, a:hover { text-decoration: underline; }						
						textarea { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; border: 1px dashed #000000; background: #FFFFFF; padding: 5px; background: #f4f4f4; }
					</style>
				</head>
				<body>
					<p>
						<b>".$error_type."</b>
						<br /><br />
						Please contact an admin over <a href=\"contact.php\">our contact-form</a>, please send with your message the failure code shown below.
						<br /><br />
						<textarea readonly=\"readonly\" rows=\"15\" cols=\"40\" style=\"width:500px;\">Time: ".date("F j, Y, g:i:s a")."\nIP: {$_SERVER['REMOTE_ADDR']}\rFailure-Message: {$error_message}\nQuery Executed: {$query}</textarea>
					</p>		
				</body>
			</html>";
			exit($error_html);
			return;
		}
}
?>