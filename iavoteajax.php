<?php

//save votes in the DB
include_once('iavote_options.php');

//change this for Interval seconds to vote
$seconds = $options['vote-seconds-interval'];

include_once("../../../wp-blog-header.php");

if ( !empty($_POST['id']) && ($_POST['type'] == 0 or $_POST['type'] == 1) ) {
	
	global $wpdb;
	
	$userid = getUserId();	
	
	//vote in X time for the current post and current user
	$query = "select count(*) from ".$wpdb->prefix."iavotes where 
	user = '$userid' and
	post_id = '".addslashes($_POST['id'])."' and
	
	TIMESTAMPDIFF(SECOND,timestamp, NOW()) <= $seconds
	
	";
	//echo $query;
	//$query = "select count(*) from wp_comments";
	
	$count = $wpdb->get_var($wpdb->prepare($query));
	
	if ( $count == 0 ) {//ok vote!
		$wpdb->query($wpdb->prepare("insert into ".$wpdb->prefix."iavotes
		(post_id, user, type)
		values
		('".addslashes($_POST['id'])."', '$userid', '".addslashes($_POST['type'])."')
		"));
		if ( $_POST['type'] == 1 ) echo "ia-vote:TRUE";
		else if ( $_POST['type'] == 0 ) echo "ia-vote:FALSE";
	}
}


?>