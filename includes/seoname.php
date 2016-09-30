      <?
      /*****************************************************************
      * seoname.php
      * -------------------
      * This file is part of AMAZECS
      *
      * @package : amazecs
      * @purpose : URL re-write functions
      * @copyright : (C) 2004,2006 Len Johnson
      * @mail : GB WEBS, 4 Leominster Walk,
      * West Midlands, B45 9SW, UK
      * @support : http://www.web-bureau.com/forum/
      * @version : $Id: seoname.php,v 1.0.1 $
      * @license : http://opensource.org/licenses/gpl-license.php
      *
      *****************************************************************/
      /*****************************************************************
      * GNU Public License
      * This program is free software; you can redistribute it and/or modify it
      * under the terms of the GNU General Public License as published by
      * the Free Software Foundation; either version 2 of the License,
      * or (at your option) any later version.
      *
      * This program is distributed in the hope that it will be useful,
      * but WITHOUT ANY WARRANTY; without even the implied warranty
      * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
      *****************************************************************/
      /**
      * CHANGELOG
      *
      * 26.06.06 added seonewsopts function
      */

      function seoname($seoit) {
      // $seoit = strip_tags($seoit);
      $seoit = str_replace('=',' ',$seoit);
      $seoit = str_replace('/',' ',$seoit);
      $seoit = str_replace('\\',' ',$seoit);
      $seoit = str_replace('@',' ',$seoit);
      $seoit = str_replace('~',' ',$seoit);
      $seoit = str_replace('!',' ',$seoit);
      $seoit = str_replace('�',' ',$seoit);
      $seoit = str_replace('?',' ',$seoit);
      $seoit = str_replace('!',' ',$seoit);
      $seoit = str_replace('$',' ',$seoit);
      $seoit = str_replace('%',' ',$seoit);
      $seoit = str_replace('^',' ',$seoit);
      $seoit = str_replace('*',' ',$seoit);
      $seoit = str_replace('_',' ',$seoit);
      $seoit = str_replace('{',' ',$seoit);
      $seoit = str_replace('}',' ',$seoit);
      $seoit = str_replace('[',' ',$seoit);
      $seoit = str_replace(']',' ',$seoit);
      $seoit = str_replace('-',' ',$seoit);
      $seoit = str_replace(' & ',' ',$seoit);
      $seoit = str_replace('"',' ',$seoit);
      $seoit = str_replace('.',' ',$seoit);
      $seoit = str_replace('\'',' ',$seoit);
      $seoit = str_replace(',',' ',$seoit);
      $seoit = str_replace(' ','-',$seoit);
      $seoit = str_replace(' ','-',$seoit);
      $seoit = str_replace(' ','-',$seoit);
      $seoit = str_replace(':','',$seoit);
      $seoit = str_replace('#','',$seoit);
      $seoit = str_replace('(','',$seoit);
      $seoit = str_replace(')','',$seoit);
      $seoit = str_replace('---','-',$seoit);
      $seoit = str_replace('--','-',$seoit);
      $seoit = strtolower($seoit);
      return $seoit;
      }

      /*
      * modified newsopts function from
      * php fusion 6.01.2 by Nick Jones
      */
      function seonewsopts($info,$sep,$class="") {
      global $locale; $res = "";
      $link_class = $class ? " class='$class' " : "";
      if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='".seoname($info['news_subject'])."-rn".$info['news_id'].".htm'".$link_class.">".$locale['042']."</a> ".$sep." ";
      if ($info['news_allow_comments']) $res .= "<a href='".seoname($info['news_subject'])."-rn".$info['news_id'].".htm'".$link_class.">".$info['news_comments'].$locale['043']."</a> ".$sep." ";
      if ($info['news_ext'] == "y" || $info['news_allow_comments']) $res .= $info['news_reads'].$locale['044']."\n";
      $res .= $sep." <a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>\n";
      return $res;
      }
      ?>
