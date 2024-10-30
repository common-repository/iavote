=== Plugin Name ===

Contributors: iafelipe
Tags: vote posts, vote, vote pages, vote comments, iavote
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 3.2

IaVote enables bloggers to add voting functionality to their posts. Include 2 widgets: resume votes and most voted.

== Description ==

IaVote enables bloggers to add voting functionality to their posts. Include 2 widgets: resume votes and most voted.

Features:

* Vote for the best and worst
* Length of time to vote
* Dates of the votes
* Selection of votes: day, week, month and all
* Custom Pages listings votes
* Language options



For more information see: http://www.informaticaautonomos.com/aplicaciones/iavote.php


== Installation ==

1. Install plugin via admin panel

2. Add in your template files, in index.php and single.php: 

<?=iavote_showbuttons(get_the_ID())?>

3. Edit file iavote_options.php for options

4. Edit ia-vote/iavote.css for editing your own style.



Adittional functions
=====================

Widget 1: total of votes in week, month, day and forever

Add in wordpress panel widged or insert this code in your theme: 

<?php widget_iavote()?>

----------------------------

Widget 2: Resume pages

1- create the diferent pages in wordpress: latest, random, mostComment, lessVoted, mostVoted
2- set the id's in iavote_options.php
3- Put this code in index.php:
			
			global $options;
			widget_iavote_pages(
				$options['widget-pages']['latest'], $options['widget-pages']['random'], 
				$options['widget-pages']['mostComment'], $options['widget-pages']['lessVoted'],
				$options['widget-pages']['mostVoted']
			);

4- insert in page.php before: <?php get_header(); ?>
			<?php
			include('wp-content/plugins/ia-vote/page_iavote.php');
			if ( !$page_iavote ) {
			?>

and before <?php get_footer(); ?> insert:
			<?php
			}
			?>



			



