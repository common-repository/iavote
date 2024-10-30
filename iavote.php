<?php
/*
Plugin Name: IaVote
Plugin URI: http://www.informaticaautonomos.com/aplicaciones/iavote.php
Description: IaVote enables bloggers to add voting functionality to their posts.
Author: Felipe Gonzalez Lopez
Version: 1.0
Author URI: http://www.informaticaautonomos.com
*/

/*  Copyright 2011  Felipe Gonzalez Lopez  (email : reg@informaticaautonomos.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once('iavote_install.php');
include_once('iavote_options.php');
//install Options:
iavote_InstallWidget();
iavote_dbinstall();


function iavote_header() {
	?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>    
	<script type="text/javascript" src="<?php echo iavote_getpath(); ?>/iavote.js"></script>    
    <link rel="stylesheet" type="text/css" href="<?=iavote_getpath()?>/iavote.css" />
	<?php	
}

function iavote_showbuttons($id_post) {
	global $wpdb;
	global $options;
	
	//total votes
	$query = "select count(*) from ".$wpdb->prefix."iavotes where 
	post_id = '".addslashes($id_post)."' and type = 1";
	$count_ok = $wpdb->get_var($wpdb->prepare($query));
	
	//total votes
	$query = "select count(*) from ".$wpdb->prefix."iavotes where 
	post_id = '".addslashes($id_post)."' and type = 0";
	$count_ko = $wpdb->get_var($wpdb->prepare($query));	
	
	//vote in X time for the current post and current user
	$userid = getUserId();
	$seconds = $options['vote-seconds-interval'];
	$query = "select count(*) as count, type from ".$wpdb->prefix."iavotes where 
	user = '$userid' and
	post_id = '".addslashes($id_post)."' and
	
	TIMESTAMPDIFF(SECOND,timestamp, NOW()) <= $seconds	
	";
//	echo $query;
	$r = $wpdb->get_row($wpdb->prepare($query));

	$count2 = (array) $r;
	$type = $count2['type'];
	$count2 = $count2['count'];
	
	?>
    <ul>
    <?
	if ( $count2 == 0  ) {//ok vote	
		//OK?>            
        <li><div class="vote-ok">UP
        
		<a class="linkvote-ok" id="linkiavoteup<?=$id_post?>"  href="javascript:iavote('<?=$id_post?>', 1, '<?=iavote_getpath()?>')">
		<span id="iavoteup<?=$id_post?>"><?=$count_ok?></span>
		</a>
        
        </div></li>
        
        <?php 
		//KO
		?>        
        <li><div class="vote-ko">DOWN
		<a class="linkvote-ko" id="linkiavotedown<?=$id_post?>" href="javascript:iavote('<?=$id_post?>', 0, '<?=iavote_getpath()?>')">
		<span id="iavotedown<?=$id_post?>"><?=$count_ko?></span>
		</a>        
        </div></li>
        
		<?php
	}
	else {//no vote
		if ( $type == 1 ) {
			?>
            <li><div class="vote-ok">UP    
            <a href="javascript:void(0)"><span id="novote-ok"><?=$count_ok?></span></a>                
            </div></li>
            <li><div class="vote-ko">DOWN
            <a id="cursor-of" href="javascript:void(0)"><?=$count_ko?></a>
            </div></li>            
            <?php           
		}
		else {
			?>
            <li><div class="vote-ok">UP  
            <a id="cursor-of" href="javascript:void(0)"><?=$count_ok?></a>                
            </div></li>
            <li><div class="vote-ko">DOWN
            <a href="javascript:void(0)"><span id="novote-ko"><?=$count_ko?></span></a>
            </div></li>            
            <?php
		}
		?>   
		<?php			
	}
	
	?>
    
    <li><div class="comment">TOTAL COMMENTS <span><?php comments_number('0', '1', '%')?></span></div></li>	
	</ul>
    
	<?
	
}

//mode -> SECOND / MONTH / WEEK
//Type -> 1 or 2
//TIme 86400 for arg SECOND and month, week -> 1
//limit -> limit of results
//$str -> string to show in text
function show_most_voted($mode, $type, $time, $limit, $str) {
	global $wpdb;
	global $options;
    
	if (function_exists('add_theme_support')) {
		 add_theme_support('post-thumbnails');
	}	
	
	if ( $mode == 'ALL' ) {
		$query = "
		select 
		count(post_id) as count,post_id
		from 
		".$wpdb->prefix."iavotes,".$wpdb->prefix."posts	
		
		where 
		".$wpdb->prefix."posts.ID = ".$wpdb->prefix."iavotes.post_id and
		post_status = 'publish' and
		
		type = '$type' ";		
	}
	else {
		$query = "
		select 
		count(post_id) as count,post_id
		from 
		".$wpdb->prefix."iavotes,".$wpdb->prefix."posts	
		
		where 
		".$wpdb->prefix."posts.ID = ".$wpdb->prefix."iavotes.post_id and
		post_status = 'publish' and
		type = '$type' and	
		TIMESTAMPDIFF($mode,timestamp, NOW()) <= $time ";
		
	}
	
	$query .= "group by post_id order by 1 DESC limit $limit";
	//echo $query;
	//echo "<br><br>";
	
	$r = $wpdb->get_results($wpdb->prepare($query), 'ARRAY_A');
	
	if ( count($r) > 0 ) {
		
		$str_title = '';

		switch($mode) {

			case 'ALL':
			$str_title = $options['widget-forever'];
			break;
			case 'SECOND':
			$str_title = $options['widget-day'];
			break;
			case 'WEEK':
			$str_title = $options['widget-week'];
			break;
			case 'MONTH':
			$str_title = $options['widget-month'];
			break;
		}
		
	
		?>
        
            <div class="tabbertab">
            <h2><?=$str_title?></h2>    
		<?php
		
   if (function_exists('add_theme_support')) {
         add_theme_support('post-thumbnails');
    }    
			
		for($i=0;$i<count($r);$i++) {		
			$post = get_post($r[$i]['post_id']); 
			$post_title = $post->post_title;
			$post_link = get_page_link($r[$i]['post_id']);
			
//echo $r[$i]['post_id'];
			?>
                 <div class="right-list">
       		   		<span>
                    <?=get_the_post_thumbnail($r[$i]['post_id'], 
					array($options['widget-imagesize']['width'],$options['widget-imagesize']['height']));?>                    
                    </span>
                    <h3><a href="<?=$post_link?>"><?=$post_title?></a></h3>
                    <a href="<?=$post_link?>">ver más...</a>
                    <div class="clear"></div>
                    <div class="numbers">
                    	<p><?=$i+1?></p>
                    </div>
               	</div>

	    	<?php        
		}
		?></div><?php		
	}
}

function widget_iavote() {
	global $options;
	?>
    <div class="wagc-panel">
    <h5 class="top-wagc img"><?=$options['widget-title']?></h5>
    <div class="tabber">    
	<?php
	show_most_voted('ALL', 1, -1, $options['widget-results'], $options['widget-day']);
	
	show_most_voted('SECOND', 1, 86400, $options['widget-results'], $options['widget-day']);
	
	show_most_voted('WEEK', 1, 1, $options['widget-results'], $options['widget-week']);	
	
	show_most_voted('MONTH', 1, 1, $options['widget-results'], $options['widget-month']);	
	
	?>
    </div>
	</div>
	<?php

}
//arguments: the id of the posts pages
function widget_iavote_pages($latest, $random, $mostComment, $lessVoted, $mostVoted){
	?>
        <div class="nav">
            <ul>
                <li><a href="<?php echo get_page_link($latest); ?>">lo último</a></li>
                <li><a href="<?php echo get_page_link($mostVoted); ?>">lo mejor</a></li>
                <li><a href="<?php echo get_page_link($lessVoted); ?>">lo peor</a></li>
                <li><a href="<?php echo get_page_link($random); ?>">¡bah!</a></li>
                <li><a href="<?php echo get_page_link($mostComment); ?>">lo más comentado</a></li>
            </ul>
        </div>
<?php    
}
function iavote_init() {
}
function iavote_getpath() {
	$dir = dirname(__FILE__);
	$dir = str_replace("\\", "/", $dir);
	
	$base = ABSPATH;
	$base = str_replace("\\", "/", $base);
	$edir = str_replace($base, "", $dir);
	$edir = get_bloginfo('url')."/".$edir;
	$edir = str_replace("\\", "/", $edir);
	
	return $edir;	
}

//Runs the plugin
add_action('wp_head', 'iavote_header');


?>