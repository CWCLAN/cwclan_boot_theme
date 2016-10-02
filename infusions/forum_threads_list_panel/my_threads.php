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

if (file_exists(INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "forum_threads_list_panel/locale/English.php";
}

if (!iMEMBER) {
    redirect("../../index.php");
}

$imageold = "<span class='icon-folder-open mid cwtooltip' title='" . $locale['561'] . "'></span>";
$imagenew = "<span class='icon-folder mid c_orange cwtooltip' title='" . $locale['560'] . "'></span>";
$imagelocked = "<span class='icon-lock mid cwtooltip' title='" . $locale['564'] . "'></span> ";
$imagehot = "<span class='icon-star3 mid cwtooltip' title='" . $locale['563'] . "'></span> ";

add_to_title($locale['global_200'] . $locale['global_041']);

global $lastvisited;

if (!isset($lastvisited) || !isnum($lastvisited)) {
    $lastvisited = time();
}

$result = dbquery(
        "SELECT COUNT(thread_id) FROM " . DB_THREADS . " tt
	INNER JOIN " . DB_FORUMS . " tf ON tt.forum_id = tf.forum_id
	INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser = tu.user_id
	WHERE " . groupaccess('tf.forum_access') . " AND tt.thread_author='" . $userdata['user_id'] . "' LIMIT 100"
);
$rows = dbrows($result);
if ($rows) {
    if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
        $_GET['rowstart'] = 0;
    }
    $result = dbquery(
            "SELECT tt.forum_id, tt.thread_id, tt.thread_lastpostid as last_id, tt.thread_subject, tt.thread_views, tt.thread_lastuser,
		tt.thread_lastpost, tt.thread_postcount, tt.thread_locked, tt.thread_sticky, tf.forum_name, tf.forum_access, tu.user_id, tu.user_name
		FROM " . DB_THREADS . " tt
		INNER JOIN " . DB_FORUMS . " tf ON tt.forum_id = tf.forum_id
		INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser = tu.user_id
		WHERE " . groupaccess('tf.forum_access') . " AND tt.thread_author = '" . $userdata['user_id'] . "'
		ORDER BY tt.thread_lastpost DESC LIMIT " . $_GET['rowstart'] . ",20"
    );
    $i = 0;
    opentable($locale['global_041'] . "<span class='pull-right'><a href='" . $settings["siteurl"] . "' class='btn'>" . $locale['global_090'] . "</a></span>");
    echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border2 forum_table'>\n<tr>\n";
    echo "<td class='tbl2'>&nbsp;</td>\n";
    echo "<td width='100%' class='tbl2'><strong>" . $locale['global_044'] . "</strong></td>\n";
    echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_045'] . "</strong></td>\n";
    echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_046'] . "</strong></td>\n";
    echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_047'] . "</strong></td>\n";
    echo "</tr>\n";
    while ($data = dbarray($result)) {
        $locked = ($data['thread_locked'] ? $imagelocked : "");
        $sticky = ($data['thread_sticky'] ? $imagehot : "");
        echo "<tr>\n";
        echo "<td>$sticky$locked";
        if ($data['thread_lastpost'] > $lastvisited) {
            $thread_match = $data['thread_id'] . "\|" . $data['thread_lastpost'] . "\|" . $data['forum_id'];
            if (iMEMBER && preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                echo $imageold;
            } else {
                echo $imagenew;
            }
        } else {
            echo $imageold;
        }
        echo "</td>\n";
        echo "<td class='tbl1' ><a href='" . FORUM . "thread-" . seostring($data['thread_id']) . "-" . seostring($data['thread_subject']) . ".html' title='" . $data['thread_subject'] . "'>" . trimlink($data['thread_subject'], 30) . "</a><br />\n" . $data['forum_name'] . "</td>\n";
        echo "<td class='tbl1' style='text-align:center;white-space:nowrap'>" . $data['thread_views'] . "</td>\n";
        echo "<td class='tbl1' style='text-align:center;white-space:nowrap'>" . ($data['thread_postcount'] - 1) . "</td>\n";
        echo "<td class='tbl1' style='text-align:center;white-space:nowrap'>&nbsp;"
        . profile_link($data['thread_lastuser'], seostring($data['user_name']))
        . "<a href='" . FORUM . "post-" . seostring($data['thread_subject']) . "-" . $data['thread_id'] . "-" . $data['last_id'] . ".html#post_" . $data['last_id'] . "' "
        . "title='" . $locale['ftl125'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_minipost_new.gif' alt='" . $locale['ftl125'] . "' border='0' /></a>
		<br />\n" . showdate("forumdate", $data['thread_lastpost']) . "</td>\n";
        echo "</tr>\n";
        $i++;
    }
    echo "</table>\n";
    closetable();
    if ($rows > 20)
        echo "<div align='center' style='margin-top:5px;'>\n" . makepagenav($_GET['rowstart'], 20, $rows, 3) . "\n</div>\n";
} else {
    opentable($locale['global_041'] . "<span class='pull-right'><a href='" . $settings["siteurl"] . "' class='btn'>" . $locale['global_090'] . "</a></span>");
    echo "<div style='text-align:center'><br />\n" . $locale['global_053'] . "<br /><br />\n</div>\n";
    closetable();
}

require_once THEMES . "templates/footer.php";
?>
