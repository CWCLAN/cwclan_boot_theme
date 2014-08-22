<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: shoutbox_panel.php
  | Author: Nick Jones (Digitanium)
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
if (!defined("IN_FUSION")) {
    die("Access Denied");
}
include_once INFUSIONS . "shoutbox_panel/infusion_db.php";
include_once INCLUDES . "infusions_include.php";
include_once LOCALE . LOCALESET . "forum/main.php";

add_to_head("<script type='text/javascript'>
function textCounter(textarea, counterID, maxLen) {
    cnt = document.getElementById(counterID);
    if (textarea.value.length > maxLen){
        textarea.value = textarea.value.substring(0,maxLen);
    }
    cnt.innerHTML = maxLen - textarea.value.length;
}
</script>");

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS . "shoutbox_panel/locale/" . $settings['locale'] . ".php")) {
    // Load the locale file matching the current site locale setting.
    include INFUSIONS . "shoutbox_panel/locale/" . $settings['locale'] . ".php";
} else {
    // Load the infusion's default locale file.
    include INFUSIONS . "shoutbox_panel/locale/English.php";
}

$shout_settings = get_settings("shoutbox_panel");

$link = FUSION_SELF . (FUSION_QUERY ? "?" . FUSION_QUERY : "");
$link = preg_replace("^(&amp;|\?)s_action=(edit|delete)&amp;shout_id=\d*^", "", $link);
$sep = stristr($link, "?") ? "&amp;" : "?";
$shout_link = "";
$shout_message = "";

if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "delete") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
    if ((iADMIN && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='" . $_GET['shout_id'] . "' AND shout_name='" . $userdata['user_id'] . "'"))) {
        $result = dbquery("DELETE FROM " . DB_SHOUTBOX . " WHERE shout_id='" . $_GET['shout_id'] . "'" . (iADMIN ? "" : " AND shout_name='" . $userdata['user_id'] . "'"));
    }
    redirect($link);
}

if (!function_exists("sbwrap")) {

    function sbwrap($text) {
        global $locale;

        $i = 0;
        $tags = 0;
        $chars = 0;
        $res = "";

        $str_len = strlen($text);

        for ($i = 0; $i < $str_len; $i++) {
            $chr = mb_substr($text, $i, 1, $locale['charset']);
            if ($chr == "<") {
                if (mb_substr($text, ($i + 1), 6, $locale['charset']) == "a href" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "img") {
                    $chr = " " . $chr;
                    $chars = 0;
                }
                $tags++;
            } elseif ($chr == "&") {
                if (mb_substr($text, ($i + 1), 5, $locale['charset']) == "quot;") {
                    $chars = $chars - 5;
                } elseif (mb_substr($text, ($i + 1), 4, $locale['charset']) == "amp;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#39;" || mb_substr($text, ($i + 1), 4, $locale['charset']) == "#92;") {
                    $chars = $chars - 4;
                } elseif (mb_substr($text, ($i + 1), 3, $locale['charset']) == "lt;" || mb_substr($text, ($i + 1), 3, $locale['charset']) == "gt;") {
                    $chars = $chars - 3;
                }
            } elseif ($chr == ">") {
                $tags--;
            } elseif ($chr == " ") {
                $chars = 0;
            } elseif (!$tags) {
                $chars++;
            }

            if (!$tags && $chars == 18) {
                $chr .= "<br />";
                $chars = 0;
            }
            $res .= $chr;
        }

        return $res;
    }

}

openside("<span class='icon-bubble2 iconpaddr'></span>" . $locale['SB_title']);
if (iMEMBER || $shout_settings['guest_shouts'] == "1") {
    include_once INCLUDES . "bbcode_include.php";
    if (isset($_POST['post_shout'])) {
        $flood = false;
        if (iMEMBER) {
            $shout_name = $userdata['user_id'];
        } elseif ($shout_settings['guest_shouts'] == "1") {
            $shout_name = trim(stripinput($_POST['shout_name']));
            $shout_name = preg_replace("(^[+0-9\s]*)", "", $shout_name);
            if (isnum($shout_name)) {
                $shout_name = "";
            }
            include_once INCLUDES . "captchas/securimage/securimage.php";
            $securimage = new Securimage();
            if (!isset($_POST['sb_captcha_code']) || $securimage->check($_POST['sb_captcha_code']) == false) {
                redirect($link);
            }
        }
        $shout_message = str_replace("\n", " ", $_POST['shout_message']);
        $shout_message = preg_replace("/^(.{300}).*$/", "$1", $shout_message);
        $shout_message = trim(stripinput(censorwords($shout_message)));
        if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
            $comment_updated = false;
            if ((iADMIN && checkrights("S")) || (iMEMBER && dbcount("(shout_id)", DB_SHOUTBOX, "shout_id='" . $_GET['shout_id'] . "' AND shout_name='" . $userdata['user_id'] . "'"))) {
                if ($shout_message) {
                    $result = dbquery("UPDATE " . DB_SHOUTBOX . " SET shout_message='$shout_message' WHERE shout_id='" . $_GET['shout_id'] . "'" . (iADMIN ? "" : " AND shout_name='" . $userdata['user_id'] . "'"));
                }
            }
            redirect($link);
        } elseif ($shout_name && $shout_message) {
            require_once INCLUDES . "flood_include.php";
            if (!flood_control("shout_datestamp", DB_SHOUTBOX, "shout_ip='" . USER_IP . "'")) {
                $result = dbquery("INSERT INTO " . DB_SHOUTBOX . " (shout_name, shout_message, shout_datestamp, shout_ip, shout_ip_type, shout_hidden) VALUES ('$shout_name', '$shout_message', '" . time() . "', '" . USER_IP . "', '" . USER_IP_TYPE . "', '0')");
            }
        }
        redirect($link);
    }
    if (iMEMBER && (isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
        $esresult = dbquery(
                "SELECT ts.shout_id, ts.shout_name, ts.shout_message, tu.user_id, tu.user_name
			FROM " . DB_SHOUTBOX . " ts
			LEFT JOIN " . DB_USERS . " tu ON ts.shout_name=tu.user_id
			WHERE ts.shout_id='" . $_GET['shout_id'] . "'"
        );
        if (dbrows($esresult)) {
            $esdata = dbarray($esresult);
            if ((iADMIN && checkrights("S")) || (iMEMBER && $esdata['shout_name'] == $userdata['user_id'] && isset($esdata['user_name']))) {
                if ((isset($_GET['s_action']) && $_GET['s_action'] == "edit") && (isset($_GET['shout_id']) && isnum($_GET['shout_id']))) {
                    $edit_url = $sep . "s_action=edit&amp;shout_id=" . $esdata['shout_id'];
                } else {
                    $edit_url = "";
                }
                $shout_link = $link . $edit_url;
                $shout_message = $esdata['shout_message'];
            }
        } else {
            $shout_link = $link;
            $shout_message = "";
        }
    } else {
        $shout_link = $link;
        $shout_message = "";
    }

    echo "<a id='edit_shout' name='edit_shout'></a>\n";
    echo "<form name='shout_form' method='post' action='" . $shout_link . "'>\n";
    if (iGUEST) {
        echo $locale['SB_name'] . "<br />\n";
        echo "<input type='text' name='shout_name' value='' class='textbox' maxlength='30' style='width:100%' /><br />\n";
        echo $locale['SB_message'] . "<br />\n";
    }    
    echo "<textarea name='shout_message' rows='4' cols='20' class='textbox' style='width:100%' onKeyDown='textCounter(this,\"count_display\",300);' onKeyUp='textCounter(this,\"count_display\",300);'>" . $shout_message . "</textarea><br />\n";
    echo "<span id='count_display' style='float: right;padding : 1px 3px 1px 3px;'><strong>300</strong></span>";
    echo display_bbcodes("100%;", "shout_message", "shout_form", "smiley|b|u|url|color") . "\n";
    if (iGUEST) {
        echo $locale['SB_validation_code'] . "<br />\n";
        echo "<img id='sb_captcha' src='" . INCLUDES . "captchas/securimage/securimage_show.php' alt='' /><br />\n";
        echo "<a href='" . INCLUDES . "captchas/securimage/securimage_play.php'><img src='" . INCLUDES . "captchas/securimage/images/audio_icon.gif' alt='' class='tbl-border' style='margin-bottom:1px' /></a>\n";
        echo "<a href='#' onclick=\"document.getElementById('sb_captcha').src = '" . INCLUDES . "captchas/securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='" . INCLUDES . "captchas/securimage/images/refresh.gif' alt='' class='tbl-border' /></a><br />\n";
        echo $locale['SB_enter_validation_code'] . "<br />\n<input type='text' name='sb_captcha_code' class='textbox' style='width:100px' /><br />\n";
    }
    echo "<input type='submit' name='post_shout' value='" . $locale['SB_shout'] . "' class='button' />\n";
    echo "</form>\n<br />\n";
} else {
    echo "<div style='text-align:center'>" . $locale['SB_login_req'] . "</div><br />\n";
}
$numrows = dbcount("(shout_id)", DB_SHOUTBOX, "shout_hidden='0'");
$result = dbquery(
        "SELECT ts.shout_id, ts.shout_name, ts.shout_message, ts.shout_datestamp, tu.user_id, tu.user_name, tu.user_status, tu.user_avatar
	FROM " . DB_SHOUTBOX . " ts
	LEFT JOIN " . DB_USERS . " tu ON ts.shout_name=tu.user_id
	WHERE shout_hidden='0'
	ORDER BY ts.shout_datestamp DESC LIMIT 0," . $shout_settings['visible_shouts']
);
if (dbrows($result)) {
    $i = 0;
    while ($data = dbarray($result)) {
        echo "<div class='shout clearfix'>";
        echo "<div class='shoutboxname clearfix'>";

        /* UserAvatar */
        if ($data['user_avatar'] && file_exists(BASEDIR . "images/avatars/" . $data['user_avatar']) && $data['user_status'] != 6 && $data['user_status'] != 5) {
            echo "<a href='" . BASEDIR . "profile.php?lookup=" . $data['user_id'] . "'><img class='shoutbox_user_avatar' title='" . $data['user_name'] . "' src='" . IMAGES . "avatars/" . $data['user_avatar'] . "' alt='" . $locale['567'] . "' /></a>\n";
        } else {
            echo "<a href='" . BASEDIR . "profile.php?lookup=" . $data['user_id'] . "'><img class='shoutbox_user_avatar' src='" . IMAGES . "avatars/noavatar100.png' alt='" . $locale['567'] . "' /></a>\n";
        }

        echo "</div>\n";
        echo "<div class='shoutbody'>";

        /* Date */
        echo "<div class='shoutboxdate clearfix'><span class='icon-clock'></span> " . showdate("forumdate", $data['shout_datestamp']) . "</div>";

        /* Content */
        echo "<div class='shoutbox clearfix'>
        <div class='shoutheader'><a href='" . BASEDIR . "profile.php?lookup=" . $data['user_id'] . "'>" . $data['user_name'] . "</a> schrieb:</div>
        <div>" . sbwrap(parseubb(parsesmileys($data['shout_message']), "b|i|u|url|color")) . "</div></div>\n";

        /* Edit and Delete */
        if ((iADMIN && checkrights("S")) || (iMEMBER && $data['shout_name'] == $userdata['user_id'] && isset($data['user_name']))) {
            echo "[<a href='" . $link . $sep . "s_action=edit&amp;shout_id=" . $data['shout_id'] . "#edit_shout" . "' class='side'>" . $locale['SB_edit'] . "</a>]\n";
            echo "[<a href='" . $link . $sep . "s_action=delete&amp;shout_id=" . $data['shout_id'] . "' onclick=\"return confirm('" . $locale['SB_warning_shout'] . "');\" class='side'>" . $locale['SB_delete'] . "</a>]<br />\n";
        }
        $i++;
        if ($i != $numrows) {
            echo "</div></div>\n";
        }
    }
    if ($numrows > $shout_settings['visible_shouts']) {
        echo "<div style='text-align:center'>\n<a href='" . INFUSIONS . "shoutbox_panel/shoutbox_archive.php' class='side'>" . $locale['SB_archive'] . "</a>\n</div>\n";
    }
} else {
    echo "<div>" . $locale['SB_no_msgs'] . "</div>\n";
}
closeside();
?>