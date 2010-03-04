<?php
/**
*
* mods_logo [Deutsch — Du]
*
* @package language
* @version $Id: acp_logo.php, V1.0.2 2010-01-03 15:56:00 Hexcode $
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
	'LOGO_DESC'				=> 'Hier bieten wir dir die Möglichkeit, dein eigenes Logo, für dein Forum, hochzuladen. Dieses Logo wird dann im oberen Bereich des Forums gezeigt.',
	'ACTUAL' 				=> 'Aktuelles Logo',
	'UPLOAD_NEW'			=> 'Neues Logo hochladen',
	'PICTUREDATA'			=> 'Bilddatei',
	'PICTURE_DESC'			=> 'Bitte wähle hier das Bild,<br />das du später als Logo des Forums nutzen möchtest.<br />Erlaubt sind .gif .jpeg und .png Dateien.',
	'FINISH'				=> 'Das Logo wurde erfolgreich auf dem Server gespeichert!<br />',
	'TO_HIGH'				=> 'Bild ist zu breit oder zu hoch! Max 1000 x 500 Pixel!<br />',
	'TO_BIG'				=> 'Bild ist zu gross!<br /> Es sind max. 2MB erlaubt!<br />',
	'NO_PIC'				=> 'Kein Bild zum hochladen ausgew&auml;hlt.<br />',
	'WRONG_FILETYPE'		=> 'Ungültiger Dateityp!<br />',
	'ERROR_WHILE_TRANSFER'	=> 'Es trat ein Fehler beim Upload auf',
	'FINISH_DEL'			=> 'Das Logo wurde erfolgreich gelöscht!',
	'NO_FINISH_DEL'			=> 'Das Logo konnte nicht gelöscht werden!',
	'DELETE'				=> 'Logo löschen',
	'UPLOAD'				=> 'Logo hochladen',
	'LOGO_UPLOAD_HEAD'		=> 'Logo Upload',
	'RESET'					=> 'Zurücksetzen',
));
?>