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
        
        // Check if user can only view own data
        $staff_id = null;
        if (!has_permission('staff_report', '', 'view') && has_permission('staff_report', '', 'view_own')) {
            $staff_id = get_staff_user_id();
        } elseif (isset($data['staff_id']) && !empty($data['staff_id'])) {
            $staff_id = $data['staff_id'];
        }

        $result = $this->staff_report_model->get_staff_lead_report([
            'date_from'    => $data['date_from'] ?? null,
            'date_to'      => $data['date_to'] ?? null,
            'staff_id'     => $staff_id,
            'source_id'    => $data['source'] ?? null,
            'status_id'    => $data['status'] ?? null,
            'custom_fields' => $data['custom_fields'] ?? [],
        ]);

        header('Content-Type: application/json');
        echo json_encode($result);
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
        
        // Check if user can only view own data
        $staff_id = null;
        if (!has_permission('staff_report', '', 'view') && has_permission('staff_report', '', 'view_own')) {
            $staff_id = get_staff_user_id();
        } elseif (isset($data['staff_id']) && !empty($data['staff_id'])) {
            $staff_id = $data['staff_id'];
        }

        $result = $this->staff_report_model->get_staff_lead_report([
            'date_from'    => $data['date_from'] ?? null,
            'date_to'      => $data['date_to'] ?? null,
            'staff_id'     => $staff_id,
            'source_id'    => $data['source'] ?? null,
            'status_id'    => $data['status'] ?? null,
            'custom_fields' => $data['custom_fields'] ?? [],
        ]);

        $this->load->helper('export');
        
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

        $filename = 'staff_lead_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        export_excel($rows, $headers, $filename);
    }
}
