<?php
defined('ABSPATH') or die('No script kiddies please!');


class PDR_Geolocation {

    public static function geocode_address($address) {
        $api_key = get_option('pdr_google_maps_api_key');
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$api_key}";

        $response = wp_remote_get($url);
        $data = wp_remote_retrieve_body($response);

        if (!is_wp_error($response) && ($data = json_decode($data)) && $data->status == 'OK') {
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;
            return array('latitude' => $latitude, 'longitude' => $longitude);
        }

        return false;
    }
}
