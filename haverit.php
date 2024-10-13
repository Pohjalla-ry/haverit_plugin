<?php
/**
 * Plugin Name:       Haverit Plugin
 * Plugin URI:        https://haverit.net/
 * Description:       Haverit country taxonomy.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Vesa Kankkunen
 * Author URI:        https://hylyt.net/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       haverit
 * ###Domain Path:       /languages
 */

/*
{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {URI to Plugin License}.
*/

/** haverit quick test */
if ( ! function_exists( 'haverit_maa_taxonomy' ) ) {

  // Register Custom Taxonomy
  function haverit_maa_taxonomy() {

        $labels = array(
                'name'                       => _x( 'Maat', 'Taxonomy General Name', 'haverit_domain' ),
                'singular_name'              => _x( 'Maa', 'Taxonomy Singular Name', 'haverit_domain' ),
                'menu_name'                  => __( 'Maat', 'haverit_domain' ),
                'all_items'                  => __( 'Kaikki maat', 'haverit_domain' ),
                'parent_item'                => __( 'Parent maa', 'haverit_domain' ),
                'parent_item_colon'          => __( 'Parent maa:', 'haverit_domain' ),
                'new_item_name'              => __( 'New Maa', 'haverit_domain' ),
                'add_new_item'               => __( 'Add New Maa', 'haverit_domain' ),
                'edit_item'                  => __( 'Edit Maa', 'haverit_domain' ),
                'update_item'                => __( 'Update Maa', 'haverit_domain' ),
                'view_item'                  => __( 'View Maa', 'haverit_domain' ),
                'separate_items_with_commas' => __( 'Separate maat with commas', 'haverit_domain' ),
                'add_or_remove_items'        => __( 'Add or remove maa', 'haverit_domain' ),
                'choose_from_most_used'      => __( 'Valitse yleisimmin käytetyistä', 'haverit_domain' ),
                'popular_items'              => __( 'Suositut maat', 'haverit_domain' ),
                'search_items'               => __( 'Search maa', 'haverit_domain' ),
                'not_found'                  => __( 'Not Found', 'haverit_domain' ),
                'no_terms'                   => __( 'Ei yhtään maakoodia', 'haverit_domain' ),
                'items_list'                 => __( 'Maakoodi list', 'haverit_domain' ),
                'items_list_navigation'      => __( 'maakoodi list navigation', 'haverit_domain' ),
        );
        $args = array(
                'labels'                     => $labels,
                'hierarchical'               => true,
                'public'                     => true,
                'show_ui'                    => true,
                'show_admin_column'          => true,
                'show_in_nav_menus'          => true,
                'show_tagcloud'              => true,
                'show_in_rest'               => true,
        );
        register_taxonomy( 'haverit_maa', array( 'post' ), $args );
 }
 add_action( 'init', 'haverit_maa_taxonomy', 0 );
}
  
function haverit_set_default_object_terms( $post_id, $post ) {
    if ( 'publish' === $post->post_status ) {
        $defaults = array(
            //'post_tag' => array( 'taco', 'banana' ),
            'haverit_maa' => array( 'FI' ),
            );
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( (array) $taxonomies as $taxonomy ) {
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
            }
        }
    }
}
add_action( 'save_post', 'haverit_set_default_object_terms', 100, 2 );

function fill_haverit(){
  $terms = get_terms('haverit_maa', array('hide_empty' => false));
  $args = array('numberposts'       => -1,'post_type' => 'post');
  $posts_array = get_posts( $args );
  $run_once=0;
  foreach ($posts_array as $post) {
      haverit_set_default_object_terms($post->ID,$post);
  }
}




/**
 * Activate the plugin.
 */ 
function haverit_activate() { 
    $term=term_exists('FI','haverit_maa');	
    //if($term == 0 || $term !== null){
    //    wp_create_term('FI','haverit_maa');
    //}       
    flush_rewrite_rules(); 
}
register_activation_hook( __FILE__, 'haverit_activate' );
