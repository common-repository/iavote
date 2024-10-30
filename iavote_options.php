<?php
//options of the plugin

//interval in seconds, for votes by user. Default 24h -> 86400 seconds
$options['vote-seconds-interval'] = 86400;

//widget1
$options['widget-title'] = 'Top Votes';
$options['widget-results'] = 5;
$options['widget-day'] = 'Today';
$options['widget-month'] = 'This Month';
$options['widget-week'] = 'This Week';
$options['widget-forever'] = 'Forever';
$options['widget-imagesize']['width'] = 50;
$options['widget-imagesize']['height'] = 50;

//widget2
//set the ids of the static pages
$options['widget-pages-limit'] = 10;
$options['widget-pages']['latest'] = 2;
$options['widget-pages']['random'] = 8;
$options['widget-pages']['mostComment'] = 57;
$options['widget-pages']['lessVoted'] = 63;
$options['widget-pages']['mostVoted'] = 52;
$options['widget-pages']['latest-text'] = 'Latest';
$options['widget-pages']['random-text'] = 'Random';
$options['widget-pages']['mostComment-text'] = 'Most commented';
$options['widget-pages']['lessVoted-text'] = 'The worst';
$options['widget-pages']['mostVoted-text'] = 'The best';


//type of register user
function getUserId() {
//	return md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	return md5($_SERVER['REMOTE_ADDR']);
}
