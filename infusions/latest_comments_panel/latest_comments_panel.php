<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: latest_comments_panel.php
  | Author: gh0st2k
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */

if (!defined("IN_FUSION")) {
    die("Access Denied");
}

$displayComments = 10;
$comment_short = 23;
$comment_long = 70;

openside("<span class='icon-bubbles iconpaddr'></span>" . $locale['global_025']);

$result = dbquery("(SELECT comment_id, comment_item_id, 
                    comment_type, comment_message, comment_datestamp
                    FROM fusion_comments WHERE comment_hidden='0')
                    UNION
                        (SELECT comment_id, task_id as comment_item_id, 'T' as comment_type, comment as comment_message, timestamp as comment_datestamp FROM fusion_awtodo_comment)
                    ORDER BY comment_datestamp DESC");
if (dbrows($result)) {
    $output = "";
    $i = 0;
    while ($data = dbarray($result)) {
        if ($i == $displayComments) {
            break;
        }
        switch ($data['comment_type']) {
            case "N":
                $access = dbcount("(news_id)", DB_NEWS, "news_id='" . $data['comment_item_id'] . "' AND
									" . groupaccess('news_visibility') . " AND
									(news_start='0'||news_start<=" . time() . ") AND
									(news_end='0'||news_end>=" . time() . ") AND
									news_draft='0'
									");
                if ($access > 0) {
                    $comment = trimlink($data['comment_message'], $comment_short);
                    $commentext = parsesmileys(trimlink($data['comment_message'], $comment_long));
                    $commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='" . $data['comment_item_id'] . "' AND comment_type='N' AND comment_id<=" . $data['comment_id']);
                    $commentStart = $commentStart - 1;
                    if ($commentStart > $settings['comments_per_page']) {
                        $commentStart = "&amp;c_start=" . floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
                    } else {
                        $commentStart = "";
                    }
                    $output .='<span class="icon-newspaper iconpaddr"></span><a href="' . BASEDIR . 'news.php?readmore=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                    $i++;
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
                    $comment = trimlink($data['comment_message'], $comment_short);
                    $commentext = parsesmileys(trimlink($data['comment_message'], $comment_long));
                    $commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='" . $data['comment_item_id'] . "' AND comment_type='A' AND comment_id<=" . $data['comment_id']);
                    $commentStart = $commentStart - 1;
                    if ($commentStart > $settings['comments_per_page']) {
                        $commentStart = "&amp;c_start=" . floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
                    } else {
                        $commentStart = "";
                    }
                    $output .= THEME_BULLET . ' <a href="' . BASEDIR . 'articles.php?article_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                    $i++;
                }
                continue;
            case "P":
                $access = dbquery("SELECT photo_id FROM " . DB_PHOTOS . " p, " . DB_PHOTO_ALBUMS . " a WHERE
									p.photo_id='" . $data['comment_item_id'] . "' AND
									p.album_id=a.album_id AND
									" . groupaccess('a.album_access')
                );
                if (dbrows($access) > 0) {
                    $comment = trimlink($data['comment_message'], $comment_short);
                    $commentext = parsesmileys(trimlink($data['comment_message'], $comment_long));
                    $commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='" . $data['comment_item_id'] . "' AND comment_type='P' AND comment_id<=" . $data['comment_id']);
                    $commentStart = $commentStart - 1;
                    if ($commentStart > $settings['comments_per_page']) {
                        $commentStart = "&amp;c_start=" . floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
                    } else {
                        $commentStart = "";
                    }
                    $output .='<span class="icon-image iconpaddr"></span><a href="' . BASEDIR . 'photogallery.php?photo_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                    $i++;
                }
                continue;
            case "C":
                $access = dbcount("(page_id)", DB_CUSTOM_PAGES, "page_id='" . $data['comment_item_id'] . "' AND " . groupaccess('page_access'));
                if ($access > 0) {
                    $comment = trimlink($data['comment_message'], $comment_short);
                    $commentext = parsesmileys(trimlink($data['comment_message'], $comment_tall));
                    $commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='" . $data['comment_item_id'] . "' AND comment_type='C' AND comment_id<=" . $data['comment_id']);
                    $commentStart = $commentStart - 1;
                    if ($commentStart > $settings['comments_per_page']) {
                        $commentStart = "&amp;c_start=" . floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
                    } else {
                        $commentStart = "";
                    }
                    $output .='<span class="icon-file iconpaddr"></span><a href="' . BASEDIR . 'viewpage.php?page_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                    $i++;
                }
                continue;
            case "D":
                $access = dbquery("SELECT download_id FROM " . DB_DOWNLOADS . " d, " . DB_DOWNLOAD_CATS . " c WHERE
									d.download_id='" . $data['comment_item_id'] . "' AND
									d.download_cat=c.download_cat_id AND
									" . groupaccess('c.download_cat_access')
                );
                if (dbrows($access) > 0) {
                    $comment = trimlink($data['comment_message'], $comment_short);
                    $commentext = parsesmileys(trimlink($data['comment_message'], $comment_long));
                    $commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='" . $data['comment_item_id'] . "' AND comment_type='D' AND comment_id<=" . $data['comment_id']);
                    $commentStart = $commentStart - 1;
                    if ($commentStart > $settings['comments_per_page']) {
                        $commentStart = "&amp;c_start=" . floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
                    } else {
                        $commentStart = "";
                    }
                    $output .='<span class="icon-folder iconpaddr"></span><a href="' . BASEDIR . 'downloads.php?download_id=' . $data["comment_item_id"] . $commentStart . '#c' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                    $i++;
                }
                continue;
            case "T":
                $comment = trimlink($data['comment_message'], $comment_short);
                $commentext = parsesmileys(trimlink($data['comment_message'], $comment_long));
                $output .='<span class="icon-tag iconpaddr"></span><a href="' . BASEDIR . 'infusions/aw_todo/task.php?id=' . $data["comment_item_id"] . '#comm' . $data["comment_id"] . '"title="' . $commentext . '" class="side latestcomments">' . $comment . '</a><br />';
                continue;
        }
    }
    echo $output;
} else {
    echo "<div style='text-align:center'>" . $locale['global_026'] . "</div>\n";
}
closeside();
?>