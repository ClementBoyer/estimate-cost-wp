<?php
/**
 * @package Estimate-Cost-Wp
 **/

/** 
 * Plugin Name: Estimate Cost WP
 * Plugin URI: https://github.com/Gen0s-Dev/plugin-wp
 * Description: Outils pour faire des devis sur Word Press
 * Author: Boyer Clément
 * Version: 0.1.1
 * Author URI: https://gokami.fr
 * License: GPLv2 or later
 * Text Domain: estimate-cost-wp
**/

// Pour protéger contre accès direct au ficier
if ( ! defined ( 'ABSPATH' ) )
{
    die;
}

class EstimateCostPlugin
{
    // Permet d'insérer css et js
    function register_admin_scripts()
    {
        add_action( 'admin_enqueue_scripts', array ( $this ,'enqueue' ) );
    }
    //Créer Menu Back End
    function create_post_type ()
    {
        add_action( 'init', array ( $this , 'custom_post_type' ) );
    }
    // Ajout colonne dans hook
    function columns ()
    {
        add_filter( 'manage_devis_posts_columns', array ($this , 'create_columns' ) );
    }
    // Ajout colonne dans back end
    function rows ()
    {
        add_action( 'manage_devis_posts_custom_column', array ($this,'create_rows'), 10, 2 );
    }
    // Cache options publier Meta box
    
    function not_publish_options_metabox ()
    {
        add_action('admin_head-post.php', array ($this,'hide_publish_metabox') );
        add_action('admin_head-post-new.php', array ($this,'hide_publish_metabox') );
    }

    // Cache buttons et eléments éditor Wordpress

    function not_buttons_editor ()
    {
        add_action('admin_head-post.php', array ($this,'hide_options_editor') );
        add_action('admin_head-post-new.php', array ($this,'hide_options_editor') );
    }

    // Shortcode content devis

    function shortcode ()
    {
        add_shortcode( 'devis', array ($this,'devis_shortcode' ) );
    }
   
    // Boutons TinyMCE

    function bouton_tinymce ()
    {
        add_action( 'admin_head',array ($this,'custom_boutons_tinymce') );
    }

    /*
    ===============================
    Boutons TinyMCE
    ===============================
    */
    function custom_boutons_tinymce()
    {
        global $typenow ;
        // Verification utilisateur permision
        if (! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ))
        {
            return false;
        }
        //Verification post type
        if (! in_array($typenow,array ('devis') ))
        {
            return;
        }
        // Check tinymce enable
        if ( get_user_option( 'rich_editing' ) == 'true')
        {
            add_filter( 'mce_external_plugins',array ($this,'custom_add_tinymce_plugin'));
            add_filter( 'mce_buttons', array ($this,'custom_add_tinymce_button' ));
        }

    }

    function custom_add_tinymce_plugin($plugin_array)
    {
        $my_post_type = 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            $plugin_array['custom_button'] = plugins_url( '/admin/js/text-button.js', __FILE__ );
            return $plugin_array;
        }
    }

    function custom_add_tinymce_button($button)
    {
        array_push ($button,'|','custom_button');
        return $button;
    }

    /*
    ===============================
    Meta Box
    ===============================
    */
    // function shortcode_meta_box ()
    // {
    //     add_meta_box( 'shortcode_view', title, callback, screen, context, priority, callback_args );
    // }

    /*
    ===============================
    Shortcode content devis
    ===============================
    */
    function devis_shortcode ($atts)
    {
       
        shortcode_atts( array (
            'id' => '',
        ), $atts );

        $id=$atts['id'];

        $content = '<div class="container">'.get_post_field( 'post_content',$id ).'</div>';

        return $content;

    }
    
    /*
    ===============================
    Hide Options Publish Box
    ===============================
    */
    
    function hide_publish_metabox ()
    {
        $my_post_type= 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>';
        }

    }

     /*
    ===============================
    Hide Options Editor 
    ===============================
    */

    function hide_options_editor ()
    {
        $my_post_type= 'devis';
        global $post;
        if($post->post_type == $my_post_type)
        {
            echo '
                <style type="text/css">
                    #mceu_29-body,
                    #mceu_31,
                    #insert-media-button{
                        display:none;
                    }
                </style>';
        }

    }
   


    /*
    ===============================
    Custom Column 
    ===============================
    */

    function create_rows ( $column, $post_id ) 
    {
        switch ( $column ):
            case 'shortcode':
                // Shortcode view
                $id = get_the_ID();
                $title= get_the_title( );
                echo '[devis id ='.'"'.$id.'"'.' titre ='.'"'.$title.'"'.'] ';
            break;
          default:
            break;
        endswitch;
        
    }

    function create_columns($column)
    {
        // Création colonne

        $newColumns = array ();
	    $newColumns['title'] = 'Titre';
	    $newColumns['shortcode'] = 'Shortcode';
	    $newColumns['author'] = 'Auteur';
	    $newColumns['date'] = 'Date';
	    return $newColumns;
    
    }

    /*
    ===============================
    Activation Plugin  
    ===============================
    */

    function activation ()
    {
        // Génerer CPT
        $this->custom_post_type();
        // flush rewrite rules
        flush_rewrite_rules();
    }

    /*
    ===============================
    Désactivation Plugin  
    ===============================
    */
    function desactivation ()
    {
        // flush rewrite rules
        flush_rewrite_rules();
    }
    /*
    ===============================
    Custom Post Type   
    ===============================
    */
    // Insfrastrure pour créer Menu avec Create Modified and Delete de Wordpress
    function custom_post_type ()
    {
        $labels = array(
            'name'                  => 'Devis',
            'singular_name'         => 'Devis',
            'add_new'               => 'Créer un devis',
            'all_items'             => 'Liste des devis',
            'add_new_item'          => 'Créer un devis',
            'edit_item'             => 'Edition devis',
            'new_item'              => 'Nouveau devis',
            'view_item'             => 'Visualiser devis',
            'search_item'           => 'Chercher devis',
            'not_found'             => 'Devis non trouvé',
            'not_found_in_trash'    => 'Devis non trouvé dans la corbeille',
            );

        $args = array (
            'labels'                => $labels,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'has_archive'           => false,
            'publicly_queryable'    => true,
            'query_var'             => true,
            'rewrite'               => array ('slug'=>'devis'),
            'capability_type'       => 'page',
            'hierarchical'          => false,
            'supports'              => array ('title','editor','revision','custom-fields'),
            'taxonomies'            => array (''),
            'menu_position'         => 55,
            'menu_icon'             => 'dashicons-clipboard',
            'exclude_from_search'   => true,
            );

        register_post_type('devis', $args );
    }

    /*
    ===============================
    Gestion Scripts   
    ===============================
    */

    // Fonction pour charger scripts dans partie Admin.
    function enqueue ($hook) 
    {
        //css
        wp_enqueue_style( 'plugincustomcss', plugins_url( '/admin/css/custom.css', __FILE__ ), array (),'1.0.0','all' );

        //js
        wp_enqueue_script( 'plugincustomjs', plugins_url( '/admin/js/custom.js', __FILE__ ), array (),'1.0.0', true );

    }
}
/*
===============================
Demarrage Functions  
===============================
*/
if (class_exists('EstimateCostPlugin'))
{   
    $estimatecostPlugin = new EstimateCostPlugin();
    $estimatecostPlugin->register_admin_scripts();
    $estimatecostPlugin->create_post_type();
    $estimatecostPlugin->columns();
    $estimatecostPlugin->rows();
    $estimatecostPlugin->not_publish_options_metabox(); 
    $estimatecostPlugin->not_buttons_editor(); 
    $estimatecostPlugin->shortcode();
    $estimatecostPlugin->bouton_tinymce();
}

//Activation

register_activation_hook( __FILE__ ,array($estimatecostPlugin,'activation'));

//Désactivation

register_deactivation_hook( __FILE__ ,array($estimatecostPlugin,'desactivation'));





