<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: latestsphotos.php
  | Author: globeFrEak
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +--------------------------------------------------------+
  | HighSlide Infusion by FlyingDuck.Dk
  +--------------------------------------------------------+
  | Photogallery based on the work of Nick Jones & Wooya
  | Modified and improved by | FlyingDuck.Dk.
  | Infusionized by FlyingDuck.Dk
  | HighSlide JavaScript by Torstein H�ns
  | Copyrighted under the Creative Commons 2.5 license.
  +------------------------------------------------------- */
require_once "../../maincore.php";
require_once THEMES . "templates/header.php";
include LOCALE . LOCALESET . "photogallery.php";

if (file_exists(INFUSIONS . "hsgallery_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "hsgallery_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "hsgallery_panel/locale/English.php";
}
//add_to_head("<script type='text/javascript' src='" . INFUSIONS . "hsgallery_panel/highslide/highslide-with-gallery.js'></script>");
//add_to_head("<link rel='stylesheet' href='" . INFUSIONS . "hsgallery_panel/highslide/highslide.css' type='text/css' />");

define("SAFEMODE", @ini_get("safe_mode") ? true : false);
define("PHOTODIR", PHOTOS);

/////////                            
if (isset($_GET['sort']) && $_GET['sort'] == "user") {
    if (isset($_POST['user']) && isnum($_POST['user'])) {
        $user = $_POST['user'];
        $result = dbquery(
                "SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes, ta.album_title AS album_title
                                            FROM " . DB_PHOTOS . " tp
                                            LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
                                            LEFT JOIN " . DB_RATINGS . " tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
                                            LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
                                            WHERE " . groupaccess('album_access') . "
                                                AND tp.photo_user = " . $user . "
                                            GROUP BY photo_id ORDER BY photo_datestamp DESC LIMIT 33");
        add_to_title("&nbsp;-&nbsp;Die vierzig neusten Fotos eines Users");
        $text = "Die vierzig neusten Fotos eines Users";
    } else {
        $result = dbquery(
                "SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes, ta.album_title AS album_title
					FROM " . DB_PHOTOS . " tp
					LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
					LEFT JOIN " . DB_RATINGS . " tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
					LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
					WHERE " . groupaccess('album_access') . "
                                        GROUP BY photo_id ORDER BY photo_datestamp DESC LIMIT 33");
        add_to_title("&nbsp;-&nbsp;Die vierzig neusten Fotos");
        $text = "Die vierzig neusten Fotos";
    }
} elseif (isset($_GET['sort']) && $_GET['sort'] == "album") {
    if (isset($_POST['album']) && isnum($_POST['album'])) {
        $album = $_POST['album'];
        $result = dbquery(
                "SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes, ta.album_title AS album_title
                                            FROM " . DB_PHOTOS . " tp
                                            LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
                                            LEFT JOIN " . DB_RATINGS . " tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
                                            LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
                                            WHERE " . groupaccess('album_access') . "
                                                AND tp.album_id = " . $album . "
                                            GROUP BY photo_id ORDER BY photo_datestamp DESC LIMIT 33");
        add_to_title("&nbsp;-&nbsp;Die vierzig neusten Fotos eines Bilderalbums");
        $text = "Die vierzig neusten Fotos eines Bilderalbums";
    } else {
        $result = dbquery(
                "SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes, ta.album_title AS album_title
					FROM " . DB_PHOTOS . " tp
					LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
					LEFT JOIN " . DB_RATINGS . " tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
					LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
					WHERE " . groupaccess('album_access') . "
                                        GROUP BY photo_id ORDER BY photo_datestamp DESC LIMIT 33");
        add_to_title("&nbsp;-&nbsp;Die vierzig neusten Fotos");
        $text = "Die vierzig neusten Fotos";
    }
} else {
    $result = dbquery(
            "SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes, ta.album_title AS album_title
					FROM " . DB_PHOTOS . " tp
					LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
					LEFT JOIN " . DB_RATINGS . " tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
					LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
					WHERE " . groupaccess('album_access') . "
                                        GROUP BY photo_id ORDER BY photo_datestamp DESC LIMIT 33");
    add_to_title("&nbsp;-&nbsp;Die vierzig neusten Fotos");
    $text = "Die vierzig neusten Fotos";
}

echo "<!--new_photos_info-->";
opentable($text);
/// GUI START ///

echo "<h5>Sortierung der neusten Fotos eines Users</h5>";
$resultuser = dbquery(
        "SELECT tu.user_name AS user_name, tu.user_id AS user_id
                                            FROM " . DB_PHOTOS . " tp
                                            LEFT JOIN " . DB_USERS . " tu ON tp.photo_user=tu.user_id
                                            LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
                                            WHERE " . groupaccess('album_access') . "
                                            GROUP BY tp.photo_user"
);
echo "<form name='sort' method='post' action='" . FUSION_SELF . "?sort=user'>\n";
echo "<select name='user' size='1' onchange=\"javascript:this.form.submit()\">\n";
echo "<option value='u'>zur&uuml;cksetzen</option>\n";
while ($datauser = dbarray($resultuser)) {
    if ($datauser['user_id'] == $_POST['user']) {
        echo "<option value='" . $datauser['user_id'] . "' selected>" . $datauser['user_name'] . "</option>\n";
    } else {
        echo "<option value='" . $datauser['user_id'] . "'>" . $datauser['user_name'] . "</option>\n";
    }
}
echo "</select>\n";
echo "</form>\n";
echo "<h5>Sortierung der neusten Fotos eines Bilderalbums</h5>";
$resultalbum = dbquery(
        "SELECT tp.album_id AS album_id, ta.album_title AS album_title
                                            FROM " . DB_PHOTOS . " tp                                            
                                            LEFT JOIN " . DB_PHOTO_ALBUMS . " ta ON tp.album_id = ta.album_id
                                            WHERE " . groupaccess('album_access') . "
                                            GROUP BY tp.album_id"
);
echo "<form name='sort' method='post' action='" . FUSION_SELF . "?sort=album'>\n";
echo "<select name='album' size='1' onchange=\"javascript:this.form.submit()\">\n";
echo "<option value='a'>zur&uuml;cksetzen</option>\n";
while ($dataalbum = dbarray($resultalbum)) {
    if ($dataalbum['album_id'] == $_POST['album']) {
        echo "<option value='" . $dataalbum['album_id'] . "' selected>" . $dataalbum['album_title'] . "</option>\n";
    } else {
        echo "<option value='" . $dataalbum['album_id'] . "'>" . $dataalbum['album_title'] . "</option>\n";
    }
}
echo "</select>\n";
echo "</form>\n";
echo "<br><hr />";
/// GUI ENDE ///


echo "<div class='row'>";
$counter = 0;
$count3 = 0;
while ($data = dbarray($result)) {

    //Foto Album Ansicht (vor und zurück & slideshow aktiv)//
    //Anzeige im JAVASCRIPT POPUP von Bild Info & Kommentar link//
    $photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='" . $data['photo_id'] . "'");

    /*     * $highslides = "<div class='highslide-caption' id='caption" . $data['photo_id'] . "' style='text-align:left'>" . ($data['photo_description'] != '' ? nl2br(parseubb($data['photo_description'])) . "<br/>\n" : "") . "<a href='" . INFUSIONS . "hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html'>" . $locale['hsg117'] . "(" . $photo_comments . ")</a>
      &nbsp;&nbsp;<a href='" . INFUSIONS . "hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html'>" . $locale['hsg122'] . "</a>
      </div>
      <div id='controlbar' class='highslide-overlay controlbar' style='display:block;'>
      <a href='#' class='previous' onclick='return hs.previous(this)' title='" . $locale['hsg113'] . "'></a>
      <a href='#' class='next' onclick='return hs.next(this)' title='" . $locale['hsg114'] . "'></a>
      <a href='#' class='highslide-move' onclick='return false' title='" . $locale['hsg115'] . "'></a>
      <a href='#' class='close' onclick='return hs.close(this)' title='" . $locale['hsg116'] . "'></a>
      </div>\n";* */

    if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) {
        //    echo "<hr>";
    }

    echo "<div class='pic col-xs-6 col-sm-4'>";
    echo "<div class='thumbnail'>";
    //echo "<a href='" . PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_filename'] . "' onclick=\"return hs.expand(this, { captionId: 'caption" . $data['photo_id'] . "' });\" rel='highslide' title='" . $data['photo_description'] . "' class='highslide'>";
    if ($data['photo_thumb1'] && file_exists(PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'])) {
        echo "<a href='" . INFUSIONS . "hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html' class='cwtooltip' title='".$data['photo_title']."'>";
        echo "<div class='crop' style='background-image: url(\"" . PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "\")'></div>";  
        echo "</a>";
//echo "<a href='" . INFUSIONS . "hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html'><img src='" . PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "' alt='" . $data['photo_thumb1'] . "' title='" . $locale['431'] . "' class='img-responsive'></a>"; //old class='photogallery_album_photo'
    } else {
        echo $locale['432'];
    }
    /**
      echo "<div class='caption'>";
      echo "<h4>" . trimlink($data['photo_title'], 30) . "</h4>";
      echo "<div>";
      echo "Datum:&nbsp;" . showdate("shortdate", $data['photo_datestamp']) . "<br />\n";
      echo "Album:&nbsp;" . $data['album_title'] . "<br />";
      echo $locale['434'] . "<a href='" . BASEDIR . "user_" . $data['user_id'] . "_" . seostring($data['user_name']) . ".html'>" . $data['user_name'] . "</a><br />";
      echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']) . $photo_comments . "<br />";
      //echo $locale['437'] . ($data['count_votes'] > 0 ? str_repeat("<img src='" . IMAGES . "star.gif' alt='*' style='vertical-align:middle' />", ceil($data['sum_rating'] / $data['count_votes'])) : $locale['438']) . "<br />\n";
      echo $locale['435'] . $data['photo_views'] . "</span><br />\n";
      echo "<a href='" . INFUSIONS . "hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html' title='" . $locale['408'] . "'>" . $locale['407'] . "</a>";
      echo "</div>";
      echo "</div>"; */
    echo "</div>";
    echo "</div>";
    $counter++;
    /* $count3++;

      if ($count3 === 3) {
      $count3 = 0;
      echo "</div><div class='row'>";
      } */
    //echo $highslides;
}
echo "</div>";


//Config Script für die HSGALLERY.JS//
echo "<script type='text/javascript'>
				
				hs.fullExpandTitle = '" . $locale['hsg110'] . "';
				hs.restoreTitle = '" . $locale['hsg112'] . "';
				hs.focusTitle = '" . $locale['hsg121'] . "';
				hs.loadingText = '" . $locale['hsg111'] . "';
				hs.loadingTitle = '" . $locale['hsg120'] . "';
				hs.creditsText = '" . $locale['hsg118'] . "';
				hs.creditsTitle = '" . $locale['hsg119'] . "';				
				hs.showCredits = 0;
				//hs.dimmingOpacity = 0.75;

				hs.graphicsDir = 'highslide/graphics/';
				hs.align = 'center';
				hs.transitions = ['expand', 'crossfade'];
				hs.outlineType = 'rounded-white';
				hs.fadeInOut = true;
				hs.dimmingOpacity = 0.75;

				// define the restraining box
				hs.useBox = true;
				hs.width = 800;
				hs.height = 600;

				// Add the controlbar
				hs.addSlideshow({
				//slideshowGroup: 'group1',
				interval: 3000,
				repeat: true,
				useControls: true,
				fixedControls: 'fit',
				overlayOptions: {
				opacity: 1,
				position: 'bottom center',
				hideOnMouseOut: true
				}
				});
				</script>\n";

closetable();

require_once THEMES . "templates/footer.php";
?>