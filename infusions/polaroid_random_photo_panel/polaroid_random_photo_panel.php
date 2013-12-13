<?php

if (!defined("IN_FUSION")) {
    die("Access Denied");
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
    echo "<a href='" . BASEDIR . "cw_photogallery.php?photo_id=" . $data['photo_id'] . "'>";
    echo "<img src='" . PHOTOS . "album_" . $data['album_id'] . "/" . $data['photo_thumb1'] . "' " . $title . " /></a><br>";
    echo ($photo_comments == 1 ? $locale['436b'] : $locale['436']) . $photo_comments . "<br>";
    echo "<a href = '" . BASEDIR . "cw_photogallery.php?album_id=" . $data['album_id'] . "'>" . $data['album_title'] . "</a>";
    echo "&nbsp;<a href = '" . BASEDIR . "cw_latestsphotos.php'><strong>neue Fotos</strong></a>";
    echo "</div>";
}
?>


