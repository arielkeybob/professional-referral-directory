<?php
defined('ABSPATH') or die('No script kiddies please!');
include_once(plugin_dir_path(__FILE__) . '../../includes/utility-functions.php');
?>

<div class="service-result alternative-layout">
    <span> Template 2</span>
    <h3 class="service-title"><?php the_title(); ?></h3>
    <div class="service-thumbnail">
        <?php echo '<img src="' . pdr_get_service_thumbnail_url('medium') . '" alt="Service Thumbnail">'; ?>
    </div>
    <div class="service-content">
        <p class="service-excerpt"><?php the_excerpt(); ?></p>
        <p class="service-taxonomy service-type">
            <strong><?php _e('Service Types:', 'referralhub'); ?></strong>
            <?php echo pdr_get_taxonomy_terms_as_string('service_type'); ?>
        </p>
        <p class="service-taxonomy service-location">
            <strong><?php _e('Service Locations:', 'referralhub'); ?></strong>
            <?php echo pdr_get_taxonomy_terms_as_string('service_location'); ?>
        </p>
    </div>
    <a class="read-more" href="<?php the_permalink(); ?>">
        <?php _e('Read more', 'referralhub'); ?>
    </a>
    <div class="service-author-details">
        <p class="author-name">
            <i class="fas fa-user"></i> <?php the_author(); ?>
        </p>

        <?php echo pdr_get_author_email_html(); ?>
        <?php echo pdr_get_author_url_html(); ?>
        <?php echo pdr_get_author_phone_html(); ?>
        <?php echo pdr_get_author_social_html(); ?>
    </div>
</div>
