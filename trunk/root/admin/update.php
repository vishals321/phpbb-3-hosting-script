<?php
/* Include the required files */
require_once('auth.php');

/* Load language */
$lang = $language->admin_language_select($database, $root_connection, "update");
	$aktuelle_version = file_get_contents("http://www.script-base.eu/version.xml");
	$xml_parser = xml_parser_create();
	xml_parse_into_struct($xml_parser, $aktuelle_version, $vals, $index);
	$aktuelle_version = $vals[0]['attributes']['VERSION'];
	$aktuelle_version_download = $vals[0]['value'];
	if($installed_version != $aktuelle_version)
	{
		$version_check = $lang[1]['L_NOT_ACTUAL'];
		$version_check = str_replace("<# aktuelle_version #>", $aktuelle_version, $version_check);
		$version_check = str_replace("<# installed_version #>", $installed_version, $version_check);
		$link = $aktuelle_version_download;
	}
	else
	{
		$version_check = $lang[1]['L_ACTUAL'];
		$link = false;
	}

/* Set up template vars */
$content = array(
        'TITLE' => $site_title,
		'META_KEYWORDS' => $meta_keywords,
		'META_DESCRIPTION' => $meta_description,
		'LINK' => $link,
		'NEW' => $aktuelle_version,
		'VERSION' => $installed_version,
		'VERSION_CHECK' => $version_check,
);
$template->assign_vars(array_merge($lang[0], $lang[1], $content));
/* Template engine start */
$template->set_filenames(array(
    'header' => 'admin/overall_header.html',
	'footer' => 'admin/overall_footer.html',
    'body' => 'admin/update.html'
));
/* Ouput the page */
$template->display('header');
$template->display('body');
$template->display('footer');
?>