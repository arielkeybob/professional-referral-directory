<div class="wrap">
    <h1><?php echo esc_html__('Contact Details', 'professionaldirectory'); ?></h1>

    <?php if ($contact): ?>
        <div class="contact-details">
            <div class="pdr-column-left">
                <form id="contact-form" class="contact-details-form" method="post">
                    <?php wp_nonce_field('update_contact_' . $contact_id, '_wpnonce', false); ?>
                    <input type="hidden" name="action" value="save_contact_details">
                    <input type="hidden" name="contact_id" value="<?php echo esc_attr($contact_id); ?>">

                    <!-- Custom Name Field -->
                    <div class="form-field editable-field">
                        <input type="text" id="custom_name" class="large-text" name="custom_name" value="<?php echo esc_attr($custom_name ? $custom_name : $contact->default_name); ?>" readonly>
                        <button type="button" id="edit-name" class="edit-button"><i class="material-icons">edit</i></button>
                    </div>

                    <!-- Default Name Field -->
                    <div class="form-field">
                        <span> <strong><?php echo esc_html__('Default Name:', 'professionaldirectory'); ?></strong>
                        <?php echo ' ' . esc_html($contact->default_name); ?> </span>
                    </div>

                    <!-- Email Field -->
                    <div class="form-field">
                        <label><strong><?php echo esc_html__('Email:', 'professionaldirectory'); ?></strong></label>
                        <span><?php echo esc_html($contact->email); ?></span>
                    </div>
                </div>

                <div class="pdr-column-right">
                    <!-- Status Dropdown -->
                    <div class="form-field client-status">
                        <label for="contact_status"><strong><?php echo esc_html__('Status:', 'professionaldirectory'); ?></strong></label>
                        <select name="contact_status" id="contact_status" class="regular-text">
                            <?php foreach (['active', 'lead', 'not_interested', 'client'] as $option): ?>
                                <option value="<?php echo esc_attr($option); ?>" <?php echo selected($status, $option, false); ?>>
                                    <?php echo esc_html(ucfirst($option)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <?php if (!empty($searches)): ?>
                <h2><?php echo esc_html__('Associated Searches', 'professionaldirectory'); ?></h2>
                <?php foreach ($searches as $search): ?>
                    <div class="search-details">
                        <p><strong><?php echo esc_html__('ID da Pesquisa:', 'professionaldirectory'); ?></strong> <?php echo esc_html($search->id); ?></p>
                        <p><strong><?php echo esc_html__('Data da Pesquisa:', 'professionaldirectory'); ?></strong> <?php echo esc_html($search->search_date); ?></p>
                        <p><strong><?php echo esc_html__('Tipo de Serviço:', 'professionaldirectory'); ?></strong> <?php echo esc_html($search->service_type); ?></p>
                        <div class="form-field">
                            <label for="search_status_<?php echo esc_attr($search->id); ?>"><strong><?php echo esc_html__('Status da Pesquisa:', 'professionaldirectory'); ?></strong></label>
                            <select name="searches[<?php echo esc_attr($search->id); ?>]" id="search_status_<?php echo esc_attr($search->id); ?>" class="regular-text">
                                <?php foreach (['pending', 'approved', 'rejected'] as $option): ?>
                                    <option value="<?php echo esc_attr($option); ?>" <?php echo selected($search->search_status, $option, false); ?>>
                                        <?php echo esc_html(ucfirst($option)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-field">
                <button type="submit" class="button button-primary"><?php echo esc_html__('Save All Changes', 'professionaldirectory'); ?></button>
            </div>
        </form>
    <?php else: ?>
        <p><?php echo esc_html__('No contact found.', 'professionaldirectory'); ?></p>
    <?php endif; ?>
</div>
<script src="<?php echo esc_url($js_url); ?>"></script>
