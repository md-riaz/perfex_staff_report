<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Staff_report_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get staff lead report with dynamic status columns
     * @param array $filters
     * @return array
     */
    public function get_staff_lead_report($filters = [])
    {
        // Get all lead statuses
        $this->db->order_by('statusorder', 'ASC');
        $statuses = $this->db->get(db_prefix() . 'leads_status')->result_array();

        // Build base query for leads with filters
        $this->db->select(db_prefix() . 'leads.assigned, ' . db_prefix() . 'leads.status, COUNT(' . db_prefix() . 'leads.id) as count');
        $this->db->from(db_prefix() . 'leads');
        
        // Apply date filters
        if (!empty($filters['date_from'])) {
            $this->db->where(db_prefix() . 'leads.dateadded >=', to_sql_date($filters['date_from'], true));
        }
        if (!empty($filters['date_to'])) {
            $this->db->where(db_prefix() . 'leads.dateadded <=', to_sql_date($filters['date_to'], true));
        }
        
        // Apply date assigned filter
        if (!empty($filters['date_assigned_from'])) {
            $this->db->where(db_prefix() . 'leads.assigned >=', to_sql_date($filters['date_assigned_from'], true));
        }
        if (!empty($filters['date_assigned_to'])) {
            $this->db->where(db_prefix() . 'leads.assigned <=', to_sql_date($filters['date_assigned_to'], true));
        }
        
        // Apply last contact date filter
        if (!empty($filters['last_contact_from'])) {
            $this->db->where(db_prefix() . 'leads.lastcontact >=', to_sql_date($filters['last_contact_from'], true));
        }
        if (!empty($filters['last_contact_to'])) {
            $this->db->where(db_prefix() . 'leads.lastcontact <=', to_sql_date($filters['last_contact_to'], true));
        }

        // Apply staff filter
        if (!empty($filters['staff_id'])) {
            if (is_array($filters['staff_id'])) {
                $this->db->where_in(db_prefix() . 'leads.assigned', $filters['staff_id']);
            } else {
                $this->db->where(db_prefix() . 'leads.assigned', $filters['staff_id']);
            }
        }

        // Apply source filter
        if (!empty($filters['source_id'])) {
            if (is_array($filters['source_id'])) {
                $this->db->where_in(db_prefix() . 'leads.source', $filters['source_id']);
            } else {
                $this->db->where(db_prefix() . 'leads.source', $filters['source_id']);
            }
        }

        // Apply status filter
        if (!empty($filters['status_id'])) {
            if (is_array($filters['status_id'])) {
                $this->db->where_in(db_prefix() . 'leads.status', $filters['status_id']);
            } else {
                $this->db->where(db_prefix() . 'leads.status', $filters['status_id']);
            }
        }
        
        // Apply lead field filters
        if (!empty($filters['name'])) {
            $this->db->like(db_prefix() . 'leads.name', $filters['name']);
        }
        if (!empty($filters['email'])) {
            $this->db->like(db_prefix() . 'leads.email', $filters['email']);
        }
        if (!empty($filters['phone'])) {
            $this->db->like(db_prefix() . 'leads.phonenumber', $filters['phone']);
        }
        if (!empty($filters['country'])) {
            $this->db->where(db_prefix() . 'leads.country', $filters['country']);
        }
        if (!empty($filters['city'])) {
            $this->db->like(db_prefix() . 'leads.city', $filters['city']);
        }
        if (!empty($filters['state'])) {
            $this->db->like(db_prefix() . 'leads.state', $filters['state']);
        }
        if (!empty($filters['zip'])) {
            $this->db->like(db_prefix() . 'leads.zip', $filters['zip']);
        }
        if (isset($filters['lead_value_from']) && $filters['lead_value_from'] !== '') {
            $this->db->where(db_prefix() . 'leads.lead_value >=', (float)$filters['lead_value_from']);
        }
        if (isset($filters['lead_value_to']) && $filters['lead_value_to'] !== '') {
            $this->db->where(db_prefix() . 'leads.lead_value <=', (float)$filters['lead_value_to']);
        }
        if (isset($filters['is_public']) && $filters['is_public'] !== '') {
            $this->db->where(db_prefix() . 'leads.is_public', (int)$filters['is_public']);
        }
        if (isset($filters['lost']) && $filters['lost'] !== '') {
            $this->db->where(db_prefix() . 'leads.lost', (int)$filters['lost']);
        }
        if (isset($filters['junk']) && $filters['junk'] !== '') {
            $this->db->where(db_prefix() . 'leads.junk', (int)$filters['junk']);
        }

        // Apply custom field filters
        if (!empty($filters['custom_fields']) && is_array($filters['custom_fields'])) {
            foreach ($filters['custom_fields'] as $field_id => $field_value) {
                if ($field_value !== '' && $field_value !== null) {
                    $this->db->join(
                        db_prefix() . 'customfieldsvalues cfv_' . $field_id,
                        'cfv_' . $field_id . '.relid = ' . db_prefix() . 'leads.id AND cfv_' . $field_id . '.fieldto = "leads" AND cfv_' . $field_id . '.fieldid = ' . $this->db->escape($field_id),
                        'left'
                    );
                    $this->db->like('cfv_' . $field_id . '.value', $field_value);
                }
            }
        }

        $this->db->group_by([db_prefix() . 'leads.assigned', db_prefix() . 'leads.status']);
        $lead_counts = $this->db->get()->result_array();

        // Get all staff members
        $this->db->select('staffid, CONCAT(firstname, " ", lastname) as full_name, firstname, lastname');
        $this->db->from(db_prefix() . 'staff');
        $this->db->where('active', 1);
        $this->db->order_by('firstname', 'ASC');
        
        if (!empty($filters['staff_id'])) {
            if (is_array($filters['staff_id'])) {
                $this->db->where_in('staffid', $filters['staff_id']);
            } else {
                $this->db->where('staffid', $filters['staff_id']);
            }
        }
        
        $staff_members = $this->db->get()->result_array();

        // Organize data by staff and status
        $organized_data = [];
        foreach ($staff_members as $staff) {
            $staff_id = $staff['staffid'];
            $organized_data[$staff_id] = [
                'staff_id'         => $staff_id,
                'staff_name'       => $staff['full_name'],
                'status_counts'    => [],
                'status_percentages' => [],
                'total'            => 0,
            ];

            // Initialize all status counts to 0
            foreach ($statuses as $status) {
                $organized_data[$staff_id]['status_counts'][$status['id']] = 0;
                $organized_data[$staff_id]['status_percentages'][$status['id']] = 0;
            }
        }

        // Fill in actual counts
        foreach ($lead_counts as $count) {
            if (isset($organized_data[$count['assigned']])) {
                $organized_data[$count['assigned']]['status_counts'][$count['status']] = (int)$count['count'];
                $organized_data[$count['assigned']]['total'] += (int)$count['count'];
            }
        }
        
        // Calculate percentages
        foreach ($organized_data as $staff_id => $staff_data) {
            if ($staff_data['total'] > 0) {
                foreach ($statuses as $status) {
                    $count = $staff_data['status_counts'][$status['id']];
                    $organized_data[$staff_id]['status_percentages'][$status['id']] = 
                        round(($count / $staff_data['total']) * 100, 2);
                }
            }
        }

        // Calculate totals row
        $totals = [
            'staff_id'           => null,
            'staff_name'         => _l('total'),
            'status_counts'      => [],
            'status_percentages' => [],
            'total'              => 0,
        ];

        foreach ($statuses as $status) {
            $totals['status_counts'][$status['id']] = 0;
            $totals['status_percentages'][$status['id']] = 0;
        }

        foreach ($organized_data as $staff_data) {
            foreach ($statuses as $status) {
                $totals['status_counts'][$status['id']] += $staff_data['status_counts'][$status['id']];
            }
            $totals['total'] += $staff_data['total'];
        }
        
        // Calculate total percentages
        if ($totals['total'] > 0) {
            foreach ($statuses as $status) {
                $count = $totals['status_counts'][$status['id']];
                $totals['status_percentages'][$status['id']] = 
                    round(($count / $totals['total']) * 100, 2);
            }
        }

        // Convert to indexed array and sort by total (descending) - staff ranking
        $data = array_values($organized_data);
        usort($data, function($a, $b) {
            return $b['total'] - $a['total'];
        });
        $data[] = $totals;

        return [
            'statuses' => $statuses,
            'data'     => $data,
        ];
    }

    /**
     * Get lead count for specific staff and status
     * @param int $staff_id
     * @param int $status_id
     * @param array $filters
     * @return int
     */
    public function get_lead_count($staff_id, $status_id, $filters = [])
    {
        $this->db->select('COUNT(id) as count');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('assigned', $staff_id);
        $this->db->where('status', $status_id);

        // Apply date filter
        if (!empty($filters['date_from'])) {
            $this->db->where('dateadded >=', to_sql_date($filters['date_from'], true));
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('dateadded <=', to_sql_date($filters['date_to'], true));
        }

        // Apply source filter
        if (!empty($filters['source_id'])) {
            $this->db->where('source', $filters['source_id']);
        }

        $result = $this->db->get()->row();
        return $result ? (int)$result->count : 0;
    }
}
