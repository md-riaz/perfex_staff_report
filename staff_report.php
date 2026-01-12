<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Advanced Leads Report
Description: Comprehensive staff lead performance report with dynamic status columns, advanced filtering, and ranking
Version: 1.1.0
Requires at least: 2.3.*
Author: Staff Report Module
Author URI: https://github.com/md-riaz/perfex_staff_report
*/

define('STAFF_REPORT_MODULE_NAME', 'staff_report');

hooks()->add_action('admin_init', 'staff_report_module_init_menu_items');

/**
 * Register activation module hook
 */
register_activation_hook(STAFF_REPORT_MODULE_NAME, 'staff_report_module_activation_hook');

function staff_report_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files
 */
register_language_files(STAFF_REPORT_MODULE_NAME, [STAFF_REPORT_MODULE_NAME]);

/**
 * Init staff report module menu items in setup in admin_init hook
 * @return null
 */
function staff_report_module_init_menu_items()
{
    $CI = &get_instance();

    $capabilities = [];
    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'view_own' => _l('permission_view_own'),
    ];

    register_staff_capabilities('staff_report', $capabilities, _l('staff_report'));

    if (has_permission('staff_report', '', 'view') || has_permission('staff_report', '', 'view_own')) {
        $CI->app_menu->add_sidebar_menu_item('staff-report', [
            'slug'     => 'staff-report',
            'name'     => _l('staff_report'),
            'icon'     => 'fa fa-bar-chart',
            'href'     => admin_url('staff_report'),
            'position' => 15,
        ]);
    }
}

/**
 * Get module upload path
 */
function staff_report_module_upload_path()
{
    return FCPATH . 'uploads/staff_report/';
}
