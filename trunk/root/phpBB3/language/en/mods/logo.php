<?php
/**
*
* mods_logo [Deutsch — Du]
*
* @package language
* @version $Id: acp_logo.php, V1.1.1 2010-01-03 15:56:00 Hexcode $
* @copyright (c) 2010 www.script-base.eu
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Logo-Upload settings
$lang = array_merge($lang, array(
	'LOGO_DESC'				=> 'Here you get the chance to upload your own logo, later the logo is shown at the top of your forum.',
	'ACTUAL' 				=> 'actual logo',
	'UPLOAD_NEW'			=> 'Upload new logo',
	'PICTUREDATA'			=> 'Picture',
	'PICTURE_DESC'			=> 'Please pick here a picture,<br/>later it is shown at the header of your forum.<br/>You can use .gif .jpeg and .png files.',
	'FINISH'				=> 'The logo was successful uploaded!<br/>',
	'TO_HIGH'				=> 'The picture ist to high or to wide! Max 1000 x 500 pixels!<br/>',
	'TO_BIG'				=> 'The filesize of the picture is to big!<br/> Max. 2MB!<br/>',
	'NO_PIC'				=> 'No picture was posted.<br/>',
	'WRONG_FILETYPE'		=> 'Wrong filetype!<br/>',
	'ERROR_WHILE_TRANSFER'	=> 'There was a failure while uploading',
	'FINISH_DEL'			=> 'The Logo was deleted successfully!',
	'NO_FINISH_DEL'			=> 'The Logo was not deleted successfully...',
));
?>