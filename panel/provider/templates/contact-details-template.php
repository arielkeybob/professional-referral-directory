<?php
    defined('ABSPATH') or die('No script kiddies please!');
?>

<div class="wrap">
    <h1 class="header"><?php echo esc_html__('Contact Details', 'referralhub'); ?></h1>

    <?php if ($contact) : ?>
        <div class="row contact-content">
            <div class="col s12">
                <form id="contact-form" method="post">
                    <?php wp_nonce_field('update_contact_' . $contact_id, '_wpnonce', false); ?>
                    <input type="hidden" name="action" value="save_contact_details">
                    <input type="hidden" name="contact_id" value="<?php echo esc_attr($contact_id); ?>">

                    <div class="row">
                        <!-- Custom Name Field -->
                        <div class="input-field col s12 m6">
                            <div>
                                <input type="text" id="custom_name" class="validate" name="custom_name" value="<?php echo esc_attr($custom_name ? $custom_name : $contact->default_name); ?>" readonly>
                                <a href="javascript:void(0);" id="edit-name" class="btn-floating waves-effect waves-light red"><i class="material-icons">edit</i></a>
                            </div>
                            <!-- Default Name Field Display -->
                            <div>
                                <p class="form-static-text">
                                    <strong><?php echo esc_html__('Default Name:', 'referralhub'); ?></strong>
                                    <?php echo esc_html($contact->default_name); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Contact Status Dropdown -->
                        <div class="col s12 m6">
                            <div class="col s12 m6" style="display: flex; align-items: center; justify-content: flex-end;">
                                <span style="margin-right: 10px;">
                                    <?php echo esc_html__('Contact Tag:', 'referralhub'); ?>
                                </span>
                                <select name="contact_status" id="contact_status">
                                    <option value="" disabled selected><?php echo esc_html__('Choose Status', 'referralhub'); ?></option>
                                    <?php foreach (['active', 'lead', 'not_interested', 'client'] as $option) : ?>
                                        <option value="<?php echo esc_attr($option); ?>" <?php echo selected($status, $option, false); ?>><?php echo esc_html(ucfirst($option)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Inicialização do Materialize Select -->
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var elems = document.querySelectorAll('select');
                                var instances = M.FormSelect.init(elems, {});

                                // Ajustando a largura do dropdown para se adequar ao conteúdo mais largo
                                var select = document.getElementById('contact_status');
                                var instance = M.FormSelect.getInstance(select);
                                select.style.width = instance.dropdownOptions.clientWidth + 'px';
                            });
                        </script>
                        
                    </div>

                    <div class="row">
                        <!-- Email Field Display -->
                        <div class="col s12 m6">
                            <p class="form-static-text">
                                <strong><?php echo esc_html__('Email:', 'referralhub'); ?></strong>
                                <?php echo esc_html($contact->email); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Associated Inquiries -->
                    <?php if (!empty($inquiries)) : ?>
                        <div class="contact-inquiries">
                            <h2><?php echo esc_html__('Associated Inquiries', 'referralhub'); ?></h2>
                            <div class="row"> <!-- Garante que os cards estarão em uma estrutura de grid -->
                                <?php foreach ($inquiries as $inquiry) : ?>
                                    <div class="col s12 m6 l4"> <!-- Cards lado a lado conforme o tamanho da tela -->
                                        <div class="card">
                                            <div class="card-content">
                                                <span class="card-title"><?php echo get_the_title($inquiry->service_id); ?></span>

                                                <p><strong><?php echo esc_html__('Inquiry Date:', 'referralhub'); ?></strong> <?php echo esc_html($inquiry->inquiry_date); ?></p>
                                                <p><strong><?php echo esc_html__('Service Type:', 'referralhub'); ?></strong> <?php echo esc_html($inquiry->service_type); ?></p>

                                                <div class="input-field">
                                                    <select name="inquiries[<?php echo esc_attr($inquiry->id); ?>]" id="inquiry_status_<?php echo esc_attr($inquiry->id); ?>">
                                                        <option value="" disabled selected><?php echo esc_html__('Choose Status', 'referralhub'); ?></option>
                                                        <?php foreach (['pending_response', 'in_negotiation', 'agreement_reached', 'no_deal'] as $option) : ?>
                                                            <option value="<?php echo esc_attr($option); ?>" <?php echo selected($inquiry->inquiry_status, $option, false); ?>><?php echo esc_html(ucfirst(str_replace('_', ' ', $option))); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <label for="inquiry_status_<?php echo esc_attr($inquiry->id); ?>"><?php echo esc_html__('Inquiry Status:', 'referralhub'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="col s12">
                        <button type="submit" class="btn waves-effect waves-light"><?php echo esc_html__('Save All Changes', 'referralhub'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php else : ?>
        <p><?php echo esc_html__('No contact found.', 'referralhub'); ?></p>
    <?php endif; ?>
</div>
<script src="<?php echo esc_url($js_url); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        M.FormSelect.init(document.querySelectorAll('select'));
    });
</script>
