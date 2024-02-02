<?php
// Função auxiliar para obter termos de taxonomia como string
function pdr_get_taxonomy_terms_as_string($taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if (empty($terms)) {
        return '';
    }

    $term_list = array_map(function($term) {
        return esc_html($term->name);
    }, $terms);

    return implode(', ', $term_list);
}

// includes/utility-functions.php

function pdr_get_service_thumbnail_url($size = 'full') {
    if (has_post_thumbnail()) {
        return get_the_post_thumbnail_url(null, $size);
    } else {
        return plugins_url('public/img/service-placeholder.png', PDR_MAIN_FILE);
    }
}

