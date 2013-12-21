<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: admin_header.php
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
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

define("ADMIN_PANEL", true);

require_once INCLUDES . "output_handling_include.php";
require_once INCLUDES . "header_includes.php";
require_once THEME . "theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) {
    redirect(BASEDIR . "maintenance.php");
}
if (iMEMBER) {
    $result = dbquery("UPDATE " . DB_USERS . " SET user_lastvisit='" . time() . "', user_ip='" . USER_IP . "', user_ip_type='" . USER_IP_TYPE . "' WHERE user_id='" . $userdata['user_id'] . "'");
}

echo "<!DOCTYPE html>\n";
echo "<head>\n<title>" . $settings['sitename'] . "</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
if (file_exists(IMAGES . "favicon.ico")) {
    echo "<link rel='shortcut icon' href='" . IMAGES . "favicon.ico' type='image/x-icon' />\n";
}
if (function_exists("get_head_tags")) {
    echo get_head_tags();
}
echo "<link rel='stylesheet' href='" . THEME . "styles.css' type='text/css' media='screen' />\n";
echo "<script type='text/javascript' src='" . INCLUDES . "jquery/jquery.js'></script>\n";
echo "<script type='text/javascript' src='" . INCLUDES . "jscript.js'></script>\n";
echo "<script type='text/javascript' src='" . INCLUDES . "jquery/admin-msg.js'></script>\n";

if ($settings['tinymce_enabled'] == 1) {
    echo "<script language='javascript' type='text/javascript' src='" . INCLUDES . "jscripts/ckeditor/ckeditor.js'></script>\n";
    echo"<script src='" . INCLUDES . "jscripts/ckeditor/adapters/jquery.js'></script>
   <script type='text/javascript'>
   var ckenable = 0;
   function advanced() {
    ckenable = 1;              	
   }
   $(document).ready(function() {
    if (ckenable == 1){
        $( 'textarea' ).ckeditor();
    } 
    });       
</script>";
}

echo "</head>\n<body>\n";

require_once THEMES . "templates/panels.php";

ob_start();
?>