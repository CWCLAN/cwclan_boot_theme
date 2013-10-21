<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright � 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Name: menu.php
  | Author : Smokeman
  | Email: smokeman@esenet.dk
  | Web: http://www.phpfusion-tips.dk/
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
include INFUSIONS . "css_dropdown_menu/infusion_db.php";

$menusettings = dbarray(dbquery("SELECT * FROM " . DB_MENU_SETTINGS));

$msql = dbquery("SELECT * FROM " . DB_MENUS . " WHERE menu_cat='0' ORDER BY menu_order");
if (dbrows($msql) != 0) {
    echo "<ul class='nav navbar-nav'>";
    while ($mdata = dbarray($msql)) {
        if (checkgroup($mdata['menu_access'])) {
            $link_target_m = ($mdata['menu_window'] == "1" ? " target='_blank'" : "");


            $msql2 = dbquery("SELECT * FROM " . DB_MENUS . " WHERE menu_cat='" . $mdata['menu_id'] . "' ORDER BY menu_order");
            if (dbrows($msql2) != 0) {
                echo "<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>" . $mdata['menu_name'] . "<b class='caret'></b></a>";
                echo "<ul class='dropdown-menu'>";
                if (strstr($mdata['menu_link'], "http://") || strstr($mdata['menu_link'], "https://")) {
                    echo "<li><a href='" . $mdata['menu_link'] . "'" . $link_target_m . ">" . $mdata['menu_name'] . "</a></li>";
                } else {
                    echo "<li><a href='" . BASEDIR . $mdata['menu_link'] . "'" . $link_target_m . ">" . $mdata['menu_name'] . "</a></li>";
                }
                while ($mdata2 = dbarray($msql2)) {
                    if (checkgroup($mdata2['menu_access'])) {
                        $link_target_m2 = ($mdata2['menu_window'] == "1" ? " target='_blank'" : "");
                        $msql3 = dbquery("SELECT * FROM " . DB_MENUS . " WHERE menu_cat='" . $mdata2['menu_id'] . "' ORDER BY menu_order");
                        if (dbrows($msql3) != 0) {
                            echo "<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>" . $mdata2['menu_name'] . "<b class='caret'></b></a>";
                            echo "<ul class='dropdown-menu'>";
                            if (strstr($mdata2['menu_link'], "http://") || strstr($mdata2['menu_link'], "https://")) {
                                echo "<li><a href='" . $mdata2['menu_link'] . "'" . $link_target_m2 . ">" . $mdata2['menu_name'] . "</a></li>";
                            } else {
                                echo "<li><a href='" . BASEDIR . $mdata2['menu_link'] . "'" . $link_target_m2 . ">" . $mdata2['menu_name'] . "</a></li>";
                            }
                            while ($mdata3 = dbarray($msql3)) {
                                if (checkgroup($mdata3['menu_access'])) {
                                    $link_target = ($mdata3['menu_window'] == "1" ? " target='_blank'" : "");
                                    if (strstr($mdata3['menu_link'], "http://") || strstr($mdata3['menu_link'], "https://")) {
                                        echo "<li><a href='" . $mdata3['menu_link'] . "'" . $link_target . ">" . $mdata3['menu_name'] . "</a></li>";
                                    } else {
                                        echo "<li><a href='" . BASEDIR . $mdata3['menu_link'] . "'" . $link_target . ">" . $mdata3['menu_name'] . "</a></li>";
                                    }
                                }
                            }
                            echo "</ul>\n";
                        } else {
                            if (strstr($mdata2['menu_link'], "http://") || strstr($mdata2['menu_link'], "https://")) {
                                echo "<li><a href='" . $mdata2['menu_link'] . "'" . $link_target_m2 . ">" . $mdata2['menu_name'] . "</a>";
                            } else {
                                echo "<li><a href='" . BASEDIR . $mdata2['menu_link'] . "'" . $link_target_m2 . ">" . $mdata2['menu_name'] . "</a>";
                            }
                        }
                        echo "</li>";
                    }
                }
                echo "</ul>\n";
            } else {
                if (strstr($mdata['menu_link'], "http://") || strstr($mdata['menu_link'], "https://")) {
                    echo "<li><a href='" . $mdata['menu_link'] . "'" . $link_target_m . ">" . $mdata['menu_name'] . "</a>";
                } else {
                    echo "<li><a href='" . BASEDIR . $mdata['menu_link'] . "'" . $link_target_m . ">" . $mdata['menu_name'] . "</a>";
                }
            }
            echo "</li>\n";
        }
    }
    echo "</ul>\n";
}
?>