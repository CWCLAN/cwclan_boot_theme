<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: forum_extension_panel.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

include_once INFUSIONS."forum_extension_panel/forum_extension_core.php";

if (file_exists($dir."locale/".$settings['locale'].".php")) {
	include $dir."locale/".$settings['locale'].".php";
} else {
	include $dir."locale/English.php";
}

if($in_forum){
	if(stristr($url, $places['index']) && $options['forum_panel'] && !(!$options['top_posters'] && !$options['forum_stats'] && !$options['user_stats'])){
		//General Stats
		list($posts) = dbarraynum(dbquery("SELECT SUM(forum_postcount) FROM ".DB_FORUMS));
		$posts = empty($posts) ? 0 : $posts;
		list($threads) = dbarraynum(dbquery("SELECT SUM(forum_threadcount) FROM ".DB_FORUMS));
		$threads = empty($threads) ? 0 : $threads;
		list($age) = dbarraynum(dbquery("SELECT user_joined from ".DB_USERS." WHERE user_id=1"));
		$age = empty($age) ? 0 : $age;
		$threadspday = almost_null($threads/((time() - $age)/(3600*24)));
		$postspday = almost_null($posts/((time() - $age)/(3600*24)));
		
		//Top Posters
		list($tposter_id, $tposter_name, $tposter_posts) = dbarraynum(dbquery("SELECT user_id, user_name, user_posts FROM ".DB_USERS." ORDER BY user_posts DESC LIMIT 1"));
		
		list($aposter_id, $aposter_name, $aposter_ppday) = dbarraynum(dbquery("SELECT user_id, user_name, (user_posts/((".time()."-user_joined)/(24*3600))) FROM ".DB_USERS." WHERE user_joined < (".time()."-(3600*24)) ORDER BY user_posts DESC LIMIT 1"));
		
		//User Stats
		$total_users = dbcount("(user_id)", DB_USERS);
		$newest_member = array();
		list($newest_member['name'], $newest_member['id']) = dbarraynum(dbquery("SELECT user_name, user_id FROM ".DB_USERS." ORDER BY user_joined DESC LIMIT 1"));
		$online_guests = dbcount("(online_ip)", DB_ONLINE, "online_user='0' AND online_lastactive > (".time()."-5*60)");
		$online_users_res = dbquery("SELECT user_name, user_id, user_level FROM ".DB_USERS." WHERE user_lastvisit > (".time()."-5*60) ORDER BY user_lastvisit DESC");
		$online_users = array();
		while($online_users_data = dbarray($online_users_res)){
			$online_users[] = array("user_id" => $online_users_data['user_id'], "user_name" => $online_users_data['user_name'], "user_level" => $online_users_data['user_level']);
		}
		$total_online = $online_guests+count($online_users);
		list($max_online, $max_online_time) = explode(":", $stats['max_online_users']);
		if($total_online > $max_online){
			$stats['max_online_users'] = $total_online.":".time();
			update_stats();
			$max_online = $total_online;
			$max_online_time = time();
		}
		
		opentable($locale['forum_ext_title_forum']);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_ext_forum'>\n\t<tr>
			<th class='' width='1%' style='white-space: no-wrap;' rowspan='4'><img style='max-width:initial' alt='".$locale['forum_ext_stats']."' src='".$dir."images/forum_stats.png' /></th></tr>";
		if($options['forum_stats'])	{
			echo "
			<tr>
				<td class='tbl1'>
					<strong>".number_format($threads)."</strong> ".$locale['forum_ext_threads']." ::
					<strong>".number_format($posts)."</strong> ".$locale['forum_ext_posts']." ::
					<strong>".$threadspday."</strong> ".$locale['forum_ext_threadspday']." ::
					<strong>".$postspday."</strong> ".$locale['forum_ext_postspday']."
				</td>
			</tr>\n";
		}
		if($options['top_posters'])	{
			echo "<tr>
				<td class='tbl2'>
					".$locale['forum_ext_topposter'].": <a href='".BASEDIR."profile.php?lookup=".$tposter_id."'>".$tposter_name."</a> (".$tposter_posts." ".$locale['forum_ext_posts'].") ::
					".$locale['forum_ext_actposter'].": <a href='".BASEDIR."profile.php?lookup=".$aposter_id."'>".$aposter_name."</a> (".round($aposter_ppday, 2)." ".$locale['forum_ext_postspday'].")
				</td>
			</tr>\n";
		}
		if($options['user_stats']){
			echo "<tr>
				<td class='tbl1'>
				".$locale['forum_ext_total_users'].": <strong>".number_format($total_users)."</strong> :: ".$locale['forum_ext_newest_member'].": <a href='".BASEDIR."profile.php?lookup=".$newest_member['id']."'>".$newest_member['name']."</a><br/>
				".$locale['forum_ext_users_online'].": <strong>$online_guests</strong> ".$locale['forum_ext_guests'].", <strong>".count($online_users)."</strong> ".$locale['forum_ext_members'].":\n ".user_list(0, $online_users)."<br/>
				".sprintf($locale['forum_ext_max_online_users'], "<strong>".$max_online."</strong>", showdate("forumdate", $max_online_time))."
				</td>
			</tr>\n";
		}
		echo "</table>\n";
		closetable();
	
	}elseif(stristr($url, $places['thread']) && $options['similar_threads']){
		
		$thread_id = isnum($_GET['thread_id']) ? $_GET['thread_id'] : 0;
		
		if($thread_id){
			list($thread_subject) = dbarraynum(dbquery("SELECT thread_subject from ".DB_THREADS." WHERE thread_id=".$thread_id.""));
			
			$rel_thread_res = dbquery("
			SELECT tt.thread_id, tt.thread_subject, tf.forum_id, tf.forum_name, tf.forum_access, tt.thread_postcount, tt.thread_lastpost
			FROM ".DB_THREADS." tt
			INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
			WHERE MATCH (thread_subject) AGAINST ('".$thread_subject."' IN BOOLEAN MODE) AND thread_id != ".$thread_id." AND ".groupaccess('tf.forum_access')." ORDER BY tt.thread_lastpost DESC LIMIT 5");
			
			if(dbrows($rel_thread_res)){
				opentable($locale['forum_ext_title_thread']);
				echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_ext_thread'>\n
					<tr>
						<th class='forum-caption'>".$locale['global_044']."</th>
						<th class='forum-caption'>".$locale['global_048']."</th>
						<th class='forum-caption'>".$locale['global_046']."</th>
						<th class='forum-caption'>".$locale['global_047']."</th>
					</tr>\n";
				$i = 0;
				while($thread = dbarray($rel_thread_res)){
					$i++; $row = $i%2 ? " class='tbl1'" : " class='tbl2'";
					echo "
					<tr>
						<td class='tbl".$row."'><a href='".FUSION_SELF."?thread_id=".$thread['thread_id']."'>".$thread['thread_subject']."</a></td>
						<td class='tbl".$row."'>".$thread['forum_name']."</td>
						<td class='tbl".$row."'>".$thread['thread_postcount']."</td>
						<td class='tbl".$row."'>".showdate("forumdate", $thread['thread_lastpost'])."</td>
					</tr>";
				}
				
				echo "</table>";
				closetable();
			}
		}
	
	}elseif(stristr($url, $places['profile']) && $options['profile_panel'] && isset($_GET['lookup'])){
		
		$user_id = isnum($_GET['lookup']) ? $_GET['lookup'] : 0;
		
		if($user_id){
			list($name, $posts, $age) = dbarraynum(dbquery("SELECT user_name, user_posts, user_joined FROM ".DB_USERS." WHERE user_id=".$user_id));
			$posts = empty($posts) ? 0 : $posts;
			list($threads) = dbarraynum(dbquery("SELECT COUNT(thread_id) FROM ".DB_THREADS." WHERE thread_author=".$user_id));
			$threads = empty($threads) ? 0 : $threads;
			
			$threadspday = almost_null($threads/((time() - $age)/(3600*24)));
			$postspday = almost_null($posts/((time() - $age)/(3600*24)));
			
			list($ranked_higher) = dbarraynum(dbquery("SELECT COUNT(user_id) FROM ".DB_USERS." WHERE user_posts>".$posts));
			$rank = $ranked_higher+1;
			list($allposts) = dbarraynum(dbquery("SELECT SUM(forum_postcount) FROM ".DB_FORUMS));
			$percentage = empty($posts) || empty($allposts) ? 0 : ($posts*100.0)/$allposts;
			$percentage = almost_null($percentage);
			
		
			opentable(sprintf($locale['forum_ext_title_profile'], $name));
				echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_ext_profile'>\n\t<tr>
					<th class='forum-caption' width='1%' style='white-space: nowrap;' rowspan='2'><img alt='".$locale['forum_ext_stats']."' src='".$dir."/images/forum_stats.png' /></th>
					<td class='tbl1'>
						".number_format($threads)." ".$locale['forum_ext_threads']." ::
						".number_format($posts)." ".$locale['forum_ext_posts']." ::
						".$threadspday." ".$locale['forum_ext_threadspday']." ::
						".$postspday." ".$locale['forum_ext_postspday']."
					</td>
				</tr>
				<tr>
					<td class='tbl1'>
						".sprintf($locale['forum_ext_ranking'], $name, number_format($rank), $percentage)."
					</td>
				</tr>
			</table>";
			foreach(array("threads", "posts") as $type){
				$other_type = $type=="threads"? "posts" : "threads";
				if($type == "threads"){
					if(!isset($_GET['show']) || (isset($_GET['show']) && $_GET['show'] != "posts")){
						$visibility = "";
					}else{
						$visibility = "style='display: none;'";
					}
				}else{
					if(isset($_GET['show']) && $_GET['show'] == "posts"){
						$visibility = "";
					}else{
						$visibility = "style='display: none;'";
					}
				}
				if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
				$where = $type == "threads" ? "tt.thread_author='$user_id' GROUP BY tt.thread_id" : "tp.post_author='".$user_id."'";
				
				$rows_res = dbquery("SELECT post_id FROM ".DB_POSTS." tp
				INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
				INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
				WHERE ".groupaccess('tf.forum_access')." AND $where
				ORDER BY tp.post_datestamp DESC");
			
				$result = dbquery("SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
				tf.forum_name, tf.forum_access, tt.thread_subject
				FROM ".DB_POSTS." tp
				INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
				INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
				WHERE ".groupaccess('tf.forum_access')." AND $where
				ORDER BY tp.post_datestamp DESC LIMIT ".$_GET['rowstart'].",10");
				
				echo "<script type='text/javascript'>
				$(document).ready(function(){
					$('#forum_panel_".$other_type."_toggle').click(function() {
						$('#forum_panel_".$other_type."').show();
						$('#forum_panel_".$type."').hide();
						return false;
					});
				});</script>
				<div id='forum_panel_".$type."' ".$visibility.">";
				

				if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
				echo "
				<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n\t<tr>
					<th class='forum-caption' style='font-size:12px;'>".$locale['forum_ext_recent_'.$type]." <a href='".FUSION_SELF."?lookup=".$user_id."&amp;show=".$other_type."' id='forum_panel_".$other_type."_toggle'>".$locale['forum_ext_recent_show_'.$other_type.'']."</a></th>
					<th class='forum-caption' style='font-size:12px;'>".$locale['global_048']."</th>
					<th class='forum-caption' style='font-size:12px;'>".$locale['global_047']."</th>
				</tr>\n";
				$rows = dbrows($rows_res);
				if ($rows) {
					$i=0;
					while ($data = dbarray($result)) {
						$i++; $row = $i%2 ? "class='tbl1'" : "class='tbl2'";
						echo "<tr>\n\t<td width='100%' $row><a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".trimlink($data['thread_subject'], 40)."</a></td>
						<td width='1%' style='white-space:nowrap' $row>".trimlink($data['forum_name'], 30)."</td>
						<td align='center' width='1%' style='white-space:nowrap' $row>".showdate("forumdate", $data['post_datestamp'])."</td>\n</tr>\n";
					}
					if ($rows > 10){
						echo "<tr><td class='tbl2' colspan='3'><div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 10, $rows, 3, FUSION_SELF."?lookup=".$_GET['lookup']."&amp;show=$type&amp;")."\n</div></td></tr>\n";
					}
				} else {
					echo "<tr><td colspan='3' style='text-align:center' class='tbl1'>\n".$locale['forum_ext_no_'.$type]."</td></tr>\n";
				}
				echo "</table>\n";
				echo "</div>\n";
			}
			closetable();
		}
	}elseif(stristr($url, $places['reply']) && $options['thread_preview']){
		
		$thread_id = isnum($_GET['thread_id']) ? $_GET['thread_id'] : 0;
		
		if($thread_id){
			$posts_res = dbquery(
				"SELECT p.*, u.*, u2.user_name AS edit_name
				FROM ".DB_POSTS." p
				LEFT JOIN ".DB_USERS." u ON p.post_author = u.user_id
				LEFT JOIN ".DB_USERS." u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
				WHERE p.thread_id='".$thread_id."' ORDER BY post_datestamp LIMIT 20"
			);
			
			opentable($locale['forum_ext_title_reply']);
			echo "<div style='max-height: 600px; overflow: auto;'><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_ext_reply'>\n\t";
			$i = 0;
			while($post_data = dbarray($posts_res)){
				$i++;
				$class = $i%2 == 0 ? "tbl1" : "tbl2";
				echo "<tr><td rowspan='2' valign='top' class='$class' width='1%' style='white-space: nowrap;'>
						<a style='font-weight:bold;' href='".BASEDIR."profile.php?lookup=".$post_data['user_id']."'>".$post_data['user_name']."</a><br/>
					</td><td class='$class'>
						".showdate("forumdate", $post_data['post_datestamp'])."
					</td></tr><tr>
					<td class='$class'>
						".nl2br(parseubb($post_data['post_message']))."
					</td></tr>";
			}
			echo "</table></div>";
			closetable();
		}
	}elseif((stristr($url, $places['postify']) || (stristr($url, $places['edit']) && isset($_POST['savechanges']))) && $options['skip_postify']){
		if(isset($_GET['thread_id']) && isnum($_GET['thread_id'])){
			$url = "viewthread.php?forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id'];
			if(isset($_GET['post_id']) && isnum($_GET['post_id']) && !isset($_POST['delete'])){
				$url .= "&pid=".$_GET['post_id']."#post_".$_GET['post_id'];
			}
			$skip_postify_redirect_url = $url;
			function skip_postify($output){
				global $skip_postify_redirect_url;
				redirect($skip_postify_redirect_url);
			}
			add_handler("skip_postify");
		}
	}
	
	if((stristr($url, $places['index']) || stristr($url, $places['forum']) || stristr($url, $places['thread'])) && $options['forum_observer']){
		$user_id = iMEMBER ? $userdata['user_id'] : USER_IP;
		$forum_id = "";
		$thread_id = "";
		if(stristr($url, $places['index'])){
			$forum_id = 0;
			$thread_id = 0;
		}elseif(stristr($url, $places['forum']) && isset($_GET['forum_id']) && isnum($_GET['forum_id'])){
			$forum_id = $_GET['forum_id'];
			$thread_id = 0;
		}elseif(stristr($url, $places['thread']) && isset($_GET['thread_id']) && isnum($_GET['thread_id'])){
			list($forum_id) = dbarraynum(dbquery("SELECT forum_id FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."'"));
			$thread_id = $_GET['thread_id'];
		}
		if(isnum($forum_id) && isnum($thread_id)){
			dbquery("REPLACE INTO ".DB_FORUM_OBSERVER." SET user_id='$user_id', forum_id='$forum_id', thread_id='$thread_id', age='".time()."'");
		}
		dbquery("DELETE FROM ".DB_FORUM_OBSERVER." WHERE age < (".time()."-5*60)");
		
		add_handler("forum_observer");
	}
}
?>