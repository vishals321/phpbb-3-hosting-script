<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "faq");
/* Switch Case */
switch($_GET['case'])
{
	case 'push_up':
		$id = mysql_escape_string($_GET['id']);
		$id_new = $id-1;
		$database->query("UPDATE `hosting_faq` SET `id` = '-1' WHERE `id` = {$id_new};", $root_connection);
		$database->query("UPDATE `hosting_faq` SET `id` = '{$id_new}' WHERE `id` = {$id};", $root_connection);
		$database->query("UPDATE `hosting_faq` SET `id` = '{$id}' WHERE `id` = '-1';", $root_connection);
		$error['NO_ERROR'] = true;
	break;
	case 'push_down':
		$id = mysql_escape_string($_GET['id']);
		$id_new = $id+1;
		$database->query("UPDATE `hosting_faq` SET `id` = '-1' WHERE `id` = {$id_new};", $root_connection);
		$database->query("UPDATE `hosting_faq` SET `id` = '{$id_new}' WHERE `id` = {$id};", $root_connection);
		$database->query("UPDATE `hosting_faq` SET `id` = '{$id}' WHERE `id` = '-1';", $root_connection);
		$error['NO_ERROR'] = true;
	break;
	case 'del':
		if(isset($_GET['id']))
		{
			$id = mysql_escape_string($_GET['id']);
			$database->query("DELETE FROM `hosting_faq` WHERE `id` = {$id};", $root_connection);
			$error['del'] = true;
		}
	break;
	case 'new':
		$case = 'new';
	break;
	case 'new-go':
		if(isset($_POST['submit']))
		{
			$headline = mysql_escape_string($_POST['headline']);
			$description = mysql_escape_string($_POST['description']);
			$error['NO_ERROR'] = true;
			$database->query("INSERT INTO `hosting_faq` (`headline`, `description`) VALUES ('{$headline}', '{$description}');", $root_connection);
		}
	break;
	case 'edit-go':
		if(isset($_POST['submit']))
		{
			$id = mysql_escape_string($_POST['id']);
			$headline = mysql_escape_string($_POST['headline']);
			$description = mysql_escape_string($_POST['description']);
			$error['NO_ERROR'] = true;
			$database->query("UPDATE `hosting_faq` SET `headline` = '{$headline}', `description` = '{$description}' WHERE `id` = {$id};", $root_connection);
		}
	break;
	case 'edit':
		$id = mysql_escape_string($_GET['id']);
		$sql = $database->query("SELECT `id`,`headline`,`description` FROM `hosting_faq` WHERE `id` = '{$id}';", $root_connection);
		if($database->total_rows($sql) != 0)
		{
			$case = 'edit';
			$row = mysql_fetch_array($sql);
			$template->assign_block_vars('data', array('ID' => $row['id'],'HEADLINE' => $row['headline'],'DESCRIPTION' => $row['description']));
		}
		else
		{
			$error['NOT_THERE'] = true;
		}
	break;
}
/*Normal output*/
$sql = $database->query("SELECT `id`,`headline` FROM `hosting_faq` ORDER BY `id` ASC;", $root_connection);
while ($row = mysql_fetch_array($sql))
{
	$template->assign_block_vars('faq', array('ID' => $row['id'],'HEADLINE' => $row['headline']));
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
		'CASE' => $case,
		'ERROR' => $error_da,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/faq.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>