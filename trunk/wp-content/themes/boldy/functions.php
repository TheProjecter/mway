<?php

/*******************************
 MENUS SUPPORT
********************************/
if ( function_exists( 'wp_nav_menu' ) ){
	if (function_exists('add_theme_support')) {
		add_theme_support('nav-menus');
		add_action( 'init', 'register_my_menus' );
		function register_my_menus() {
			register_nav_menus(
				array(
					'main-menu' => __( 'Main Menu' )
				)
			);
		}
	}
}

if ( function_exists( 'wp_nav_menu' ) ){
	if (function_exists('add_theme_support')) {
		add_theme_support('nav-menus');
		add_action( 'init', 'register_my_menus_middle' );
		function register_my_menus_middle() {
			register_nav_menus(
				array(
					'middle-menu' => __( 'Middle Menu' )
				)
			);
		}
	}
}


/* CallBack functions for menus in case of earlier than 3.0 Wordpress version or if no menu is set yet*/

function primarymenu(){ ?>
			<div id="mainMenu" class="ddsmoothmenu">
				<ul>
					<?php wp_list_pages('title_li='); ?>
					<?php wp_list_categories('hide_empty=1&exclude=1&title_li='); ?>
				</ul>
			</div>
<?php }


function middlemenu(){ ?>
			<div id="middleMenu" id="middle">
				<ul id="d">
					<?php wp_list_pages('title_li='); ?>
					<?php wp_list_categories('hide_empty=1&exclude=1&title_li='); ?>
				</ul>
			</div>
<?php }

/*******************************
 THUMBNAIL SUPPORT
********************************/

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 300, 200, true );

/* Get the thumb original image full url */

function get_thumb_urlfull ($postID) {
$image_id = get_post_thumbnail_id($post);  
$image_url = wp_get_attachment_image_src($image_id,'large');  
$image_url = $image_url[0]; 
return $image_url;
}

/*******************************
 EXCERPT LENGTH ADJUST
********************************/

function home_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'home_excerpt_length');


/*******************************
 WIDGETS AREAS
********************************/

if ( function_exists('register_sidebar') )
register_sidebar(array(
	'name' => 'sidebar',
	'before_widget' => '<div class="rightBox">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
));

register_sidebar(array(
	'name' => 'footer',
	'before_widget' => '<div class="boxFooter">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
));
	
/*******************************
 PAGINATION
********************************
 * Retrieve or display pagination code.
 *
 * The defaults for overwriting are:
 * 'page' - Default is null (int). The current page. This function will
 *      automatically determine the value.
 * 'pages' - Default is null (int). The total number of pages. This function will
 *      automatically determine the value.
 * 'range' - Default is 3 (int). The number of page links to show before and after
 *      the current page.
 * 'gap' - Default is 3 (int). The minimum number of pages before a gap is 
 *      replaced with ellipses (...).
 * 'anchor' - Default is 1 (int). The number of links to always show at begining
 *      and end of pagination
 * 'before' - Default is '<div class="emm-paginate">' (string). The html or text 
 *      to add before the pagination links.
 * 'after' - Default is '</div>' (string). The html or text to add after the
 *      pagination links.
 * 'title' - Default is '__('Pages:')' (string). The text to display before the
 *      pagination links.
 * 'next_page' - Default is '__('&raquo;')' (string). The text to use for the 
 *      next page link.
 * 'previous_page' - Default is '__('&laquo')' (string). The text to use for the 
 *      previous page link.
 * 'echo' - Default is 1 (int). To return the code instead of echo'ing, set this
 *      to 0 (zero).
 *
 * @author Eric Martin <eric@ericmmartin.com>
 * @copyright Copyright (c) 2009, Eric Martin
 * @version 1.0
 *
 * @param array|string $args Optional. Override default arguments.
 * @return string HTML content, if not displaying.
 */
 
function emm_paginate($args = null) {
	$defaults = array(
		'page' => null, 'pages' => null, 
		'range' => 3, 'gap' => 3, 'anchor' => 1,
		'before' => '<div class="emm-paginate">', 'after' => '</div>',
		'title' => __('Pages:'),
		'nextpage' => __('&raquo;'), 'previouspage' => __('&laquo'),
		'echo' => 1
	);

	$r = wp_parse_args($args, $defaults);
	extract($r, EXTR_SKIP);

	if (!$page && !$pages) {
		global $wp_query;

		$page = get_query_var('paged');
		$page = !empty($page) ? intval($page) : 1;

		$posts_per_page = intval(get_query_var('posts_per_page'));
		$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
	}
	
	$output = "";
	if ($pages > 1) {	
		$output .= "$before<span class='emm-title'>$title</span>";
		$ellipsis = "<span class='emm-gap'>...</span>";

		if ($page > 1 && !empty($previouspage)) {
			$output .= "<a href='" . get_pagenum_link($page - 1) . "' class='emm-prev'>$previouspage</a>";
		}
		
		$min_links = $range * 2 + 1;
		$block_min = min($page - $range, $pages - $min_links);
		$block_high = max($page + $range, $min_links);
		$left_gap = (($block_min - $anchor - $gap) > 0) ? true : false;
		$right_gap = (($block_high + $anchor + $gap) < $pages) ? true : false;

		if ($left_gap && !$right_gap) {
			$output .= sprintf('%s%s%s', 
				emm_paginate_loop(1, $anchor), 
				$ellipsis, 
				emm_paginate_loop($block_min, $pages, $page)
			);
		}
		else if ($left_gap && $right_gap) {
			$output .= sprintf('%s%s%s%s%s', 
				emm_paginate_loop(1, $anchor), 
				$ellipsis, 
				emm_paginate_loop($block_min, $block_high, $page), 
				$ellipsis, 
				emm_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}
		else if ($right_gap && !$left_gap) {
			$output .= sprintf('%s%s%s', 
				emm_paginate_loop(1, $block_high, $page),
				$ellipsis,
				emm_paginate_loop(($pages - $anchor + 1), $pages)
			);
		}
		else {
			$output .= emm_paginate_loop(1, $pages, $page);
		}

		if ($page < $pages && !empty($nextpage)) {
			$output .= "<a href='" . get_pagenum_link($page + 1) . "' class='emm-next'>$nextpage</a>";
		}

		$output .= $after;
	}

	if ($echo) {
		echo $output;
	}

	return $output;
}

/**
 * Helper function for pagination which builds the page links.
 *
 * @access private
 *
 * @author Fahiz Mohamed <fahiz@netwidz.com>
 * @copyright Copyright (c) 2010, Fahiz Mohamed
 * @version 1.0
 *
 * @param int $start The first link page.
 * @param int $max The last link page.
 * @return int $page Optional, default is 0. The current page.
 */
function emm_paginate_loop($start, $max, $page = 0) {
	$output = "";
	for ($i = $start; $i <= $max; $i++) {
		$output .= ($page === intval($i)) 
			? "<span class='emm-page emm-current'>$i</span>" 
			: "<a href='" . get_pagenum_link($i) . "' class='emm-page'>$i</a>";
	}
	return $output;
}

function post_is_in_descendant_category( $cats, $_post = null )
{
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

/*******************************
 CUSTOM COMMENTS
********************************/

function mytheme_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
   	<div class="gravatar">
	 <?php echo get_avatar($comment,$size='50',$default='http://www.gravatar.com/avatar/61a58ec1c1fba116f8424035089b7c71?s=32&d=&r=G' ); ?>
	 <div class="gravatar_mask"></div>
	</div>
     <div id="comment-<?php comment_ID(); ?>">
	  <div class="comment-meta commentmetadata clearfix">
	    <?php printf(__('<strong>%s</strong>'), get_comment_author_link()) ?><?php edit_comment_link(__('(Edit)'),'  ','') ?> <span><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?>
	  </span>
	  </div>
	  
      <div class="text">
		  <?php comment_text() ?>
	  </div>
	  
	  <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.') ?></em>
         <br />
      <?php endif; ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php }

/*******************************
  THEME OPTIONS PAGE
********************************/

add_action('admin_menu', 'boldy_theme_page');
function boldy_theme_page ()
{
	if ( count($_POST) > 0 && isset($_POST['boldy_settings']) )
	{
		$options = array ('logo_img', 'logo_alt','contact_email','contact_text','cufon','linkedin_link','twitter_user','latest_tweet','facebook_link','keywords','description','analytics','copyright','home_box1','home_box1_link','home_box2','home_box2_link','home_box3','home_box3_link','blurb_enable','blurb_text','blurb_link','blurb_page', 'footer_actions','actions_hide','portfolio','blog','slider');
		
		foreach ( $options as $opt )
		{
			delete_option ( 'boldy_'.$opt, $_POST[$opt] );
			add_option ( 'boldy_'.$opt, $_POST[$opt] );	
		}			
		 
	}
	add_menu_page(__('Boldy Options'), __('Boldy Options'), 'edit_themes', basename(__FILE__), 'boldy_settings');
	add_submenu_page(__('Boldy Options'), __('Boldy Options'), 'edit_themes', basename(__FILE__), 'boldy_settings');
}
function boldy_settings()
{?>
<div class="wrap">
	<h2>Boldy Options Panel</h2>
	
<form method="post" action="">

	<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px;color:#2481C6; text-transform:uppercase;"><strong>General Settings</strong></legend>
	<table class="form-table">
		<!-- General settings -->
		
		<tr valign="top">
			<th scope="row"><label for="logo_img">Change logo (full path to logo image)</label></th>
			<td>
				<input name="logo_img" type="text" id="logo_img" value="<?php echo get_option('boldy_logo_img'); ?>" class="regular-text" /><br />
				<em>current logo:</em> <br /> <img src="<?php echo get_option('boldy_logo_img'); ?>" alt="<?php echo get_option('boldy_logo_alt'); ?>" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="logo_alt">Logo ALT Text</label></th>
			<td>
				<input name="logo_alt" type="text" id="logo_alt" value="<?php echo get_option('boldy_logo_alt'); ?>" class="regular-text" />
			</td>
		</tr>
        
		 <tr valign="top">
			<th scope="row"><label for="cufon">Cufon Font Replacement</label></th>
			<td>
				<select name="cufon" id="cufon">
					<option value="yes" <?php if(get_option('boldy_cufon') == 'yes'){?>selected="selected"<?php }?>>Yes</option>		
					<option value="no" <?php if(get_option('boldy_cufon') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="b">Choose Blog Category</label></th>
			<td>
				<?php wp_dropdown_categories("name=blog&hide_empty=0&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_blog')); ?>
			</td>
		</tr>
		 <tr valign="top">
			<th scope="row"><label for="portfolio">Choose Portfolio Category</label></th>
			<td>
				<?php wp_dropdown_categories("name=portfolio&hide_empty=0&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_portfolio')); ?>
			</td>
		</tr>
	</table>
	</fieldset>
	
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
		</p>
	
	<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>Social Links</strong></legend>
		<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="twitter_user">Twitter Username</label></th>
			<td>
				<input name="twitter_user" type="text" id="twitter_user" value="<?php echo get_option('boldy_twitter_user'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="latest_tweet">Display Latest Tweet</label></th>
			<td>
				<select name="latest_tweet" id="latest_tweet">		
					<option value="yes" <?php if(get_option('boldy_latest_tweet') == 'yes'){?>selected="selected"<?php }?>>Yes</option>
                    <option value="no" <?php if(get_option('boldy_latest_tweet') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row"><label for="facebook_link">Facebook link</label></th>
			<td>
				<input name="facebook_link" type="text" id="facebook_link" value="<?php echo get_option('boldy_facebook_link'); ?>" class="regular-text" />
			</td>
		</tr>
        <tr valign="top">
			<th scope="row"><label for="flickr_link">LinknedIn link</label></th>
			<td>
				<input name="linkedin_link" type="text" id="linkedin_link" value="<?php echo get_option('boldy_linkedin_link'); ?>" class="regular-text" />
			</td>
		</tr>
        </table>
        </fieldset>
		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
		</p>
		
		<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px;color:#2481C6; text-transform:uppercase;"><strong>Homepage Settings</strong></legend>
	<table class="form-table">
		<!-- Homepage Boxes 1 -->
		<tr>
			<th colspan="2"><strong>Homepage Slider </strong></th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="slider">Homepage Slider Images Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=slider&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_slider')); ?>
			</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Homepage Boxes </strong></th>
		</tr>
		<tr>
			<th colspan="2"> They should be ALL selected ! Other way the row wont appear at all.</th>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_box1">Home Box1 Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=home_box1&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_home_box1')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_box1_link">Home Box1 "read more" link</label></th>
			<td>
				<input name="home_box1_link" type="text" id="home_box1_link" value="<?php echo get_option('boldy_home_box1_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_box2">Homepage Box2 Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=home_box2&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_home_box2')); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_box2_link">Home Box2 "read more" link</label></th>
			<td>
				<input name="home_box2_link" type="text" id="home_box2_link" value="<?php echo get_option('boldy_home_box2_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="home_box3">Home Box3 Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=home_box3&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_home_box3')); ?>
			</td>
		</tr>	
		<tr valign="top">
			<th scope="row"><label for="home_box3_link">Home Box3 "read more" link</label></th>
			<td>
				<input name="home_box3_link" type="text" id="home_box3_link" value="<?php echo get_option('boldy_home_box3_link'); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Homepage Blurb (request quote section) </strong></th>
		</tr>
		 <tr>
			<th><label for="blurb_enable">Display Homepage Blurb</label></th>
			<td>
				<select name="blurb_enable" id="blurb_enable"> 
					<option value="yes" <?php if(get_option('boldy_blurb_enable') == 'yes'){?>selected="selected"<?php }?>>Yes</option>		
					<option value="no" <?php if(get_option('boldy_blurb_enable') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select><br />
                <em>If "Yes" is selected and text field is empty, the blurb wont appear</em>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="blurb_text">Blurb Text</label></th>
			<td>
				<textarea name="blurb_text" id="blurb_text" rows="3" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('boldy_blurb_text')); ?></textarea>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="blurb_link">Request Quote Link</label></th>
			<td>
				<input name="blurb_link" type="text" id="blurb_link" value="<?php echo get_option('boldy_blurb_link'); ?>" class="regular-text" />
				<br />
				<em>You can either enter a link manually or select a page to point at.</em>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="blurb_page">Request Quote Page</label></th>
			<td>
				<?php wp_dropdown_pages("name=blurb_page&show_option_none=".__('- Select -')."&selected=" .get_option('boldy_blurb_page')); ?>
				<br />
				<em>You can either enter a link manually or select a page to point at.</em>
			</td>
		</tr>
	</table>
	</fieldset>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
		</p>
	
    <fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>Contact Page Settings</strong></legend>
		<table class="form-table">	
        <tr>
        	<td colspan="2"></td>
        </tr>
         <tr valign="top">
			<th scope="row"><label for="contact_text">Contact Page Text</label></th>
			<td>
				<textarea name="contact_text" id="contact_text" rows="7" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('boldy_contact_text')); ?></textarea>
			</td>
		</tr>
        <tr valign="top">
			<th scope="row"><label for="contact_email">Email Address for Contact Form</label></th>
			<td>
				<input name="contact_email" type="text" id="contact_email" value="<?php echo get_option('boldy_contact_email'); ?>" class="regular-text" />
			</td>
		</tr>
        </table>
     </fieldset>
	 <p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
	</p>
	
	<fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>Footer</strong></legend>
		<table class="form-table">
		<tr>
			<th colspan="2"><strong>Footer Twitter &amp; Quick Contact </strong></th>
		</tr>
		<tr>
			<th><label for="footer_actions">Display Footer Twitter &amp; Quick Contact Section</label></th>
			<td>
				<select name="footer_actions" id="footer_actions"> 
					<option value="yes" <?php if(get_option('boldy_footer_actions') == 'yes'){?>selected="selected"<?php }?>>Yes</option>		
					<option value="no" <?php if(get_option('boldy_footer_actions') == 'no'){?>selected="selected"<?php }?>>No</option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="actions_hide">Default Section Visibility</label></th>
			<td>
				<select name="actions_hide" id="actions_hide"> 
					<option value="visible" <?php if(get_option('boldy_actions_hide') == 'visible'){?>selected="selected"<?php }?>>Visible</option>		
					<option value="hidden" <?php if(get_option('boldy_actions_hide') == 'hidden'){?>selected="selected"<?php }?>>Hidden</option>
				</select>
			</td>
		</tr>
		<tr>
			<th colspan="2"><strong>Copyright Info</strong></th>
		</tr>
        <tr>
			<th><label for="copyright">Copyright Text</label></th>
			<td>
				<textarea name="copyright" id="copyright" rows="4" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('boldy_copyright')); ?></textarea><br />
				<em>You can use HTML for links etc.</em>
			</td>
		</tr>
		
		
	</table>
	</fieldset>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
	</p>
        
      <fieldset style="border:1px solid #ddd; padding-bottom:20px; margin-top:20px;">
	<legend style="margin-left:5px; padding:0 5px; color:#2481C6;text-transform:uppercase;"><strong>SEO</strong></legend>
		<table class="form-table">
        <tr>
			<th><label for="keywords">Meta Keywords</label></th>
			<td>
				<textarea name="keywords" id="keywords" rows="7" cols="70" style="font-size:11px;"><?php echo get_option('boldy_keywords'); ?></textarea><br />
                <em>Keywords comma separated</em>
			</td>
		</tr>
        <tr>
			<th><label for="description">Meta Description</label></th>
			<td>
				<textarea name="description" id="description" rows="7" cols="70" style="font-size:11px;"><?php echo get_option('boldy_description'); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><label for="ads">Google Analytics code:</label></th>
			<td>
				<textarea name="analytics" id="analytics" rows="7" cols="70" style="font-size:11px;"><?php echo stripslashes(get_option('boldy_analytics')); ?></textarea>
			</td>
		</tr>
		
	</table>
	</fieldset>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
		<input type="hidden" name="boldy_settings" value="save" style="display:none;" />
	</p>
</form>
</div>
<?php }

function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/images/custom-login-logo.gif) !important; }
    </style>';
}
//add the mway logo to login
add_action('login_head', 'my_custom_login_logo');
//add the mway logo to admin panel
add_action('admin_head', 'my_custom_logo');

function my_custom_logo() {
   echo '<style type="text/css">
         #header-logo { background-image: url('.get_bloginfo('template_directory').'/images/custom-logo.gif) !important; }</style>';
}

//remove upgrade notification
if ( !current_user_can( 'edit_users' ) ) {
  add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );
  add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
}
//change admin colour
function custom_colors() {
   echo '<style type="text/css">#wphead{background:#069}</style>';
}

add_action('admin_head', 'custom_colors');

function example_dashboard_widget_function() {
	// Display whatever it is you want to show
	echo "Hello World, I'm a great Dashboard Widget";
} 

// Create the function use in the action hook
function example_add_dashboard_widgets() {
	wp_add_dashboard_widget('example_dashboard_widget', 'Example Dashboard Widget', 'example_dashboard_widget_function');
}
// Hoook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );

function remove_menus () {
global $menu;
		$restricted = array(__('Dashboard'),/* __('Appearance'),*/ __('Tools'), __('Users'), __('Settings'), /*__('Plugins')*/);
		end ($menu);
		while (prev($menu)){
			$value = explode(' ',$menu[key($menu)][0]);
			if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
		}
}
add_action('admin_menu', 'remove_menus');

function example_remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
 	global $wp_meta_boxes;

	// Remove the incomming links widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);	

	// Remove right now
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
}

// Hoook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets' );

//footer

function remove_footer_admin (){
    echo 'Fueled by <a href="http://www.netwidz.com
     
    " target="_blank">NetWidZ..!</a> | Designed by <a href="http://www.netwidz.com
     
    " target="_blank">NetWidZ..! Productions</a> </p>';
    }

    add_filter('admin_footer_text', 'remove_footer_admin');

function custom_colors_footer() {
 echo '<style type="text/css">#footer{background:#520709}</style>';
}
add_action('admin_footer', 'custom_colors_footer');



add_filter('admin_footer_text', 'remove_footer_admin');

add_editor_style('custom-editor-style.css');


add_action( 'init', 'create_my_post_types' );

function create_my_post_types() {
	register_post_type( 'super_duper',
		array(
			'labels' => array(
				'name' => __( 'MWay Post' ),
				'singular_name' => __( 'MWay Post' )
			),
			'public' => true,
		)
	);
}


add_action('admin_init', 'remove_theme_menus');
function remove_theme_menus() {
	global $submenu;
	unset($submenu['themes.php'][5]);
	unset($submenu['theme-editor.php']);
	unset($submenu['themes.php'][15]);
}

add_action('admin_init', 'remove_editor_menus');
function remove_editor_menus() {
	global $submenu;
//	$submenu['themes.php'][10] = array(__('Editor'), 'edit_themes', 'theme-editor.php');

	unset($submenu['theme-editor.php'][10]);
	//unset($submenu['theme-editor.php'][15]);
}

// PHP errors widget
function slt_PHPErrorsWidget() {
	$logfile = '/home/path/logs/php-errors.log'; // Enter the server path to your logs file here
	$displayErrorsLimit = 100; // The maximum number of errors to display in the widget
	$errorLengthLimit = 300; // The maximum number of characters to display for each error
	$fileCleared = false;
	$userCanClearLog = current_user_can( 'manage_options' );
	// Clear file?
	if ( $userCanClearLog && isset( $_GET["slt-php-errors"] ) && $_GET["slt-php-errors"]=="clear" ) {
		$handle = fopen( $logfile, "w" );
		fclose( $handle );
		$fileCleared = true;
	}
	// Read file
	if ( file_exists( $logfile ) ) {
		$errors = file( $logfile );
		$errors = array_reverse( $errors );
		if ( $fileCleared ) echo '<p><em>File cleared.</em></p>';
		if ( $errors ) {
			echo '<p>'.count( $errors ).' error';
			if ( $errors != 1 ) echo 's';
			echo '.';
			if ( $userCanClearLog ) echo ' [ <b><a href="'.get_bloginfo("url").'/wp-admin/?slt-php-errors=clear" onclick="return confirm(\'Are you sure?\');">CLEAR LOG FILE</a></b> ]';
			echo '</p>';
			echo '<div id="slt-php-errors" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
			echo '<ol style="padding:0;margin:0;">';
			$i = 0;
			foreach ( $errors as $error ) {
				echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
				$errorOutput = preg_replace( '/\[([^\]]+)\]/', '<b>[$1]</b>', $error, 1 );
				if ( strlen( $errorOutput ) > $errorLengthLimit ) {
					echo substr( $errorOutput, 0, $errorLengthLimit ).' [...]';
				} else {
					echo $errorOutput;
				}
				echo '</li>';
				$i++;
				if ( $i > $displayErrorsLimit ) {
					echo '<li style="padding:2px;border-bottom:2px solid #ccc;"><em>More than '.$displayErrorsLimit.' errors in log...</em></li>';
					break;
				}
			}
			echo '</ol></div>';
		} else {
			echo '<p>No errors currently logged.</p>';
		}
	} else {
		echo '<p><em>There was a problem reading the error log file.</em></p>';
	}
}

// Add widgets
function slt_dashboardWidgets() {
	wp_add_dashboard_widget( 'slt-php-errors', 'PHP errors', 'slt_PHPErrorsWidget' );
}
add_action( 'wp_dashboard_setup', 'slt_dashboardWidgets' );

/////////////////////////////////////////////////



/******* CUSTOM POST TYPE: JOBS *********/

add_action('init', 'job_register');

function job_register() {
	$args = array(
		'label' => __('Jobs'),
		'singular_label' => __('Job'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'supports' => array('title', 'editor')
	);

	register_post_type( 'job' , $args );
}

	add_action("admin_init", "job_admin_init");
	add_action('save_post', 'save_job_meta');

	function job_admin_init(){
		add_meta_box("job_meta", "Job Options", "job_meta_options", "job", "normal", "core");
	}

	function job_meta_options(){
		global $post;
		$custom = get_post_custom($post->ID);
		$job_clinic = $custom["job_clinic"][0];
		$job_supervisor = $custom["job_supervisor"][0];
		$job_type = $custom["job_type"][0];
		$job_hours = $custom["job_hours"][0];
		$job_salary = $custom["job_salary"][0];
		$job_salarytype = $custom["job_salarytype"][0];
	
?>
	<h2 style="clear:left;margin:20px 0 0 15px;">Location</h2>
	<div style="float:left;padding:5px 15px;">
		<label for="job_clinic">Clinic </label>
		<input type="text" name="job_clinic" size="30" autocomplete="on" value="<?php echo $job_clinic; ?>">
	</div>
	<div style="float:left;padding:5px 15px;">
		<label for="job_supervisor">Supervisor </label>
		<input type="text" name="job_supervisor" size="30" autocomplete="on" value="<?php echo $job_supervisor; ?>">
	</div>
	<div style="float:left;clear:left;">
		<h2 style="clear:left;margin:20px 0 0 15px;">Details</h2>
		<div style="float:left;padding:5px 15px;">
			<label for="job_type">Type </label>
			<select name="job_type" id="job_type">
				<option value="">Select one</option>
				<option value="Full Time"<?php if ($job_type=="Full Time") echo " selected" ?>>Full Time</option>
				<option value="Part Time"<?php if ($job_type=="Part Time") echo " selected" ?>>Part Time</option>
				<option value="Temporary Full Time"<?php if ($job_type=="Temporary Full Time") echo " selected" ?>>Temporary Full Time</option>
				<option value="Temporary Part Time"<?php if ($job_type=="Temporary Part Time") echo " selected" ?>>Temporary Part Time</option>
			</select>
		</div>
		<div style="float:left;padding:5px 15px;">
			<label for="job_hours">Hours </label>
			<input type="text" name="job_hours" size="5" autocomplete="on" value="<?php echo $job_hours; ?>">
		</div>
		<div style="float:left;padding:5px 15px;">
			<label for="job_salary">Salary $</label>
			<input type="text" name="job_salary" size="10" autocomplete="on" value="<?php echo $job_salary; ?>">
			<select name="job_salarytype" id="job_salarytype">
				<option value="">Select one</option>
				<option value="per Hour"<?php if ($job_salarytype=="per Hour") echo " selected" ?>>per Hour</option>
				<option value="per Week"<?php if ($job_salarytype=="per Week") echo " selected" ?>>per Week</option>
				<option value="per Year"<?php if ($job_salarytype=="per Year") echo " selected" ?>>per Year</option>
			</select>
		</div>
	</div>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
<?php
	}

function save_job_meta(){
	global $post;
	update_post_meta($post->ID, "job_clinic", $_POST["job_clinic"]);
	update_post_meta($post->ID, "job_supervisor", $_POST["job_supervisor"]);
	update_post_meta($post->ID, "job_type", $_POST["job_type"]);
	update_post_meta($post->ID, "job_hours", $_POST["job_hours"]);
	update_post_meta($post->ID, "job_salary", $_POST["job_salary"]);
	update_post_meta($post->ID, "job_salarytype", $_POST["job_salarytype"]);
}

add_filter("manage_edit-job_columns", "job_edit_columns");
add_action("manage_posts_custom_column",  "job_custom_columns");

function job_edit_columns($columns){
		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Name",
			"job_clinic" => "Clinic",
			"job_supervisor" => "Supervisor",
			"job_type" => "Type",
			"job_hours" => "Hours",
			"job_salary" => "Salary",
		);

		return $columns;
}

function job_custom_columns($column){
		global $post;
		switch ($column)
		{
			case "job_clinic":
				$custom = get_post_custom();
				echo $custom["job_clinic"][0];
				break;
			case "job_supervisor":
				$custom = get_post_custom();
				echo $custom["job_supervisor"][0];
				break;
			case "job_type":
				$custom = get_post_custom();
				echo $custom["job_type"][0];
				break;
			case "job_hours":
				$custom = get_post_custom();
				echo $custom["job_hours"][0];
				break;
			case "job_salary":
				$custom = get_post_custom();
				$test=$custom["job_salary"][0]; if(!empty($test)): echo "$ " . $custom["job_salary"][0]; endif;
				echo " " . $custom["job_salarytype"][0];
				break;
		}
}

?>

<?php

add_action('admin_menu', 'create_theme_options_page');
add_action('admin_init', 'register_and_build_fields' );

function create_theme_options_page() {
	add_options_page('Theme Options', 'Theme Options', 'administrator', __FILE__, 'options_page_fn');
}


function register_and_build_fields(){
	register_setting( 'plugin_options', 'plugin_options', 'validate_setting' );
	
	add_settings_section('main_section', 'Main Settings', 'section_text_fn', __FILE__);
	
	add_settings_field('color_scheme', 'Color Scheme:', 'color_scheme_setting', __FILE__, 'main_section');
	add_settings_field('logo', 'Logo:', 'logo_setting', __FILE__, 'main_section'); // LOGO
	add_settings_field('banner_heading', 'Banner Heading:', 'banner_heading_setting', __FILE__, 'main_section');
	add_settings_field('adverting_information', 'Advertising Info:', 'advertising_information_setting', __FILE__, 'main_section');
	
	add_settings_field('ad_one', 'Ad:', 'ad_setting_one', __FILE__, 'main_section'); // Ad1
	add_settings_field('ad_two', 'Second Ad:', 'ad_setting_two', __FILE__, 'main_section'); // Ad2
	
}


function options_page_fn() {
?>
	<div id="theme-options-wrap" class="widefat">
		<div class="icon32" id="icon-tools"></div>
		
		<h2>My Theme Options</h2>
		<p>Take control of your theme, by overriding the default settings with your own specific preferences.</p>
		
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
	</div>
<?php
}


// Banner Heading
function banner_heading_setting() {
	$options = get_option('plugin_options');
	echo "<input name='plugin_options[banner_heading]' type='text' value='{$options['banner_heading']}' />";
}


// Color Scheme
function  color_scheme_setting() {
	$options = get_option('plugin_options');
	$items = array("Red", "Green", "Blue");
	
	echo "<select name='plugin_options[color_scheme]'>";
		foreach( $items as $item ) {
			$selected = ( $options['color_scheme'] === $item ) ? 'selected = "selected"' : '';
			echo "<option value='$item' $selected>$item</option>";
		}
	echo "</select>";
}


// Advertising info
function advertising_information_setting() {
	$options = get_option('plugin_options');
	echo "<textarea name='plugin_options[advertising_information]' rows='10' cols='60' type='textarea'>{$options['advertising_information']}</textarea>";
}


// Ad one
function ad_setting_one() {
	echo '<input type="file" name="ad_one" />'; 
}


// Ad two
function ad_setting_two() {
	echo '<input type="file" name="ad_two" />'; 
}


// Logo
function logo_setting() {
	echo '<input type="file" name="logo" />';
}


// This function can be used to validate the inputs
function validate_setting($plugin_options) {
	$keys = array_keys($_FILES);
	$i = 0;
	
	foreach( $_FILES as $image ) {
		// if a files was upload
		if ( $image['size'] ) {
			// if it is an image
			if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {
		       	$override = array('test_form' => false); 
		       	$file = wp_handle_upload($image, $override);

				$plugin_options[$keys[$i]] = $file['url'];
			}
			else {
				$options = get_option('plugin_options');
				$plugin_options[$keys[$i]] = $options[$logo];
				wp_die('No image was uploaded.');
			}
		 }
	
		// else, retain the image that's already on file.
		else {
			$options = get_option('plugin_options');
			$plugin_options[$keys[$i]] = $options[$keys[$i]];
		}
		$i++;
	}

	return $plugin_options;
}
	

function section_text_fn() {}


// Add stylesheet (replace with your own)
add_action('admin_head', 'admin_register_head');
function admin_register_head() {
    $url = get_bloginfo('template_directory') . '/functions/options_page.css';
    echo "<link rel='stylesheet' href='$url' />\n";
}