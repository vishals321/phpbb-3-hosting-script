<?php
/**
*
* @author Hexcode http://www.script-base.eu
*
* @package acp
* @version $Id: acp_logo.php, V1.0.1 2010-01-12 23:00:00 Hexcode $
* @copyright (c) 2010 www.script-base.eu
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
/**
* @package acp
*/
class acp_logo
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang('mods/logo');
		$this->tpl_name = 'acp_logo';
		$this->page_title = 'Logo-Upload';

		// Datenbank Prefix auslesen
		//
		$split = explode('.', $_SERVER['HTTP_HOST']);
		if($split[0] == "http://" OR $split[0] == "www" OR $split[0]  == "http://www"){ $subdomain = $split[1];}
		else {
		$subdomain = $split[0];
		}

		//$subdomain = ereg_replace('http://', '', $split[0]);
		$subdomain = str_replace('-', '_', $subdomain);
		
		//
		// Main Script
		//
		if(request_var('delete', '', true))
		{
			if(!@unlink($phpbb_root_path."images/logos/".$subdomain .".gif"))
			{
				$ausgabe .= $user->lang['NO_FINISH_DEL'];
			}
			else
			{
				$ausgabe .= $user->lang['FINISH_DEL'];
			}
		}
		elseif(request_var('Upload', '', true))
		{
			include_once($phpbb_root_path . 'includes/functions_upload.' . $phpEx);
			$max_size = 2097152;
			$upload = new fileupload('LOGO',array('gif', 'jpg', 'png', 'bmp', 'jpeg'),$max_size,0,0,1000,500);
			if($upload->is_valid('upload'))
			{
				$file = $upload->form_upload('upload');
				$filename = $file->get('filename');
				if($file->is_uploaded($filename))
				{
					if($upload->valid_extension(&$file) and $upload->valid_content(&$file))
					{
						if($file->get('filesize') <= $max_size)
						{
							if($upload->valid_dimensions(&$file))
							{
								@unlink($phpbb_root_path."images/logos/".$subdomain .".gif");
								if(@copy($filename,$phpbb_root_path."images/logos/".$subdomain .".gif"))
								{
									$ausgabe .= $user->lang['FINISH'];
								}
								else
								{
									$ausgabe = $user->lang['ERROR_WHILE_TRANSFER'];
								}
							}
							else
							{
								$ausgabe .= $user->lang['TO_HIGH'];
							}
						}
						else
						{
							$ausgabe .= $user->lang['TO_BIG'];
						}
					}
					else
					{
						$ausgabe .= $user->lang['WRONG_FILETYPE'];
					}
				}
				else
				{
					$ausgabe .= $user->lang['NO_PIC'];
				}
			}
			else
			{
				$ausgabe = $user->lang['ERROR_WHILE_TRANSFER'];
			}		
		}
		
		$filename= $phpbb_root_path."images/logos/".$subdomain .".gif";	
		if (file_exists($filename)) {
			$logo = "<img border=\"0\" src=\"".$phpbb_root_path."images/logos/".$subdomain .".gif\">";
		} else {
			$logo = "<b>No Logo</b>";
		}

			$template->assign_vars(array(
				'U_ACTION'		=> $this->u_action,
				'LOGO' => $logo,
				'AUSGABE' => $ausgabe,			
				)
			);
	}
}
?>