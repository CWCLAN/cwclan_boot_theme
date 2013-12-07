<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright C 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: mumbleview_panel.php
  | Author: Philipp H. (globeFrEak)
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
if (!defined("IN_FUSION")) {
    die("Access Denied");
}

if (file_exists(INFUSIONS . "mumbleview_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "mumbleview_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "mumbleview_panel/locale/English.php";
}
include INFUSIONS . "mumbleview_panel/mumbleviewer.php";
try {
    Ice_loadProfile();
    //$secret = array('secret'=>'cwclan.de');
    $base = $ICE->stringToProxy("Meta:tcp -h 127.0.0.1 -p 6503");
    //$meta = $base->ice_checkedCast("::Murmur::Meta")->ice_context($secret);
    $meta = $base->ice_checkedCast("::Murmur::Meta");

    $servers = $meta->getBootedServers();
    $default = $meta->getDefaultConf();
    $version = $meta->getVersion($major, $minor, $patch, $text);
    $version = $text;

    $servers = $meta->getBootedServers();

    foreach ($servers as $s) {
        $tmp_url = $url;
        $port = $s->getConf('port');
        if (!$port) {
            $port = $default['port'] + $s->id() - 1;
            ;
        }
        $tmp_url = $url . $port . '/';

        $name = $s->getConf("registername");
        if (!$name) {
            $name = $default["registername"];
        }

        $tree = $s->getTree();
        $players = $s->getUsers();

        $playercount = count($players);
        openside("<span class='icon-headphones iconpaddr'></span>Mumble ".$version." (" . $playercount . ")", "off");

        echo "<div class='div_channel'>\n";
        printmainchannel($tree, $tmp_url, $name, $players);
        echo "</div>\n";
    }
} catch (Ice_LocalException $ex) {
    echo "Mumble ist zur Zeit nicht im Dienst!!";
    return;
}
echo "<hr />";
echo "<center>";
echo "<div class='channelname'><a href='mumble://mumble.cwclan.de:64738/?version=1.2.0' target='_blank'>Verbinden</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='" . BASEDIR . "seite_mumble_13.html'>Hilfe</a></div>";
echo "</center>";
closeside();
?>