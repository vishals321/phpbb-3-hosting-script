<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "admin");
/* Switch Case */
switch($_GET['case'])
{
	case 'new':
		$case = "new";
	break;
	case 'del':
		if(isset($_GET['id']))
		{
			$id = mysql_escape_string($_GET['id']);
			if($id != 1)
			{
				$database->query("DELETE FROM `hosting_admins` WHERE `id` = {$id};", $root_connection);
				$error['DEL'] = true;
			}
			else
			{
				$error['NO_ACC'] = true;
			}

		}
	break;
	case 'new-go':
		if(isset($_POST['submit']))
		{
			$admin_name = mysql_escape_string($_POST['admin_name']);
			$password = mysql_escape_string($_POST['password']);
			$sql = $database->total_rows($database->query("SELECT * FROM `hosting_admins` WHERE `admin_name` = '{$admin_name}';", $root_connection));
			if($sql == 0)
			{
				$error['NO_ERROR'] = true;
				$database->query("INSERT INTO `hosting_admins` (`admin_name`, `password`) VALUES ('{$admin_name}', '".md5($password)."');", $root_connection);
			}
			else
			{
				$error['ERROR_THERE'] = true;
			}
		}
	break;
	case 'edit-go':
		if(isset($_POST['submit']))
		{
			$id = mysql_escape_string($_POST['id']);
			$admin_name = mysql_escape_string($_POST['admin_name']);
			$sql = $database->total_rows($database->query("SELECT * FROM `hosting_admins` WHERE `admin_name` = '{$admin_name}';", $root_connection));
			if($sql == 0)
			{
				$password = mysql_escape_string($_POST['password']);
				$password_h = mysql_escape_string($_POST['password_h']);
				if($password == $password_h)
				{
					$database->query("UPDATE `hosting_admins` SET `admin_name` = '{$admin_name}' WHERE `id` = {$id};", $root_connection);
				}
				else
				{
					$database->query("UPDATE `hosting_admins` SET `admin_name` = '{$admin_name}', `password` = '".md5($password)."' WHERE `id` = {$id};", $root_connection);

				}
				$error['NO_ERROR'] = true;
			}
			else
			{
				$error['ERROR_THERE'] = true;
			}
		}
	break;
	case 'edit':
		$id = mysql_escape_string($_GET['id']);
		$sql = $database->query("SELECT * FROM `hosting_admins` WHERE `id` = '{$id}';", $root_connection);
		$count = $database->total_rows($sql);
		if($count == 0 or $id == '1')
		{
			$error['NO_ACC'] = true;
		}
		else
		{
			$out_i = mysql_fetch_array($sql);
			$case = 'edit';
		}
	break;
}
/*Normal output*/
$sql = $database->query("SELECT * FROM `hosting_admins` ORDER BY `id` ASC;", $root_connection);
while ($row = mysql_fetch_array($sql))
{
	$template->assign_block_vars('info', array('ID' =>$row['id'], 'ADMIN_NAME' => $row['admin_name'],));
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
		'ADMIN_NAME' => $out_i['admin_name'],
		'ID' => $out_i['id'],
		'PASSWORD' => $out_i['password'],
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/admin.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>