<?php

// Configuration Start
// Set here your Server IP or DNS
$url = 'mumble://mumble.cwclan.de:';

// Configuration End

function printmainchannel($channelobject, $url, $servername, $players) {
    $channeldepth = 0;
    $menustatus = array("1", "1");

    $channelobject->c->name = $servername;

    //var_dump ($channelobject);

    echo "<a class='channelname' href=\"" . $url . "?version=1.2.0\" title=\"Verbinden mit: " . $channelobject->c->name . " Mumble\"><img src=\"" . INFUSIONS . "mumbleview_panel/images/mumble-icon.png\" alt=\"Verbinden mit: " . $channelobject->c->name . " Mumble\" height='16' width='16'/> " . $channelobject->c->name . "</a><br />\n";
    if (count($channelobject->children) + count($channelobject->users) > 0) {
        echo "<div class='div_channel' id='div_channel_" . $channelobject->c->id . "'>\n";
        foreach ($channelobject->children as $children) {
            printchannel($children, $channelobject->children[count($channelobject->children) - 1]->c->id, $channeldepth + 1, $menustatus, $url);
        }

        foreach ($channelobject->users as $users) {
            printplayers($users, $channelobject->users[count($channelobject->users) - 1]->userid, $channeldepth + 1, $menustatus, $url);
        }
        echo "</div>\n";
    }
}

function printchannel($channelobject, $lastid, $channeldepth, $menustatus, $url) {
    $menustatus[$channeldepth] = 1;
    if ($channelobject->c->id == $lastid) {
        $menustatus[$channeldepth] = 0;
    }

    $count = 1;

    if (count($channelobject->users) > 0) {

        while ($count < $channeldepth) {
            if ($menustatus[$count] == 0) {
                echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_space.gif' alt='Mumbleviewer' height='10' width='11'/>";
            } else {
                echo "<img border=0 src=" . INFUSIONS . "mumbleview_panel/images/list_tree_line.gif height='10' width='11' alt=Mumbleviewer>";
            }
            $count++;
        }


        if (count($channelobject->children) + count($channelobject->users) > 0) {
            //if (count($channelobject->users) > 0)
            if ($channelobject->c->id != $lastid) {
                echo "<a href=\"javascript:set_Layer('div_channel_" . $channelobject->c->id . "')\"><img name='div_channel_" . $channelobject->c->id . "' src='" . INFUSIONS . "mumbleview_panel/images/list_tree_open.png' alt='Mumbleviewer' height='10' width='11'/></a>";
            } else {
                echo "<a href=\"javascript:set_Layer('div_channel_" . $channelobject->c->id . "')\"><img name='div_channel_" . $channelobject->c->id . "' src='" . INFUSIONS . "mumbleview_panel/images/list_tree_open.png' alt='Mumbleviewer' height='10' width='11'/></a>";
            }
        } else {
            if ($channelobject->c->id != $lastid) {
                echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_mid.gif' alt='Mumbleviewer' height='10' width='11'/>";
            } else {
                echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_end.gif' alt='Mumbleviewer' height='10' width='11'/>";
            }
        }
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_channel.png' alt='Mumbleviewer' height='10' width='16'/>";

#### K�rzt den Channelnamen nach $cut Zeichen ###
        $channelnameorig = $channelobject->c->name;
        $uncut = strlen($channelnameorig);
        $cut = "14";
        $channelnameorig = substr($channelnameorig, 0, $cut);

        if ($uncut > $cut) {
            $channelname = $channelnameorig . "...";
        } else {
            $channelname = $channelobject->c->name;
        }

        echo "<a href=\"" . $url . "" . str_replace(" ", "%20", $channelobject->c->name) . "/?version=1.2.0\" title=\"Verbinden mit: " . $channelobject->c->name . "\">" . $channelname . "</a> (" . count($channelobject->users) . ")<br />\n";
    }

    if (count($channelobject->children) + count($channelobject->users) > 0) {
        //if (count($channelobject->users) > 0)
        echo "<div class='div_channel' id='div_channel_" . $channelobject->c->id . "'>\n";
        foreach ($channelobject->children as $children) {
            printchannel($children, $channelobject->children[count($channelobject->children) - 1]->c->id, $channeldepth + 1, $menustatus, $url);
        }

        foreach ($channelobject->users as $users) {
            printplayers($users, $channelobject->users[count($channelobject->users) - 1]->userid, $channeldepth + 1, $menustatus, $url);
        }
        echo "</div>\n";
    }
    return $menustatus;
}

function printplayers($playerobject, $lastid, $channeldepth, $menustatus) {
    echo "<div class='div_player'>\n";

    $menustatus[$channeldepth] = 1;
    if ($channelobject->c->id == $lastid) {
        $menustatus[$channeldepth] = 0;
    }

    $count = 1;
    while ($count < $channeldepth) {
        if ($menustatus[$count] == 0) {
            echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_line.gif' alt='Mumbleviewer' height='10' width='11'/>";
        } else {
            echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_line.gif' alt='Mumbleviewer' height='10' width='11'/>";
        }
        $count++;
    }
    if ($playerobject->userid == $lastid)
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_end.gif' alt='Mumbleviewer' height='10' width='11'/>";
    else
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_tree_mid.gif' alt='Mumbleviewer' height='10' width='11'/>";
    echo "<img src='" . INFUSIONS . "mumbleview_panel/images/list_player.png' alt='Spieler' title='Spieler' height='10' width='16'/>";

#### K�rzt den Playernamen nach $cut Zeichen ###
    $playernameorig = $playerobject->name;
    $uncut = strlen($playernameorig);
    $cut = "11";
    $playernameorig = substr($playernameorig, 0, $cut);

    if ($uncut > $cut) {
        $playername = $playernameorig . "...";
    } else {
        $playername = $playerobject->name;
    }

    echo $playername;
    if ($playerobject->userid != -1) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_auth.png' alt='registrierter Spieler' title='registrierter Spieler' height='10' width='16'/>";
    }
    if ($playerobject->mute) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_suppressed.png' alt='stummer Spieler(Server)' title='stummer Spieler(Server)' height='10' width='16'/>";
    }
    if ($playerobject->deaf) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_taub.png' alt='tauber Spieler(Server)' title='tauber Spieler(Server)' height='10' width='16'/>";
    }
    if ($playerobject->suppressed) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_suppressed.png' alt='unterdr�ckter Spieler' title='unterdr�ckter Spieler' height='10' width='16'/>";
    }
    if ($playerobject->selfMute) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_selfmute.png' alt='stummer Spieler' title='stummer Spieler' height='10' width='16'/>";
    }
    if ($playerobject->selfDeaf) {
        echo "<img src='" . INFUSIONS . "mumbleview_panel/images/player_selfdeaf.png' alt='tauber Spieler' title='tauber Spieler' height='10' width='16'/>";
    }
    echo "<br /></div>\n";
    return $menustatus;
}

?>