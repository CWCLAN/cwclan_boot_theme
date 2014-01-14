<?php

/* ---------------------------------------------------+ 
  | PHP-Fusion 6 Content Management System
  +----------------------------------------------------+
  | Copyright � 2002 - 2007 Nick Jones
  | http://www.php-fusion.co.uk/
  +----------------------------------------------------+
  | Released under the terms & conditions of v2 of the
  | GNU General Public License. For details refer to
  | the included gpl.txt file or visit http://gnu.org
  +----------------------------------------------------+
  /*---------------------------------------------------+
  | Advanced Forum Threads List Panel - (AFTLP)
  +----------------------------------------------------+
  | Modder: Shedrock / Xandra - Fuzed Themes
  | Special Credit: Xandra - Thank you for all your help
  | Support: http://phpfusion-themes.com
  |----------------------------------------------------+
  | Converted to PHP-Fusion v7 by Smokeman
  | PHPFusion-Tips.dk
  | Website: http://www.phpfusion-tips.dk
  +----------------------------------------------------
  | Converted to PHP-Fusion v7.02.xx by globeFrEak
  | cwclan.de
  | Website: http://www.cwclan.de
  | Modified for own Theme cwclan_boot
  +--------------------------------------------------------+
  | Modded for full responsive PHP-Fusion Theme
  | Repo : https://github.com/globeFrEak/CWCLAN-PHPF-Theme
  | Modders : globeFrEak, nevo & xero - www.cwclan.de
  +-------------------------------------------------------- */
require_once "../../maincore.php";
require_once THEMES . "templates/header.php";

if (!iMEMBER) {
    redirect("../../index.php");
}

if (!isset($lastvisited) || !isnum($lastvisited))
    $lastvisited = time();

add_to_title($locale['global_200'] . $locale['global_043']);

opentable($locale['global_043']."<span class='pull-right'><a href='".$settings["siteurl"]."' class='btn'>".$locale['global_090']."</a></span>");
$result = dbquery(
        "SELECT COUNT(post_id), tf.* FROM " . DB_POSTS . " tp
	INNER JOIN " . DB_FORUMS . " tf ON tp.forum_id = tf.forum_id
	WHERE " . groupaccess('tf.forum_access') . " AND tp.post_datestamp > '" . $lastvisited . "'
	GROUP BY tp.post_id"
);
$rows = dbrows($result);
$threads = 0;
if ($rows) {
    if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
        $_GET['rowstart'] = 0;
    }
    $result = dbquery(
            "SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
		tf.forum_name, tf.forum_access, tt.thread_subject, tu.user_id, tu.user_name
		FROM " . DB_POSTS . " tp
		INNER JOIN " . DB_FORUMS . " tf ON tp.forum_id = tf.forum_id
		INNER JOIN " . DB_THREADS . " tt ON tp.thread_id = tt.thread_id
		LEFT JOIN " . DB_USERS . " tu ON tp.post_author = tu.user_id
		WHERE " . groupaccess('tf.forum_access') . " AND tp.post_datestamp > '" . $lastvisited . "'
		ORDER BY tp.post_datestamp DESC LIMIT " . $_GET['rowstart'] . ",20"
    );
    $i = 0;
    $threads = dbrows($result);
    echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border2 forum_table'>\n<tr>\n";
    echo "<td class='tbl2' style='white-space:nowrap'><strong>" . $locale['global_048'] . "</strong></td>\n";
    echo "<td class='tbl2'><strong>" . $locale['global_044'] . "</strong></td>\n";
    echo "<td class='tbl2' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_050'] . "</strong></td>\n";
    echo "</tr>\n";
    while ($data = dbarray($result)) {        
        echo "<tr>\n";
        echo "<td class='tbl1' style='white-space:nowrap'>" . $data['forum_name'] . "</td>\n";
        echo "<td class='tbl1' ><a href='" . BASEDIR . "forum/viewthread.php?thread_id=" . $data['thread_id'] . "&amp;pid=" . $data['post_id'] . "#post_" . $data['post_id'] . "'>" . $data['thread_subject'] . "</a></td>\n";
        echo "<td class='tbl1' style='text-align:center;white-space:nowrap'><a href='" . BASEDIR . "profile.php?lookup=" . $data['post_author'] . "'>" . $data['user_name'] . "</a><br />\n" . showdate("forumdate", $data['post_datestamp']) . "</td>\n";
        echo "</tr>\n";
        $i++;
    }
    echo "<tr>\n</table>\n";
    echo "<div style='text-align:center'>" . sprintf($locale['global_055'], $rows, $threads) . "</div>\n";
} else {
    echo "<div style='text-align:center'>" . sprintf($locale['global_055'], 0, 0) . "</div>\n";
}
closetable();
if ($rows > 20) {
    echo "<div align='center' style='margin-top:5px;'>\n" . makepagenav($_GET['rowstart'], 20, $rows, 3) . "\n</div>\n";
}

require_once THEMES . "templates/footer.php";
?>