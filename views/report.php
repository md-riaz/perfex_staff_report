<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link href="<?php echo module_dir_url('staff_report', 'assets/css/staff_report.css'); ?>" rel="stylesheet" type="text/css" />
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo _l('staff_report'); ?>
                                </h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>

                        <!-- Filters Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <i class="fa fa-filter"></i> <?php echo _l('filters'); ?>
                                        <a href="#" class="pull-right" onclick="toggleFilters(); return false;">
                                            <i class="fa fa-angle-down" id="filter-toggle-icon"></i>
                                        </a>
                                    </div>
                                    <div class="panel-body" id="filters-panel" style="display: block;">
                                        <!-- Date Filters Section -->
                                        <div id="date-filters-section" style="display: none;">
                                            <h5>
                                                <i class="fa fa-calendar"></i> <?php echo _l('date_filters'); ?>
                                                <a href="#" class="pull-right" onclick="toggleSection('date-filters-section'); return false;">
                                                    <i class="fa fa-angle-up"></i>
                                                </a>
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date_from"><?php echo _l('leads_dt_datecreated_from'); ?></label>
                                                        <input type="date" id="date_from" name="date_from" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date_to"><?php echo _l('leads_dt_datecreated_to'); ?></label>
                                                        <input type="date" id="date_to" name="date_to" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date_assigned_from"><?php echo _l('date_assigned_from'); ?></label>
                                                        <input type="date" id="date_assigned_from" name="date_assigned_from" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="date_assigned_to"><?php echo _l('date_assigned_to'); ?></label>
                                                        <input type="date" id="date_assigned_to" name="date_assigned_to" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="last_contact_from"><?php echo _l('last_contact_from'); ?></label>
                                                        <input type="date" id="last_contact_from" name="last_contact_from" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="last_contact_to"><?php echo _l('last_contact_to'); ?></label>
                                                        <input type="date" id="last_contact_to" name="last_contact_to" class="form-control" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                        
                                        <!-- System Filters Section -->
                                        <h5><i class="fa fa-filter"></i> <?php echo _l('system_filters'); ?></h5>
                                        <?php if (has_permission('staff_report', '', 'view')) { ?>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="staff_id"><?php echo _l('staff_member'); ?></label>
                                                    <select name="staff_id" id="staff_id" class="form-control selectpicker" data-live-search="true" multiple data-actions-box="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <?php foreach ($staff_members as $member) { ?>
                                                            <option value="<?php echo $member['staffid']; ?>">
                                                                <?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="source"><?php echo _l('lead_source'); ?></label>
                                                    <select name="source" id="source" class="form-control selectpicker" data-live-search="true" multiple data-actions-box="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <?php foreach ($sources as $s) { ?>
                                                            <option value="<?php echo $s['id']; ?>">
                                                                <?php echo $s['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><?php echo _l('lead_status'); ?></label>
                                                    <select name="status" id="status" class="form-control selectpicker" data-live-search="true" multiple data-actions-box="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <?php foreach ($lead_statuses as $status) { ?>
                                                            <option value="<?php echo $status['id']; ?>">
                                                                <?php echo $status['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        
                                        <!-- Show Additional Filters Buttons -->
                                        <div class="row" style="margin-top: 15px;">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-default btn-sm" onclick="toggleSection('date-filters-section'); return false;">
                                                    <i class="fa fa-calendar"></i> <?php echo _l('date_filters'); ?>
                                                </button>
                                                <button type="button" class="btn btn-default btn-sm" onclick="toggleSection('lead-fields-section'); return false;">
                                                    <i class="fa fa-user"></i> <?php echo _l('lead_fields'); ?>
                                                </button>
                                                <?php if (!empty($custom_fields)) { ?>
                                                <button type="button" class="btn btn-default btn-sm" onclick="toggleSection('custom-fields-section'); return false;">
                                                    <i class="fa fa-cog"></i> <?php echo _l('custom_fields'); ?>
                                                </button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <!-- Lead Field Filters Section -->
                                        <div id="lead-fields-section" style="display: none;">
                                            <h5>
                                                <i class="fa fa-user"></i> <?php echo _l('lead_fields'); ?>
                                                <a href="#" class="pull-right" onclick="toggleSection('lead-fields-section'); return false;">
                                                    <i class="fa fa-angle-up"></i>
                                                </a>
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo _l('lead_name'); ?></label>
                                                        <input type="text" id="name" name="name" class="form-control" placeholder="<?php echo _l('search_by_name'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email"><?php echo _l('lead_email'); ?></label>
                                                        <input type="text" id="email" name="email" class="form-control" placeholder="<?php echo _l('search_by_email'); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="phone"><?php echo _l('lead_phone'); ?></label>
                                                        <input type="text" id="phone" name="phone" class="form-control" placeholder="<?php echo _l('search_by_phone'); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="country"><?php echo _l('clients_country'); ?></label>
                                                        <select name="country" id="country" class="form-control selectpicker" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                                                            <?php foreach ($countries as $country) { ?>
                                                                <option value="<?php echo $country['country_id']; ?>">
                                                                    <?php echo $country['short_name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="city"><?php echo _l('clients_city'); ?></label>
                                                        <input type="text" id="city" name="city" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="state"><?php echo _l('clients_state'); ?></label>
                                                        <input type="text" id="state" name="state" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="zip"><?php echo _l('clients_zip'); ?></label>
                                                        <input type="text" id="zip" name="zip" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="lead_value_from"><?php echo _l('lead_value_from'); ?></label>
                                                        <input type="number" step="0.01" id="lead_value_from" name="lead_value_from" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="lead_value_to"><?php echo _l('lead_value_to'); ?></label>
                                                        <input type="number" step="0.01" id="lead_value_to" name="lead_value_to" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="is_public"><?php echo _l('lead_public'); ?></label>
                                                        <select name="is_public" id="is_public" class="form-control selectpicker">
                                                            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                                                            <option value="1"><?php echo _l('lead_public'); ?></option>
                                                            <option value="0"><?php echo _l('lead_private'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="lost"><?php echo _l('lead_lost'); ?></label>
                                                        <select name="lost" id="lost" class="form-control selectpicker">
                                                            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                                                            <option value="1"><?php echo _l('lead_lost'); ?></option>
                                                            <option value="0"><?php echo _l('not_lost'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="junk"><?php echo _l('lead_junk'); ?></label>
                                                        <select name="junk" id="junk" class="form-control selectpicker">
                                                            <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                                                            <option value="1"><?php echo _l('lead_junk'); ?></option>
                                                            <option value="0"><?php echo _l('not_junk'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <?php if (!empty($custom_fields)) { ?>
                                        <div id="custom-fields-section" style="display: none;">
                                            <h5>
                                                <?php echo _l('custom_fields'); ?>
                                                <a href="#" class="pull-right" onclick="toggleSection('custom-fields-section'); return false;">
                                                    <i class="fa fa-angle-up"></i>
                                                </a>
                                            </h5>
                                            <div class="row">
                                                <?php foreach ($custom_fields as $field) { ?>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="custom_field_<?php echo $field['id']; ?>">
                                                            <?php echo $field['name']; ?>
                                                        </label>
                                                        <?php $field_name = 'custom_fields[' . $field['id'] . ']'; ?>
                                                        <?php if ($field['type'] == 'select' || $field['type'] == 'multiselect') { ?>
                                                            <select name="<?php echo $field_name; ?>" id="custom_field_<?php echo $field['id']; ?>" class="form-control selectpicker" data-live-search="true">
                                                                <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                                                                <?php 
                                                                $options = explode(',', $field['options']);
                                                                foreach ($options as $option) { 
                                                                    $option = trim($option);
                                                                ?>
                                                                    <option value="<?php echo $option; ?>"><?php echo $option; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } elseif ($field['type'] == 'date_picker') { ?>
                                                            <input type="date" id="custom_field_<?php echo $field['id']; ?>" name="<?php echo $field_name; ?>" class="form-control" autocomplete="off">
                                                        <?php } else { ?>
                                                            <input type="text" id="custom_field_<?php echo $field['id']; ?>" name="<?php echo $field_name; ?>" class="form-control">
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <hr>
                                        </div>
                                        <?php } ?>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-info" onclick="applyFilters();">
                                                    <i class="fa fa-search"></i> <?php echo _l('apply_filters'); ?>
                                                </button>
                                                <button type="button" class="btn btn-default" onclick="resetFilters();">
                                                    <i class="fa fa-refresh"></i> <?php echo _l('reset'); ?>
                                                </button>
                                                <a href="#" onclick="exportReport(); return false;" class="btn btn-success pull-right">
                                                    <i class="fa fa-file-excel-o"></i> <?php echo _l('export_to_excel'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Table Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <div id="report-loading" class="text-center" style="display: none; padding: 50px;">
                                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                                    <p><?php echo _l('loading'); ?>...</p>
                                </div>
                                <div id="report-container" style="overflow-x: auto;">
                                    <table class="table table-striped table-bordered dt-table" id="staff-report-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo _l('staff_member'); ?></th>
                                                <!-- Dynamic status columns will be inserted here -->
                                                <th><?php echo _l('total'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="100%" class="text-center"><?php echo _l('click_apply_filters_to_generate_report'); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var reportData = null;

window.addEventListener('DOMContentLoaded', function() {
    // Initialize selectpicker
    if (typeof $.fn.selectpicker !== 'undefined') {
        $('.selectpicker').selectpicker('refresh');
    }

    // Load report on page load with no filters
    applyFilters();
});

function toggleFilters() {
    var panel = document.getElementById('filters-panel');
    var icon = document.getElementById('filter-toggle-icon');
    
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
        icon.className = 'fa fa-angle-down';
    } else {
        panel.style.display = 'none';
        icon.className = 'fa fa-angle-up';
    }
}

function toggleSection(sectionId) {
    var section = document.getElementById(sectionId);
    if (section) {
        if (section.style.display === 'none') {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    }
}

function applyFilters() {
    document.getElementById('report-loading').style.display = 'block';
    document.getElementById('report-container').style.display = 'none';

    var filterData = {
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        date_assigned_from: document.getElementById('date_assigned_from').value,
        date_assigned_to: document.getElementById('date_assigned_to').value,
        last_contact_from: document.getElementById('last_contact_from').value,
        last_contact_to: document.getElementById('last_contact_to').value,
        staff_id: getSelectValue('staff_id'),
        source_id: getSelectValue('source'),
        status_id: getSelectValue('status'),
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        country: document.getElementById('country').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        zip: document.getElementById('zip').value,
        lead_value_from: document.getElementById('lead_value_from').value,
        lead_value_to: document.getElementById('lead_value_to').value,
        is_public: document.getElementById('is_public').value,
        lost: document.getElementById('lost').value,
        junk: document.getElementById('junk').value,
        custom_fields: {}
    };

    // Collect custom field values
    var customFieldInputs = document.querySelectorAll('[name^="custom_fields["]');
    customFieldInputs.forEach(function(input) {
        var matches = input.getAttribute('name').match(/custom_fields\[(\d+)\]/);
        if (matches && input.value) {
            filterData.custom_fields[matches[1]] = input.value;
        }
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', admin_url + 'staff_report/get_report_data', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    reportData = response;
                    renderReport(response);
                } else {
                    alert(response.message || '<?php echo _l('error_loading_report'); ?>');
                }
            } catch (e) {
                console.error('Error parsing response:', e);
                alert('<?php echo _l('error_loading_report'); ?>');
            }
        } else {
            console.error('Error loading report:', xhr.status);
            alert('<?php echo _l('error_loading_report'); ?>');
        }
        document.getElementById('report-loading').style.display = 'none';
        document.getElementById('report-container').style.display = 'block';
    };
    
    xhr.onerror = function() {
        console.error('Error loading report');
        alert('<?php echo _l('error_loading_report'); ?>');
        document.getElementById('report-loading').style.display = 'none';
        document.getElementById('report-container').style.display = 'block';
    };
    
    xhr.send(encodeFormData(filterData));
}

function getSelectValue(id) {
    var select = document.getElementById(id);
    if (!select) return '';
    
    var selectedOptions = Array.from(select.selectedOptions || []);
    if (selectedOptions.length === 0) return '';
    if (selectedOptions.length === 1) return selectedOptions[0].value;
    
    return selectedOptions.map(function(opt) { return opt.value; });
}

function encodeFormData(data) {
    var params = [];
    for (var key in data) {
        if (data.hasOwnProperty(key)) {
            var value = data[key];
            if (value !== null && value !== undefined && value !== '') {
                if (Array.isArray(value)) {
                    value.forEach(function(v) {
                        params.push(encodeURIComponent(key + '[]') + '=' + encodeURIComponent(v));
                    });
                } else if (typeof value === 'object') {
                    for (var subKey in value) {
                        if (value.hasOwnProperty(subKey)) {
                            params.push(encodeURIComponent(key + '[' + subKey + ']') + '=' + encodeURIComponent(value[subKey]));
                        }
                    }
                } else {
                    params.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
                }
            }
        }
    }
    return params.join('&');
}

function renderReport(data) {
    var table = document.getElementById('staff-report-table');
    var thead = table.querySelector('thead tr');
    var tbody = table.querySelector('tbody');

    // Clear existing content
    thead.innerHTML = '';
    tbody.innerHTML = '';

    // Build header with rank column
    var headerHtml = '<th><?php echo _l('rank'); ?></th>';
    headerHtml += '<th><?php echo _l('staff_member'); ?></th>';
    data.statuses.forEach(function(status) {
        headerHtml += '<th style="background-color: ' + status.color + '; color: white;" class="text-center">' + 
                      escapeHtml(status.name) + '</th>';
    });
    headerHtml += '<th class="text-center"><strong><?php echo _l('total'); ?></strong></th>';
    thead.innerHTML = headerHtml;

    // Build body with rankings and percentages
    var bodyHtml = '';
    var rank = 1;
    data.data.forEach(function(row) {
        var isTotal = row.staff_id === null;
        var rowClass = isTotal ? 'bg-info text-bold' : '';
        
        bodyHtml += '<tr class="' + rowClass + '">';
        
        // Rank column
        if (!isTotal) {
            bodyHtml += '<td class="text-center"><strong>#' + rank + '</strong></td>';
            rank++;
        } else {
            bodyHtml += '<td class="text-center"><strong>-</strong></td>';
        }
        
        bodyHtml += '<td>' + (isTotal ? '<strong>' : '') + escapeHtml(row.staff_name) + (isTotal ? '</strong>' : '') + '</td>';
        
        data.statuses.forEach(function(status) {
            var count = row.status_counts[status.id] || 0;
            var percentage = row.status_percentages && row.status_percentages[status.id] ? row.status_percentages[status.id] : 0;
            var displayText = count;
            
            // Show percentage if available and not zero
            if (count > 0 && percentage > 0) {
                displayText += ' <small>(' + percentage + '%)</small>';
            }
            
            bodyHtml += '<td class="text-center">' + (isTotal ? '<strong>' : '') + displayText + (isTotal ? '</strong>' : '') + '</td>';
        });
        
        bodyHtml += '<td class="text-center"><strong>' + row.total + '</strong></td>';
        bodyHtml += '</tr>';
    });
    
    tbody.innerHTML = bodyHtml;
}

function resetFilters() {
    // Reset date filters
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('date_assigned_from').value = '';
    document.getElementById('date_assigned_to').value = '';
    document.getElementById('last_contact_from').value = '';
    document.getElementById('last_contact_to').value = '';
    
    // Reset system filters
    resetSelect('staff_id');
    resetSelect('source');
    resetSelect('status');
    
    // Reset lead field filters
    document.getElementById('name').value = '';
    document.getElementById('email').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('city').value = '';
    document.getElementById('state').value = '';
    document.getElementById('zip').value = '';
    document.getElementById('lead_value_from').value = '';
    document.getElementById('lead_value_to').value = '';
    
    resetSelect('country');
    resetSelect('is_public');
    resetSelect('lost');
    resetSelect('junk');
    
    // Reset custom fields
    var customFieldInputs = document.querySelectorAll('[name^="custom_fields["]');
    customFieldInputs.forEach(function(input) {
        input.value = '';
    });
    
    // Refresh all selectpickers
    if (typeof $.fn.selectpicker !== 'undefined') {
        $('.selectpicker').selectpicker('refresh');
    }
    
    applyFilters();
}

function resetSelect(id) {
    var select = document.getElementById(id);
    if (select) {
        Array.from(select.options).forEach(function(option) {
            option.selected = false;
        });
        if (typeof $.fn.selectpicker !== 'undefined') {
            $(select).selectpicker('refresh');
        }
    }
}

function exportReport() {
    var params = new URLSearchParams({
        date_from: document.getElementById('date_from').value,
        date_to: document.getElementById('date_to').value,
        date_assigned_from: document.getElementById('date_assigned_from').value,
        date_assigned_to: document.getElementById('date_assigned_to').value,
        last_contact_from: document.getElementById('last_contact_from').value,
        last_contact_to: document.getElementById('last_contact_to').value,
        staff_id: getSelectValue('staff_id'),
        source_id: getSelectValue('source'),
        status_id: getSelectValue('status'),
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        country: document.getElementById('country').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        zip: document.getElementById('zip').value,
        lead_value_from: document.getElementById('lead_value_from').value,
        lead_value_to: document.getElementById('lead_value_to').value,
        is_public: document.getElementById('is_public').value,
        lost: document.getElementById('lost').value,
        junk: document.getElementById('junk').value
    });

    // Add custom fields
    var customFieldInputs = document.querySelectorAll('[name^="custom_fields["]');
    customFieldInputs.forEach(function(input) {
        var matches = input.getAttribute('name').match(/custom_fields\[(\d+)\]/);
        if (matches && input.value) {
            params.append('custom_fields[' + matches[1] + ']', input.value);
        }
    });

    window.location.href = admin_url + 'staff_report/export?' + params.toString();
}

function escapeHtml(text) {
    if (text === null || text === undefined) {
        return '';
    }
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}
</script>

<?php init_tail(); ?>
