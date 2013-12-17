<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright © 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: site_info.php
  | Author: www.CWCLAN.de
  | Developers: globeFrEak, nevo
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
echo "<h4>Seiten Info</h4>";
echo "<ul class='horizontal'>";

if (dbcount("(online_user)", DB_ONLINE, (iMEMBER ? "online_user='" . $userdata['user_id'] . "'" : "online_user='0' AND online_ip='" . USER_IP . "'")) == 1) {
    $result = dbquery(
            "UPDATE " . DB_ONLINE . " SET online_lastactive='" . time() . "', online_ip='" . USER_IP . "'
		WHERE " . (iMEMBER ? "online_user='" . $userdata['user_id'] . "'" : "online_user='0' AND online_ip='" . USER_IP . "'"));
} else {
    $result = dbquery(
            "INSERT INTO " . DB_ONLINE . " (online_user, online_ip, online_ip_type, online_lastactive) 
		VALUES ('" . (iMEMBER ? $userdata['user_id'] : 0) . "', '" . USER_IP . "', '" . USER_IP_TYPE . "', '" . time() . "')");
}
$result = dbquery(
        "SELECT ton.online_user, tu.user_id, tu.user_name, tu.user_status FROM " . DB_ONLINE . " ton
	LEFT JOIN " . DB_USERS . " tu ON ton.online_user=tu.user_id"
);
$guests = 0;
$members = array();
while ($data = dbarray($result)) {
    if ($data['online_user'] == "0") {
        $guests++;
    } else {
        $members[] = array($data['user_id'], $data['user_name'], $data['user_status']);
    }
}
echo "<li>Gäste On: " . $guests . "</li>\n";
echo "<li>User On: " . count($members) . "</li>\n";
echo "<li>User gesamt: " . number_format(dbcount("(user_id)", DB_USERS, "user_status<='1'")) . "</li>\n";
$result = dbquery("SELECT user_id, user_name, user_status FROM " . DB_USERS . " WHERE user_status='0' ORDER BY user_joined DESC LIMIT 0,5");
echo "<li>neueste User: ";

$i = 1;
while ($data = dbarray($result)) {
    echo profile_link($data['user_id'], $data['user_name'], $data['user_status']);
    echo (($i == 5) ? "\n" : ", \n");
    $i++;
}
echo "</li>\n";
echo "</ul>";                 
?>
