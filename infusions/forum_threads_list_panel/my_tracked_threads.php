<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: my_tracked_threads.php
  | Author: Robert Gaudyn (Wooya)
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
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

if (isset($_GET['delete']) && isnum($_GET['delete']) && dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='" . $_GET['delete'] . "' AND notify_user='" . $userdata['user_id'] . "'")) {
    $result = dbquery("DELETE FROM " . DB_THREAD_NOTIFY . " WHERE thread_id=" . $_GET['delete'] . " AND notify_user=" . $userdata['user_id']);
    redirect(FUSION_SELF);
}

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
    $_GET['rowstart'] = 0;
}

opentable($locale['global_056'] . "<span class='pull-right'><a href='" . $settings["siteurl"] . "' class='btn'>" . $locale['global_090'] . "</a></span>");

$rows = dbcount("(thread_id)", DB_THREAD_NOTIFY, "notify_user=" . $userdata['user_id']);

if ($rows) {
    $result = dbquery("
	SELECT
	tf.forum_access,
	tn.thread_id, tn.notify_datestamp, tn.notify_user,
	tt.thread_subject, tt.forum_id, tt.thread_lastpost, tt.thread_lastuser,
	tu.user_id AS user_id1, tu.user_name AS user_name1, 
	tu2.user_id AS user_id2, tu2.user_name AS user_name2,
	tp.post_datestamp,
	COUNT(post_id)-1 as replies FROM " . DB_THREAD_NOTIFY . " tn
	LEFT JOIN " . DB_THREADS . " tt ON tn.thread_id = tt.thread_id
	LEFT JOIN " . DB_FORUMS . " tf ON tt.forum_id = tf.forum_id
	LEFT JOIN " . DB_USERS . " tu ON tt.thread_author = tu.user_id
	LEFT JOIN " . DB_USERS . " tu2 ON tt.thread_lastuser = tu2.user_id
	INNER JOIN " . DB_POSTS . " tp ON tt.thread_id = tp.thread_id
	WHERE tn.notify_user=" . $userdata['user_id'] . " AND " . groupaccess('forum_access') . "
	GROUP BY tn.thread_id
	ORDER BY tn.notify_datestamp DESC
	LIMIT " . $_GET['rowstart'] . ",10
	");
    echo "<table class='tbl-border2 forum_idx_table' cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
    echo "<td class='tbl1' ><strong>" . $locale['global_044'] . "</strong></td>\n";
    echo "<td class='tbl1' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_050'] . "</strong></td>\n";
    echo "<td class='tbl1' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_047'] . "</strong></td>\n";
    echo "<td class='tbl1' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_046'] . "</strong></td>\n";
    echo "<td class='tbl1' style='text-align:center;white-space:nowrap'><strong>" . $locale['global_057'] . "</strong></td>\n";
    echo "</tr>\n";
    $i = 0;
    while ($data = dbarray($result)) {
        echo "<tr>\n<td class='tbl1' ><a href='" . FORUM . "viewthread.php?thread_id=" . $data['thread_id'] . "'>" . $data['thread_subject'] . "</a></td>\n";
        echo "<td class='tbl1' ><a href='" . BASEDIR . "profile.php?lookup=" . $data['user_id1'] . "'>" . $data['user_name1'] . "</a><br />
		" . showdate("forumdate", $data['post_datestamp']) . "</td>\n";
        echo "<td class='tbl1' ><a href='" . BASEDIR . "profile.php?lookup=" . $data['user_id2'] . "'>" . $data['user_name2'] . "</a><br />
		" . showdate("forumdate", $data['thread_lastpost']) . "</td>\n";
        echo "<td class='tbl1' >" . $data['replies'] . "</td>\n";
        echo "<td class='tbl1' ><a href='" . FUSION_SELF . "?delete=" . $data['thread_id'] . "' onclick=\"return confirm('" . $locale['global_060'] . "');\">" . $locale['global_058'] . "</a></td>\n";
        echo "</tr>\n";
        $i++;
    }
    echo "</table>\n";
    closetable();
    echo "<div align='center' style='margin-top:5px;'>" . makePageNav($_GET['rowstart'], 10, $rows, 3, FUSION_SELF . "?") . "</div>\n";
} else {
    echo "<div style='text-align:center;'>" . $locale['global_059'] . "</div>\n";
    closetable();
}

require_once THEMES . "templates/footer.php";
?>
