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
  | Optimisation & Additional Love: moozaad
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
if (!defined("IN_FUSION")) {
    die("Access Denied");
}
if (file_exists(INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "forum_threads_list_panel/locale/English.php";
}
require(LOCALE . LOCALESET . "forum/main.php");

if (!isset($lastvisited) || !isnum($lastvisited))$lastvisited = time();

$min = 5;    // minimum visible posts in first level.
$max = 15;    // maximum number of posts in second level (hidden).

$imageold = "<span class='icon-folder-open mid cwtooltip' title='" . $locale['561'] . "'></span>";
$imagenew = "<span class='icon-folder mid c_orange cwtooltip' title='" . $locale['560'] . "'></span>";
$imagelocked = "<span class='icon-lock mid cwtooltip' title='" . $locale['564'] . "'></span> ";
$imagehot = "<span class='icon-star3 mid cwtooltip' title='" . $locale['563'] . "'></span> ";

$result = dbquery(
        "SELECT
    tf.forum_name,tf.forum_id,
    tt.thread_id,tt.thread_locked,tt.thread_sticky,tt.thread_subject,tt.thread_author,tt.thread_views,
    tt.thread_lastpost,tt.thread_lastuser, tt.thread_postcount, tt.thread_lastpostid as last_id,
    if(tt.thread_lastpost>$lastvisited,1,0) as new_post,
    tu.user_id, tu.user_name as user_name,
    tau.user_name as author,
    tp.post_message, tp.post_smileys
	FROM " . DB_THREADS . " tt
    INNER JOIN " . DB_FORUMS . " tf USING(forum_id)
    INNER JOIN " . DB_POSTS . " tp USING(thread_id)
    INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser=tu.user_id
    INNER JOIN " . DB_USERS . " tau ON tt.thread_author=tau.user_id
    WHERE " . groupaccess('forum_access') . "
    AND tt.thread_lastpostid = tp.post_id
    ORDER BY tt.thread_lastpost DESC LIMIT " . ($min + $max)
);

opentable($locale['ftl100']);
/////////////
// jQuery Tabs
add_to_head("<script type='text/javascript' src='" . BASEDIR . "includes/jquery.tools.min.js'></script>");

echo "<div class='wrap_forumpanel'>\n";
echo "<!-- the tabs -->\n";
echo "<ul class='tabs' style='height:25px;'>\n";
// 1.Tab Link
echo "<li><b><a href='#'><span class='icon-tag small'></span>&nbsp;neueste</a></b></li>\n";

// 2.Tab Link
if (iUSER) {
    echo "<li><b><a href='#'><span class='icon-pushpin small'></span>&nbsp;gepinnte</a></b></li>\n";
}
echo "</ul>\n";

// 1.Tab
echo "<!-- tab 'panes' START-->\n";
echo "<div class='pane'>\n";
///// ORIGINAL FORUM THREAD LIST PANEL START

$i = 0;
echo "<table style='width:100%; empty-cells:hide;' class='tbl-border2 forum_table'>\n
<tr>\n
<td>&nbsp;</td>\n
<td><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
</tr>\n";

while ($data = dbarray($result)) {
    if ($i == $min) {
        echo "</table><br />\n
<div>\n
<span class='icon-menu'></span>&nbsp;<a href=\"javascript:void(0)\" onclick=\"toggle_smt();\"><span id='show_more_threads_text'><b>" . $locale['ftl113'] . "</b></span></a>
</div>\n
<div id='show_more_threads' style='display: none;'><br />\n
<table style='width:100%; empty-cells:hide;' class='tbl-border2 forum_table'>\n
<tr>\n
<td>&nbsp;</td>\n
<td><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
<td><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
</tr>\n";
    }
    $locked = ($data['thread_locked'] ? $imagelocked : "");
    $sticky = ($data['thread_sticky'] ? $imagehot : "");
    if ($data['new_post']) {
        $thread_match = $data['thread_id'] . "|" . $data['thread_lastpost'] . "|" . $data['forum_id'];
        if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
            $folder_image = $imageold;
        }
        else
            $folder_image = $imagenew;
    } else {
        $folder_image = $imageold;
    }


    echo "<tr>
<td>$sticky$locked$folder_image</td>
<td>
<span class='small'><strong>" . $data['forum_name'] . "</strong> <a href='" . FORUM . "t_" . seostring($data['thread_id']) . "_" . seostring($data['thread_subject']) . ".html' title='" . $locale['ftl130'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_thread1.png' height='16' width='16' alt='" . $locale['ftl130'] . "'/></a></span><br />
<span class='small forum_thread_title'>
<a href='" . FORUM . "tp_" . seostring($data['thread_subject']) . "_" . seostring($data['thread_id']) . "_" . seostring($data['last_id']) . ".html#post_" . $data['last_id'] . "'>" . trimlink($data['thread_subject'], 30) . "</a></span>
</td>
<td><span class='small'><a href='" . BASEDIR . "user_" . $data['thread_author'] . "_" . seostring($data['author']) . ".html'>" . $data['author'] . "</a></span></td>
<td><span class='small'>" . $data['thread_views'] . "</span></td>
<td><span class='small'>" . ($data['thread_postcount'] - 1) . "</span></td>
<td>
<span class='small'>" . showdate("forumdate", $data['thread_lastpost']) . "</span><br />
<span class='small'><a href='" . BASEDIR . "user_" . $data['thread_lastuser'] . "_" . seostring($data['user_name']) . ".html'>" . $data['user_name'] . "</a>&nbsp;</span>
</td>
</tr>\n";
$i++;
}
if ($i > $min) {
    echo "</table><br /><script type='text/javascript'>
<!--
function toggle_smt() {
var smt = document.getElementById('show_more_threads');
var smttxt = document.getElementById('show_more_threads_text');
if (smt.style.display == 'none') {
smt.style.display = 'block';
smttxt.innerHTML = '" . $locale['ftl114'] . "';
} else {
smt.style.display = 'none';
smttxt.innerHTML = '" . $locale['ftl113'] . "';
}
}
//-->
</script></div>\n";
} else {
    echo "</table><br />\n";
}
///// ORIGINAL FORUM THREAD LIST PANEL ENDE
echo"</div>\n";
echo"<!-- tab 'panes' END -->\n";


// 2.Tab
if (iUSER) {
    echo"<!-- tab 'panes' START -->\n";
    echo"<div class='pane'>\n";
    $result = dbquery(
            "SELECT
    tf.forum_name,tf.forum_id,
    tt.thread_id,tt.thread_locked,tt.thread_sticky,tt.thread_subject,tt.thread_author,tt.thread_views,
    tt.thread_lastpost,tt.thread_lastuser, tt.thread_postcount, tt.thread_lastpostid as last_id,
    if(tt.thread_lastpost>$lastvisited,1,0) as new_post,
    tu.user_id, tu.user_name as user_name,
    tau.user_name as author,

    tp.post_message, tp.post_smileys

	FROM " . DB_THREADS . " tt
    INNER JOIN " . DB_FORUMS . " tf USING(forum_id)
    INNER JOIN " . DB_POSTS . " tp USING(thread_id)
    INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser=tu.user_id
    INNER JOIN " . DB_USERS . " tau ON tt.thread_author=tau.user_id
    WHERE " . groupaccess('forum_access') . "
    AND tt.thread_sticky = 1
    AND tt.thread_lastpostid = tp.post_id
    ORDER BY tt.thread_lastpost DESC"
    );

    $i = 0;

    echo "<table style='width:100%; empty-cells:hide;' class='tbl-border2 forum_table'>\n
  <tr>\n
  <td>&nbsp;</td>\n
  <td><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
  <td><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
  <td><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
  <td><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
  <td><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
  </tr>\n";

    while ($data = dbarray($result)) {
        $locked = ($data['thread_locked'] ? $imagelocked : "");
        $sticky = ($data['thread_sticky'] ? $imagehot : "");
        if ($data['new_post']) {
            $thread_match = $data['thread_id'] . "|" . $data['thread_lastpost'] . "|" . $data['forum_id'];
            if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                $folder_image = $imageold;
            }
            else
                $folder_image = $imagenew;
        }
        else {
            $folder_image = $imageold;
        }
        echo "<tr>
  <td>$sticky$locked$folder_image</td>
  <td>
  <span class='small'><strong>" . $data['forum_name'] . "</strong> <a href='" . FORUM . "t_" . seostring($data['thread_id']) . "_" . seostring($data['thread_subject']) . ".html' title='" . $locale['ftl130'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_thread1.png' height='16' width='16' alt='" . $locale['ftl130'] . "'/></a></span><br />
  <span class='small forum_thread_title'><a href='" . FORUM . "tp_" . seostring($data['thread_subject']) . "_" . seostring($data['thread_id']) . "_" . seostring($data['last_id']) . ".html#post_" . $data['last_id'] . "'>" . trimlink($data['thread_subject'], 30) . "</a></span>
  </td>
  <td><span class='small'><a href='" . BASEDIR . "user_" . $data['thread_author'] . "_" . seostring($data['author']) . ".html'>" . $data['author'] . "</a></span></td>
  <td><span class='small'>" . $data['thread_views'] . "</span></td>
  <td><span class='small'>" . ($data['thread_postcount'] - 1) . "</span></td>
  <td>
  <span class='small'>" . showdate("forumdate", $data['thread_lastpost']) . "</span><br />
  <span class='small'><a href='" . BASEDIR . "user_" . $data['thread_lastuser'] . "_" . seostring($data['user_name']) . ".html'>" . $data['user_name'] . "</a>&nbsp;</span>
  </td>
  </tr>\n";
        $i++;
    }
    echo "</table><br />\n";

    echo"</div>\n";
    echo"<!-- tab 'panes' END-->\n";
}
echo "</div>\n";
echo "<!-- tab 'wrap' END-->\n";
// jQuery Tabs ENDE
/////////////

if (iMEMBER) {
    echo "<hr><p style='text-align:center; vertical-align:middle; margin-top: 6px;'>
<span class='icon-file'></span>&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_threads.php'>" . $locale['ftl110'] . "</a></span>&nbsp;&nbsp;
<span class='icon-file'></span>&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_posts.php'>" . $locale['ftl111'] . "</a></span>&nbsp;&nbsp;
<span class='icon-file'></span>&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/newposts.php'>" . $locale['ftl112'] . "</a></span>&nbsp;&nbsp;
<span class='icon-file'></span>&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_tracked_threads.php'>" . $locale['ftl115'] . "</a></span></p>";
}
//
//jQuery tabs LOAD
echo '<script>
$(function() {
$("ul.tabs").tabs("> .pane");
});
</script>';
closetable();
?>