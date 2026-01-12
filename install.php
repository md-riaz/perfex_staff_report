<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'staff_report_settings')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'staff_report_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `value` text,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

// Add default permissions
$capabilities = [
    'view',
    'view_own'
];

foreach ($capabilities as $capability) {
    add_option('staff_report_' . $capability, 1);
}
