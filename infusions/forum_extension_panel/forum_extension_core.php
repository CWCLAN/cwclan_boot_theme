<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: forum_extension_core.php
| Author: Max "Matonor" Toball
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

include INFUSIONS."forum_extension_panel/infusion_db.php";


$url = FUSION_REQUEST;
$places = array(
	"index" => "/forum/index.php",
        "index2" => "/forum/index.html",
	"forum" => "/forum/viewforum.php",
	"thread" => "/forum/viewthread.php",
        "thread1" => "/forum/t_",
        "thread2" => "/forum/tp_",
	"profile2" => "/user_",
        "profile" => "/profile.php",
	"reply" => "/forum/post.php?action=reply",
	"postify" => "/forum/postify.php",
	"edit" => "/forum/post.php?action=edit",
	"admin" => "/forum_extension_panel/forum_extension_admin.php");

$in_forum = false;
foreach($places as $place){
	if(stristr($url, $place))
		$in_forum = true;
}

if($in_forum){
	if(!function_exists("array_combine")){
		function array_combine($array1, $array2) {
			$combined_array = array();
			$array1 = array_values($array1);
			$array2 = array_values($array2);
			foreach($array1 as $key => $value)
				$combined_array[(string)$value] = $array2[$key];
			return $combined_array;
		}
	}
	
	$data = dbarray(dbquery("SELECT * FROM ".DB_FORUM_EXT_PANEL));
	
	/*
	#0: Enable Forum Panel
	#1: Enable Profile Panel
	#2: Enable Similar Threads
	#3: Enable Thread Preview
	#4: Enable Forum Stats
	#5: Enable Top Posters
	#6: Enable User Stats
	*/
	
	$option_values = explode("|", $data['options']);
	$option_keys = array("forum_panel", "profile_panel", "similar_threads", "thread_preview", "forum_stats", "top_posters", "user_stats", "forum_observer", "skip_postify");
	while(count($option_values) < count($option_keys)){
		$option_values[] = "1";
	}
	$options = array_combine($option_keys, $option_values);
	foreach($options as $key=>$value)
		$options[$key] = $value == "1" ? true : false;
	
	/*
	#0: Max Users Online (number:timestamp)
	*/
	$stat_values = explode("|", $data['stats']);
	$stat_keys = array("max_online_users");
	$stats = array_combine($stat_keys, $stat_values);
	
	function update_stats(){
		global $stats;
		$values = implode("|", $stats);
		
		dbquery("UPDATE ".DB_FORUM_EXT_PANEL." SET stats='".$values."'");
	}
	
	function almost_null($number){
		$rounded = number_format(round($number, 2));
		if($rounded == 0 && $number > 0){
			$rounded = "<1";
		}
		return $rounded;
	}
	
	function user_list($guests=false, $members=false){
		global $locale;
		$string = "";
		if($guests){
			if(is_array($guests))
				$guests = count($guests);
			if(!isnum($guests))
				$guests = 0;
			if($guests > 0)
				$string .= $guests." ".$locale['forum_ext_guests'];
		}
		if($members){
			if(is_array($members)){
				if(isset($members[0]['user_name']) && isset($members[0]['user_id'])){
					$new_members = array();
					foreach($members as $member)
						$new_members[] = (isset($member['user_level']) && $member['user_level'] > 101 ? "<a style='font-weight: bold;'" : "<a")." href='".BASEDIR."profile.php?lookup=".$member['user_id']."'>".$member['user_name']."</a>";
					$members = $new_members;
				}
				if(!empty($string))
					$string .= ", ";
				$string .= implode(", ", $members);
			}
		}
		return $string;
	}
	
	function forum_observer($output){
		global $url, $places, $locale;
	
		if(stristr($url, $places['index'])){
			$matches = array();
			preg_match_all("^<!--forum_name-->.*?\?forum_id=(\d*)'^s", $output, $matches);
			if(!empty($matches[1])){
				$res = dbquery("SELECT ".DB_FORUM_OBSERVER.".user_id, forum_id, thread_id, user_name, user_level FROM ".DB_FORUM_OBSERVER."
					LEFT JOIN ".DB_USERS." ON ".DB_USERS.".user_id = ".DB_FORUM_OBSERVER.".user_id
					WHERE forum_id IN (".implode(",", $matches[1]).")");
				$guests = array();
				$members = array();
				while($data = dbarray($res)){
					if(empty($data['user_name'])){
						$guests[$data['forum_id']][] = 1;
					}else{
						$members[$data['forum_id']][] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
					}
				}
				foreach($matches[1] as $forum_id){
					$whoishere = @user_list($guests[$forum_id], $members[$forum_id]);
					
					if($whoishere){
						$output = preg_replace("^<!--forum_name-->(.*?)\?forum_id=$forum_id(.*?)</td>^s", "<!--forum_name-->\\1?forum_id=$forum_id\\2<br/><span class='small'><strong>".$locale['forum_ext_who_is_here']."</strong> $whoishere</span></td>", $output);
					}
				}
			}
		}elseif(stristr($url, $places['forum'])){
			$res = dbquery(
				"SELECT ".DB_FORUM_OBSERVER.".user_id, forum_id, thread_id, user_name, user_level FROM ".DB_FORUM_OBSERVER."
				LEFT JOIN ".DB_USERS." ON ".DB_USERS.".user_id = ".DB_FORUM_OBSERVER.".user_id
				WHERE forum_id='".$_GET['forum_id']."'");
			$guests = 0;
			$members = array();
			while($data = dbarray($res)){
				if(empty($data['user_name'])){
					$guests++;
				}else{
					$members[] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
				}
			}
			$whoishere = user_list($guests, $members);
			if($whoishere){
				if(iMEMBER)
					$output = preg_replace("^<!--pre_forum-->(.*?)<tr>^s", "<!--pre_forum-->\\1<tr><td>".$locale['forum_ext_who_is_here']." $whoishere</td>", $output);
				else
					$output = preg_replace("^<!--pre_forum-->(.*?)</div>^s", "<!--pre_forum-->\\1</div><div style='padding: 5px;'>".$locale['forum_ext_who_is_here']." $whoishere</div>", $output);
			}
		}elseif(stristr($url, $places['thread'])){
			$res = dbquery(
				"SELECT ".DB_FORUM_OBSERVER.".user_id, forum_id, thread_id, user_name, user_level FROM ".DB_FORUM_OBSERVER."
				LEFT JOIN ".DB_USERS." ON ".DB_USERS.".user_id = ".DB_FORUM_OBSERVER.".user_id
				WHERE thread_id='".$_GET['thread_id']."'");
			$guests = 0;
			$members = array();
			while($data = dbarray($res)){
				if(empty($data['user_name'])){
					$guests++;
				}else{
					$members[] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
				}
			}
			$whoishere = user_list($guests, $members);
			if($whoishere){
				if(iMEMBER)
					$output = preg_replace("^<!--pre_forum_thread-->(.*?)<tr>^s", "<!--pre_forum_thread-->\\1<tr><td>".$locale['forum_ext_who_is_here']." $whoishere</td>", $output);
				else
					$output = preg_replace("^<!--pre_forum_thread-->(.*?)</div>^s", "<!--pre_forum_thread-->\\1</div><div style='padding: 5px;'>".$locale['forum_ext_who_is_here']." $whoishere</div>", $output);
			}
		}
		return $output;
	}

}
?>