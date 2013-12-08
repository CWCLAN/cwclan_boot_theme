<?php

if (!defined("IN_FUSION")) {
    die("Access Denied");
}
if (file_exists(INFUSIONS . "hsgallery_panel/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "hsgallery_panel/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "hsgallery_panel/locale/English.php";
}
$locale['436'] = "Kommentare:";
$locale['436b'] = "Kommentar:";


$result = dbquery(
        "SELECT pa.album_id,pa.album_title,ph.photo_id,ph.photo_title,ph.photo_thumb1,pa.album_access
	FROM " . DB_PHOTO_ALBUMS . " pa, " . DB_PHOTOS . " ph
	WHERE " . groupaccess('pa.album_access') . "
	AND pa.album_id = ph.album_id
	ORDER BY RAND() LIMIT 0,1"
);


if (dbrows($result) == 1) {
    echo "<div class='polaroid'>";
    $data = dbarray($result);
    $photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type='P' AND comment_item_id='" . $data['photo_id'] . "'");
    $title = ($data['photo_title'] ? "class='cwtooltip' title='" . $data['photo_title'] . "' alt='" . $data['photo_title'] . "'" : "");    
    echo "<a class='title' href='" . BASEDIR . "infusions/hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html'>";
    echo "<img src='" . PHOTOS . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "' ".$title." /></a><br>";
    echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']) . $photo_comments . "<br>";
    echo "<a href = '" . BASEDIR . "infusions/hsgallery_panel/foto_album_" . seostring($data['album_title']) . "_" . $data['album_id'] . ".html'>" . $data['album_title'] . "</a>";
    echo "&nbsp;
    <a href = '" . INFUSIONS . "hsgallery_panel/neue_fotos.html'><strong>neue Fotos</strong></a>";
    echo "</div>";
}

/**
if (dbrows($result) == 1) {
    openside($locale['hsg103'], "on");
    $data = dbarray($result);
    $photo_comments = dbcount("(comment_id)", DB_COMMENTS, "comment_type = 'P' AND comment_item_id = '" . $data['photo_id'] . "'");
    $img_size = @getimagesize(PHOTOS . "album_" . $data['album_id'] . "/" . $data['photo_thumb1']);
    echo "<div style = 'text-align:center'>\n<a href = '" . BASEDIR . "infusions/hsgallery_panel/foto_" . seostring($data['photo_title']) . "_" . $data['photo_id'] . ".html' class = 'highslide'>\n";
    echo "<img class = 'tipTip' width = '".$img_size[0]."' height = '".$img_size[1]."' src = '" . PHOTOS . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "' title = '" . $data['photo_title'] . "' alt = '" . $data['photo_title'] . "' /></a><br />\n";
    echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']) . $photo_comments . "<br />\n";
    echo "<a href = '" . BASEDIR . "infusions/hsgallery_panel/foto_album_" . seostring($data['album_title']) . "_" . $data['album_id'] . ".html'>" . $data['album_title'] . "</a><br />\n";
    echo "<a href = '" . INFUSIONS . "hsgallery_panel/neue_fotos.html'><strong>neue Fotos</strong></a></div>\n  ";
    closeside();
}**/
?>


