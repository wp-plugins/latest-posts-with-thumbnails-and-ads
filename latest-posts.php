<?php
/*--------------------------------------------------------------
Copyright (C) shomtek.com
Website: http://www.shomtek.com
Support: info@shomtek.com
Author: Eduart Milushi
---------------------------------------------------------------*/


if( !class_exists('shomtek_latest_posts') ){

  // WP Widget hook
  class shomtek_latest_posts extends WP_Widget {

    // Processing widget
    function Shomtek_Latest_Posts() {

      $widget_settings = array( 'classname' => 'shomtek_latest_posts', 'description' => __('Shows latest posts with pics and ads between posts.', 'shomtek') );

      $control_settings = array( 'id_base' => 'shomtek_latest_posts' );

      parent::__construct( 'shomtek_latest_posts', __('Latest posts with thumbnails', 'shomtek'), $widget_settings, $control_settings );

    }

    // Widget settings form on backend
    function form( $instance ) {

      $instance = wp_parse_args( (array) $instance, array(

        'title'               => 'Latest Posts with Pics',

        'image_size'          => 'medium',

        'adstext'             => '',

        'comments_count'     => 1,

        'showdate'            => 1,

        'number'              => 8,

        'adsnumber'           => 4,

        )

      );

      $title = esc_attr($instance['title']); // widget title

      $image_size = $instance['image_size']; // post image size

      $number = intval($instance['number']); // number of posts to show

      $comments_count = ( $instance['comments_count'] === 1 ) ? true : false; // show/hide comments count

      $showdate = ( $instance['showdate'] === 1 ) ? true : false; // show/hide post date 

      $adsnumber = intval($instance['adsnumber']); // Show ads between posts

      $adstext = esc_textarea($instance['adstext']); //Ads text html or js

      ?>

      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'shomtek') ?></label>
        <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
      </p>

      <p>
        <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number of posts to show:', 'shomtek') ?></label>
        <input type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3"/>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Select Image Size', 'shomtek'); ?></label>
        <select class="widefat" id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>" style="width:100%;">
            <option value='thumbnail'<?php echo ($image_size=='thumbnail')?'selected':''; ?>><?php _e('Thumbnail', 'shomtek'); ?></option>
            <option value='medium'<?php echo ($image_size=='medium')?'selected':''; ?>><?php _e('Medium', 'shomtek'); ?></option>
            <option value='large'<?php echo ($image_size=='large')?'selected':''; ?>><?php _e('Large', 'shomtek'); ?></option>
        </select>
      </p>

      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id( 'comments_count' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'comments_count' ); ?>" <?php checked( $comments_count, 1 ); ?> />
        <label for="<?php echo $this->get_field_id( 'comments_count' ); ?>"><?php _e( 'Check to display comments count', 'shomtek' ); ?></label>
      </p>

      <p>
        <input type="checkbox" id="<?php echo $this->get_field_id( 'showdate' ); ?>" class="checkbox" name="<?php echo $this->get_field_name( 'showdate' ); ?>" <?php checked( $showdate, 1 ); ?> />
        <label for="<?php echo $this->get_field_id( 'showdate' ); ?>"><?php _e( 'Check to display posts date', 'shomtek' ); ?></label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id( 'adsnumber' ); ?>"><?php _e('Show your ads every', 'shomtek') ?></label>
        <input type="text" id="<?php echo $this->get_field_id( 'adsnumber' ); ?>" name="<?php echo $this->get_field_name( 'adsnumber' ); ?>" value="<?php echo $instance['adsnumber']; ?>" size="3"/>
        <label for="<?php echo $this->get_field_id( 'adsnumber' ); ?>"><?php _e('posts', 'shomtek') ?></label>
      </p>

      <p>
        <label for="<?php echo $this->get_field_id( 'adstext' ); ?>"><?php _e('Enter your ad code (html or js):', 'shomtek') ?></label>
        <textarea class="widefat" rows="14" cols="20" id="<?php echo $this->get_field_id('adstext'); ?>" name="<?php echo $this->get_field_name('adstext'); ?>"><?php echo $adstext; ?></textarea>
      </p>

      <?php

    }

    // Save widget settings
    function update( $new_instance, $old_instance ) {
      
      $instance = $old_instance;

      $instance['title'] = strip_tags( $new_instance['title'] );

      $instance['image_size'] = $new_instance['image_size'];

      $instance['number'] =  intval($new_instance['number']) ;

      $instance['comments_count'] = isset( $new_instance['comments_count']) ? 1 : 0;

      $instance['showdate'] = isset( $new_instance['showdate'] ) ? 1 : 0;

      $instance['adsnumber'] =  intval($new_instance['adsnumber']) ;

      if ( current_user_can('unfiltered_html') )

        $instance['adstext'] =  $new_instance['adstext'];

      else

        $instance['adstext'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['adstext']) ) );

      return $instance;

    }

    // Displaying widget on frontend
    function widget( $args, $instance ) {

      extract( $args );

      $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

      $image_size = $instance['image_size'];

      $number = $instance['number'];

      $comments_count = $instance['comments_count'];

      $showdate = $instance['showdate'];

      $adstext = apply_filters( 'widget_text', empty( $instance['adstext'] ) ? '' : $instance['adstext'], $instance );

      $adsnumber = $instance['adsnumber'];

      //query post
      $queried_object = get_queried_object();

      if ( is_single() ) {
        $this_post = $queried_object->ID;
      } else {
        $this_post = '';
      }

      $query_arg = array(

        'posts_per_page' => $number,

        'post__not_in' => array($this_post),

        'ignore_sticky_posts' => 1,

        'tax_query' => array(

          array(

            'taxonomy' => 'post_format',

            'field' => 'slug',

            'terms' => array(

              'post-format-quote',

              'post-format-video'

              ),

            'operator' => 'NOT IN'

            )

          )

        );

        $query = new WP_Query($query_arg);

        echo $before_widget;

        if ( $title ):
          echo $before_title; 
          echo $title;
          echo $after_title;
        endif;

        $postnum = 0;

        if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>

        <div class="shomtek_post">

          <div class="shomtek_img_container">
             
             <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
              
              <?php if(has_post_thumbnail()) { 

                the_post_thumbnail( $image_size, array('class' => 'shomtek_post_image'));
              
              } else {

                echo '<img class="shomtek_post_image" src="'. SHOMTEK__PLUGIN_URL .'assets/latest-posts-default.jpg" />';

                } ?>

            </a>

          <div class="shomtek_post_data">

            <h2 class="shomtek_title">

              <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" ><?php the_title(); ?></a>
            
            </h2>
  
          <?php if (( $showdate ) or ( $comments_count )) : ?>

            <?php if ( $showdate ) : ?>

              <span class="shomtek_post_date"><?php echo get_the_time( get_option( 'date_format' ) ); ?></span>

            <?php endif ?>

            <?php if ( $comments_count ) : ?>

              <span class="shomtek_comments_count"><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></span>

            <?php endif ?>

          <?php endif ?>

          </div>

          </div>

          </div>

          <?php if ( $adstext != NULL && $adsnumber !=0 && $adsnumber != NULL ):?>
            <?php $postnum++;

            if ($postnum % $adsnumber == 0) { ?>
              
              <div class="show_ads">

                <?php echo $adstext; ?>

              </div>

            <?php } ?>
          <?php endif; ?>

      <?php  endwhile; endif; 

      wp_reset_query();

      echo $after_widget;

    }

  }

}

?>