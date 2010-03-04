<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "mass_mail");
if (isset($_POST['submit']) ) 
{
	$result = $database->query("SELECT `domain`,`start_e_mail` FROM `hosting_forums`",$root_connection);
	while($row = mysql_fetch_assoc($result)) { 
		$mail_a = $row['start_e_mail'];
		$mail_t1 = $_POST['message'];
		$mail_t = str_replace("#forum#", "http://www.".$row['domain'].".".$site_title, $mail_t1);
		$mail_b = $_POST['topic'];
		$from = "From: Newsletter <noreply@".$site_title.">\n";
		$from .= "Reply-To: noreply@".$site_title."\n";
		$from .= "MIME-Version: 1.0\n";
		$from .= "Content-Type: text/html; charset=utf-8\n";
		$from .= "X-Priority: 1\n";
		$from .= "X-MSMail-Priority: High\n";
		$from .= "X-Mailer: php\n";
		if(!mail($mail_a, $mail_b, $mail_t, $from))
		{
			$error['SEND'] = $lang[1]['L_ERROR_SEND'];
		}
		else
		{
			$error['SEND'] = $lang[1]['L_GOOD_SEND'];
		}
		}	
}
					
/* Set up template vars */
if(isset($error))
{
	$template->assign_block_vars('error', $error);
	$error_da = true;
}
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'ERROR' => $error_da,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/mass_mail.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>