<?php
   /*
      Plugin Name: Wordpress Custom Plugin
      Plugin URI: 
      Description: Wordpress Custom Plugin Plugin by Punit
      Version: 1.0
      Author: Mr. Punit
      Author URI: 
   */

   class Task 
   {
         public function __construct() 
         {
               add_action( 'init', array($this,'register_movies_post_type') );
               add_filter( 'the_title', array($this,'movies_custom_title'));
               
               $this->addCustomRole();
               $this->cronSettings();
         }

         // Adding custom role - Viewer
         public function addCustomRole()
         {
             add_role(
                   'viewer', //  System name of the role.
                   __( 'Viewer'  ), // Display name of the role.
                   array(
                       'read'  => true,
                       'delete_posts'  => false,
                       'delete_published_posts' => false,
                       'edit_posts'   => false,
                       'publish_posts' => false,
                       'upload_files'  => false,
                       'edit_pages'  => false,
                       'edit_published_pages'  =>  false,
                       'publish_pages'  => false,
                       'delete_published_pages' => false, 
                   )
               );  
         }

         /* Cron Setting up and scheduling */

         public function cronSettings()
         {    
            add_filter( 'cron_schedules', array($this,'viewer_role_email') );

            if (!wp_next_scheduled('viewer_role_email'))
            {
                  wp_schedule_event( time(), 'every_five_minutes', 'viewer_role_email');
            }
            add_action('viewer_role_email', array($this,'viewer_role_email_notification'));
         }

         public function viewer_role_email( $schedules ) 
         {
             $schedules['every_five_minutes'] = array(
                     'interval'  => 60 * 5,
                     'display'   => __( 'Every 5 Minutes' )
             );
             return $schedules;
         }

         public function viewer_role_email_notification()
         {
               $args = array(
                  'role' => 'viewer'
               );

               $viewers = get_users($args);

               foreach ($viewers as $user) 
               {
                     wp_mail( $user->user_email, 'News of the jungle', 'Something happening somewhere' );
               }
         }

         /* Cron Setting up and scheduling */

         // Register Custom Post type - Movies
         public function register_movies_post_type() 
         {
               register_post_type( 'movies',
                     array(
                        'labels' => array(
                            'name' => __( 'Movies' ),
                            'singular_name' => __( 'Movie' )
                        ),
                        'public' => true,
                        'has_archive' => true,
                        'rewrite' => array('slug' => 'movies'),
                        'show_in_rest' => true,
                     )
               );             
         }

         // Custom Tile for Movies Post Type
         public function movies_custom_title( $title ) 
         {
            if(is_post_type_archive() || is_archive())
               return $title;

            if ( ! is_singular() ) return $title;

            $post_type = get_post_type(get_the_id());

            if($post_type == "movies")
            {
               $custom_title = $title . "-Upcoming this year";
              
               if( ! empty( $custom_title ) )
               {
                  $custom_title = esc_html( $custom_title );
                  return $custom_title;
               }
             }
             return $title;
         }
   }

   $obj = new Task();

?>
