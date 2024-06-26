<?php
    defined('ABSPATH') or die('No script kiddies please!');

class ContactService {
    protected $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function getContactsByAuthor($authorId) {
        $query = $this->wpdb->prepare(
            "SELECT c.contact_id, c.email, c.default_name, car.custom_name
             FROM {$this->wpdb->prefix}rhb_author_contact_relations car
             JOIN {$this->wpdb->prefix}rhb_contacts c ON car.contact_id = c.contact_id
             WHERE car.author_id = %d
             GROUP BY c.contact_id",
            $authorId
        );
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getContactDetailsById($contactId) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}rhb_contacts WHERE contact_id = %d",
            $contactId
        );
        return $this->wpdb->get_row($query);
    }

    public function getCustomNameAndStatus($contactId, $authorId) {
        $query = $this->wpdb->prepare(
            "SELECT custom_name, status FROM {$this->wpdb->prefix}rhb_author_contact_relations WHERE contact_id = %d AND author_id = %d",
            $contactId,
            $authorId
        );
        return $this->wpdb->get_row($query);
    }

    public function getInquiriesByContactId($contactId, $authorId) {
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->wpdb->prefix}rhb_inquiry_data WHERE contact_id = %d AND author_id = %d",
            $contactId,
            $authorId
        );
        return $this->wpdb->get_results($query);
    }

    public function getTotalInquiriesByService($service_id) {
        $query = $this->wpdb->prepare("SELECT COUNT(*) FROM {$this->wpdb->prefix}rhb_inquiry_data WHERE service_id = %d", $service_id);
        return (int) $this->wpdb->get_var($query);
    }

    public function getRecentInquiries($limit = 5) {
        $query = $this->wpdb->prepare("SELECT * FROM {$this->wpdb->prefix}rhb_inquiry_data ORDER BY inquiry_date DESC LIMIT %d", $limit);
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getInquiriesDistributionByServiceType() {
        $query = "SELECT service_type, COUNT(*) as total FROM {$this->wpdb->prefix}rhb_inquiry_data GROUP BY service_type";
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getServicesByCurrentUser() {
        $current_user_id = get_current_user_id();
        $post_type = 'rhb_service';
        $query = $this->wpdb->prepare("SELECT ID, post_title FROM {$this->wpdb->posts} WHERE post_type = %s AND post_author = %d", $post_type, $current_user_id);
        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function getRecentInquiriesForUser($limit = 10) {
        $current_user_id = get_current_user_id();
        $query = $this->wpdb->prepare(
            "SELECT sd.service_type, sd.inquiry_date, p.post_title, c.default_name AS name, c.email, sd.service_location, sd.contact_id
             FROM {$this->wpdb->prefix}rhb_inquiry_data AS sd
             INNER JOIN {$this->wpdb->prefix}rhb_author_contact_relations AS acr ON sd.contact_id = acr.contact_id
             INNER JOIN {$this->wpdb->prefix}rhb_contacts AS c ON acr.contact_id = c.contact_id
             INNER JOIN {$this->wpdb->posts} AS p ON sd.service_id = p.ID
             WHERE acr.author_id = %d
             ORDER BY sd.inquiry_date DESC 
             LIMIT %d", 
             $current_user_id, $limit
        );
        return $this->wpdb->get_results($query, ARRAY_A);
    }
}
