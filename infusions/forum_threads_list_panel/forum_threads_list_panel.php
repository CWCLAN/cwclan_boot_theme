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
  +---------------------------------------------------- */

if (!defined("IN_FUSION")) {
    die("Access Denied");
}

if (file_exists(INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "forum_threads_list_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "forum_threads_list_panel/locale/English.php";
}

require(LOCALE . LOCALESET . "forum/main.php");

if (!isset($lastvisited) || !isnum($lastvisited))
    $lastvisited = time();

$min = 5;    // minimum visible posts in first level.
$max = 15;    // maximum number of posts in second level (hidden).
$maximal = 10;  // maximum numbers of posts on tabs

//echo "<script type='text/javascript' language='JavaScript' src='" . INFUSIONS . "forum_threads_list_panel/ft_boxover.js'></script>\n";

$imageold = get_image("folder", $locale['561'], "", $locale['561']);
$imagenew = get_image("foldernew", $locale['560'], "", $locale['560']);
//$imagelocked = get_image("folderlock",$locale['564'],"",$locale['564']);
//$imagehot = get_image("folderhot");//THEME."forum/folderhot.gif"; //DOESN'T EXIST ANYMORE

/* * *****************************************************************
 * Start use with the BlueIce Theme for cellpic image
 * ****************************************************************** */

if (isset($userdata['user_theme']) && $userdata['user_theme'] != "Default" && file_exists(THEMES . $userdata['user_theme'] . "/theme.php")) {
    $theme = $userdata['user_theme'];
} else {
    $theme = $settings['theme'];
}
$class = $theme == "BlueIce" ? "td" : "tbl2";

/* * *****************************************************************
 * End use with the BlueIce Theme for cellpic image
 * ****************************************************************** */



$result = dbquery(
                "SELECT
    tf.forum_name,tf.forum_id,

    tt.thread_id,tt.thread_locked,tt.thread_subject,tt.thread_author,tt.thread_views,
    tt.thread_lastpost,tt.thread_lastuser, tt.thread_postcount, tt.thread_lastpostid as last_id,
    if(tt.thread_lastpost>$lastvisited,1,0) as new_post,
    tu.user_id, tu.user_name as user_name, tu.user_tf2icon,
    tau.user_name as author, tau.user_tf2icon AS author_tf2icon,

    tp.post_message, tp.post_smileys

	FROM " . DB_THREADS . " tt
    INNER JOIN " . DB_FORUMS . " tf USING(forum_id)
    INNER JOIN " . DB_POSTS . " tp USING(thread_id)
    INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser=tu.user_id
    INNER JOIN " . DB_USERS . " tau ON tt.thread_author=tau.user_id
    WHERE " . groupaccess('forum_access') . "
    AND tf.forum_id NOT LIKE 32
    AND tt.thread_lastpostid = tp.post_id
    ORDER BY tt.thread_lastpost DESC LIMIT " . ($min + $max)
);

opentable($locale['ftl100'], TRUE, "on");
/////////////
// jQuery Tabs
add_to_head("<script type='text/javascript' src='" . BASEDIR . "includes/jquery.tools.min.js'></script>");

echo "<div class='wrap_forumpanel'>\n";
echo "<!-- the tabs -->\n";
echo "<ul class='tabs' style='height:25px;'>\n";
// 1.Tab Link
echo "<li ><b><a href='#'>&nbsp;<img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_displaymore.png' height='10' width='10' alt='' />&nbsp;neueste Beitr&auml;ge&nbsp;</a></b></li>\n";

// 2.Tab Link
if (iUSER){
echo "<li ><b><a href='#'>&nbsp;<img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_displaymore.png' height='10' width='10' alt='' />&nbsp;gepinnte Threads&nbsp;</a></b></li>\n";
}
// 3.Tab Link
if (iUSER){
  $result_new = dbquery(
                "SELECT
    tf.forum_id,
    tt.thread_id,
    tt.thread_lastpost,
    if(tt.thread_lastpost>$lastvisited,1,0) as new_post

    FROM " . DB_THREADS . " tt
    INNER JOIN " . DB_FORUMS . " tf USING(forum_id)
    INNER JOIN " . DB_POSTS . " tp USING(thread_id)
    INNER JOIN " . DB_USERS . " tu ON tt.thread_lastuser=tu.user_id
    INNER JOIN " . DB_USERS . " tau ON tt.thread_author=tau.user_id
    WHERE " . groupaccess('forum_access') . "
    AND tf.forum_id = 32    
    AND tt.thread_lastpostid = tp.post_id
    AND if(tt.thread_lastpost>$lastvisited,1,0)
    ORDER BY tt.thread_lastpost ASC LIMIT 5"
  );

  if (dbrows($result_new) != 0) {
    while ($data_new = dbarray($result_new)) {
        if ($data_new['new_post']) {
            $thread_match = $data_new['thread_id'] . "|" . $data_new['thread_lastpost'] . "|" . $data_new['forum_id'];
            if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                $folder_image = "icon_displaymore.png";
            } else {
                $folder_image = "neu.gif";
            }
        } else {
            $folder_image = "icon_displaymore.png";
        }
    }
    echo "<li ><b><a href='#'>&nbsp;<img src='" . INFUSIONS . "forum_threads_list_panel/images/" . $folder_image . "' height='10' width='10' alt='' />&nbsp;TF2&nbsp;Trading&nbsp;</a></b></li>\n";
  } else {
    echo "<li ><b><a href='#'>&nbsp;<img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_displaymore.png' height='10' width='10' alt='' />&nbsp;TF2&nbsp;Trading&nbsp;</a></b></li>\n";
  }
}
echo "</ul>\n";

// 1.Tab
echo "<!-- tab 'panes' START-->\n";
echo "<div class='pane'>\n";
///// ORIGINAL FORUM THREAD LIST PANEL START

    $i = 0;
    echo "<table style='width:100%; empty-cells:hide; border-spacing:1px;' class='tbl-border2 forum_table'>\n
<tr>\n
<td align='center' class='$class' height='24'>&nbsp;</td>\n
<td align='center' class='$class'><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
<td align='center' class='$class'><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
<td align='center' class='$class'><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
<td align='center' class='$class'><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
<td align='center' class='$class'><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
</tr>\n";

    while ($data = dbarray($result)) {
        if ($i == $min) {
            echo "</table><br />\n
<div align='left'>\n
<img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_displaymore.png' height='10' width='10' alt='' />&nbsp;<a href=\"javascript:void(0)\" onclick=\"toggle_smt();\"><span id='show_more_threads_text'><b>" . $locale['ftl113'] . "</b></span></a>
</div>\n
<div id='show_more_threads' style='display: none;'><br />\n
<table style='width:100%; empty-cells:hide; border-spacing:1px;' class='tbl-border2 forum_table'>\n
<tr>\n
<td align='center' class='tbl1' height='24'>&nbsp;</td>\n
<td align='center' class='tbl1'><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
<td align='center' class='tbl1'><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
<td align='center' class='tbl1'><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
<td align='center' class='tbl1'><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
<td align='center' class='tbl1'><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
</tr>\n";
        }
        if ($i % 2 == 0) {
            $row_color = "tbl1";
        } else {
            $row_color = "tbl2";
        }
        //$post_message = preg_replace("\[spoiler](.*)[spolier]]", " ", $post_message);
        $post_message = trimlink($data['post_message'], 250);
        if ($data['post_smileys']
            )$post_message = parsesmileys($post_message);
        $post_message = phpentities(nl2br(parseubb($post_message)));

        //.93|1234180289|8   - thread_id | time | forum_id
        if ($data['new_post']) {
            $thread_match = $data['thread_id'] . "|" . $data['thread_lastpost'] . "|" . $data['forum_id'];
            if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                $folder_image = $imageold;
            }else
                $folder_image = $imagenew;
        }else
            $folder_image = $imageold;
        /* MOD Post Bewertung fangree.co.uk */
        /*$bewertung = "";
        $result_bewertung = dbquery("SELECT r.*, t.*, count(t.type_name) as total
										 FROM fusion_loa_rates_rate r
										 LEFT JOIN fusion_loa_rates_rate_type t on r.rate_type=t.type_id , fusion_posts AS p
										 WHERE p.thread_id=" . $data['thread_id'] . "
										 AND p.post_id=r.rate_post
										 AND r.rate_post=p.post_id
										 GROUP by r.rate_type");
        if (dbrows($result_bewertung)) {
            while ($data_bewertung = dbarray($result_bewertung)) {
                $bewertung .= "&nbsp;<img src='" . INFUSIONS . "loa_rates/images/forum_icons/" . $data_bewertung['type_icon'] . "' height='16' width='16'/> x " . $data_bewertung['total'];
            }
        } */
        /* MOD */

        echo "<tr>
<td align='center' class='$row_color' style='padding-left:2px; padding-right:2px;'>$folder_image</td>
<td class='$row_color'>
<span class='small'><strong>" . $data['forum_name'] . "</strong> <a href='" . FORUM . "t_" . seostring($data['thread_id']) . "_" . seostring($data['thread_subject']) . ".html' title='" . $locale['ftl130'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_thread1.png' height='16' width='16' align='right' alt='" . $locale['ftl130'] . "' border='0' /></a></span><br />
<span class='small forum_thread_title'>
<a href='" . FORUM . "tp_" . seostring($data['thread_subject']) . "_" . seostring($data['thread_id']) . "_" . seostring($data['last_id']) . ".html#post_" . $data['last_id'] . "' title=\"header=[" . str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 30))) . "] body=[" . str_replace("]", "]]", str_replace("[", "[[", $post_message)) . "] delay=[0] fade=[on]\">" . trimlink($data['thread_subject'], 30) . "</a></span>
</td>
<td align='center' class='$row_color'><span class='small'><a href='" . BASEDIR . "user_" . $data['thread_author'] . "_" . seostring($data['author']) . ".html'>" . $data['author'] . "</a></span></td>
<td align='center' class='$row_color'><span class='small'>" . $data['thread_views'] . "</span></td>
<td align='center' class='$row_color'><span class='small'>" . ($data['thread_postcount'] - 1) . "</span></td>
<td align='center' class='$row_color'>
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
if (iUSER){
  echo"<!-- tab 'panes' START -->\n";
  echo"<div class='pane'>\n";
  $result = dbquery(
                "SELECT
    tf.forum_name,tf.forum_id,

    tt.thread_id,tt.thread_locked,tt.thread_subject,tt.thread_author,tt.thread_views,
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

    echo "<table style='width:100%; empty-cells:hide; border-spacing:1px;' class='tbl-border2 forum_table'>\n
  <tr>\n
  <td align='center' class='$class' height='24'>&nbsp;</td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
  </tr>\n";

    while ($data = dbarray($result)) {

        if ($i % 2 == 0) {
            $row_color = "tbl1";
        } else {
            $row_color = "tbl2";
        }

        $post_message = trimlink($data['post_message'], 250);
        if ($data['post_smileys']
            )$post_message = parsesmileys($post_message);
        $post_message = phpentities(nl2br(parseubb($post_message)));

        //.93|1234180289|8   - thread_id | time | forum_id
        if ($data['new_post']) {
            $thread_match = $data['thread_id'] . "|" . $data['thread_lastpost'] . "|" . $data['forum_id'];
            if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                $folder_image = $imageold;
            }else
                $folder_image = $imagenew;
        }else
            $folder_image = $imageold;  
        echo "<tr>
  <td align='center' class='$row_color' style='padding-left:2px; padding-right:2px;'>$folder_image</td>
  <td class='$row_color'>
  <span class='small'><strong>" . $data['forum_name'] . "</strong> <a href='" . FORUM . "t_" . seostring($data['thread_id']) . "_" . seostring($data['thread_subject']) . ".html' title='" . $locale['ftl130'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_thread1.png' height='16' width='16' align='right' alt='" . $locale['ftl130'] . "' border='0' /></a></span><br />
  <span class='small forum_thread_title'><a href='" . FORUM . "tp_" . seostring($data['thread_subject']) . "_" . seostring($data['thread_id']) . "_" . seostring($data['last_id']) . ".html#post_" . $data['last_id'] . "' title=\"header=[" . str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 30))) . "] body=[" . str_replace("]", "]]", str_replace("[", "[[", $post_message)) . "] delay=[0] fade=[on]\">" . trimlink($data['thread_subject'], 30) . "</a></span>
  </td>
  <td align='center' class='$row_color'><span class='small'><a href='" . BASEDIR . "user_" . $data['thread_author'] . "_" . seostring($data['author']) . ".html'>" . $data['author'] . "</a></span></td>
  <td align='center' class='$row_color'><span class='small'>" . $data['thread_views'] . "</span></td>
  <td align='center' class='$row_color'><span class='small'>" . ($data['thread_postcount'] - 1) . "</span></td>
  <td align='center' class='$row_color'>
  <span class='small'>" . showdate("forumdate", $data['thread_lastpost']) . "</span><br />
  <span class='small'><a href='" . BASEDIR . "user_" . $data['thread_lastuser'] . "_" . seostring($data['user_name']) . ".html'>" . $data['user_name']."</a>&nbsp;</span>
  </td>
  </tr>\n";
        $i++;
    }
    echo "</table><br />\n";
  
  echo"</div>\n";
  echo"<!-- tab 'panes' END-->\n";
}
// 3.Tab
if (iUSER){
echo"<!-- tab 'panes' START -->\n";
echo"<div class='pane'>\n";
$result = dbquery(
                "SELECT
    tf.forum_name,tf.forum_id,

    tt.thread_id,tt.thread_locked,tt.thread_subject,tt.thread_author,tt.thread_views,
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
    AND tf.forum_id = 32
    AND tt.thread_lastpostid = tp.post_id
    ORDER BY tt.thread_lastpost DESC LIMIT 10"
);

  $i = 0;

    echo "<table style='width:100%; empty-cells:hide; border-spacing:1px;' class='tbl-border2 forum_table'>\n
  <tr>\n
  <td align='center' class='$class' height='24'>&nbsp;</td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl120'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl124'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl123'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl107'] . "</b></span></td>\n
  <td align='center' class='$class'><span class='small'><b>" . $locale['ftl108'] . "</b></span></td>\n
  </tr>\n";

    while ($data = dbarray($result)) {

        if ($i % 2 == 0) {
            $row_color = "tbl1";
        } else {
            $row_color = "tbl2";
        }

        $post_message = trimlink($data['post_message'], 250);
        if ($data['post_smileys']
            )$post_message = parsesmileys($post_message);
        $post_message = phpentities(nl2br(parseubb($post_message)));

        //.93|1234180289|8   - thread_id | time | forum_id
        if ($data['new_post']) {
            $thread_match = $data['thread_id'] . "|" . $data['thread_lastpost'] . "|" . $data['forum_id'];
            if (iMEMBER && strpos($userdata['user_threads'], $thread_match) !== FALSE) {//preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                $folder_image = $imageold;
            }else
                $folder_image = $imagenew;
        }else
            $folder_image = $imageold; 
        echo "<tr>
  <td align='center' class='$row_color' style='padding-left:2px; padding-right:2px;'>$folder_image</td>
  <td class='$row_color'>
  <span class='small'><strong>" . $data['forum_name'] . "</strong> <a href='" . FORUM . "t_" . seostring($data['thread_id']) . "_" . seostring($data['thread_subject']) . ".html' title='" . $locale['ftl130'] . "'><img src='" . INFUSIONS . "forum_threads_list_panel/images/icon_thread1.png' height='16' width='16' align='right' alt='" . $locale['ftl130'] . "' border='0' /></a></span><br />
  <span class='small forum_thread_title'><a href='" . FORUM . "tp_" . seostring($data['thread_subject']) . "_" . seostring($data['thread_id']) . "_" . seostring($data['last_id']) . ".html#post_" . $data['last_id'] . "' title=\"header=[" . str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 30))) . "] body=[" . str_replace("]", "]]", str_replace("[", "[[", $post_message)) . "] delay=[0] fade=[on]\">" . trimlink($data['thread_subject'], 30) . "</a></span>
  </td>
  <td align='center' class='$row_color'><span class='small'><a href='" . BASEDIR . "user_" . $data['thread_author'] . "_" . seostring($data['author']) . ".html'>" . $data['author'] . "</a></span></td>
  <td align='center' class='$row_color'><span class='small'>" . $data['thread_views'] . "</span></td>
  <td align='center' class='$row_color'><span class='small'>" . ($data['thread_postcount'] - 1) . "</span></td>
  <td align='center' class='$row_color'>
  <span class='small'>" . showdate("forumdate", $data['thread_lastpost']) . "</span><br />
  <span class='small'><a href='" . BASEDIR . "user_" . $data['thread_lastuser'] . "_" . seostring($data['user_name']) . ".html'>" . $data['user_name'] . "</a>&nbsp;</span>
  </td>
  </tr>\n";
        $i++;
    }
    echo "</table><br />\n";
  
  echo "</div>\n";
  echo "<!-- tab 'panes' END-->\n";
}
//3.Tab END

echo "</div>\n";
echo "<!-- tab 'wrap' END-->\n";
// jQuery Tabs ENDE
/////////////

if (iMEMBER) {
    echo "<hr /><p style='text-align:center; vertical-align:middle; margin-top: 6px;'>
<img src='" . BASEDIR . "infusions/forum_threads_list_panel/images/icon_threads.png' height='16' width='16' alt='' border='0' />&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_threads.php'>" . $locale['ftl110'] . "</a></span>&nbsp;&nbsp;
<img src='" . BASEDIR . "infusions/forum_threads_list_panel/images/icon_threads.png' alt='' height='16' width='16' border='0' />&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_posts.php'>" . $locale['ftl111'] . "</a></span>&nbsp;&nbsp;
<img src='" . BASEDIR . "infusions/forum_threads_list_panel/images/icon_threads.png' alt='' height='16' width='16' border='0' />&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/newposts.php'>" . $locale['ftl112'] . "</a></span>&nbsp;&nbsp;
<img src='" . BASEDIR . "infusions/forum_threads_list_panel/images/icon_threads.png' alt='' height='16' width='16' border='0' />&nbsp;<span class='small'><a href='" . INFUSIONS . "forum_threads_list_panel/my_tracked_threads.php'>" . $locale['ftl115'] . "</a></span></p>";
}

closetable();
//
//jQuery tabs LOAD
echo '<script>
$(function() {
$("ul.tabs").tabs("> .pane");
});
</script>';
?>