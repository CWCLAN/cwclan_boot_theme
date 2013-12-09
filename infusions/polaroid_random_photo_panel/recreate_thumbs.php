<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System
  | Copyright (C) 2002 - 2008 Nick Jones
  | http://www.php-fusion.co.uk/
  +--------------------------------------------------------+
  | Mod: Recreate all Thumbnails
  | Version: 1.00
  | Author: Philipp Horna
  | Download: http://cwclan.de
  +--------------------------------------------------------+
  | This program is released as free software under the
  | Affero GPL license. You can redistribute it and/or
  | modify it under the terms of this license which you
  | can read by viewing the included agpl.txt or online
  | at www.gnu.org/licenses/agpl.html. Removal of this
  | copyright header is strictly prohibited without
  | written permission from the original author(s).
  +-------------------------------------------------------- */
require_once "maincore.php";

require_once INCLUDES . "photo_functions_include.php";

$result = dbquery("SELECT * FROM " . DB_PHOTOS . "");
while ($data = dbarray($result)) {
    $filename = $data['photo_filename'];
    $album_id = $data['album_id'];

    $photo_name = strtolower(substr($filename, 0, strrpos($filename, ".")));
    $photo_ext = strtolower(strrchr($filename, "."));
    $photo_dest = PHOTOS . "album_" . $album_id . "/";
    $photo_file = $filename;

    $imagefile = @getimagesize($photo_dest . $photo_file);

    $photo_thumb1 = $photo_name . "_t1" . $photo_ext;
    createthumbnail($imagefile[2], $photo_dest . $photo_file, $photo_dest . $photo_thumb1, $settings['thumb_w'], $settings['thumb_h']);
    $photo_thumb2 = "";
    if ($imagefile[0] > $settings['photo_w'] || $imagefile[1] > $settings['photo_h']) {
        $photo_thumb2 = $photo_name . "_t2" . $photo_ext;
        createthumbnail($imagefile[2], $photo_dest . $photo_file, $photo_dest . $photo_thumb2, $settings['photo_w'], $settings['photo_h']);
    }
    $query = "UPDATE " . DB_PHOTOS . " set photo_thumb1 = '" . $photo_thumb1 . "', photo_thumb2 = '" . $photo_thumb2 . "' WHERE photo_id = " . $data['photo_id'] . "";
    dbquery($query);
    echo $data['photo_filename'] . "<br>";
}
?>

