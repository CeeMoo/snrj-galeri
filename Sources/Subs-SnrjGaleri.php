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
function galeriresim($type = 'son')
{
	global $modSettings, $smcFunc;
			$query_type = 'p.id_resim DESC';
			switch($type)
			{
				case 'son':
					$query_type = 'p.id_resim DESC';
				break;
				
				case 'sonters':
					$query_type = 'p.id_resim ASC';
				break;

				case 'bak':

					$query_type = 'p.bakanlar DESC';
				break;
				case 'bakters':

					$query_type = 'p.bakanlar ASC';
				break;

				case 'oylanan':

					$query_type = 'p.toplamderece DESC';
				break;
			}
			
	$query = "SELECT p.id_resim, p.id_uye, m.real_name, p.baslik, p.etiket, p.aciklama, p.dosyaismi, p.kucukdosyaismi, p.tarih,p.bakanlar,p.boyut, p.yukseklik, p.genislik,p.toplamderece, p.derece,p.tur
		FROM {db_prefix}SnrjGaleri as p
		LEFT JOIN {db_prefix}members AS m  ON (m.id_member = p.id_uye)
		ORDER BY $query_type LIMIT " . $modSettings['snrjgaleri_sayfaresim'];

	$dbresult = $smcFunc['db_query']('', $query);
	$galeriresim = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
	{
		$galeriresim[] = array(
			'id_resim' => $row['id_resim'],
			'id_uye' => $row['id_uye'],
			'real_name' => $row['real_name'],
			'baslik' => $row['baslik'],
			'etiket' => $row['etiket'],
			'aciklama' => $row['aciklama'],
			'dosyaismi' => $row['dosyaismi'],
			'kucukdosyaismi' => $row['kucukdosyaismi'],
			'tarih' => $row['tarih'],
			'bakanlar' => $row['bakanlar'],
			'boyut' => formatBytes($row['boyut']),
			'yukseklik' => $row['yukseklik'],
			'genislik' => $row['genislik'],			
			'derece' => $row['derece'],
			'toplamderece' => $row['toplamderece'],
			'tur' => $row['tur'],
		);
	}
	$smcFunc['db_free_result']($dbresult);
	return $galeriresim;
}
function galeriresimbak($galeri)
{
	global $smcFunc;
	$id = $galeri['id'];
    $smcFunc['db_query']('', "UPDATE {db_prefix}SnrjGaleri
		SET bakanlar = bakanlar + 1 WHERE id_resim= '$id' LIMIT 1");
	$query = "SELECT p.id_resim, p.id_uye, m.real_name, p.baslik, p.etiket, p.aciklama, p.dosyaismi, p.kucukdosyaismi, p.tarih,p.bakanlar,p.boyut, p.yukseklik, p.genislik,p.toplamderece, p.derece,p.tur
		FROM {db_prefix}SnrjGaleri as p
		LEFT JOIN {db_prefix}members AS m  ON (m.id_member = p.id_uye)
		WHERE   p.id_resim= '$id'
		ORDER BY p.id_resim= '$id' DESC  LIMIT 1";

	$dbresult = $smcFunc['db_query']('', $query);
	$galeriresim = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
	{
		$galeriresim[] = array(
			'id_resim' => $row['id_resim'],
			'id_uye' => $row['id_uye'],
			'real_name' => $row['real_name'],
			'baslik' => $row['baslik'],
			'etiket' => $row['etiket'],
			'aciklama' => $row['aciklama'],
			'dosyaismi' => $row['dosyaismi'],
			'kucukdosyaismi' => $row['kucukdosyaismi'],
			'tarih' => $row['tarih'],
			'bakanlar' => $row['bakanlar'],
			'boyut' => formatBytes($row['boyut']),
			'yukseklik' => $row['yukseklik'],
			'genislik' => $row['genislik'],
			'derece' => $row['derece'],
			'toplamderece' => $row['toplamderece'],
			'tur' => $row['tur'],
		);
	}
	$smcFunc['db_free_result']($dbresult);
	return $galeriresim;
}
function galeriresimsil($sil)
{
	global $smcFunc,$boarddir;
	$id=$sil['id'];
	$dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_resim, p.dosyaismi, p.kucukdosyaismi,  p.id_uye
    FROM {db_prefix}SnrjGaleri as p
    WHERE id_resim= '$id' LIMIT 1");
	$sil = $smcFunc['db_fetch_assoc']($dbresult);
	$smcFunc['db_free_result']($dbresult);
	$resimyolu= $boarddir . '/galeri/';
		@unlink($resimyolu . $sil['dosyaismi']);
		@unlink($resimyolu .'mini/'. $sil['kucukdosyaismi']);
		$smcFunc['db_query']('', "DELETE FROM {db_prefix}SnrjGaleri WHERE id_resim= '$id' LIMIT 1");
}
function galeriup($gonderveri)
{
	global $smcFunc;
	$boyut=$gonderveri['boyut'];
	$kucukdosyaismi=$gonderveri['kucukdosyaismi'];
	$dosyaismi=$gonderveri['dosyaismi'];
	$sizes=$gonderveri['sizes'];
	$size=$gonderveri['size'];
	$etiket=$gonderveri['etiket'];
	$baslik=$gonderveri['baslik'];
	$aciklama=$gonderveri['aciklama'];
	$id_uye=$gonderveri['id_uye'];
	$tim=$gonderveri['tim'];
	$smcFunc['db_query']('', "INSERT INTO {db_prefix}SnrjGaleri
		(boyut,kucukdosyaismi,dosyaismi, yukseklik, genislik, etiket, baslik, aciklama,id_uye,tarih)
		VALUES ('$boyut','$kucukdosyaismi', '$dosyaismi', '$sizes', '$size', '$etiket','$baslik', '$aciklama','$id_uye','$tim')");
}
function OncekiResim($id = 0)
{
	global $smcFunc,$previousImage;
	$dbresult = $smcFunc['db_query']('', "
		SELECT
			p.id_resim
		FROM {db_prefix}SnrjGaleri as p
		WHERE p.id_resim > '$id'
		ORDER BY p.id_resim ASC LIMIT 1");
	if ($smcFunc['db_affected_rows']() != 0)
	{
		$row = $smcFunc['db_fetch_assoc']($dbresult);
		$id_resim = $row['id_resim'];
	}
	else
	$id_resim = $id;
	$smcFunc['db_free_result']($dbresult);
	$previousImage = $id_resim;
}

function SonrakiResim($id = 0)
{
	global $smcFunc,$nextImage;
	$dbresult = $smcFunc['db_query']('', "
		SELECT
			p.id_resim
		FROM {db_prefix}SnrjGaleri as p
		WHERE p.id_resim < '$id'
		ORDER BY p.id_resim DESC LIMIT 1");

	if ($smcFunc['db_affected_rows']() != 0)
	{
		$row = $smcFunc['db_fetch_assoc']($dbresult);
		$id_resim = $row['id_resim'];
	}
	else
		$id_resim = $id;
	$smcFunc['db_free_result']($dbresult);
	$nextImage = $id_resim;
}
function galeriresimara($ara)
{
	global $context, $smcFunc;
	$arama=$ara['id'];
	$aramaquery = ("p.baslik LIKE '%$arama%' OR p.aciklama LIKE '%$arama%' OR p.etiket LIKE '%$arama%'");
	$dbresult = $smcFunc['db_query']('', "
    SELECT
    	p.id_resim, p.id_uye, m.real_name, p.baslik, p.etiket, p.aciklama, p.dosyaismi, p.kucukdosyaismi, p.tarih,p.bakanlar,p.boyut, p.yukseklik, p.genislik,p.toplamderece, p.derece,p.tur FROM {db_prefix}SnrjGaleri as p
    LEFT JOIN {db_prefix}members AS m ON (p.id_uye = m.id_member)
    WHERE $aramaquery ");
	$arar = array();
	while($row = $smcFunc['db_fetch_assoc']($dbresult))
		{
			$arar[] = array(
			'id_resim' => $row['id_resim'],
			'id_uye' => $row['id_uye'],
			'real_name' => $row['real_name'],
			'baslik' => $row['baslik'],
			'etiket' => $row['etiket'],
			'aciklama' => $row['aciklama'],
			'dosyaismi' => $row['dosyaismi'],
			'kucukdosyaismi' => $row['kucukdosyaismi'],
			'tarih' => $row['tarih'],
			'bakanlar' => $row['bakanlar'],
			'boyut' => formatBytes($row['boyut']),
			'yukseklik' => $row['yukseklik'],
			'genislik' => $row['genislik'],
			'toplamderece' => $row['toplamderece'],
			'tur' => $row['tur'],
		);
		}
		$smcFunc['db_free_result']($dbresult);
	$context['galeri']=$arar;
}
function galeriresimoyla($oyla)
{
	global $txt, $smcFunc,$user_info;
	$id=$oyla['id'];
	$derece=$oyla['derece'];
	$dbresult = $smcFunc['db_query']('', "
    SELECT
    	id_uye, id_resim
    FROM {db_prefix}SnrjGaleri_derece
    WHERE id_uye = " . $user_info['id'] . " AND id_resim = '$id'");
    $sor = $smcFunc['db_affected_rows']();
 	$smcFunc['db_free_result']($dbresult);
	$dbresult = $smcFunc['db_query']('', "
    SELECT
    	id_uye
    FROM {db_prefix}SnrjGaleri
    WHERE id_resim = '$id' LIMIT 1");
    $row = $smcFunc['db_fetch_assoc']($dbresult);
	$smcFunc['db_free_result']($dbresult);
	if ($user_info['id'] == $row['id_uye'])
		fatal_error($txt['hataoylamabir'],false);
	if ($sor != 0)
		fatal_error($txt['hataoylamaiki'],false);
	if ($derece < 1 || $derece > 5)
		$derece = 3;
	$smcFunc['db_query']('', 
	"INSERT INTO {db_prefix}SnrjGaleri_derece 
	(id_uye, id_resim, deger) 
	VALUES (" . $user_info['id'] . ", '$id','$derece')
	");
	$smcFunc['db_query']('', "
	UPDATE {db_prefix}SnrjGaleri
		SET toplamderece = toplamderece + 1, derece = derece + '$derece'
	WHERE id_resim = '$id' LIMIT 1");
}
function formatBytes($bytes, $precision = 1) {
    $units = [' B', ' Kb', ' Mb', 'Gigabyte', 'Terabyte'];
    for ($i=0; $bytes > 1024 && $i < count($units) - 1; $i++) $bytes /= 1024;
    return round($bytes,$precision).$units[$i];
}
?>
