<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Type: BBcode
| Name: Spoiler2
| Version: 1.00
| Author: Valerio Vendrame (lelebart)
+--------------------------------------------------------+
| Filename: spoiler2_bbcode_include.php
| Author: Valerio Vendrame (lelebart)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include_once INCLUDES."output_handling_include.php";

if(!function_exists("spoiler_bbcode_addtohead")) {
	function spoiler_bbcode_addtohead() {
		$return = "<!-- spoiler BBcode by lelebart, modified by globeFrEak -->
<script type='text/javascript'>
//<![CDATA[
$(document).ready(function() {
	$('.spoiler-body').hide();
	$('.spoiler-button-hide').hide();
	$('.spoiler-head').show().css('cursor', 'pointer').click(function(){
		$('span',this).toggle();
		$(this).next('.spoiler-body').slideToggle('fast');
	});
});
//]]>
</script>";
		return $return;
	}
	$spoiler_bbcode_addtohead = spoiler_bbcode_addtohead();
	add_to_head($spoiler_bbcode_addtohead);
}

$text = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#si", "<div class='code_bbcode'><div class='spoiler-main'><strong>".$locale['bb_spoiler_text']."</strong> <span class='spoiler-head' style='visibilty:hidden;display:none;'>[<span class='spoiler-toggle'>".$locale['bb_spoiler_show']."</span><span class='spoiler-toggle spoiler-button-hide'>".$locale['bb_spoiler_hide']."</span>]</span><div class='spoiler-body'>\\1</div></div></div>", $text);
?>