<?php
/**
*
* @author MrMysteria http://www.modded-bb.de
*
* @package acp
* @version $Id: acp_logo.php, V1.1.1 2008-11-09 15:56:00 MrMysteria $
* @copyright (c) 2008 www.modded-bb.de
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @package module_install
*/
class acp_logo_info
{
	function module()
	{		
		return array(
			'filename'	=> 'acp_logo',
			'title'		=> 'LOGO',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'logo'	=> array(
				'title'		=> 'LOGO',
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