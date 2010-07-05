<?php
/*
Plugin Name: Short Title Link
Plugin URI: http://blog.lunatic-code.net/wp_plugin/
Description: Truncate navigation link
Author: Masaki Komagata <komagata@fjord.jp>
Version: 0.3
Author URI: http://fjord.jp/
*/

/*
Original: http://blog.lunatic-code.net/webdesign/php/28/

USAGE

    <?php previous_post_link_short() ?>
    <?php next_post_link_short() ?>
*/

// Creating short title
function create_short_title($title_short,$length){
	$ls_search = array(	"&quot;",	//	"
						"&#34;",	//	"
						"&#x22;",	//	"
						"&lt;",		//	<
						"&#60;",	//	<
						"&#x3C;",	//	<
						"&gt;",		//	>
						"&#62;",	//	>
						"&#x3E;",	//	>
						"&#8211;",	//	-
						"&amp;",	//	&
						"&#38;",	//	&
						"&#x26;");	//	&
						
	$ls_replace = array(	'"',		//	&quot;
						'"',		//	&quot;
						'"',		//	&quot;
						'<',		//	&lt;"
						'<',		//	&lt;"
						'<',		//	&lt;"
						'>',		//	&gt;"
						'>',		//	&gt;"
						'>',		//	&gt;"
						"-",		//	&#8211;-
						'&',		//	&amp;"
						'&',		//	&amp;"
						'&');		//	&amp;"
	
	$ls_research = array(	'&',		//	&amp;"
						'"',		//	&quot;
						"&#8211; ",	//	-
						'<',		//	&lt;"
						'>');		//	&gt;"
						
	$ls_rereplace = array(	"&amp;",	//	&
						"&#8211; ",	//	-
						"&quot;",	//	"
						"&lt;",		//	<
						"&gt;");	//	>

	$new_title = str_replace($ls_search, $ls_replace, $title_short);

	$len = mb_strwidth($new_title);						// バイト数を調べる
	if( $len>$length ){									// 
		$title_short = mb_strimwidth($new_title , 0, $length, "...");
		$new_title_short = $title_short;
	}
	else{
		$new_title_short = $new_title ;
	}
	
	
	$short_title = str_replace($ls_research, $ls_rereplace, $new_title_short);

	return $short_title;
}

// Prev
function previous_post_link_short($format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '', $length = 32) {

	if ( is_attachment() )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_previous_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;

	$title = apply_filters('the_title', $post->post_title, $post);

	$short_title = create_short_title($title,$length);

	$string = '<a href="'.get_permalink($post->ID).'"' . ' title="' . $title . '">';
	$link = str_replace('%title', $short_title, $link);
	$link = $pre . $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	echo $format;
}

// Next
function next_post_link_short($format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '', $length = 32) {

	$post = get_next_post($in_same_cat, $excluded_categories);

	if ( !$post )
		return;

	$title = apply_filters('the_title', $post->post_title, $post);

	$short_title = create_short_title($title,$length);

	$string = '<a href="'.get_permalink($post->ID).'"' . ' title="' . $title . '">';
	$link = str_replace('%title', $short_title, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	echo $format;
}
?>
