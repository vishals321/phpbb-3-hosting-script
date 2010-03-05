<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "settings");
if(isset($_POST['submit']))
{
	$site_contact = mysql_escape_string($_POST['site_contact']);
	$site_title = mysql_escape_string($_POST['site_title']);
	$meta_keywords = mysql_escape_string($_POST['meta_keywords']);
	$meta_description = mysql_escape_string($_POST['meta_description']);
	$style_name = mysql_escape_string($_POST['style_name']);
	$default_language = mysql_escape_string($_POST['default_language']);
	$site_owner = mysql_escape_string($_POST['site_owner']);
	$site_street = mysql_escape_string($_POST['site_street']);
	$site_city = mysql_escape_string($_POST['site_city']);
	$site_fon = mysql_escape_string($_POST['site_fon']);
	$site_icq = mysql_escape_string($_POST['site_icq']);
	$re_captcha_private = mysql_escape_string($_POST['re_captcha_private']);
	$re_captcha_public = mysql_escape_string($_POST['re_captcha_public']);
	$gzip = mysql_escape_string($_POST['gzip']);
	$avatar_filesize = mysql_escape_string($_POST['avatar_filesize']);
	$attachment_quota = mysql_escape_string($_POST['attachment_quota']);
	$attachment_quota = $attachment_quota*1048576;
	
	if(empty($gzip))
	{
		$gzip = 0;
	}
	
	if(empty($attachment_quota))
	{
		$attachment_quota = 0;
	}
	
	if(empty($avatar_filesize))
	{
		$avatar_filesize = 0;
	}
	
	if (!preg_match("/^[0-9a-zA-Z_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $site_contact)) {
		$error['ADRESS'] = true;
	}
	if(!isset($error))
	{
		$new_config_values = array(
			"site_contact"		=>	$site_contact,
			"site_title"		=>	$site_title,
			"meta_keywords"		=>	$meta_keywords,
			"meta_description"	=>	$meta_description,
			"style_name"		=>	$style_name,
			"default_language"	=>	$default_language,
			"site_owner"		=>	$site_owner,
			"site_street"		=>	$site_street,
			"site_city"			=>	$site_city,
			"site_fon"			=>	$site_fon,
			"site_icq"			=>	$site_icq,
			"re_captcha_private"=>	$re_captcha_private,
			"re_captcha_public"	=>	$re_captcha_public,
			"gzip"				=>	$gzip,
			"avatar_filesize"	=>	$avatar_filesize,
			"attachment_quota"	=>	$attachment_quota,
		);
				
		foreach ($new_config_values as $config_name => $config_value)
		{
			$database->query("UPDATE `hosting_config` SET `config_value` = '{$config_value}' WHERE `config_name` = '{$config_name}';", $root_connection);
		}
		$message = true;
	}
}
/*content*/
$sql = $database->query("SELECT * FROM `hosting_config`;", $root_connection);
while ($row = mysql_fetch_array($sql)) {
	$setting[$row['config_name']] = $row['config_value'];
}
$setting['attachment_quota'] = $setting['attachment_quota']/1048576;
$i = 0;
$languages = $language->check_available_languages();
$count = count($languages);
while ($count > $i) {
	$selected = '';
	if($languages[$i] == $setting['default_language'])
	{
		$selected = 'selected="selected"';
	}
	$lang_output .= "<option value=\"{$languages[$i]}\" $selected >{$languages[$i]}</option>";
	$i++;
}
$i = 0;
$templates = $template->check_available_templates();
$count = count($templates);
while ($count > $i) {
	$selected = '';
	if($templates[$i] == $setting['style_name'])
	{
		$selected = 'selected="selected"';
	}
	$template_output .= "<option value=\"{$templates[$i]}\" $selected >{$templates[$i]}</option>";
	$i++;
}
/* Set up template vars */
$arr = array(
		'SITE_TITLE' => $setting['site_title'],
		'META_KEYWORDS' => $setting['meta_keywords'],
		'META_DESCRIPTION' => $setting['meta_description'],
		'SITE_CONTACT' => $setting['site_contact'],
		'STYLE_NAME' => $template_output,
		'DEFAULT_LANGUAGE' => $lang_output,
		'SITE_OWNER' => $setting['site_owner'],
		'SITE_STREET' => $setting['site_street'],
		'SITE_CITY' => $setting['site_city'],
		'SITE_FON' => $setting['site_fon'],
		'SITE_ICQ' => $setting['site_icq'],
		'SITE_CITY' => $setting['site_city'],
		'RE_CAPTCHA_PRIVATE' => $setting['re_captcha_private'],
		'RE_CAPTCHA_PUBLIC' => $setting['re_captcha_public'],
		'GZIP' => $setting['gzip'],
		'AVATAR_FILESIZE' => $setting['avatar_filesize'],
		'ATTACHMENT_QUOTA' => $setting['attachment_quota'],
);
$template->assign_block_vars('settings', $arr);
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'MESSAGE' => $message,
		'ERROR' => $error['ADRESS'],
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/settings.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>