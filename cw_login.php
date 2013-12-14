<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2011 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: cwclan_login.php
  | Author: Philipp Horna (globeFrEak)
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
require_once "maincore.php";
include THEME . "theme.php";

if (iMEMBER) {
    redirect(BASEDIR . "news.php");
} else {
    if (isset($_POST['Regh'])) {
        redirect('register.php');
    }
    echo "<!DOCTYPE html>\n";
    echo "<head>\n<title>" . $settings['sitename'] . "</title>\n";
    echo "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
    echo "<meta name='description' content='" . $settings['description'] . "' />\n";
    echo "<meta name='keywords' content='" . $settings['keywords'] . "' />\n";
    if (file_exists(IMAGES . "favicon.ico")) {
        echo "<link rel='shortcut icon' href='" . IMAGES . "favicon.ico' type='image/x-icon' />\n";
    }
    echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    echo "<link rel='icon' href='" . THEME . "img/favicon.png' type='image/png'>";
    echo "<link rel='apple-touch-icon' href='" . THEME . "img/icon-200.png' />";
    echo "<link rel='image_src' href='" . THEME . "img/icon-200.png'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/bootstrap.login.min.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/icomoon.login.min.css'>";
    echo "<link rel='stylesheet' href='" . THEME . "css/cwclan_login.min.css' media='screen' />\n";
    echo "</head>\n<body>\n";
    echo '<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-7">
            <div class="panel panel-default">
                <div class="panel-head">
                    <span class="icon-lock2"></span> Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="login" role = "login" method="post" action="' . BASEDIR . 'news.php">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-4 control-label">
                            ' . $locale['global_101'] . '</label>
                        <div class="col-sm-8">
                            <input type = "text" class = "form-control cw-form-control" name = "user_name" placeholder = "' . $locale['global_101'] . '" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-4 control-label">
                            ' . $locale['global_102'] . '</label>
                        <div class="col-sm-8">
                            <input type = "password" class = "form-control cw-form-control" name = "user_pass" placeholder = "' . $locale['global_102'] . '" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input type = "checkbox" name = "remember_me" value = "y" title = "' . $locale['global_103'] . '">
                                    ' . $locale['global_103'] . '
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type = "submit" class = "button" name = "login">
                                ' . $locale['global_104'] . '</button>                                 
                        </div>
                    </div>                    
                    </form>
                </div>
                <div class="panel-foot">';
    if ($settings['enable_registration']) {
        echo '<a href="register.php"><span class="icon-pencil"></span> Registrieren</a>';
    }
    echo' | <a href="lostpassword.php"><span class="icon-key"></span> Passwort vergessen</a>';
    echo '</div>
        </div>
    </div>
</div>';

    echo "</body>\n</html>\n";
}
if (ob_get_length() !== FALSE) {
    ob_end_flush();
}
mysql_close($db_connect);
?>