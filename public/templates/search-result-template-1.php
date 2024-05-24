<?php
defined('ABSPATH') or die('No script kiddies please!');


// Em search-result-template-1.php
include_once(plugin_dir_path(__FILE__) . '../../includes/utility-functions.php');

?>


<div class="service-result">
    
        <div class="service-thumbnail">
            <?php 
            echo '<img src="' . pdr_get_service_thumbnail_url('medium') . '" alt="Service Thumbnail">';
            ?>
        </div>
    

    <div class="service-content">
    <span> Template 1</span>
        <h3 class="service-title"><?php the_title(); ?></h3>
        <p class="service-excerpt"><?php the_excerpt(); ?></p>
        <p class="service-taxonomy service-type">
            <strong><?php _e('Service Types:', 'professionaldirectory'); ?></strong> 
            <?php echo pdr_get_taxonomy_terms_as_string('service_type'); ?>
        </p>
        <p class="service-taxonomy service-location">
            <strong><?php _e('Service Locations:', 'professionaldirectory'); ?></strong> 
            <?php echo pdr_get_taxonomy_terms_as_string('service_location'); ?>
        </p>
    </div>

    <div class="service-author-details">
        <p class="author-name">
            <i class="fas fa-user"></i> <?php the_author(); ?>
        </p>

        <?php echo pdr_get_author_email_html(); ?>
        <?php echo pdr_get_author_url_html(); ?>
        <?php echo pdr_get_author_phone_html(); ?>
        <?php echo pdr_get_author_social_html(); ?>
    </div>



    <a class="read-more" href="<?php the_permalink(); ?>">
        <?php _e('Read more', 'professionaldirectory'); ?>
    </a>
</div>
