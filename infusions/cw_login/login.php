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

    echo'<div class="user_login_name">Hi <a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '">' . $userdata['user_name'] . '</a>!
    </div>
    <div class="user_login_content">
    
    <div class="user_login_icons">
        <a href="' . BASEDIR . 'edit_profile.php" class="tp" data-toggle="tooltip" title="' . $locale['global_120'] . '"><span class="icon-cog"></span></a>';
    echo '<a href="' . BASEDIR . 'messages.php" class="tp" data-toggle="tooltip" title="' . $locale['global_121'] . '"><span class="icon-envelope"></span></a>';
    echo '<a href="' . BASEDIR . 'index.php?logout=yes" class="tp" data-toggle="tooltip" title="' . $locale['global_124'] . '"><span class="icon-switch"></span></a>';
    if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
        echo '<a href="' . ADMIN . 'index.php' . $aidlink . '" class="tp" data-toggle="tooltip" title="' . $locale['global_123'] . '"><span class="icon-wrench"></span></a>';
    }
    echo'</div>
        </div>';
    echo'<div class = "user_avatar">';
    if ($userdata['user_avatar']) {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/' . $userdata['user_avatar'] . '" class="tp" data-toggle="tooltip" alt="'.$userdata['user_name'].'\' Avatar" title="'.$userdata['user_name'].'\'s Avatar" width="32"></a>';
    } else {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/noavatar100.png" alt="no Avatar" width="32"></a';
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
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/' . $userdata['user_avatar'] . '" class="tp" data-toggle="tooltip" alt="'.$userdata['user_name'].'\' Avatar" title="'.$userdata['user_name'].'\'s Avatar" width="32"></a>';
    } else {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/noavatar100.png" alt="no Avatar" width="32"></a';
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
    echo "<a href='".BASEDIR."cwclan_login.php' id='login' style='float:right;padding-top:3px;padding-bottom:3px;' class='btn cwclear'>Login</a>";
}
?>	