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

add_to_title($locale['global_200'] . $locale['global_042']);

$result = dbquery(
        "SELECT COUNT(post_id) AS rows FROM " . DB_POSTS . " tp
	INNER JOIN " . DB_FORUMS . " tf ON tp.forum_id=tf.forum_id
	WHERE " . groupaccess('tf.forum_access') . " AND post_author='" . $userdata['user_id'] . "'
	ORDER BY tp.post_datestamp DESC"
);
//$rows = dbrows($result);
$rows = dbarray($result);
if ($rows['rows']) {
    if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
        $_GET['rowstart'] = 0;
    }
    $result = dbquery(
            "SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
		tf.forum_name, tf.forum_access, tt.thread_subject
		FROM " . DB_POSTS . " tp
		INNER JOIN " . DB_FORUMS . " tf ON tp.forum_id=tf.forum_id
		INNER JOIN " . DB_THREADS . " tt ON tp.thread_id=tt.thread_id
		WHERE " . groupaccess('tf.forum_access') . " AND tp.post_author='" . $userdata['user_id'] . "'
		ORDER BY tp.post_datestamp DESC LIMIT " . $_GET['rowstart'] . ",20"
    );
    $i = 0;
    opentable($locale['global_042']."<span class='pull-right'><a href='".$settings["siteurl"]."' class='btn'>".$locale['global_090']."</a></span>");
    echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border2 forum_table'>\n<tr>\n";
    echo "<td class='tbl2' style='white-space:nowrap'><strong>" . $locale['global_048'] . "</strong></td>\n";
    echo "<td class='tbl2'><strong>" . $locale['global_044'] . "</strong></td>\n";
    echo "<td align='center' class='tbl2' style='white-space:nowrap'><strong>" . $locale['global_049'] . "</strong></td>\n";
    echo "</tr>\n";
    while ($data = dbarray($result)) {        
        echo "<tr>\n";
        echo "<td class='tbl1' style='white-space:nowrap'>" . trimlink($data['forum_name'], 30) . "</td>\n";
        echo "<td class='tbl1' ><a href='" . FORUM . "viewthread.php?thread_id=" . $data['thread_id'] . "&amp;pid=" . $data['post_id'] . "#post_" . $data['post_id'] . "' title='" . $data['thread_subject'] . "'>" . trimlink($data['thread_subject'], 40) . "</a></td>\n";
        echo "<td class='tbl1' align='center' style='white-space:nowrap'>" . showdate("forumdate", $data['post_datestamp']) . "</td>\n";
        echo "</tr>\n";
        $i++;
    }
    echo "</table>\n";
    if ($rows['rows'] > 20) {
        echo "<div align='center' style='margin-top:5px;'>\n" . makepagenav($_GET['rowstart'], 20, $rows['rows'], 3) . "\n</div>\n";
    }
    closetable();
} else {
    opentable($locale['global_042']."<span class='pull-right'><a href='".$settings["siteurl"]."' class='btn'>".$locale['global_090']."</a></span>");
    echo "<div style='text-align:center'><br />\n" . $locale['global_054'] . "<br /><br />\n</div>\n";
    closetable();
}

require_once THEMES . "templates/footer.php";
?>
