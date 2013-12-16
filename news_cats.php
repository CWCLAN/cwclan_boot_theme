<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: news_cats.php
  | Author: Nick Jones (Digitanium)
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
include LOCALE . LOCALESET . "news_cats.php";

add_to_title($locale['global_200'] . $locale['400']);

opentable($locale['400']);
if (isset($_GET['cat_id']) && isnum($_GET['cat_id'])) {
    $res = 0;
    $result = dbquery("SELECT news_cat_name FROM " . DB_NEWS_CATS . " WHERE news_cat_id='" . $_GET['cat_id'] . "'");
    if (dbrows($result) || $_GET['cat_id'] == 0) {
        $data = dbarray($result);
        $rows = dbcount("(news_id)", DB_NEWS, "news_cat='" . $_GET['cat_id'] . "' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0'");
        if ($rows) {
            $res = 1;
            echo "<!--pre_news_cat-->\n";
            if ($_GET['cat_id'] != 0) {
                echo "<!--news_cat_image--><img src='" . get_image("nc_" . $data['news_cat_name']) . "' alt='" . $data['news_cat_name'] . "' class='pull-right' />\n";
                echo "<h4>" . $locale['401'] . " " . $data['news_cat_name'] . "</h4>\n<strong>" . $locale['402'] . "</strong> $rows\n<hr>\n";
            } else {
                echo "<h4>" . $locale['403'] . "</h4>\n";
                echo "<strong>" . $locale['401'] . "</strong> $rows<!--news_cat_news-->\n<hr>\n";
            }
            $result2 = dbquery("SELECT news_id, news_subject FROM " . DB_NEWS . " WHERE news_cat='" . $_GET['cat_id'] . "' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0' ORDER BY news_datestamp DESC");
            if (dbrows($result)) {
                echo "<div class='row news_cats'>";
                while ($data2 = dbarray($result2)) {
                    echo "<div class='pic col-xs-6'>";
                    echo "<span class='icon-newspaper mid'></span> <a href='news.php?readmore=" . $data2['news_id'] . "'>" . $data2['news_subject'] . "</a>\n";
                    echo "</div>";
                }
            }
            echo "</div>\n";
            echo "<h5 class='pull-right'><a href='" . FUSION_SELF . "'>" . $locale['406'] . "</a></h5>\n";
            echo "<!--sub_news_cat-->\n";
        }
    }
    if (!$res) {
        redirect(FUSION_SELF);
    }
} else {
    $res = 0;
    $result = dbquery("SELECT news_cat_id, news_cat_name FROM " . DB_NEWS_CATS . " ORDER BY news_cat_id");
    if (dbrows($result)) {
        echo "<!--pre_news_cat_idx-->\n";
        while ($data = dbarray($result)) {
            $rows = dbcount("(news_id)", DB_NEWS, "news_cat='" . $data['news_cat_id'] . "' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0'");
            echo "<!--news_cat_image--><img src='" . get_image("nc_" . $data['news_cat_name']) . "' alt='" . $data['news_cat_name'] . "' class='pull-right' />\n";
            echo "<h4>" . $locale['401'] . " " . $data['news_cat_name'] . "</h4>\n<strong>" . $locale['402'] . "</strong> $rows\n<hr>\n";
            if ($rows) {
                echo "<div class='row news_cats'>";
                $result2 = dbquery("SELECT news_id, news_subject FROM " . DB_NEWS . " WHERE news_cat='" . $data['news_cat_id'] . "' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0' ORDER BY news_datestamp DESC LIMIT 10");
                while ($data2 = dbarray($result2)) {
                    echo "<div class='pic col-xs-6'>";
                    echo "<span class='icon-newspaper mid'></span> <a href='news.php?readmore=" . $data2['news_id'] . "'>" . $data2['news_subject'] . "</a>\n";
                    echo "</div>";
                }
                if ($rows > 10) {
                    echo "<div class='pic col-xs-6'><span class='icon-plus mid'></span> <a href='" . FUSION_SELF . "?cat_id=" . $data['news_cat_id'] . "'>" . $locale['405'] . "</a></div>\n";
                }
                echo "</div>";
            } else {
                echo THEME_BULLET . " " . $locale['404'] . "\n";
            }
            echo "";
        }
        $res = 1;
    }
    $result = dbquery("SELECT * FROM " . DB_NEWS . " WHERE news_cat='0' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0' ORDER BY news_datestamp DESC LIMIT 10");
    if (dbrows($result)) {
        if ($res == 0) {
            echo "\n";
        }
        $nrows = dbcount("(news_id)", DB_NEWS, "news_cat='0' AND " . groupaccess('news_visibility') . " AND (news_start='0'||news_start<=" . time() . ") AND (news_end='0'||news_end>=" . time() . ") AND news_draft='0'");
        echo "<h4>" . $locale['403'] . "</h4>\n";
        echo "<strong>" . $locale['402'] . "</strong> $nrows\n<hr>\n";
        echo "<div class='row news_cats'>";
        while ($data = dbarray($result)) {
            echo "<div class='pic col-xs-6'>";
            echo "<span class='icon-newspaper mid'></span> <a href='news.php?readmore=" . $data['news_id'] . "'>" . $data['news_subject'] . "</a>\n";
            echo "</div>";
        }
        
        $res = 1;
        if ($nrows > 10) {
            echo "<div class='pic col-xs-6'>";
            echo "<span class='icon-plus mid'></span> <a href='" . FUSION_SELF . "?cat_id=0'>" . $locale['405'] . "</a>\n";
            echo "</div>";           
        }
        echo "</div>";        
    }
    if ($res == 1) {
        echo "<!--sub_news_cat_idx-->\n";
    } else {
        echo "<div style='text-align:center'><br />\n" . $locale['407'] . "<br />\n</div>\n";
    }
}
closetable();

require_once THEMES . "templates/footer.php";
?>