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
    echo '<div class="no-tabletbook no-mobile pull-right"> <!-- PC Resolution Login -->
                    <div class="logged_in_box">';

    echo'<div class="user_login_content">
    <div class="user_login_name"><a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '">' . $userdata['user_name'] . '</a>
    </div>
    <div class="user_login_icons">
        <a href="' . BASEDIR . 'edit_profile.php" class="tp" data-toggle="tooltip" title="' . $locale['global_120'] . '"><span class="icon-cog"></span></a>';
    echo '|<a href="' . BASEDIR . 'messages.php" class="tp" data-toggle="tooltip" title="' . $locale['global_121'] . '"><span class="icon-envelope"></span></a>';
    echo '|<a href="' . BASEDIR . 'index.php?logout=yes" class="tp" data-toggle="tooltip" title="' . $locale['global_124'] . '"><span class="icon-switch"></span></a>';
    if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
        echo '|<a href="' . ADMIN . 'index.php' . $aidlink . '" class="tp" data-toggle="tooltip" title="' . $locale['global_123'] . '"><span class="icon-wrench"></span></a>';
    }
    echo'</div>
        </div>';
    echo'<div class = "user_avatar">';
    if ($userdata['user_avatar']) {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/' . $userdata['user_avatar'] . '" alt="' . $locale['567'] . '" width="32"></a>';
    } else {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/noavatar100.png" alt="' . $locale['567'] . '" width="32"></a';
    }
    echo '</div>';
    echo'</div>                             
         </div>
<div class="no-pc"> <!-- Mobile / Tabletbook Resolution Login -->
            <div class="responsive-login">
                <div class="logged_in_box">
                    <div style="width: 75%;margin:0 auto;">';
    echo'<div class="user_avatar">';
    if ($userdata['user_avatar']) {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/' . $userdata['user_avatar'] . '" alt="' . $locale['567'] . '" width="32"></a>';
    } else {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/noavatar100.png" alt="' . $locale['567'] . '" width="32"></a';
    }
    echo'</div>';
    echo'<div class="user_login_content">
                         <div><a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '">' . $userdata['user_name'] . '</a></div>
                         <div class="user_login_icons">
                         <a href="' . BASEDIR . 'edit_profile.php" class="tp" data-toggle="tooltip" title="' . $locale['global_120'] . '"><span class="icon-cog"></span></a>';
    echo '|<a href="' . BASEDIR . 'messages.php" class="tp" data-toggle="tooltip" title="' . $locale['global_121'] . '"><span class="icon-envelope"></span></a>';
    echo '|<a href="' . BASEDIR . 'index.php?logout=yes" class="tp" data-toggle="tooltip" title="' . $locale['global_124'] . '"><span class="icon-switch"></span></a>';
    if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
        echo '|<a href="' . ADMIN . 'index.php' . $aidlink . '" class="tp" data-toggle="tooltip" title="' . $locale['global_123'] . '"><span class="icon-wrench"></span></a>';
    }
    echo'</div>';
    echo'</div>
    </div>
    </div>
    </div>
    </div>';
} else {
    if (isset($_POST['Regh'])) {
        redirect('register.php');
    }
    echo "<a href='#' id='login' style='float:right' class='btn cwclear' data-toggle='popover' data-content='";
        echo '<form class = "navbar-form navbar-right" role = "login" method="post" action="' . FUSION_SELF . '">
                <div class = "form-group">
                
                    <input type = "text" class = "form-control cw-form-control" name = "user_name" placeholder = "' . $locale['global_101'] . '">
                    <input type = "password" class = "form-control cw-form-control" name = "user_pass" placeholder = "' . $locale['global_102'] . '">
                    <br>
                    <div style="color: #999;
text-align: center;margin-top:5px">Remember me: <input type = "checkbox" name = "remember_me" value = "y" title = "' . $locale['global_103'] . '" style = "vertical-align:middle;" /></div>
                    
                </div>
                <button type = "submit" class = "button" style = "display:block;margin: 10px auto;" name = "login">
                ' . $locale['global_104'] . '</button><hr><div style="text-align:center">'; #
             if ($settings['enable_registration']) {
                echo '<a href="register.php">Registrieren</a>';
            }
            echo' | <a href="lostpassword.php">Passwort vergessen</a></div>
            </form>';    
    echo "' >click me hard</a>";
}
?>	