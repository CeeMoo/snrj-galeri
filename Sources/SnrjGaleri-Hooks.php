<?php
/**
 * @package SnrjGaleri
 * @version 1.0
 * @author Snrj - http://smf.konusal.com
 * Copyright 2017 SnrjGaleri
 *
 */
if (!defined('SMF'))
	die('Hacking attempt...');

function galeri_actions(&$actionArray)
{
	if (loadlanguage('SnrjGaleri') == false)
		loadLanguage('SnrjGaleri','english');
  $actionArray += array('galeri' => array('SnrjGaleri.php', 'Galeri_Ana'));
  
}
function galeri_admin(&$admin_areas)
{
   global $txt;
		$admin_areas['config']['areas']['modsettings']['subsections']['admin_ayarlar'] = array($txt['galeri_admin_ayarlar']);
}
function Galeri_Ayarlar_mod(&$sub_actions)
{
	$sub_actions['admin_ayarlar'] = 'Galeri_Ayarlar';
}
function Galeri_Ayarlar($return_config = false)
{
		global $txt, $scripturl, $context;

		$config_vars = array(
				array('text', 'galeri_catbar'),
				array('int', 'snrjgaleri_max_genislik'),
				array('int', 'snrjgaleri_max_yukseklik'),
				array('int', 'snrjgaleri_min_genislik'),
				array('int', 'snrjgaleri_min_yukseklik'),
				array('int', 'snrjgaleri_max_dosyaboyutu'),
				array('int', 'snrjgaleri_sayfaresim'),
				array('check', 'snrjgaleri_kod_bbc'),
				array('check', 'snrjgaleri_kod_direct'),
				array('check', 'snrjgaleri_kod_html'),
				
				'',
				array('title', 'galerigelismisbaslik'),
				array('check', 'gelismismod'),
				array('check', 'gelismismoddondur'),  
				array('check', 'gelismismodcevir'), 
				array('check', 'gelismismodaltyazi'),	
				array('check', 'gelismismodextra'),	
				array('check', 'gelismismodwatermark'),
				'',
				
				array('title', 'galeriizinlerbalik'),
				array('title', 'galerigor'),
				array('permissions', 'galerigor', 'subtext' => $txt['permissionhelp_galerigor']),	  
				'',
				array('title', 'resimekle'),
				array('permissions', 'resimekle', 'subtext' => $txt['permissionhelp_resimekle']),
				'',
				array('title', 'resimsil'),
				array('permissions', 'resimsil', 'subtext' => $txt['permissionhelp_resimsil']),
				'',
				array('title', 'resimoyla'),
				array('permissions', 'resimoyla', 'subtext' => $txt['permissionhelp_resimoyla']),
				
		);
	if ($return_config)
			return $config_vars;

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=admin_ayarlar';
		$context['settings_title'] = $txt['galeri_admin_ayarlar'];

		if (isset($_GET['save']))
		{
			saveDBSettings($config_vars);
			redirectexit('action=admin;area=modsettings;sa=admin_ayarlar');
		}

		prepareDBSettingContext($config_vars);
}
function galeri_menu(&$menu_buttons)
{
	global $txt,$scripturl;
		if (loadlanguage('SnrjGaleri') == false)
		loadLanguage('SnrjGaleri','english');
		$insert = 'home'; 
		$counter = 0;
		foreach ($menu_buttons as $area => $dummy)
			if (++$counter && $area == $insert )
				break;
		$menu_buttons = array_merge(
			array_slice($menu_buttons,0,$counter),
			array(
                    'current_theme' => array(
                    	'title' => $txt['galeri_baslik'],
                    	'href' => $scripturl . '?action=galeri',
                        'show' => allowedTo('galerigor'),
            		    'sub_buttons' => array(),
				    
			    )	
		    ),
			array_slice($menu_buttons, $counter)
	    );
}
function Galeri_credits()
{
	global $context,$modSettings;
	if ($context['current_action'] == 'credits') {
        $context['copyrights']['mods'][] = $modSettings['galericopy'].' &copy; 2017, snrj';
    }
}
function Galeri_buffer(&$buffer)
{
	global  $context,$modSettings;
	if($context['current_action'] == 'galeri'){
	$bul = '/<li class="copyright">/';
	$degistir = '<li class="copyright">'.$modSettings['galericopy'].' , ';
	$buffer = preg_replace( $bul, $degistir, $buffer );
	}
	return $buffer;
}
function Galeri_Who($actions)
{
	global $txt;
	$return = '';
	if (!empty($actions['action'])) {
		if ($actions['action'] == 'galeri')
			$return = $txt['galeri_who'];
	}
	return $return;
}
?>