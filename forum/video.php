<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Filename: video.php
  | Author: Fraev (http://fplace.atwebpages.com)
  | Email: framp_one [at] email.it
  | Adjusted for phpfusion7 by: Hamidians wpa852[at]gmail.com
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



foreach ($_GET as $key => $item) {
    if ($key != 'link' and $key != 'debug') {
        $_GET['link'].='&' . $key . '=' . $item;
    }
}
If ($_GET['link']) {
    If ($_GET['debug'])
        echo '<script>';
    echo "window.document.write('" . preg_replace('/<\/script>/', '</scr\'+\'ipt>', addslashes(get_video($_GET['link']))) . "');";
    If ($_GET['debug'])
        echo '</script>';
}

function get_video($link) {
    $width = "560";
    $height = "315";
    $values = array(
//http://www.youtube.com/watch?v=OygxkgewEhU
        array('/youtube\.com.*v=([^&]*)/i', '<div class="video-container"><iframe src="http://www.youtube.com/embed/{ID_VIDEO}" frameborder="0" width="' . $width . '" height="' . $height . '"></iframe></div></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.youtube.com/watch?v=OygxkgewEhU
        // array('/youtube\.com.*v=([^&]*)/i', '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/{ID_VIDEO}"></param><embed src="http://www.youtube.com/v/{ID_VIDEO}" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '"></embed></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.youtu.be/OygxkgewEhU
        array('/youtu\.be\/([^&]*)/i', '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/{ID_VIDEO}"></param><embed src="http://www.youtube.com/v/{ID_VIDEO}" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '"></embed></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://vids.myspace.com/index.cfm?fuseaction=vids.individual&videoID=1590276358
        array('/vids\.myspace\.com.*?videoID=([^&]*)/i', '<object width="' . $width . 'px" height="' . $height . 'px"><param name="wmode" value="transparent"/><param name="allowscriptaccess" value="always"/><param name="movie" value="http://lads.myspace.com/videos/vplayer.swf"/><param name="flashvars" value="m={ID_VIDEO}"/><embed src="http://lads.myspace.com/videos/vplayer.swf" width="' . $width . '" height="' . $height . '" flashvars="m={ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" /></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
        array('/myspacetv\.com.*?videoID=([^&]*)/i', '<object width="' . $width . 'px" height="' . $height . 'px"><param name="wmode" value="transparent"/><param name="allowscriptaccess" value="always"/><param name="movie" value="http://lads.myspace.com/videos/vplayer.swf"/><param name="flashvars" value="m={ID_VIDEO}"/><embed src="http://lads.myspace.com/videos/vplayer.swf" width="' . $width . '" height="' . $height . '" flashvars="m={ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" /></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://video.yahoo.com/video/play?vid=1845135&fr=&cache=1
        array('/video\.yahoo.*vid=([^&]*)/i', '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" /><param name="allowFullScreen" value="true" /><param name="flashVars" value="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" allowFullScreen="true" flashVars="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" ></embed></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://photobucket.com/video/recent/imaan_sygku/22533661.flv?o=10
        array('/(photobucket\.com)/i', '{DOWNLOAD%/<input name="txtThumbTag2" id="txtThumbTag2".*?value="(.*?)"/ism%html_entity_decode}'),
//http://www.gametrailers.com/player/30032.html
        array('/gametrailers\.com\/player\/(.*?).html/i', '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"  codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" id="gtembed" width="' . $width . '" height="' . $height . '">      <param name="allowScriptAccess" value="sameDomain" />   <param name="allowFullScreen" value="true" /> <param name="movie" value="http://www.gametrailers.com/remote_wrap.php?mid={ID_VIDEO}"/> <param name="quality" value="high" /> <embed src="http://www.gametrailers.com/remote_wrap.php?mid={ID_VIDEO}" swLiveConnect="true" name="gtembed" align="middle" allowScriptAccess="sameDomain" allowFullScreen="true" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '"></embed> </object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.gamespot.com/video/938343/6185167/videoplayerpop?
        array('/gamespot\.com\/video\//i', '<embed id="mymovie" width="' . $width . '" height="' . $height . '" flashvars="playerMode=embedded&movieAspect=4.3&flavor=EmbeddedPlayerVersion&skin=http://image.com.com/gamespot/images/cne_flash/production/media_player/proteus/one/skins/gamespot.png&paramsURI={DOWNLOAD%/so\.addVariable\(\'paramsURI\', \'(.*?)\'\);/ism%}" wmode="transparent" allowscriptaccess="always" quality="high" name="mymovie" style="" src="http://image.com.com/gamespot/images/cne_flash/production/media_player/proteus/one/proteus2.swf" type="application/x-shockwave-flash"/></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.megavideo.com/?v=QZ4O9C8P
        array('/(megavideo\.com)/i', '{DOWNLOAD%/<input type="text" value=\'(.*?)\'/%}'),
//http://www.vimeo.com/173714
        array('/vimeo\.com\/([^&]*)/i', '<div class="video-container"><iframe src="//player.vimeo.com/video/{ID_VIDEO}" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.gamevideos.com/video/id/17281
        array('/(gamevideos\.com)/i', '{DOWNLOAD%/Embed: <input.*value="(.*?)"/%html_entity_decode}'),
//http://www.myvideo.de/watch/4276644/Handys_boese
        array('/myvideo.de\/watch\/(.*?)\//i', "<object width='" . $width . "' height='" . $height . "' type='application/x-shockwave-flash' data='http://www.myvideo.de/movie/{ID_VIDEO}'><param name='movie' value='http://www.myvideo.de/movie/{ID_VIDEO}'/><param name='AllowFullscreen' value='true' /><embed src='http://www.myvideo.de/movie/{ID_VIDEO}' width='" . $width . "' height='" . $height . "'></embed></object></br><a href='{LINK}' target='_blank'><i>Link:{KURZLINK}</i></a>"),
//http://www.comedycentral.com/videos/index.jhtml?videoId=173093
        array('/comedycentral.*videoId=([^&]*)/i', "<embed FlashVars='videoId={ID_VIDEO}' src='http://www.comedycentral.com/sitewide/video_player/view/default/swf.jhtml' quality='high' bgcolor='#cccccc' width='332' height='316' name='comedy_central_player' align='middle' allowScriptAccess='always' allownetworking='external' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'></embed></br><a href='{LINK}' target='_blank'><i>Link:{KURZLINK}</i></a>"),
//http://www.clipfish.de/player.php?videoid=MTMyNzg4fDI0NTY3MzM%3D&tl=4712&utm_source=ft&utm_medium=ft_2&utm_term=ft_2_unset&utm_content=ft_2_unset_video&utm_campaign=cf
        array('/clipfish\.de.*?videoid=([^&]*)/i', "<object width=''.$width.'' height='" . $height . "'><param name='movie' value='http://www.clipfish.de/videoplayer.swf?videoid={ID_VIDEO}' /><param name='allowFullScreen' value='true' /><embed src='http://www.clipfish.de/videoplayer.swf?videoid=MTMyNzg4fDI0NTY3MzM' width='" . $width . "' height='" . $height . "' name='player' allowFullScreen='true' type='application/x-shockwave-flash'></embed></object></br><a href='{LINK}' target='_blank'><i>Link:{KURZLINK}</i></a>"),
//http://video.golem.de/games/4931/battlefield-3-live-demo-von-der-e3-2011.html?q=medium
        array('/video\.golem\.de\/[^&]*\/([^&]*)\//i', '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://video.golem.de/player/videoplayer.swf?id={ID_VIDEO}&autoPl=false"></param><param name="wmode" value="transparent"><embed src="http://video.golem.de/player/videoplayer.swf?id={ID_VIDEO}&autoPl=false" type="application/x-shockwave-flash" wmode="transparent" width="' . $width . '" height="' . $height . '"></embed></object></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>'),
//http://www.dailymotion.com/video/xnvyw4_sahara-momentos_creation
        array('/dailymotion\.com\/video\/(.*)/i', '<div class="video-container"><iframe src="http://www.dailymotion.com/embed/video/{ID_VIDEO}" width="' . $width . '" height="' . $height . '" frameborder="0"></iframe></div></br><a href="{LINK}" target="_blank"><i>Link:{KURZLINK}</i></a>')
    );

    if (strlen($link) > 20) {
        $kurzlink = substr($link, 0, 20) . "...";
    } else {
        $kurzlink = $link;
    }

    foreach ($values as $value) {
        if (preg_match($value[0], $link, $matches)) {
            $id_video = $matches[1];
            return preg_replace_callback('/{.*?}/', create_function('$matches', 'switch (true){
case preg_match("/\{ID_VIDEO\}/", $matches[0]):
return "' . $id_video . '";
break;
case preg_match("/\{LINK\}/", $matches[0]):
return "' . $link . '";
break;
case preg_match("/\{KURZLINK\}/", $matches[0]):
return "' . $kurzlink . '";
break;

case preg_match("/\{DOWNLOAD(.*?)%(.*?)%(.*?)\}/", $matches[0], $matches2):
if (empty($matches2[1])) $matches2[1]="' . $link . '";
preg_match($matches2[2], file_get_contents(str_replace(" ","+",$matches2[1])), $matches3);
if (empty($matches2[3])){
return $matches3[1];
}else{
$t=$matches3[1];
foreach(explode("|", $matches2[3]) as $e){
eval(\'$t=\'.$e.\'($t);\');
}
return $t;
}
break;
}
return $matches[0];'), $value[1]);
        }
    }
    return 'Error, site not recognized';
}

?>