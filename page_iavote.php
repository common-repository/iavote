<?php

$query = '';
global $options;
foreach ( $options['widget-pages'] as $key => $value ) {
	if ( get_the_ID() == $value ) {
		switch($key) {
			//latest articles
			case 'latest':
			$query = "select ".$wpdb->prefix."posts.ID 
			from ".$wpdb->prefix."posts	
			where 
			post_status = 'publish' and
			post_type = 'post'
			order by post_date  DESC
			limit ".$options['widget-pages-limit'];			
			break;
			case 'random':
			$query = "select ".$wpdb->prefix."posts.ID
			from ".$wpdb->prefix."posts	
			where 
			post_status = 'publish' and
			post_type = 'post'
			order by rand()
			limit ".$options['widget-pages-limit'];				
			break;
			case 'lessVoted':
			$query = "select ".$wpdb->prefix."posts.ID
			 from ".$wpdb->prefix."iavotes,".$wpdb->prefix."posts	
			where 
			".$wpdb->prefix."posts.ID = ".$wpdb->prefix."iavotes.post_id and
			post_status = 'publish'	and 
			type = 0 and
			post_type = 'post'
			group by post_id order by count(post_id) DESC
			limit ".$options['widget-pages-limit'];						
			break;
			case 'mostVoted':
			$query = "select ".$wpdb->prefix."posts.ID
			from ".$wpdb->prefix."iavotes,".$wpdb->prefix."posts	
			where 
			".$wpdb->prefix."posts.ID = ".$wpdb->prefix."iavotes.post_id and
			post_status = 'publish'	and 
			post_type = 'post' and
			type = 1
			group by post_id order by count(post_id) DESC
			limit ".$options['widget-pages-limit'];									
			break;
			case 'mostComment':
			$query = "select ".$wpdb->prefix."posts.ID
			from ".$wpdb->prefix."comments,".$wpdb->prefix."posts
			where 
			".$wpdb->prefix."posts.ID = ".$wpdb->prefix."comments.comment_post_ID and
			post_status = 'publish'	and 
			post_type = 'post'
			group by comment_post_ID order by count(comment_post_ID) DESC
			limit ".$options['widget-pages-limit'];		
			break;
		}
	}
}



if ( empty($query) ) $page_iavote = false;
else {
	$page_iavote = true;
	
	//echo $query;
	$r = $wpdb->get_results($wpdb->prepare($query), 'ARRAY_A');
	$posts = array();

	$total_count = count($r);
	$half_count = round ($total_count / 2);					

	
	echo '<div class="thumb-panel-1">'; 
	for($i=0;$i<count($r);$i++) {		
		
		if ( $i == $half_count  ) {
			echo '</div><div class="thumb-panel-2">';
		}
	
		
		$wp_query =  new WP_Query( 'p='.$r[$i]['ID'] );
		
		//1 solo post
		while( $wp_query->have_posts() ) : $wp_query->the_post();
		
			?>
                <div class="thumb-ad">
                    <div class="thumb-img">
                        <?php the_post_thumbnail( 'thumb_246' ); ?>
                    </div>
                    <div class="thumb-desc">
                        <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="" ><?php echo ($wp_query->post_count / 2); the_title(); ?></a></h2>   
                        <p class="vermas"><a href="<?php the_permalink(); ?>">ver m&aacute;s...</a> <span><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php printf( __( 'Por %s'), get_the_author() ); ?></a></span></p>                                
                        <div class="clear"></div>
                    </div>
                    <div class="rating">
                    <?=iavote_showbuttons(get_the_ID())?>
                      <div class="clear"></div>
                    </div>
                </div>  

		<?		
		endwhile;
		
	}	
	?></div><?
}
	

