<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Author: Philipp Horna (globeFrEak)
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
require_once "maincore.php";
require_once THEMES . "templates/header.php";

add_to_title("Die neuesten Kommentare");
opentable("Die neuesten Kommentare");

$output_comm = "";

$rows = dbcount("(comment_id)", DB_COMMENTS, "comment_hidden='0'");
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) {
    $_GET['rowstart'] = 0;
}
if ($rows != 0) {
    $result = dbquery(
            "SELECT c.comment_id, c.comment_item_id, c.comment_type, c.comment_name, c.comment_message, c.comment_datestamp, 
                    u.user_id, u.user_name, u.user_avatar, u.user_status, u.user_lastvisit
		FROM " . DB_COMMENTS . " c
		LEFT JOIN " . DB_USERS . " u ON c.comment_name=u.user_id
		WHERE c.comment_hidden='0'
		ORDER BY c.comment_datestamp DESC LIMIT " . $_GET['rowstart'] . ",20"
    );
    while ($data = dbarray($result)) {
        switch ($data['comment_type']) {
            case "N":
                $access = dbcount("(news_id)", DB_NEWS, "news_id='" . $data['comment_item_id'] . "' AND
									" . groupaccess('news_visibility') . " AND
									(news_start='0'||news_start<=" . time() . ") AND
									(news_end='0'||news_end>=" . time() . ") AND
									news_draft='0'
									");
                if ($access > 0) {
                    $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                    if ($data['user_name']) {
                        $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                        //---ONLINE STATUS START---//
                        $lseen = time() - $data['user_lastvisit'];
                        if ($lseen < 200) {
                            $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                        } else {
                            $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                        }
                        //---ONLINE STATUS END--//
                    } else {
                        $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                    }
                    $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                    $output_comm .= "</div>\n<div class='shoutbox'>\n";
                    $output_comm .= "<div class='shoutboxname clearfix'>";

                    $output_comm .= "</div><div>";
                    $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                    $output_comm .= "</div></div></div>";
                    $output_comm .= "<div class='shoutboxname'>";
                    
                    $output_comm .='<a href="' . BASEDIR . 'news.php?readmore=' . $data["comment_item_id"] . '#c' . $data["comment_id"] . '"><span class="icon-newspaper iconpaddr comment_img"></span></a><br />';
                    $output_comm .= "</div></div>\n";
                }
                continue;
            case "A":
                $access = dbquery("SELECT article_id FROM " . DB_ARTICLES . " a, " . DB_ARTICLE_CATS . " c WHERE
									a.article_id='" . $data['comment_item_id'] . "' AND
									a.article_cat=c.article_cat_id AND
									" . groupaccess('c.article_cat_access') . " AND
									a.article_draft='0'
									");
                if (dbrows($access) > 0) {
                    $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                    if ($data['user_name']) {
                        $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                        //---ONLINE STATUS START---//
                        $lseen = time() - $data['user_lastvisit'];
                        if ($lseen < 200) {
                            $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                        } else {
                            $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                        }
                        //---ONLINE STATUS END--//
                    } else {
                        $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                    }
                    $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                    $output_comm .= "</div>\n<div class='shoutbox'>\n";
                    $output_comm .= "<div class='shoutboxname clearfix'>";

                    $output_comm .= "</div><div>";
                    $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                    $output_comm .= "</div></div></div>";
                    $output_comm .= "<div class='shoutboxname'>";
                    
                    //$output_comm .= THEME_BULLET . ' <a href="' . BASEDIR . 'articles.php?article_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '">' . $comment . '</a><br />';
                    $output_comm .= "</div></div>\n";
                }
                continue;
            case "P":
                $access = dbquery("SELECT photo_id, photo_thumb1, photo_title, p.album_id FROM " . DB_PHOTOS . " p, " . DB_PHOTO_ALBUMS . " a WHERE
									p.photo_id='" . $data['comment_item_id'] . "' AND
									p.album_id=a.album_id AND
									" . groupaccess('a.album_access')
                );
                if (dbrows($access) == 1 ) {
                    while ($access_data = dbarray($access)) {                                               
                        $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                        if ($data['user_name']) {
                            $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                            //---ONLINE STATUS START---//
                            $lseen = time() - $data['user_lastvisit'];
                            if ($lseen < 200) {
                                $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                            } else {
                                $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                            }
                            //---ONLINE STATUS END--//
                        } else {
                            $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                        }
                        $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                        $output_comm .= "</div>\n<div class='shoutbox'>\n";
                        $output_comm .= "<div class='shoutboxname clearfix'>";

                        $output_comm .= "</div><div>";
                        $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                        $output_comm .= "</div></div></div>";
                        $output_comm .= "<div class='shoutboxname'>";                      

                        if ($access_data['photo_thumb1'] && file_exists(PHOTOS . "album_" . $access_data['album_id'] . "/" . $access_data['photo_thumb1'])) {
                            $output_comm .= '<a href="' . BASEDIR . 'cw_photogallery.php?photo_id=' . $data["comment_item_id"] . '#c' . $data["comment_id"] . '"><img class="comment_img" title="' . $access_data['photo_title'] . '" src="' . PHOTOS . "album_" . $access_data['album_id'] . "/" . $access_data['photo_thumb1'] . '" /></a>';
                        } else {
                            $output_comm .= PHOTODIR . $access_data['photo_thumb1'];
                            $output_comm .= '<a href="' . BASEDIR . 'cw_photogallery.php?photo_id=' . $data["comment_item_id"] . '#c' . $data["comment_id"] . '"><span class="icon-image iconpaddr comment_img"></span></a>';
                        }

                        $output_comm .= "</div></div>\n";
                    }
                }
                continue;
            case "C":
                $access = dbcount("(page_id)", DB_CUSTOM_PAGES, "page_id='" . $data['comment_item_id'] . "' AND " . groupaccess('page_access'));
                if ($access > 0) {
                    $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                    if ($data['user_name']) {
                        $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                        //---ONLINE STATUS START---//
                        $lseen = time() - $data['user_lastvisit'];
                        if ($lseen < 200) {
                            $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                        } else {
                            $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                        }
                        //---ONLINE STATUS END--//
                    } else {
                        $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                    }
                    $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                    $output_comm .= "</div>\n<div class='shoutbox'>\n";
                    $output_comm .= "<div class='shoutboxname clearfix'>";

                    $output_comm .= "</div><div>";
                    $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                    $output_comm .= "</div></div></div>";
                    $output_comm .= "<div class='shoutboxname'>";
                    
                    $output .='<a href="' . BASEDIR . 'viewpage.php?page_id=' . $data["comment_item_id"] . '#c' . $data["comment_id"] . '"><span class="icon-file iconpaddr comment_img"></span></a>';
                    $output_comm .= "</div></div>\n";
                }
                continue;
            case "D":
                $access = dbquery("SELECT download_id FROM " . DB_DOWNLOADS . " d, " . DB_DOWNLOAD_CATS . " c WHERE
									d.download_id='" . $data['comment_item_id'] . "' AND
									d.download_cat=c.download_cat_id AND
									" . groupaccess('c.download_cat_access')
                );
                if (dbrows($access) > 0) {
                    $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                    if ($data['user_name']) {
                        $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                        //---ONLINE STATUS START---//
                        $lseen = time() - $data['user_lastvisit'];
                        if ($lseen < 200) {
                            $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                        } else {
                            $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                        }
                        //---ONLINE STATUS END--//
                    } else {
                        $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                    }
                    $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                    $output_comm .= "</div>\n<div class='shoutbox'>\n";
                    $output_comm .= "<div class='shoutboxname clearfix'>";

                    $output_comm .= "</div><div>";
                    $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                    $output_comm .= "</div></div></div>";
                    $output_comm .= "<div class='shoutboxname'>";
                    
                    $output .='<a href="' . BASEDIR . 'downloads.php?download_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"><span class="icon-folder iconpaddr comment_img"></span></a>';
                    
                    $output_comm .= "</div></div>\n";
                }
                continue;
            case "T":
                $output_comm .= "<div class='shout clearfix'><div class='shoutbody clearfix'><div class='shoutboxdate clearfix'>\n";
                if ($data['user_name']) {
                    $output_comm .= "<span class='comment-name'><span class='slink'>" . profile_link($data['user_id'], $data['user_name'], $data['user_status']) . "</span>\n</span>\n";
                    //---ONLINE STATUS START---//
                    $lseen = time() - $data['user_lastvisit'];
                    if ($lseen < 200) {
                        $output_comm .= "<img src='" . BASEDIR . "images/online.gif' alt='" . $data['user_name'] . " ist Online' title='" . $data['user_name'] . " ist Online' /> \n";
                    } else {
                        $output_comm .= "<img src='" . BASEDIR . "images/offline.gif' alt='" . $data['user_name'] . " ist Offline' title='" . $data['user_name'] . " ist Offline' /> \n";
                    }
                    //---ONLINE STATUS END--//
                } else {
                    $output_comm .= "<span class='comment-name'>" . $data['comment_name'] . "</span>\n";
                }
                $output_comm .= "<span class='small'>" . showdate("longdate", $data['comment_datestamp']) . "</span>";
                $output_comm .= "</div>\n<div class='shoutbox'>\n";
                $output_comm .= "<div class='shoutboxname clearfix'>";

                $output_comm .= "</div><div>";
                $output_comm .= nl2br(parseubb(parsesmileys($data['comment_message'])));
                $output_comm .= "</div></div></div>";
                $output_comm .= "<div class='shoutboxname'>";
                
                $output .='<a href="' . BASEDIR . 'infusions/aw_todo/task.php?id=' . $data["comment_item_id"] . '#comm' . $data["comment_id"] . '" class="side latestcomments"><span class="icon-tag iconpaddr comment_img"></span></a>';
                
                $output_comm .= "</div></div>\n";
                continue;
        }
    }
    echo $output_comm;
} else {
    echo "<div style='text-align:center'><br />\n" . $locale['SB_no_msgs'] . "<br /><br />\n</div>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>\n" . makepagenav($_GET['rowstart'], 20, $rows, 3, FUSION_SELF . "?") . "\n</div>\n";

require_once THEMES . "templates/footer.php";
?>
