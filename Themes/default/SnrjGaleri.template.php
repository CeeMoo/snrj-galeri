<?php
/**
 * @package SnrjGaleri
 * @version 1.0
 * @author Snrj - http://smf.konusal.com
 * Copyright 2017 SnrjGaleri
 *
 */
function template_main()
{
	global $txt,$context,$scripturl,$modSettings;
	echo '
		<div class="cat_bar">	
			<h3 class="catbg">',!empty($modSettings['galeri_catbar'])? $modSettings['galeri_catbar']:$txt['galeriwelcome'],'</h3>
		</div>
		<div class="information">';
			if(allowedTo('resimekle'))
			echo'<button class="galeributton floatleft" id="galeriBtn">'.$txt['galeriresimekle'].'</button>';
			echo'<form method="post" action="' . $scripturl . '?action=galeri;sa=ara" accept-charset="', $context['character_set'], '" class="floatleft aram">
				<input type="text" name="arama" placeholder="', $txt['galeriarama'], '"/>
				<button type="submit" name="submit" class="galeributton"> ', $txt['search'], '</button> 
			</form>
			<form action="' . $scripturl . '?action=galeri" accept-charset="', $context['character_set'], '" onsubmit="submitonce(this);" method="post" enctype="multipart/form-data" class="floatright">
				<label class="dropdown">
					<select name="types">
						<option value="son">'.$txt['galeriselectson'].'</option>
						<option value="sonters">'.$txt['galeriselectsonters'].'</option>
						<option value="bak">'.$txt['galeriselectbak'].'</option>
						<option value="bakters">'.$txt['galeriselectbakters'].'</option>
						<option value="oylanan">'.$txt['galeriselectoylanan'].'</option>
					</select>
				</label>
			<button type="submit" class="galeributton">'.$txt['galeriselect'].'</button>
			</form><br class="clear"/>
		</div>
		<div class="grow">';
			foreach($context['galeri'] as $galeri)
			{	
			echo '
				<div class="galeribox">
					<div class="galeriContainer">
						<div class="thumbnailContainer">
							<a href="' . $scripturl . '?action=galeri;sa=bak;resim=' . $galeri['id_resim'] . '" title="',$galeri['baslik'],'">
								<img class="thumbImage" src="' . $context['galeri_url'] . 'mini/',$galeri['kucukdosyaismi'],'" alt="',$galeri['baslik'],'"/>
							</a>
							<div class="thumbnailOverlay">
								<div class="overlayDetails">',$galeri['baslik'],'</div>
							</div>
						</div>
						<div class="titleSection">
							'.$txt['galeriby'] . '
							<a href="' . $scripturl . '?action=profile;u=' . $galeri['id_uye'] . '">'  . $galeri['real_name'] . '</a>
						</div>
						<div class="statsSection">
							<div class="statSpan">' . $txt['galeribakan'] .' : ' . $galeri['bakanlar'] . '</div>
							<div class="statSpan">';
									$max_num_stars = 5;
									if ($galeri['toplamderece'] == 0)
									{
										echo $txt['oyyok'];
									}
									else
									{
										$derece =($galeri['derece'] / ($galeri['toplamderece']* $max_num_stars) * 100);
										if ($derece == 0)
											echo $txt['oyfis'];
										else if ($derece <= 20)
											echo $txt['oyy'].' : '.str_repeat('<span class="generic_star"></span>', 1);
										else if ($derece <= 40)
											echo $txt['oyy'].' : '.str_repeat('<span class="generic_star"></span>', 2);
										else if ($derece <= 60)
											echo $txt['oyy'].' : '.str_repeat('<span class="generic_star"></span>', 3);
										else if ($derece <= 80)
											echo $txt['oyy'].' : '.str_repeat('<span class="generic_star"></span>', 4);
										else if ($derece <= 100)
											echo $txt['oyy'].' : '.str_repeat('<span class="generic_star"></span>', 5);
									}
					
								echo'
							</div>
						</div>
						<div class="galeridate">
							' . timeformat($galeri['tarih'], '%d %b %Y %H:%M') . '
						</div>
					</div>
				</div>';
			}
	echo'
		</div>
		<br class="clear"/>';
	template_resimekle();
}


function template_resimebak()
{
	global $txt,$modSettings,$context, $scripturl,$nextImage,$previousImage;
	echo '<div class="grow">
			<div class="gcolright">
				<div class="cat_bar">
					<h3 class="catbg">' . $context['galerim']['baslik'] . '</h3>
				</div> 
				<div class="gsabit">
					<div class="drop-shadow curved h2">
						<img id="zoom" src="' . $context['galeri_url'] . '' . $context['galerim']['dosyaismi']  . '" alt="' . $context['galerim']['baslik']  . '">
					</div>
					<div id="Galeri_Slides" class="Galeri-zoom">
					  <span class="kapa">&times;</span>
					  <img class="Galeri-zoom-content" id="zoom1">
					  <div id="caption"></div>
					</div>
					<ul class="pager">';
						$showSpacer = false;
						if ($previousImage != $context['galerim']['id_resim'])
						{
							$showSpacer = true;
							echo ' <li><a href="', $scripturl, '?action=galeri;sa=bak&resim=',$previousImage, '" title="', $txt['galeriileri'], '"><img src="' . $context['galeri_url'] . 'icon/prev.png" alt="*" /></a></li>';
						}
						echo'<li><a href="' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '" download="' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '" title="', $txt['galeriindir'], '"><img src="' . $context['galeri_url'] . 'icon/download.png" alt="*" /></a></li>';
						echo'<li><a href="' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '" title="', $txt['galeritamekran'], '"><img src="' . $context['galeri_url'] . 'icon/fullscreen.png" alt="*" /></a></li>';
						if ($nextImage  != $context['galerim']['id_resim'])
						{
							if ($showSpacer == true)
							echo '<li><a href="', $scripturl, '?action=galeri;sa=bak&resim=',$nextImage, '" title="', $txt['galerigeri'], '"><img src="' . $context['galeri_url'] . 'icon/next.png" alt="*" /></a></li>';

						}
			echo '  </ul>
				</div>
			</div>
			<div class="gcolleft">
				<div class="cat_bar">
					<h3 class="catbg">'.$txt['galeriresimbilgileri'].'</h3>
				</div>
				<div class="information">';
					if(!empty($context['galerim']['aciklama']))
					echo'
					<fieldset>
						<legend>' . $txt['galeriaciklama'] . ' </legend>' . (parse_bbc($context['galerim']['aciklama'])  ). '
					</fieldset>';
					echo'
					<span class="smalltext floatleft">' . $txt['galeribakan'] .'</span>
					<span class="smalltext floatright">'. $context['galerim']['bakanlar'] . '</span>
					<br />
					<span class="smalltext floatleft">'  . $txt['galeriboyut'] . '</span>
					<span class="smalltext floatright">' . $context['galerim']['boyut']  . ' </span>
					<br />
					<span class="smalltext floatleft">'  . $txt['galeriyukseklik'] . '</span>
					<span class="smalltext floatright">' . $context['galerim']['yukseklik']  . ' </span>
					<br />
					<span class="smalltext floatleft">' . $txt['galerigenislik'] . '</span>
					<span class="smalltext floatright"> ' . $context['galerim']['genislik'] . '</span>
					<br />
					<span class="smalltext floatleft">' .$txt['galeriby'] . '</span>
					<span class="smalltext floatright">
						<a href="' . $scripturl . '?action=profile;u=' . $context['galerim']['id_uye'] . '">'.$context['galerim']['real_name'].'</a>
					</span>
					<br />
					<span class="smalltext floatleft">' . $txt['galeritarih']. '</span>
					<span class="smalltext floatright"> ' . timeformat($context['galerim']['tarih'], '%d %b %Y %H:%M') . ' </span>
					<br />';

					if (!empty($context['galerim']['etiket']))
					{
						$etiket = explode(' ',$context['galerim']['etiket']);
						$etiketler = count($etiket);
						echo'<span class="smalltext floatleft">
								' . $txt['galerietiket'] . '
							</span>
							<span class="smalltext floatright galerietiket"><form method="post" action="' . $scripturl . '?action=galeri;sa=ara" accept-charset="', $context['character_set'], '">
';
							for($i = 0; $i < $etiketler;$i++)
							{
								echo '		<input type="submit" name="arama" value="' . $etiket[$i] . '" />
';
							}
							echo '</form>
							</span>
							<br />';
					}
			echo'</div>';
					if ($modSettings['snrjgaleri_kod_bbc']  || $modSettings['snrjgaleri_kod_direct'] || $modSettings['snrjgaleri_kod_html'])
					{
					echo'
						<div class="cat_bar">
							<h3 class="catbg">'.$txt['galeripaylas'].'</h3>
						</div>
						<div class="information">';
						if ($modSettings['snrjgaleri_kod_bbc'])
						{
							echo '<label class="smalltext">', $txt['galeri_bbc'], '</label>
							<input class="paylas" type="text" value="[img]' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '[/img]"  /><br class="clear"/>';
						}
						if ($modSettings['snrjgaleri_kod_direct'])
						{
							echo '<label class="smalltext">', $txt['galeri_direklink'], '</label>
							<input class="paylas" type="text" value="' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '"  /><br class="clear"/>';
						}
						if ($modSettings['snrjgaleri_kod_html'])
						{
							echo '<label class="smalltext">', $txt['galeri_htmllink'], '</label>
							<input class="paylas" type="text" value="<img src=&#34;' . $context['galeri_url'] . $context['galerim']['dosyaismi']  . '&#34; />"  /><br class="clear"/>';
						}
					echo'</div>';
					}
			echo'
				<div class="cat_bar">
					<h3 class="catbg">'.$txt['galerimenu'].'</h3>
				</div>
				<div class="information">
					<ul class="menulist">';
						if(allowedTo('resimsil'))
						echo'
						<li class="menulistli">
							<a href="' . $scripturl . '?action=galeri;sa=sil;resim=' . $context['galerim']['id_resim'] . '">' . $txt['galerisil'] . '</a>
						</li>';
						if(allowedTo('resimekle'))
						echo'
						<li class="menulistli">
							<a id="galeriBtn">'.$txt['galeriresimekle'].'</a>
						</li>';
						echo'
						<li class="menulistli">
							<a href="', $scripturl, '?action=galeri">' . $txt['galerianasayfa'] . '</a>
						</li>
					</ul>
				</div>';
				echo'<div class="derece">';
					if ($context['galerim']['toplamderece'] == 0)
					{
						echo $txt['oyyokk'];
					}
					else
					{	
						$stars = 5;
						$derece =($context['galerim']['derece'] / ($context['galerim']['toplamderece']* $stars) * 100);
						if ($derece == 0)
							echo $txt['oyfis'];
						else if ($derece <= 20)
							echo $txt['oyortala'].' : '.str_repeat('<span class="generic_star"></span>', 1);
						else if ($derece <= 40)
							echo $txt['oyortala'].' : '.str_repeat('<span class="generic_star"></span>', 2);
						else if ($derece <= 60)
							echo $txt['oyortala'].' : '.str_repeat('<span class="generic_star"></span>', 3);
						else if ($derece <= 80)
							echo $txt['oyortala'].' : '.str_repeat('<span class="generic_star"></span>', 4);
						else if ($derece <= 100)
							echo $txt['oyortala'].' : '.str_repeat('<span class="generic_star"></span>', 5);
						echo '<br/>'.$txt['oyveren'].' : '.$context['galerim']['toplamderece'];
					}
					if (allowedTo('resimoyla'))
					{
						$stars =1;
						echo '<hr/><div class="stars"><form method="post" action="' . $scripturl . '?action=galeri;sa=oyla">
								<input type="hidden" name="id" value="' . $context['galerim']['id_resim'] . '" />
								<input type="submit" name="submit"  class="galeributton" value="' . $txt['resim_oyla'] . '" />';
							for($i = 5; $i >= $stars;$i--)
								echo '<input class="star star-' . $i .'" id="star-' . $i .'" type="radio" name="derece" value="' . $i .'" />
									<label class="star star-' . $i .'" for="star-' . $i .'"></label>';
						echo '</form></div>';
					}
			echo '</div>
			</div>
		</div>
		<br class="clear" />';	
		echo'<script>
				var modal = document.getElementById(\'Galeri_Slides\');
				var img = document.getElementById(\'zoom\');
				var modalImg = document.getElementById("zoom1");
				var captionText = document.getElementById("caption");
				img.onclick = function(){
					modal.style.display = "block";
					modalImg.src = this.src;
					captionText.innerHTML = this.alt;
				}
				var span = document.getElementsByClassName("kapa")[0];
				span.onclick = function() { 
					modal.style.display = "none";
				}
			</script>';
	  template_resimekle();
}		

function template_resimekle()
{
	global $txt, $context, $scripturl,$modSettings;
	if(allowedTo('resimekle'))
	{
	echo '<div id="galeriModal" class="galeriacilir">
			<div class="galeri-content"> 
				<div class="cat_bar">	
					<h3 class="catbg">
						'.$txt['galeriresimekle'].'<span class="kapat">&times;</span> 
					</h3>
				</div>
				<div class="resimyolla">
					<form action="' . $scripturl . '?action=galeri&sa=ekle" accept-charset="', $context['character_set'], '" onsubmit="submitonce(this);" method="post" enctype="multipart/form-data">
					<label for="baslik">'.$txt['galeribaslik'].'</label>
					<input type="text" name="baslik" placeholder="'.$txt['galerizorunlu'].'" />
					<label for="aciklama">'.$txt['galeriaciklama'].'</label>
					<textarea class="resimtext" name="aciklama" placeholder="'.$txt['galeriistek'].$txt['galeribbckullan'].'"></textarea>
					<label for="etiket">'.$txt['galerietiket'].'</label>
					<input type="text" name="etiket" placeholder="'.$txt['galeriistek'].'" />
					<label for="dosya">'.$txt['galerisec'].'</label>
					<input type="file" name="image" /><hr />';
					if (empty($modSettings['gelismismod']))
					{
					echo'<fieldset  id="galerigelismis">
						<legend><a href="javascript:void(0);" onclick="document.getElementById(\'galerigelismis\').style.display = \'none\';document.getElementById(\'galerigelismis_groups_link\').style.display = \'block\'; return false;">' . $txt['galerigelismis'] . '</a> </legend>';
					if (empty($modSettings['gelismismoddondur']))
						{
						echo'
							<label>'.$txt['galerigelismisdondur'].'</label>
							<select name="dondur">
								<option value="">'.$txt['dondurme'].'</option>
								<option value="90">'.$txt['dondursag'].'</option>
								<option value="270">'.$txt['dondursol'].'</option>
								<option value="180">'.$txt['dondurbas'].'</option>
							</select>';
						}
						if (empty($modSettings['gelismismodcevir']))
						{ 
						echo'
							 <label>'.$txt['galerigelismiscevir'].'</label>
							 <select name="cevir">
								<option value="">'.$txt['cevirme'].'</option>
								<option value="jpg">jpg</option>
								<option value="jpeg">jpeg</option>
								<option value="jpe">jpe</option>
								<option value="gif">gif</option>
								<option value="png">png</option>
								<option value="bmp">bmp</option>
							 </select>';
						}
						if (empty($modSettings['gelismismodaltyazi']))
						{ 
						echo'
							 <fieldset class="gelismisaltyazı">
								<legend>'.$txt['resimaltyazilegend'].'</legend>
								<input type="text" name="altyazi" placeholder="'.$txt['resimaltyazi'].'" />
								<input type="text" name="yazirenk" placeholder="'.$txt['resimyazirenk'].'" />
								<input type="text" name="arkarenk" placeholder="'.$txt['resimarkarenk'].'" />
								<input type="int" name="arkarenkseffaf" placeholder="'.$txt['resimarkarenkseffaf'].'" />
								<input type="int" name="yazibuyuk" placeholder="'.$txt['resimyazibuyuk'].'" />
								<label>'.$txt['resimyazipozisyon'].'</label>
								<select name="yazipozisyon">
									<option value="BR">'.$txt['yazipozisyonbr'].'</option>
									<option value="B">'.$txt['yazipozisyonb'].'</option>
									<option value="BL">'.$txt['yazipozisyonbl'].'</option>
									<option value="R">'.$txt['yazipozisyonr'].'</option>
									<option value="L">'.$txt['yazipozisyonl'].'</option>
									<option value="TR">'.$txt['yazipozisyontr'].'</option>
									<option value="T">'.$txt['yazipozisyont'].'</option>
									<option value="TL">'.$txt['yazipozisyontl'].'</option>							
								 </select>
								 <label>'.$txt['resimyazidurus'].'</label>
								 <select name="yazidurus">
									<option value="H">'.$txt['yazidurusyatay'].'</option>
									<option value="V">'.$txt['yazidurusdikey'].'</option>
								 </select>
							 </fieldset>';
						} 
						if (empty($modSettings['gelismismodextra']))
						{ 
						echo'
							 <fieldset class="gelismisaltyazı">
								<legend>'.$txt['gelismisextra'].'</legend>
								<input type="int" name="resimborder" placeholder="'.$txt['resimborder'].'" />
								<input type="text" name="resimborderrenk" placeholder="'.$txt['resimborderrenk'].'" />
								<input type="int" name="resimbordertrans" placeholder="'.$txt['resimbordertrans'].'" />
								<input type="text" name="resimparlaklık" placeholder="'.$txt['resimparlaklık'].'" />
								<input type="text" name="resimkontrast" placeholder="'.$txt['resimkontrast'].'" />
								<input type="text" name="resimseffaf" placeholder="'.$txt['resimseffaf'].'" />
								<input type="int" name="resimpiksel" placeholder="'.$txt['resimpiksel'].'" />
								'.$txt['resimgritonlama'].' <input type="checkbox" name="resimgritonlama" class="floatright"/><br class="clear"/>
								'.$txt['resimnegatif'].' <input type="checkbox" name="resimnegatif" class="floatright" /><br class="clear"/>
							 </fieldset>';
						} 
						echo'
					</fieldset>
					<a href="javascript:void(0);" onclick="document.getElementById(\'galerigelismis\').style.display = \'block\'; document.getElementById(\'galerigelismis_groups_link\').style.display = \'none\'; return false;" id="galerigelismis_groups_link" style="display: none;">[ ', $txt['galerigelismis'], ' ]</a>

					<script type="text/javascript"><!-- // --><![CDATA[
						document.getElementById("galerigelismis").style.display = "none";
						document.getElementById("galerigelismis_groups_link").style.display = "";
					// ]]></script><hr />';
					}
					echo'
					<input type="submit" name="yolla" class="floatright" value="'.$txt['galeriyukle'].'" />
					<br class="clear"/></form>
				</div>
		    </div>
		</div>
		<script>
			var galeriacilir = document.getElementById(\'galeriModal\');
			var btn = document.getElementById("galeriBtn");
			var span = document.getElementsByClassName("kapat")[0];
			btn.onclick = function() {
				galeriacilir.style.display = "block";
			}
			span.onclick = function() {
				galeriacilir.style.display = "none";
			}
			window.onclick = function(event) {
				if (event.target == galeriacilir) {
					galeriacilir.style.display = "none";
				}
			}
		</script>';
	}

}

?>