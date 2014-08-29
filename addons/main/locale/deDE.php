<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id: deDE.php 2539 2012-06-10 03:40:09Z ulminia@gmail.com $
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Locale
 */

// -[ deDE Localization ]-

// Installer names
$lang['news']         = 'Neuigkeiten';
$lang['news_desc']    = 'Neuigkeitensystem mit Kommentaren';

// Button names
$lang['cms_button']   = 'Gildenportal|Schreibt Artikel und andere Items von interesse';

$lang['no_news']      = 'Keine Neuigkeiten verf�gbar';
$lang['bad_news_id']  = 'Falsche Neuigkeiten ID';

$lang['add_news']     = 'Neuigkeiten hinzuf�gen';
$lang['enable_html']  = 'HTML aktivieren';
$lang['disable_html'] = 'HTML deaktivieren';
$lang['posted_by']    = 'Ver�ffentlicht von';
$lang['edit']         = 'Bearbeiten';
$lang['edit_news']    = 'Neuigkeit bearbeiten';
$lang['comments']     = 'Kommentare';
$lang['add_comment']  = 'Kommentar hinzuf�gen';
$lang['edit_comment'] = 'Kommentar bearbeiten';
$lang['n_comment']    = '%s Kommentar';
$lang['n_comments']   = '%s Kommentare';

$lang['whos_online']  = 'Wer ist online';
$lang['total']			= 'Total';
$lang['reg']			= 'Registered';
$lang['guests']			= 'Guests';
$lang['bots']			= 'Bots';

$lang['b_title']  = 'Titel';
$lang['b_desc']   = 'Beschreibung';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Bilddatei';
$lang['b_add']    = 'Slider Bild hinzuf�gen';
$lang['b_upload'] = 'Slider Bild hochladen';
$lang['b_video'] = 'Video';

$lang['news_edit_success']     = 'Neuigkeit erfolgreich bearbeitet';
$lang['news_add_success']      = 'Neuigkeit erfolgreich hinzugef�gt';
$lang['slider_add_success']    = 'Slider Bild %1$s erfolgreich hinzugef�gt';
$lang['slider_error_db']       = 'Es gab ein Datenbankproblem beim hinzuf�gen des Slider Bildes';
$lang['slider_file_error']     = 'Der Upload wurde abgebrochen. Das Bild [%1$s] konnte nicht hochgeladen werden.';
$lang['news_error_process']    = 'Es ist ein Problem beim Verarbeiten des Artikels aufgetreten';

$lang['comment_edit_success']  = 'Kommentar erfolgreich editiert';
$lang['comment_add_success']   = 'Kommentar erfolgreich hinzugef�gt';
$lang['comment_error_process'] = 'Es ist ein Problem beim Verarbeiten des Kommentars aufgetreten';

// Config strings
$lang['admin']['cmsmain_conf']          = 'Neuigkeiten Konfiguration|Grundlegende Einstellungen';
$lang['admin']['cmsmain_slider']        = 'Slider|Bildslider Konfiguration';
$lang['admin']['cmsmain_slider_images'] = 'Slider Billder';
$lang['admin']['cmsmain_slider_add']    = 'Slider hinzuf�gen';
$lang['admin']['news_add']              = 'Neuigkeiten hinzuf�gen|Minimum ben�tigtes Login Level um Neuigkeiten hinzuzuf�gen.';
$lang['admin']['news_edit']             = 'Neuigkeiten bearbeiten|Minimum ben�tigtes Login Level um Neuigkeiten zu bearbeiten.';
$lang['admin']['comm_add']              = 'Kommentar hinzuf�gen|Minimum ben�tigtes Login Level um Kommentare hinzuzuf�gen.';
$lang['admin']['comm_edit']             = 'Kommentare bearbeiten|Minimum ben�tigtes Login Level um Kommentare zu bearbeiten.';
$lang['admin']['news_html']             = 'HTML Neuigkeiten|Standardm��ige Aktivierung oder Deaktivierung von HTML in Neuigkeiten, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Neuigkeiten';
$lang['admin']['comm_html']             = 'HTML Kommentare|Standardm��ige Aktivierung oder Deaktivierung von HTML in Kommentaren, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Kommentare';
$lang['admin']['news_nicedit']          = 'Nicedit Textbox|Aktivierung oder Deaktivierung der Niceedit Textbox.';

// Slider options
$lang['admin']['slider_skin']              = 'Slider Skin|W�hle das Aussehen der Buttons und Icons des Sliders';
$lang['admin']['slider_alignment']         = 'Ausrichtung|obenLinks, openMitte, obenRechts, mitteLinks, mitte, mitteRechts, untenLinks, untenMitte, untenRechts';
$lang['admin']['slider_autoAdvance']       = 'Auto Slide|Alle Bilder automatisch abspielen.';
$lang['admin']['slider_mobileAutoAdvance'] = 'Mobil Auto Slide|Alle Bilder auf mobilen Ger�ten automatisch abspielen';
$lang['admin']['slider_barDirection']      = 'Leistenrichtung|';
$lang['admin']['slider_barPosition']       = 'Leistenposition|';
$lang['admin']['slider_easing']            = 'Lockerung|';
$lang['admin']['slider_mobileEasing']      = 'Mobil Lockerung|Leer lassen was die Einstellungen die gleich wie bei Lockerung sein sollen.';
$lang['admin']['slider_fx']                = 'Effekt|Art des Slideeffekts<br />- Es kann mehr als ein Effekt ausgew�hlt werden';
$lang['admin']['slider_mobileFx']          = 'Mobil Effekt|Leer lassen f�r die gleichen Einstellungen wie bei Effekt.';
$lang['admin']['slider_gridDifference']    = 'Grid Abstand|Um die Grid-Bl�cke langsamer als die Slices zu machen, muss dieser Wert kleiner als der Wert von transPeriod sein.';
$lang['admin']['slider_height']            = 'H�he|H�he der Slider Bilder.<br />- Es k�nnen Pixel (als Beispiel \'300px\'), ein Prozentwert (relativ zur Breite der Slideshow, als Beispiel \'50%\') oder auto angegeben werden.';
$lang['admin']['slider_hover']             = 'Hover|Pause beim �berfahren mit der Maus. Nicht verf�gbar auf mobilen Ger�ten.';
$lang['admin']['slider_loader']            = 'Loader Typ|Loader Display Typ.<br />- Wenn "pie" ausgew�hlt wurde, wird auf �lteren Browser wie z.B. IE8 ein Ladebalken angezeigt, wenn diese kein Kreis wiedergeben k�nnen.';
$lang['admin']['slider_loaderColor']       = 'Loader Farbe|Farbe des Loaderbildes';
$lang['admin']['slider_loaderBgColor']     = 'Loader Hintergrundfarbe|Hintergrundfarbe des Loaderbildes';
$lang['admin']['slider_loaderOpacity']     = 'Loader Deckkraft|Die Transparenz des Loaderbildes';
$lang['admin']['slider_loaderPadding']     = 'Loader Padding|Wieviel Pixel Abstand soll zwischen dem Loaderbild und dem Hintergrund sein';
$lang['admin']['slider_loaderStroke']      = 'Loader Strich|Die Dicke des Kreises und des Ladebalkens.<br />Bedenke: f�r den Kreis, muss die Dicke kleiner als die H�lfte des Kreisdurchmessers sein';
$lang['admin']['slider_minHeight']         = 'Min H�he|Mindesth�he des Sliders<br />- Kann auch leer gelassen werden';
$lang['admin']['slider_navigation']        = 'Navigation|Zeigt die Nafivationsbuttons (Zur�ck, Vor und Start/Stop Buttons)';
$lang['admin']['slider_navigationHover']   = 'Navigation Hover|Wenn "ja / yes" aktiviert ist, wird die Navigatio nur beim �berfahren mit der Maus angezeigt, bei "nein / no" immer.';
$lang['admin']['slider_mobileNavHover']    = 'Mobile Navigation Hover|Das gleich wie "Navigation Hover" nur f�r mobile Ger�te';
$lang['admin']['slider_opacityOnGrid']     = 'Deckkraft auf Raster|Wendet einen Fade Effekt auf die Bl�cke und Slices an<br />- Wenn die Slideshow im Volldbild l�uft oder einfach nur gro� ist, ist es empfohlen diese Einstellung auf "false" zu setzen f�r einen glatteren Effekt.';
$lang['admin']['slider_overlayer']         = 'Overlayer|Platziert einen zus�tzliche "transparente" Ebene �ber die Bilder um ein einfaches speichern der Bilder durch Rechtsklick mit der Maus zu verhinden';
$lang['admin']['slider_pagination']        = 'Seitengenierung|Zeigt das Seitengenerierungsicon';
$lang['admin']['slider_playPause']         = 'Start/Pause|Zeigt den Start/Pause Button';
$lang['admin']['slider_pauseOnClick']      = 'Pause bei Klick|Stoppt die Slideshow beim anklicken der Slider';
$lang['admin']['slider_pieDiameter']       = 'Kreisdurchmesser|Durchmesser des Kreises des Loaders';
$lang['admin']['slider_piePosition']       = 'Kreisposition|Wo soll der Kreis des Loaders angezeigt werden';
$lang['admin']['slider_portrait']          = 'Portr�t|Aktivieren, wenn Bilder nicht beschnitten werden sollen';
$lang['admin']['slider_cols']              = 'Spalten|';
$lang['admin']['slider_rows']              = 'Reihen|';
$lang['admin']['slider_slicedCols']        = 'Sliced Spalten|Wenn 0 = der Wert von Spalten';
$lang['admin']['slider_slicedRows']        = 'Sliced Reihen|Wenn 0 = der Wert von Reihen';
$lang['admin']['slider_slideOn']           = 'Slide On|Wann soll der �bergangseffekt angewandt werden';
$lang['admin']['slider_thumbnails']        = 'Vorschaubilder|Zeigt Vorschaubilder beim �berfahren der Seitenicons mit der Maus';
$lang['admin']['slider_time']              = 'Dauer|Dauer in Millisekunden zwischen zwei Slideeffekten';
$lang['admin']['slider_transPeriod']       = 'Effektdauer|L�nge des �bergangseffekts in Millisekunden';
