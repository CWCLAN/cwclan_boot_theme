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
  | Modded for full responsive PHP-Fusion Theme
  | Repo : https://github.com/globeFrEak/CWCLAN-PHPF-Theme
  | Modders : globeFrEak, nevo & xero - www.cwclan.de
  +-------------------------------------------------------- */
require_once "maincore.php";
require_once THEMES . "templates/header.php";
include LOCALE . LOCALESET . "photogallery.php";

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
    if (isset($_POST['album']) && $datauser['user_id'] == $_POST['user']) {
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
    if (isset($_POST['album']) && $dataalbum['album_id'] == $_POST['album']) {
        echo "<option value='" . $dataalbum['album_id'] . "' selected>" . $dataalbum['album_title'] . "</option>\n";
    } else {
        echo "<option value='" . $dataalbum['album_id'] . "'>" . $dataalbum['album_title'] . "</option>\n";
    }
}
echo "</select>\n";
echo "</form>\n";
echo "<br><hr />";
/// GUI ENDE ///

echo "<div class='row gallery'>";
while ($data = dbarray($result)) {
    $photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='" . $data['photo_id'] . "'");
    $title = ($data['photo_title'] ? "<span class='photo_title'>" . $data['photo_title'] . "</span>\n" : "");
    echo "<div class='pic col-xs-6'>";
    echo "<div class='thumbnail'>";
    echo "<a href='" . BASEDIR . "cw_photogallery.php?photo_id=" . $data['photo_id'] . "' class='cwtooltip' title='" . $data['photo_title'] . "'>";
    if ($data['photo_thumb2'] && file_exists(PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb2'])) {
        echo "<div class='crop' style='background-image: url(\"" . PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb2'] . "\")'>$title</div>";
    } elseif ($data['photo_thumb1'] && file_exists(PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'])) {
        echo "<div class='crop' style='background-image: url(\"" . PHOTODIR . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "\")'>$title</div>";
    } else {
        echo $locale['432'];
    }
    echo "</a>\n<!--photogallery_album_photo_info-->\n";
    echo "<span class='pull-right'>" . showdate("shortdate", $data['photo_datestamp']) . "</span>";
    echo ($data['photo_allow_comments'] ? ($photo_comments == 1 ? "<span>" . $locale['436b'] : "<span>" . $locale['436']) . $photo_comments . "</span>\n<br>\n" : "");
    echo "<span>" . $locale['435'] . $data['photo_views'] . "</span>\n<br>\n";
    echo "</div>\n";
    echo "</div>\n";
}
echo "</div>";
closetable();
require_once THEMES . "templates/footer.php";
?>