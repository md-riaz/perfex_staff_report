<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Drop the settings table
if ($CI->db->table_exists(db_prefix() . 'staff_report_settings')) {
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'staff_report_settings`');
}

// Remove options
$capabilities = [
    'view',
    'view_own'
];

foreach ($capabilities as $capability) {
    delete_option('staff_report_' . $capability);
}
