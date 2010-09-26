<?php 
/*
Plugin Name:SEO Friendly Title Generator
Version: 0.5
Plugin URI: http://www.netwidz.com
discription: Replace title withe SEO friendly 
Author: Fahiz
Author URI: http://www.netwidz.com
*/

   
      function make_seo_name($title) {
   
      return preg_replace('/[^a-z0-9_-]/i', '', strtolower(str_replace(' ', '-', trim($title))));
   
      }


	add_filter('the_title','make_seo_name');
	
	
       function string_to_underscore_name($string) {
   
      $string = preg_replace('/[\'"]/', '', $string);
   
      $string = preg_replace('/[^a-zA-Z0-9]+/', '_', $string);
   
      $string = strtolower(trim($string, '_'));
   
      return $string;
      }
     add_filter('wpupdate_update-url','string_to_underscore_name');

     function doContent($content) {
$content = $content . '<p class="extra">Write Down your custom content here!</p>';
return $content;
}
add_filter('the_excerpt_rss', 'doContent');
add_filter('the_content_rss', 'doContent');

add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars )
{
$qvars[] = ' myvar';
return $qvars;
}
      //Output: 13_inside_movie_films_bye
    
	
	
?>