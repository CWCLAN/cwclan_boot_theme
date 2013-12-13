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
    echo '<!-- Login --><div class="logged_in_box">';
    echo'<div class = "user_avatar">';
    if ($userdata['user_avatar']) {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/' . $userdata['user_avatar'] . '" class="tp" data-toggle="tooltip" alt="' . $userdata['user_name'] . '\' Avatar" title="' . $userdata['user_name'] . '\'s Avatar" width="32"></a>';
    } else {
        echo '<a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '"><img src="' . IMAGES . 'avatars/noavatar100.png" alt="no Avatar" width="32"></a';
    }
    echo '</div>';
    echo'<div class="user_login_content">';
    echo'<div class="user_login_icons">';
    // Profil Link
    echo'<a href="' . BASEDIR . 'edit_profile.php" class="tp" title="' . $locale['global_120'] . '"><span class="icon-cog"></span></a>';
    // Messages    
    $pm_count = dbcount("(message_id)",DB_MESSAGES,"message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder=0");
    echo '<a href="' . BASEDIR . 'cw_messages.php" class="tp" title="' . $locale['global_121'] . '"><span class="icon-envelop"></span>'.($pm_count > 0 ? " <span class='badge'>$pm_count</span>" : "").'</a>';
    // Einsendung
    echo '<span class="dropdown" id="dropSubmit">';
    echo '<span class="icon-download2 dropdown-toggle cwtooltip" data-toggle="dropdown" title="Einsendungen"></span>';
    echo'<ul class="dropdown-menu" role="menu" aria-labelledby="dropSubmit" id="SubmitMenu">';
    echo'<li><a href="' . BASEDIR . 'cw_submit.php?stype=n">News einsenden <span class="icon-newspaper mid"></span></a></li>';
    echo'<li><a href="' . BASEDIR . 'cw_submit.php?stype=p">Foto einsenden <span class="icon-image2 mid"></span></a></li>';
    echo'<li><a tabindex="-1" href="' . BASEDIR . 'infusions/uploader_panel/uploader.php">CW Cloud <span class="icon-cloud mid"></span></a></li>';
    echo'</ul></span>';
    // Logout
    echo '<a href="' . BASEDIR . 'index.php?logout=yes" class="tp" title="' . $locale['global_124'] . '"><span class="icon-switch"></span></a>';
    // Admin Link + Status Einsendungen
    if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
        echo '<a href="' . ADMIN . 'index.php' . $aidlink . '" class="tp" data-toggle="tooltip" title="' . $locale['global_123'] . '"><span class="icon-wrench"></span></a>';
        $sub_count = dbcount("(submit_id)", DB_SUBMISSIONS,"");
        if ($sub_count > 0){
            echo '<a href="' . ADMIN . 'submissions.php' . $aidlink . '" class="tp" data-toggle="tooltip" title="neue Einsendungen!"><span class="badge pulse">'.$sub_count.'</span></a>';             
        }
        
    }
    echo'</div>
        </div>';
    echo'<div class="user_login_name">Hi <a href="' . BASEDIR . 'profile.php?lookup=' . $userdata['user_id'] . '">' . $userdata['user_name'] . '</a>!</div>';
    echo'</div><!-- /Login -->';
} else {
    echo '<!-- Login --><div class="logged_in_box" style="text-align:right">';
    echo "<a href='" . BASEDIR . "cw_login.php' id='login' class='btn cwclear'><span class='icon-lock2'></span>Login</a>";
    echo '<!-- Login --></div>';
}
?>	