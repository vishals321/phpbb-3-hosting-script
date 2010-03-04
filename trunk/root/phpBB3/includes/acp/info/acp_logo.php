<?php
/**
*
* @author Hexcode http://www.script-base.eu
*
* @package acp
* @version $Id: acp_logo.php, V1.0.2 2010-02-11 18:00:00 Hexcode $
* @copyright (c) 2010 www.script-base.eu
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
/**
* @package module_install
*/
class acp_logo_info
{
	function module()
	{		
		return array(
			'filename'	=> 'acp_logo',
			'title'		=> 'Logo-Upload',
			'version'	=> '1.0.2',
			'modes'		=> array(
				'logo'	=> array(
				'title'		=> 'Logo-Upload',
				'auth'		=> 'acl_a_board',
				'cat'		=> array('ACP_BOARD_CONFIGURATION'),
				),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
	
	function update()
	{
	}
}

?>