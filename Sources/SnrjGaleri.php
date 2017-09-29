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

function Galeri_Ana()
{
	global $txt, $sourcedir, $boardurl, $context, $scripturl, $mbname ;
	loadTemplate('SnrjGaleri');
	isAllowedTo('galerigor');
	$context['galeri_url'] = $boardurl . '/galeri/';
	$context['html_headers'] ='<link rel="stylesheet" type="text/css" href="'. $context['galeri_url']. 'css/galeri.css" />';
	$context['canonical_url'] = $scripturl.'?action=galeri';
	$context['linktree'][] = array('name' => '<a href="' . $scripturl . '?action=galeri">'.$txt['galeri_baslik'].'</span></a>');
	$context['page_title'] =$mbname . ' - ' . $txt['galeri_baslik'];
	require_once($sourcedir . '/Subs-SnrjGaleri.php');
	require_once($sourcedir . '/class.upload.php');
	if (isset($_POST['types'])){
        $types = $_POST['types'];
    } else if (isset($_GET['types'])) {
        $types = $_GET['types'];
    } else {
        $types = 'son';
    }
	$context['galeri'] = galeriresim($type = $types);
	$subActions = array(
		'ekle' => 'ResimEkleme',
		'bak' => 'ResimeBak',
		'sil' => 'ResimSil',
		'ara' => 'ResimArama',
		'oyla' => 'ResimOylama',
	);
    if (isset($_GET['sa']))
        $sa = $_GET['sa'];
    else
        $sa = '';
	if (!empty($subActions[$sa]))
		$subActions[$sa]();

}

function ResimEkleme()
{
	global $user_info, $txt, $modSettings, $context, $sourcedir, $smcFunc;
	isAllowedTo('resimekle');
	if ( isset( $_POST[ 'yolla' ] ) ) {
	if(empty($_REQUEST['baslik']))
		fatal_error($txt['hatabaslik'],false);
	$hoppa= $txt['galeri_baslik'].'-'.time();
    $image = new Upload( $_FILES[ 'image' ] );
    if(!in_array($image->image_src_type,$image->allowed = array ('jpg','jpeg','jpe','gif','png','bmp')))
		fatal_error($txt['hataturu'],false);	
	if($image->file_src_size>$modSettings['snrjgaleri_max_dosyaboyutu'])
		fatal_error($txt['hataboyut'],false);
	if($image->image_src_x>$modSettings['snrjgaleri_max_genislik'])
		fatal_error($txt['hatagenislik'],false);
	if($image->image_src_y>$modSettings['snrjgaleri_max_yukseklik'])
		fatal_error($txt['hatagenislik'],false);
	if ( $image->uploaded ) {
        $image->file_new_name_body = $hoppa;
		if (!empty($_POST['cevir'])){
			$image->image_convert = $_POST['cevir'];
			$isimdegis = $_POST['cevir'];
		}else{
			$isimdegis = $image->file_src_name_ext;
		}
		if (!$modSettings['gelismismod'])
		{
			if (!$modSettings['gelismismoddondur'])
			{
				if (isset($_POST['dondur'])){
				$image->image_rotate = $_POST['dondur'];
				}
			}
			if (!$modSettings['gelismismodaltyazi'])
			{ 
				if (isset($_POST['altyazi'])){
				$image->image_text            = $smcFunc['htmlspecialchars']($_POST['altyazi'],ENT_QUOTES);
				$image->image_text_color      = !empty($_POST['yazirenk'])? $_POST['yazirenk'] :'#fff';
				$image->image_text_background = !empty($_POST['arkarenk'])? $_POST['arkarenk'] :'#000';
				$image->image_text_background_opacity = !empty($_POST['arkarenkseffaf'])? $_POST['arkarenkseffaf'] :'90';
				$image->image_text_padding_x  = 5;
				$image->image_text_padding_y  = 10;
				$image->image_text_size    = !empty($_POST['yazibuyuk'])? $_POST['yazibuyuk'] :'120' ;
				$image->image_text_position  = $_POST['yazipozisyon'];
				$image->image_text_direction  = $_POST['yazidurus'];
				}
			}	
			if (!$modSettings['gelismismodextra'])
			{ 	
				if (isset($_POST['resimborder'])){
				$image->image_border          = $_POST['resimborder'];
				$image->image_border_color    = !empty($_POST['resimborderrenk'])? $_POST['resimborderrenk'] :'#000';
				$image->image_border_transparent = !empty($_POST['resimbordertrans'])? $_POST['resimbordertrans'] :0;
				}
				if (isset($_POST['resimparlaklık'])){
				$image->image_brightness	= $_POST['resimparlaklık'];
				}
				if (isset($_POST['resimkontrast'])){
				$image->image_contrast	= $_POST['resimkontrast'];
				}
				if (isset($_POST['resimseffaf'])){
				$image->image_opacity	= $_POST['resimseffaf'];
				}
				if (isset($_POST['resimpiksel'])){
				$image->image_pixelate	= $_POST['resimpiksel'];
				}
				if (isset($_POST['resimgritonlama'])){
				$image->image_greyscale	= true;
				}
				if (isset($_POST['resimnegatif'])){
				$image->image_negative	= true;
				}
			}	
		}	
		if ($modSettings['gelismismodwatermark']){
		$image->image_watermark       = 'watemark.png';
		$image->image_watermark_position = 'BR';
		}
		$image->allowed = array ( 'image/*' );
		$image->Process( 'galeri/' );
		
		require_once($sourcedir . '/Subs-SnrjGaleri.php');
		$gonderveri = array(
			'baslik' => $smcFunc['htmlspecialchars']($_REQUEST['baslik'],ENT_QUOTES),
			'aciklama' => $smcFunc['htmlspecialchars']($_REQUEST['aciklama'],ENT_QUOTES),
			'etiket' => $smcFunc['htmlspecialchars']($_REQUEST['etiket'],ENT_QUOTES),
			'dosyaismi' => $hoppa.'.'.$isimdegis,
			'kucukdosyaismi' => $hoppa.'.'.$isimdegis,
			'boyut' => $image->file_src_size,
			'tim' => time(),
			'size'=> $image->image_dst_x,
			'sizes'=> $image->image_dst_y,
			'id_uye'=> $user_info['id'],
		);
		galeriup($gonderveri);
		$image->file_new_name_body = $hoppa;
        if (isset($_POST['cevir'])){
			$image->image_convert = $_POST['cevir'];
		}
		if (isset($_POST['dondur'])){
			$image->image_rotate = $_POST['dondur'];
		}
        $image->image_resize = true;
        $image->image_x = $modSettings['snrjgaleri_min_genislik'];
        $image->image_y = $modSettings['snrjgaleri_min_yukseklik'];
		
        $image->allowed = array ( 'image/*' );
        $image->Process( 'galeri/mini' );
	}
		redirectexit('action=galeri');
	}
}
function ResimeBak()
{
	global $context, $txt, $mbname, $scripturl, $sourcedir;
	isAllowedTo('galerigor');
	if (isset($_REQUEST['resim']))
		$id = (int) $_REQUEST['resim'];
	if (isset($_REQUEST['id']))
		$id = (int) $_REQUEST['id'];
	if (empty($id))
		fatal_error($txt['hataresimbak'],false);	
	$galeri = array(
		'id' => $id,
	);
	require_once($sourcedir . '/Subs-SnrjGaleri.php');
	$context['galerim'] = galeriresimbak($galeri);
	$context['galerim'] = $context['galerim'][0];
	if ($id != $context['galerim']['id_resim'])
	fatal_error($txt['hataresimbak'],false);	
	$context['sub_template']  = 'resimebak';
	$context['page_title'] =$mbname . ' - ' . $context['galerim']['baslik'];
	$context['linktree'][] = array('name' => '<a href="' . $scripturl . '?action=galeri;sa=bak;resim='.$context['galerim']['id_resim'].'">'.$context['galerim']['baslik'].'</span></a>');
	OncekiResim($context['galerim']['id_resim']);
	SonrakiResim($context['galerim']['id_resim']);
}

function ResimSil()
{
	global $txt, $sourcedir;
	isAllowedTo('resimsil');	
	if (isset($_REQUEST['resim']))
	$id = (int) $_REQUEST['resim'];
	if (isset($_REQUEST['id']))
		$id = (int) $_REQUEST['id'];
	if (empty($id))
	fatal_error($txt['hatagenel']);		
	$sil = array(
		'id' => $id,
	);
	require_once($sourcedir . '/Subs-SnrjGaleri.php');
	galeriresimsil($sil);
	redirectexit('action=galeri');
}


function ResimArama()
{
	global  $txt, $smcFunc, $sourcedir;
	$arama =  $smcFunc['htmlspecialchars']($_REQUEST['arama'],ENT_QUOTES);
	if (empty($arama))
	fatal_error($txt['hataarama'],false);
	$ara = array(
		'id' => $arama,
	);	
	require_once($sourcedir . '/Subs-SnrjGaleri.php');
	galeriresimara($ara);
}

function ResimOylama()
{
	global $txt, $sourcedir;
	isAllowedTo('resimoyla');	
	$id = (int) $_REQUEST['id'];
	if (empty($id))
	fatal_error($txt['hatagenel'],false);	
	$derece = (int) $_REQUEST['derece'];
	if (empty($derece))
	fatal_error($txt['hataoy'],false);
	$oyla = array(
		'id' => $id,
		'derece' => $derece,
	);	
	require_once($sourcedir . '/Subs-SnrjGaleri.php');
	galeriresimoyla($oyla);
	redirectexit('action=galeri;sa=bak;resim=' . $id);
}
?>
