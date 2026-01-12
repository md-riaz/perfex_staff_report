<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Staff_report extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_report_model');
        $this->load->model('leads_model');
        $this->load->model('staff_model');
        $this->load->helper('staff_report');
    }

    /**
     * Main report view
     * @return void
     */
    public function index()
    {
        if (!has_permission('staff_report', '', 'view') && !has_permission('staff_report', '', 'view_own')) {
            access_denied('staff_report');
        }

        $data['title'] = _l('staff_report');
        
        // Get all staff members
        $data['staff_members'] = $this->staff_model->get();
        
        // Get lead statuses
        $this->load->model('leads_model');
        $data['lead_statuses'] = $this->leads_model->get_status();
        
        // Get lead sources
        $data['sources'] = $this->leads_model->get_source();
        
        // Load custom fields for leads
        $this->load->model('custom_fields_model');
        $data['custom_fields'] = $this->custom_fields_model->get('', [
            'fieldto' => 'leads',
            'active'  => 1,
        ]);

        $this->load->view('report', $data);
    }

    /**
     * Get report data via AJAX
     * @return json
     */
    public function get_report_data()
    {
        if (!has_permission('staff_report', '', 'view') && !has_permission('staff_report', '', 'view_own')) {
            ajax_access_denied();
        }

        $data = $this->input->post();
        
        // Sanitize filters
        $filters = staff_report_sanitize_filters($data);
        
        // Check if user can only view own data
        if (!has_permission('staff_report', '', 'view') && has_permission('staff_report', '', 'view_own')) {
            $filters['staff_id'] = get_staff_user_id();
        }

        try {
            $result = $this->staff_report_model->get_staff_lead_report($filters);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success'  => true,
                'data'     => $result['data'],
                'statuses' => $result['statuses'],
            ]);
        } catch (Exception $e) {
            log_message('error', 'Staff Report Error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => _l('error_loading_report'),
            ]);
        }
    }

    /**
     * Export report to Excel
     * @return void
     */
    public function export()
    {
        if (!has_permission('staff_report', '', 'view') && !has_permission('staff_report', '', 'view_own')) {
            access_denied('staff_report');
        }

        $data = $this->input->get();
        
        // Sanitize filters
        $filters = staff_report_sanitize_filters($data);
        
        // Check if user can only view own data
        if (!has_permission('staff_report', '', 'view') && has_permission('staff_report', '', 'view_own')) {
            $filters['staff_id'] = get_staff_user_id();
        }

        try {
            $result = $this->staff_report_model->get_staff_lead_report($filters);

            // Prepare export data
            $headers = [_l('staff_member')];
            foreach ($result['statuses'] as $status) {
                $headers[] = $status['name'];
            }
            $headers[] = _l('total');

            $rows = [];
            foreach ($result['data'] as $row) {
                $export_row = [$row['staff_name']];
                foreach ($result['statuses'] as $status) {
                    $export_row[] = $row['status_counts'][$status['id']] ?? 0;
                }
                $export_row[] = $row['total'];
                $rows[] = $export_row;
            }

            // Use simple CSV export as fallback if export helper doesn't exist
            $filename = 'staff_lead_report_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            
            foreach ($rows as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
        } catch (Exception $e) {
            log_message('error', 'Staff Report Export Error: ' . $e->getMessage());
            set_alert('danger', _l('error_loading_report'));
            redirect(admin_url('staff_report'));
        }
    }
}
