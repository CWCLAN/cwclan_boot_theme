<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Smokeman
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."css_dropdown_menu/infusion_db.php";

if (file_exists(INFUSIONS."css_dropdown_menu/locale/".$settings['locale'].".php")) {
	include INFUSIONS."css_dropdown_menu/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."css_dropdown_menu/locale/English.php";
}

$inf_title = $locale['CDM000'];
$inf_description = $locale['CDM001'];
$inf_version = "1.0e";
$inf_developer = "Smokeman/MarcusG";
$inf_email = "smokeman@esenet.dk";
$inf_weburl = "http://www.phpfusion-tips.dk/";

$inf_folder = "css_dropdown_menu";

$inf_newtable[1] = DB_MENUS." (
menu_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
menu_cat SMALLINT(5) NOT NULL DEFAULT '0',
menu_name VARCHAR(100) DEFAULT '' NOT NULL,
menu_order SMALLINT(5) NOT NULL DEFAULT '0',
menu_link VARCHAR(200) DEFAULT '' NOT NULL,
menu_access SMALLINT(5) DEFAULT '0' NOT NULL,
menu_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (menu_id)
);";

$inf_newtable[2] = DB_MENU_SETTINGS." (
mset_horver CHAR(1),
mset_subhorver CHAR(1),
mset_imgon VARCHAR(100),
mset_imgon_sub VARCHAR(100),
mset_imgoff VARCHAR(100),
mset_imgoff_main VARCHAR(100),
mset_bgcolor VARCHAR(8),
mset_textcolor VARCHAR(8),
mset_hbgcolor VARCHAR(8),
mset_hbgcolor_sub VARCHAR(8),
mset_hbgcolor_sub_on VARCHAR(8),
mset_htextcolor VARCHAR(8),
mset_htextcolor_sub VARCHAR(8),
mset_textcolor_sub VARCHAR(8),
mset_bordercol VARCHAR(8),
mset_width VARCHAR(10),
mset_width_sub VARCHAR(10),
mset_textweight VARCHAR(10),
mset_textweight_sub VARCHAR(10),
mset_textweight_on VARCHAR(10),
mset_textweight_sub_on VARCHAR(10),
mset_textsize CHAR(2),
mset_textalign VARCHAR(10),
mset_textfont VARCHAR(100)
);";

$inf_insertdbrow[1] = DB_MENU_SETTINGS." VALUES ('1', '0', 'images/main_bg_hoover.gif', 'images/sub_bg_hoover.gif', 'images/sub_bg.gif', 'images/main_bg.gif', '1E7EB5', 'FFFFFF', '1EB52C', '1EB52C', 'E8F0E9', 'FFFFFF', 'FFFFFF', '555555', '999000', '120', '150', 'normal', 'normal', 'normal', 'normal', '11', 'left', 'arial')";

$inf_adminpanel[1] = array(
	"title" => $locale['CDM000'],
	"image" => "css_ddm.png",
	"panel" => "menu_admin.php",
	"rights" => "CDM"
);

$inf_droptable[1] = DB_MENUS;
$inf_droptable[2] = DB_MENU_SETTINGS;

?>