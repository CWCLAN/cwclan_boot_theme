<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright © 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: login.php
  | Author: www.CWCLAN.de
  | Developers: globeFrEak, ununseptium
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
include LOCALE . LOCALESET . "global.php";

global $userdata, $aidlink;

if (iMEMBER) {
    echo '<div class="navi_hint no-pc"><a data-toggle="collapse" data-target=".login"></a></div>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".login">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse login collapse">
                <div class="no-tabletbook no-mobile"> <!-- PC Resolution Login -->
                    <div class="logged_in_box">
                            <div class="user_avatar">
                                <a href="#"><img src="img/content/useravatar_small_nevo.png" class="tp" data-toggle="tooltip" title="Zu deinem Profil" width="40"></a>
                            </div>
                            <div class="user_login_content">
                                <div><a href="#">nevo</a></div>
                                <div class="user_login_icons">
                                    <a href="#" class="tp" data-toggle="tooltip" title="Einstellungen"><span class="icon-cog"></span></a> 
                                    | 
                                    <a href="#" class="tp" data-toggle="tooltip" title="Nachrichten"><span class="icon-envelop"></span></a>
                                    | 
                                    <a href="'.BASEDIR.'index.php?logout=yes" class="tp" data-toggle="tooltip" title="Logout"><span class="icon-switch"></span></a>
                                </div>
                            </div>
                        </div>
                    <div class="login-info"><a href="#">Registrieren</a>&nbsp;&nbsp;&nbsp;<a href="#">Passwort vergessen</a></div>
                </div>
                <div class="no-pc"> <!-- Mobile / Tabletbook Resolution Login -->
                    <div class="responsive-login">
                        <div class="logged_in_box">
                            <div style="width: 75%;margin:0 auto;">
                                <div class="user_avatar">
                                    <a href="#"><img src="img/content/useravatar_small_nevo.png" class="tp2" data-toggle="tooltip" title="Zu deinem Profil" width="40"></a>
                                </div>
                                <div class="user_login_content">
                                    <div><a href="#">nevo</a></div>
                                    <div class="user_login_icons">
                                        <a href="#" class="tp2" data-toggle="tooltip" title="Einstellungen"><span class="icon-cog"></span></a>&nbsp;|&nbsp;<a href="#" class="tp2" data-toggle="tooltip" title="Nachrichten"><span class="icon-envelop"></span></a>&nbsp;|&nbsp;<a href="#" class="tp2" data-toggle="tooltip" title="Logout"><span class="icon-switch"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
} else {
    if (isset($_POST['Regh'])) {
        redirect('register.php');
    }
    echo '<div class="navi_hint no-pc"><a data-toggle="collapse" data-target=".login"></a></div>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".login">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse login collapse">
                <div class="no-tabletbook no-mobile"> <!-- PC Resolution Login -->
                    <form style="margin-top:-7px" name="loginform" method="post" action="' . $action_url . '">
                        <input type="text" name="user_name" placeholder="' . $locale['global_101'] . '"> 
                        <input type="password" name="user_pass" placeholder="' . $locale['global_102'] . '">
                        <label style="display:inline;"><input type="checkbox" name="remember_me" value="y" title="' . $locale['global_103'] . '" style="vertical-align:middle;" /></label>
                        <input type="submit" class="cw-btn" name="login" value="' . $locale['global_104'] . '">
                    </form>
                    <div class="login-info">';
    if ($settings['enable_registration']) {
        echo $locale['global_105'];
    }
    echo '&nbsp;&nbsp;&nbsp;';
    echo $locale['global_106'];
    echo '</div>
              </div>
                <div class="no-pc"> <!-- Mobile / Tabletbook Resolution Login -->
                    <div class="responsive-login">
                        <div class="login-box-r">
                            <table class="table table-condensed">
                                <tr>
                                    <td>
                                        <div align="center">
                                            <input type="text" name="login-name" placeholder="Login">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="center">
                                            <input type="password" name="password" placeholder="Password">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="center">
                                            <input type="submit" value="Einloggen" class="cw-btn">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div align="center">
                                            <a href="#">Registrieren</a>&nbsp;&nbsp;&nbsp;<a href="#">Passwort vergessen</a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>';
}
?>	