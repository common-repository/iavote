<?php

function iavote_InstallWidget(){
	register_sidebar_widget(__('Ia-Votes'), 'widget_iavote');
	add_action("plugins_loaded", "iavote_init");
}

function iavote_dbinstall(){
	global $wpdb;
	$table_name = $wpdb->prefix.'iavotes';
	
	if($wpdb->get_var("SHOW TABLES LIKE '".$wpdb->prefix."iavotes'") != $table_name) {
		$query ="
			CREATE TABLE IF NOT EXISTS `wp_iavotes` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `post_id` int(11) NOT NULL,
			  `user` varchar(250) NOT NULL,
			  `type` int(11) NOT NULL,
			  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;				
		";		
		$wpdb->query($query);
		$wpdb->query($query2);
	}	
}