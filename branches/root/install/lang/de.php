<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 0.0.1 $Id: install/lang/de.php 2010-02-03 11:41:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
$lang = array(
	'save_mode_warning' => "<h1><font color='red'>Safe-Mode muss zur Benutzung des Scripts deaktiviert sein!</font></h1>",
	'desc' => "Über dieses Script wird das Script-Base.eu Forenhosting Script installiert. Während der Installation musst du nur einige Angaben zur Datenbankverbindung eingeben und deine gewünschten Einstellungen treffen",
	'start_installation' => "Installation starten",
	'desc_install' => "Danke das du dich für unser Script entschieden hast! Wir starten nun mit der Installation, gebe dazu einfach deine Datenbankdaten in das Formular ein, die config.php wird dannach automatisch beschrieben",
	'host' => "Gebe hier den Server ein auf dem die Datenbank liegt. Dieser sollte in den meisten Fällen localhost sein",
	'database' => "Datenbank",
	'database_desc' => "Der Name der Datenbank die später verwendet wird",
	'user' => "Der Datenbanknutzer der genutzt werden soll",
	'password' => "Das zum Nutzer gehörende Passwort",
	'database_save' => "Einstellungen speichern",
	'error_connect' => "<font color=#ff0000>Es konnte keine Verbindung zur Datenbank aufgebaut werden!</font>",
	'config_write_correct' => "<font color=green>Die config.php wurde erfolgreich beschrieben und die Datenbanken wurden erstellt. Bitte änder die Zugriffsrechte der Datei nun auf 0644!</font>",
	'next' => "Nächster Schritt",
	'username' => "Admin-Name",
	'username_desc' => "Der Benutzername mit dem man sic später im Admin-Bereich anmelden soll",
	'password' => "Passwort",
	'password_desc' => "Das Passwort zum Benutzernamen",
	'email' => "Die E-Mail Adresse des Forenhostings. Alle Kontaktnachrichten etc. werden zu dieser E-Mail gesendet.",
	'finish' => "Das Script wurde erfolgreich installiert lösche nun bitte das Verzeichnis 'install'.",
	'finish_desc' => "Zum Hosting",
	'config_not_writeable' => "Die Datei <b>includes/config.php</b> ist nicht beschreibbar.<br/>",
	'phpBB_config_not_writeable' => "Der Ordner <b>phpBB3/configs</b> ist nicht beschreibbar.<br/>",
	'phpBB_config_not_writeable' => "Der Ordner <b>phpBB3/configs</b> ist nicht beschreibbar.<br/>",
	'logos_not_writeable' => "Der Ordner <b>phpBB3/images/logos</b> ist nicht beschreibbar.<br/>",
	'store_not_writeable' => "Der Ordner <b>phpBB3/store</b> ist nicht beschreibbar.<br/>",
	'download_not_writeable' => "Der Ordner <b>phpBB3/download</b> ist nicht beschreibbar.<br/>",
	'please_set_chmod' => "Bitte setzte bei diesen Ordnern/Dateien den CHMOD auf 0777, dannach kehre zur Installation zurück und probiere es erneut.",
);
?>