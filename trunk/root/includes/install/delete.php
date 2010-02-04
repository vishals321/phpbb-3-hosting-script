<?php
/**
*
* @package www.script-base.eu - free phpBB hosting Script
* @version 1.0.0 $Id: includes/install/delete.php 2010-01-18 17:00:00Z Hexcode $
* @copyright (c) 2009 - 2010 by www.script-base.eu
* @license http://creativecommons.org/licenses/by-nd/3.0/de/ Attribution-No Derivative Works 3.0 Germany
*
*/
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_zebra`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_words`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_warnings`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_user_group`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_users`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_topics_watch`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_topics_track`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_topics_posted`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_topics`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles_theme`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles_template_data`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles_template`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles_imageset_data`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles_imageset`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_styles`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_smilies`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_sitelist`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_sessions_keys`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_sessions`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_search_wordmatch`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_search_wordlist`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_search_results`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_reports_reasons`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_reports`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_ranks`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_profile_lang`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_profile_fields_lang`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_profile_fields_data`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_profile_fields`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_privmsgs_to`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_privmsgs_rules`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_privmsgs_folder`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_privmsgs`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_posts`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_poll_votes`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_poll_options`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_modules`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_moderator_cache`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_log`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_lang`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_icons`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_groups`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_forums_watch`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_forums_track`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_forums_access`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_forums`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_extension_groups`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_extensions`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_drafts`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_disallow`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_confirm`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_config`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_bots`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_bookmarks`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_bbcodes`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_banlist`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_attachments`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_acl_users`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_acl_roles_data`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_acl_roles`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_acl_options`;";
$phpbb_install_queries[] = "DROP TABLE IF EXISTS `<# phpBB #>_acl_groups`;";
?>