<?php
/**
 * @package SnrjGaleri
 * @version 1.0
 * @author Snrj - http://smf.konusal.com
 * Copyright 2017 SnrjGaleri
 *
 */
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
  require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
  die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');
  
global $smcFunc;
db_extend('Packages');
db_extend('Extra');
// hooks
$hook_functions = array(
		'integrate_pre_include' => '$sourcedir/SnrjGaleri-Hooks.php',
        'integrate_admin_areas' => 'galeri_admin',
	    'integrate_menu_buttons' => 'galeri_menu',
		'integrate_actions' => 'galeri_actions',
		'integrate_modify_modifications' => 'Galeri_Ayarlar_mod',
		'integrate_buffer' => 'Galeri_buffer',
		'integrate_credits' => 'Galeri_credits',
		'integrate_whos_online' => 'Galeri_Who',
);
if (!empty($context['uninstalling']))
	$call = 'remove_integration_function';
else
	$call = 'add_integration_function';

foreach ($hook_functions as $hook => $function)
	$call($hook, $function);

// settings

$mod_settings = array(
	'snrjgaleri_max_genislik' => '1920',
	'snrjgaleri_max_yukseklik' => '1080',
	'snrjgaleri_max_dosyaboyutu' => '5000000',
	'snrjgaleri_sayfaresim' => '20',
	'snrjgaleri_min_genislik' => '250',
	'snrjgaleri_min_yukseklik' => '175',
	'snrjgaleri_kod_bbc' => '1',
	'snrjgaleri_kod_direct' => '1',
	'snrjgaleri_kod_html' => '1',
	'gelismismod' => '0',
	'gelismismoddondur' => '0',
	'gelismismodcevir' => '0',
	'gelismismodaltyazi' => '1',
	'gelismismodextra' => '1',
	'gelismismodwatermark' => '0',
	'galericopy' => '<a href="http://smf.konusal.com" target="_blank">Snrj Galeri</a>',
	'galeri_catbar' => '',
);

updateSettings($mod_settings);


//Database

$smcFunc['db_create_table']('{db_prefix}SnrjGaleri', array(
	array('name' => 'id_resim', 'type' => 'int','auto' => true),
	array('name' => 'id_uye', 'type' => 'int'),
	array('name' => 'baslik', 'type' => 'varchar', 'size' => 100, 'default' => '',),
	array('name' => 'etiket', 'type' => 'varchar', 'size' => 255, 'default' => '',),
	array('name' => 'aciklama','type' => 'text',),	
	array('name' => 'dosyaismi','type' => 'tinytext',),
	array('name' => 'kucukdosyaismi','type' => 'tinytext',),
	array('name' => 'tarih', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'bakanlar', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'boyut', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'yukseklik', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'genislik', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'toplamderece', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'derece', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'tur', 'type' => 'int', 'null' => false, 'default' => 0),
	),
	array(array('type' => 'primary', 'columns' => array('id_resim')),)
);
$smcFunc['db_create_table']('{db_prefix}SnrjGaleri_derece', array(
	array('name' => 'id', 'type' => 'int','auto' => true),
	array('name' => 'id_resim', 'type' => 'int'),
	array('name' => 'id_uye', 'type' => 'int', 'null' => false, 'default' => 0),
	array('name' => 'deger','type' => 'tinyint','null' => false),
	),
	array(array('type' => 'primary', 'columns' => array('id')),)
);





?>