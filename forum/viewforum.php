<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: viewforum.php
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
require_once "../maincore.php";
require_once THEMES . "templates/header.php";
include LOCALE . LOCALESET . "forum/main.php";

$imageold = "<span class='icon-folder-open mid cwtooltip' title='" . $locale['561'] . "'></span>";
$imagenew = "<span class='icon-folder mid c_orange cwtooltip' title='" . $locale['560'] . "'></span>";
$imagelocked = "<span class='icon-lock mid cwtooltip' title='" . $locale['564'] . "'></span> ";
$imagehot = "<span class='icon-star3 mid cwtooltip' title='" . $locale['563'] . "'></span> ";

if (!isset($lastvisited) || !isnum($lastvisited)) {
    $lastvisited = time();
}

if (!isset($_GET['forum_id']) || !isnum($_GET['forum_id'])) {
    redirect("index.php");
}

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
    $_GET['rowstart'] = 0;
}

$threads_per_page = 20;

add_to_title($locale['global_200'] . $locale['400']);

/////
//subcategories begin
function subcats($forum_id) {
    $imageold = "<span class='icon-folder-open mid cwtooltip' title='Keine neuen Beiträge'></span>";
    $imagenew = "<span class='icon-folder mid c_orange cwtooltip' title='Neue Beiträge'></span>";
    global $settings, $locale, $userdata, $lastvisited;
    $a_result = dbquery("SELECT * FROM " . DB_FORUMS . " f LEFT JOIN " . DB_USERS . " u on f.forum_lastuser=u.user_id WHERE " . groupaccess('f.forum_access') . " AND forum_parent='" . $_GET['forum_id'] . "' ORDER BY forum_order");
    if (dbrows($a_result) != 0) {

        echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_idx_table'>\n<tr>\n";
        echo "<td colspan='2' class='tbl2'>Subforum</td>\n";
        echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>" . $locale['402'] . "</td>\n";
        echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>" . $locale['403'] . "</td>\n";
        echo "<td width='1%' class='tbl2' style='white-space:nowrap'>" . $locale['404'] . "</td>\n";
        echo "</tr>\n";

        while ($a_data = dbarray($a_result)) {
            echo "<tr>\n";
            $moderators = "";
            if ($a_data['forum_moderators']) {
                $mod_groups = explode(".", $a_data['forum_moderators']);
                foreach ($mod_groups as $mod_group) {
                    if ($moderators)
                        $moderators .= ", ";
                    //$moderators .= $mod_group < 101 ? "<a href='" . BASEDIR . "profile.php?group_id=" . $mod_group . "'>" . getgroupname($mod_group) . "</a>" : getgroupname($mod_group);
                    //"^/gruppe-([0-9]+)-(.*)\.html$" => "/profile.php?group_id=$1",
                    $moderators .= $mod_group < 101 ? "<a href='" . BASEDIR . "gruppe-" . $mod_group . "-" . seostring(getgroupname($mod_group)) . ".html'>" . getgroupname($mod_group) . "</a>" : getgroupname($mod_group);
                }
            }
            if ($a_data['forum_lastpost'] > $lastvisited) {
                $forum_match = "\|" . $a_data ['forum_lastpost'] . "\|" . $a_data ['forum_id'];
                if (iMEMBER && preg_match("({$forum_match}\.|{$forum_match}$)", $userdata['user_threads'])) {
                    $fim = $imageold;
                } else {
                    $fim = $imagenew;
                }
            } else {
                $fim = $imageold;
            }

            echo "<td width='1%'>" . $fim . "</td>\n";
            //echo "<td class='tbl1 forum_name'><!--forum_name--><a href='viewforum.php?forum_id=" . $a_data['forum_id'] . "'>" . $a_data['forum_name'] . "</a><br />\n";
            //"^/forum/f-([0-9]+)-(.*).html$" => "/forum/viewforum.php?forum_id=$1",
            echo "<td class='tbl1 forum_name'><!--forum_name--><a href='" . FORUM . "f-" . $a_data['forum_id'] . "-" . seostring($a_data['forum_name']) . ".html'>" . $a_data['forum_name'] . "</a><br />\n";
            if ($a_data['forum_description'] || $moderators) {
                echo "<span class='small'>" . $a_data['forum_description'] . "</span>\n";
                //echo "<span class='small'>" . $a_data['forum_description'] . ($a_data['forum_description'] && $moderators ? "<br />\n" : "");
                //echo ($moderators ? "<strong>" . $locale['411'] . "</strong>" . $moderators . "</span>\n" : "</span>\n") . "\n";
            }
            echo "</td>\n";
            echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>" . $a_data['forum_threadcount'] . "</td>\n";
            echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>" . $a_data['forum_postcount'] . "</td>\n";
            echo "<td width='1%' class='tbl2' style='white-space:nowrap'>";
            if ($a_data['forum_lastpost'] == 0) {
                echo $locale['405'] . "</td>\n</tr>\n";
            } else {
                echo showdate("forumdate", $a_data['forum_lastpost']) . "<br />\n";
                echo "<span class='small'>" . $locale['406'] . profile_link($a_data['forum_lastuser'], $a_data['user_name'], $a_data['user_status']) . "</span></td>\n";
                echo "</tr>\n";
            }
        }
        echo "</table>";
    }
}

//subcategories end
//////


$result = dbquery(
        "SELECT f.*, f2.forum_name AS forum_cat_name FROM " . DB_FORUMS . " f
	LEFT JOIN " . DB_FORUMS . " f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='" . $_GET['forum_id'] . "'"
);
if (dbrows($result)) {
    $fdata = dbarray($result);
    if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) {
        redirect("index.php");
    }
} else {
    redirect("index.php");
}

if ($fdata['forum_post']) {
    $can_post = checkgroup($fdata['forum_post']);
} else {
    $can_post = false;
}

if (iSUPERADMIN) {
    define("iMOD", true);
}

if (!defined("iMOD") && iMEMBER && $fdata['forum_moderators']) {
    $mod_groups = explode(".", $fdata['forum_moderators']);
    foreach ($mod_groups as $mod_group) {
        if (!defined("iMOD") && checkgroup($mod_group)) {
            define("iMOD", true);
        }
    }
}

if (!defined("iMOD")) {
    define("iMOD", false);
}
////
// Subforums begin
if ($fdata['forum_parent'] != 0) {
    $sub_data = dbarray(dbquery("SELECT forum_id, forum_name FROM " . DB_FORUMS . " WHERE forum_id='" . $fdata['forum_parent'] . "'"));
    //$caption = $fdata['forum_cat_name'] . " &raquo; <a href='" . FORUM . "viewforum.php?forum_id=" . $sub_data['forum_id'] . "'>" . $sub_data['forum_name'] . "</a> &raquo; " . $fdata['forum_name'];
    //"^/forum/f-([0-9]+)-(.*).html$" => "/forum/viewforum.php?forum_id=$1",
    $caption = $fdata['forum_cat_name'] . " &raquo; <a href='" . FORUM . "f-" . $sub_data['forum_id'] . "-" . seostring($sub_data['forum_name']) . ".html'>" . $sub_data['forum_name'] . "</a> &raquo; " . $fdata['forum_name'];
} else {
    $caption = $fdata['forum_cat_name'] . " &raquo; " . $fdata['forum_name'];
}
// Subforums end
////
$caption = $fdata['forum_cat_name'] . " &raquo; " . $fdata['forum_name'];
add_to_title($locale['global_201'] . $fdata['forum_name']);

if (isset($_POST['delete_threads']) && iMOD) {
    $thread_ids = "";
    if (isset($_POST['check_mark']) && is_array($_POST['check_mark'])) {
        foreach ($_POST['check_mark'] as $thisnum) {
            if (isnum($thisnum)) {
                $thread_ids .= ($thread_ids ? "," : "") . $thisnum;
            }
        }
    }
    if ($thread_ids) {
        $result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM " . DB_POSTS . " WHERE thread_id IN (" . $thread_ids . ") GROUP BY post_author");
        if (dbrows($result)) {
            while ($pdata = dbarray($result)) {
                $result2 = dbquery("UPDATE " . DB_USERS . " SET user_posts=user_posts-" . $pdata['num_posts'] . " WHERE user_id='" . $pdata['post_author'] . "'");
            }
        }
        $result = dbquery("SELECT attach_name FROM " . DB_FORUM_ATTACHMENTS . " WHERE thread_id IN (" . $thread_ids . ")");
        if (dbrows($result)) {
            while ($data = dbarray($result)) {
                if (file_exists(FORUM . "attachments/" . $data['attach_name'])) {
                    unlink(FORUM . "attachments/" . $data['attach_name']);
                }
            }
        }
        $result = dbquery("DELETE FROM " . DB_POSTS . " WHERE thread_id IN (" . $thread_ids . ") AND forum_id='" . $_GET['forum_id'] . "'");
        $deleted_posts = mysql_affected_rows();
        $result = dbquery("DELETE FROM " . DB_THREADS . " WHERE thread_id IN (" . $thread_ids . ") AND forum_id='" . $_GET['forum_id'] . "'");
        $deleted_threads = mysql_affected_rows();
        $result = dbquery("DELETE FROM " . DB_THREAD_NOTIFY . " WHERE thread_id IN (" . $thread_ids . ")");
        $result = dbquery("DELETE FROM " . DB_FORUM_ATTACHMENTS . " WHERE thread_id IN (" . $thread_ids . ")");
        $result = dbquery("DELETE FROM " . DB_FORUM_POLL_OPTIONS . " WHERE thread_id IN (" . $thread_ids . ")");
        $result = dbquery("DELETE FROM " . DB_FORUM_POLL_VOTERS . " WHERE thread_id IN (" . $thread_ids . ")");
        $result = dbquery("DELETE FROM " . DB_FORUM_POLLS . " WHERE thread_id IN (" . $thread_ids . ")");
        $result = dbquery("SELECT post_datestamp, post_author FROM " . DB_POSTS . " WHERE forum_id='" . $_GET['forum_id'] . "' ORDER BY post_datestamp DESC LIMIT 1");
        if (dbrows($result)) {
            $ldata = dbarray($result);
            $forum_lastpost = "forum_lastpost='" . $ldata['post_datestamp'] . "', forum_lastuser='" . $ldata['post_author'] . "'";
        } else {
            $forum_lastpost = "forum_lastpost='0', forum_lastuser='0'";
        }
        $result = dbquery("UPDATE " . DB_FORUMS . " SET " . $forum_lastpost . ", forum_postcount=forum_postcount-" . $deleted_posts . ", forum_threadcount=forum_threadcount-" . $deleted_threads . " WHERE forum_id='" . $_GET['forum_id'] . "'");
    }
    $rows_left = dbcount("(thread_id)", DB_THREADS, "forum_id='" . $_GET['forum_id'] . "'") - 3;
    if ($rows_left <= $_GET['rowstart'] && $_GET['rowstart'] > 0) {
        $_GET['rowstart'] = ((ceil($rows_left / $threads_per_page) - 1) * $threads_per_page);
    }
    redirect(FUSION_SELF . "?forum_id=" . $_GET['forum_id'] . "&rowstart=" . $_GET['rowstart']);
}

opentable($locale['450']);
echo "<div class='tbl2 forum_breadcrumbs'><a href='" . FORUM . "index.php'>" . $settings['sitename'] . "</a> &raquo; " . $caption . "</div>\n";
echo "<br />";
subcats($_GET['forum_id']); //subcategories
echo"<!--pre_forum-->";

$rows = dbcount("(thread_id)", DB_THREADS, "forum_id='" . $_GET['forum_id'] . "' AND thread_hidden='0'");

$post_info = "";
if ($rows > $threads_per_page || (iMEMBER && $can_post)) {
    $post_info .= "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n";
    if ($rows > $threads_per_page) {
        $post_info .= "<td style='padding:4px 0px 4px 0px'>";
        $post_info .= makepagenav($_GET['rowstart'], $threads_per_page, $rows, 3, FUSION_SELF . "?forum_id=" . $_GET['forum_id'] . "&amp;");
        $post_info .= "</td>\n";
    }
    if (iMEMBER && $can_post) {
        $post_info .= "<td align='right' style='padding:4px 0px 4px 0px'>";
        $post_info .= "<a href='post.php?action=newthread&amp;forum_id=" . $_GET['forum_id'] . "' class='btn'>";
        $post_info .= $locale['566'] . "</a></td>\n";
    }
    $post_info .= "</tr>\n</table>\n";
}

echo $post_info;

if (iMOD) {
    echo "<form name='mod_form' method='post' action='" . FUSION_SELF . "?forum_id=" . $_GET['forum_id'] . "&amp;rowstart=" . $_GET['rowstart'] . "'>\n";
}
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_table'>\n<tr>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>&nbsp;</td>\n";
echo "<td class='tbl2 forum-caption'>" . $locale['451'] . "</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>" . $locale['452'] . "</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap' align='center' >" . $locale['453'] . "</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap' align='center'>" . $locale['454'] . "</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>" . $locale['404'] . "</td>\n</tr>\n";

if ($rows) {
    $result = dbquery(
            "SELECT t.*, tu1.user_name AS user_author, tu1.user_status AS status_author,
		tu2.user_name AS user_lastuser, tu2.user_status AS status_lastuser
		FROM " . DB_THREADS . " t
		LEFT JOIN " . DB_USERS . " tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN " . DB_USERS . " tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id='" . $_GET['forum_id'] . "' AND thread_hidden='0'
		ORDER BY thread_sticky DESC, thread_lastpost DESC LIMIT " . $_GET['rowstart'] . ",$threads_per_page"
    );
    $numrows = dbrows($result);
    while ($tdata = dbarray($result)) {
        $thread_match = $tdata['thread_id'] . "\|" . $tdata['thread_lastpost'] . "\|" . $fdata['forum_id'];
        echo "<tr>\n";
        if ($tdata['thread_locked']) {
            echo "<td align='center' width='25' class='tbl2'>" . $imagelocked . "</td>";
        } else {
            if ($tdata['thread_lastpost'] > $lastvisited) {
                if (iMEMBER && ($tdata['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
                    $folder = $imageold;
                } else {
                    $folder = $imagenew;
                }
            } else {
                $folder = $imageold;
            }
            echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>$folder</td>";
        }
        $reps = ceil($tdata['thread_postcount'] / $threads_per_page);
        //$threadsubject = "<a href='viewthread.php?thread_id=" . $tdata['thread_id'] . "'>" . $tdata['thread_subject'] . "</a>";
        //"^/forum/thread-([0-9]+)-(.*).html$" => "/forum/viewthread.php?thread_id=$1",
        $threadsubject = "<a href='" . FORUM . "thread-" . $tdata['thread_id'] . "-" . seostring($tdata['thread_subject']) . ".html'>" . $tdata['thread_subject'] . "</a>";
        if ($reps > 1) {
            $ctr = 0;
            $ctr2 = 1;
            $pages = "";
            $middle = false;
            while ($ctr2 <= $reps) {
                if ($reps < 5 || ($reps > 4 && ($ctr2 == 1 || $ctr2 > ($reps - 3)))) {
                    //$pnum = "<a href='viewthread.php?thread_id=" . $tdata['thread_id'] . "&amp;rowstart=$ctr'>$ctr2</a> ";
                    //"^/forum/thread-([0-9]+)-([0-9]+)-(.*).html$" => "/forum/viewthread.php?thread_id=$1&rowstart=$2",
                    $pnum = "<a href='" . FORUM . "thread-" . $tdata['thread_id'] . "-" . $ctr . "-" . seostring($tdata['thread_subject']) . "'>$ctr2</a> ";
                } else {
                    if ($middle == false) {
                        $middle = true;
                        $pnum = "... ";
                    } else {
                        $pnum = "";
                    }
                }
                $pages .= $pnum;
                $ctr = $ctr + $threads_per_page;
                $ctr2++;
            }
            $threadsubject .= "<br />(" . $locale['455'] . trim($pages) . ")";
        }
        echo "<td width='100%' class='tbl1'>";
        if (iMOD) {
            echo "<input type='checkbox' name='check_mark[]' value='" . $tdata['thread_id'] . "' />\n";
        }
        if ($tdata['thread_sticky'] == 1) {
            echo $imagehot;
        }
        echo $threadsubject . "</td>\n";
        echo "<td width='1%' class='tbl2' style='white-space:nowrap'>" . profile_link($tdata['thread_author'], $tdata['user_author'], $tdata['status_author']) . "</td>\n";
        echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>" . $tdata['thread_views'] . "</td>\n";
        echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>" . ($tdata['thread_postcount'] - 1) . "</td>\n";
        echo "<td width='1%' class='tbl1' style='white-space:nowrap'>" . showdate("forumdate", $tdata['thread_lastpost']) . "<br />\n";
        echo "<span class='small'>" . $locale['406'] . profile_link($tdata['thread_lastuser'], $tdata['user_lastuser'], $tdata['status_lastuser']) . "</span></td>\n";
        echo "</tr>\n";
    }
    echo "</table><!--sub_forum_table-->\n";
} else {
    if (!$rows) {
        echo "<tr>\n<td colspan='6' class='tbl1' style='text-align:center'>" . $locale['456'] . "</td>\n</tr>\n</table><!--sub_forum_table-->\n";
    } else {
        echo "</table><!--sub_forum_table-->\n";
    }
}

if (iMOD) {
    if ($rows) {
        echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n<td style='padding-top:5px'>";
        echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',1);return false;\">" . $locale['460'] . "</a> ::\n";
        echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',0);return false;\">" . $locale['461'] . "</a></td>\n";
        echo "<td align='right' style='padding-top:5px'><input type='submit' name='delete_threads' value='" . $locale['462'] . "' class='button' onclick=\"return confirm('" . $locale['463'] . "');\" /></td>\n";
        echo "</tr>\n</table>\n";
    }
    echo "</form>\n";
    if ($rows) {
        echo "<script type='text/javascript'>\n";
        echo "/* <![CDATA[ */\n";
        echo "function setChecked(frmName,chkName,val) {\n";
        echo "dml=document.forms[frmName];\n" . "len=dml.elements.length;\n" . "for(i=0;i < len;i++) {\n";
        echo "if(dml.elements[i].name == chkName) {\n" . "dml.elements[i].checked = val;\n}\n}\n}\n";
        echo "/* ]]>*/\n";
        echo "</script>\n";
    }
}

echo $post_info;

$forum_list = "";
$current_cat = "";
$result = dbquery(
        "SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM " . DB_FORUMS . " f
	INNER JOIN " . DB_FORUMS . " f2 ON f.forum_cat=f2.forum_id
	WHERE " . groupaccess('f.forum_access') . " AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
////
//subcategories
while ($data2 = dbarray($result)) {
    if ($data2['forum_cat_name'] != $current_cat) {
        if ($current_cat != "") {
            $forum_list .= "</optgroup>\n";
        }
        $current_cat = $data2['forum_cat_name'];
        $forum_list .= "<optgroup label='" . $data2['forum_cat_name'] . "'>\n";
    }
    $sel = ($data2['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");
    $forum_list .= "<option value='" . $data2['forum_id'] . "'$sel>" . $data2['forum_name'] . "</option>\n";
    $forum_list .= jump_to_forum($data2['forum_id']);
}
//subcategories
////
$forum_list .= "</optgroup>\n";
echo "<div style='padding-top:5px'>\n" . $locale['540'] . "<br />\n";
echo "<select name='jump_id' class='textbox' onchange=\"jumpforum(this.options[this.selectedIndex].value);\">";
echo $forum_list . "</select>\n</div>\n";

echo "<div><hr />\n";
echo $imagenew . " - " . $locale['470'] . "<br />\n";
echo $imageold . " - " . $locale['472'] . "<br />\n";
echo $imagelocked . " - " . $locale['473'] . "<br />\n";
echo $imagehot . " - " . $locale['474'] . "\n";
echo "</div><!--sub_forum-->\n";
closetable();

echo "<script type='text/javascript'>\n" . "function jumpforum(forumid) {\n";
echo "document.location.href='" . FORUM . "viewforum.php?forum_id='+forumid;\n}\n";
echo "</script>\n";

list($threadcount, $postcount) = dbarraynum(dbquery("SELECT COUNT(thread_id), SUM(thread_postcount) FROM " . DB_THREADS . " WHERE forum_id='" . $_GET['forum_id'] . "' AND thread_hidden='0'"));
if (isnum($threadcount) && isnum($postcount)) {
    dbquery("UPDATE " . DB_FORUMS . " SET forum_postcount='$postcount', forum_threadcount='$threadcount' WHERE forum_id='" . $_GET['forum_id'] . "'");
}

////
//subcategories begin
function jump_to_forum($forum_id) {
    global $fdata;
    $jump_list = "";
    $sel = "";
    $result = dbquery("SELECT f.forum_id, f.forum_parent, f2.forum_name AS forum_cat_name FROM " . DB_FORUMS . " f
	LEFT JOIN " . DB_FORUMS . " f2 ON f2.forum_id=f.forum_id
	WHERE " . groupaccess('f.forum_access') . " AND f2.forum_parent='$forum_id'");
    while ($data = dbarray($result)) {
        $sel = ($data['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");
        $jump_list .= "<option value='" . $data['forum_id'] . "'$sel>&nbsp;&nbsp;-" . $data['forum_cat_name'] . "</option>\n";
    }
    return $jump_list;
}

//subcategories end
////
require_once THEMES . "templates/footer.php";
?>