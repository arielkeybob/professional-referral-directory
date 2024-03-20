<?php
// contact-service.php

if (!defined('WPINC')) {
    die;
}

class ContactService {
    protected $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getContactsByAuthor($authorId) {
        $query = $this->wpdb->prepare(
            "SELECT c.contact_id, c.email, c.default_name, car.custom_name
             FROM {$this->wpdb->prefix}pdr_author_contact_relations car
             JOIN {$this->wpdb->prefix}pdr_contacts c ON car.contact_id = c.contact_id
             WHERE car.author_id = %d
             GROUP BY c.contact_id",
            $authorId
        );
        return $this->wpdb->get_results($query, ARRAY_A);
    }
}
