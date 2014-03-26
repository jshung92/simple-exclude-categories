<?php
/*
Plugin Name: Simple Exclude Categories
Description: Hide posts in categories from WordPress Homepage
Plugin URI: http://sihung.tk/
Version: 1.0
Author: Trang Si Hung
Author URI: http://sihung.tk/
Author Email: trangsihung@gmail.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2013 Someblog (trangsihung@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'admin_init', 'exclude_cats_settings_api_init' );
add_filter('pre_get_posts', 'excludeCat');

function exclude_cats_settings_api_init() {
	add_settings_field(
		'exclude_cats',
		'Exclude Categories',
		'exclude_cats_setting_callback_function',
		'reading',
		'default'
	);

	register_setting( 'reading', 'exclude_cats' );

	wp_register_style( 'exclude-cats-styles', plugins_url('plugin.css', __FILE__) );
	wp_enqueue_style( 'exclude-cats-styles');
}

function exclude_cats_setting_callback_function() {
    $options    = get_option('exclude_cats');
    $pag        = 'exclude_cats';
    $_cats      = get_terms( 'category' );
    $html       = '';

    foreach ($_cats as $term) {
    	if($options != ''){
    		$checked = in_array($term->term_id, $options) ? 'checked="checked"' : '';
    	}

    	$html .= '<p class="cat_l">';
        $html .= sprintf( '<input type="checkbox" id="%1$s[%2$s]" name="%1$s[]" value="%2$s" %3$s />', $pag, $term->term_id, $checked );
        $html .= sprintf( '<label for="%1$s[%3$s]"> %2$s</label><br>', $pag, $term->name, $term->term_id );
        $html .= '</p>';
    }

    $html .= '<p class="cb"></p>';

    echo $html;
}

function excludeCat($query) {
  if ( $query->is_home ) {
    $query->set('category__not_in', get_option('exclude_cats'));
  }
  return $query;
}
