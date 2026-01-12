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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date_from"><?php echo _l('report_from'); ?></label>
                                                    <input type="text" id="date_from" name="date_from" class="form-control datepicker" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date_to"><?php echo _l('report_to'); ?></label>
                                                    <input type="text" id="date_to" name="date_to" class="form-control datepicker" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (has_permission('staff_report', '', 'view')) { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="staff_id"><?php echo _l('staff_member'); ?></label>
                                                    <select name="staff_id" id="staff_id" class="form-control selectpicker" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""><?php echo _l('staff_report_all_staff'); ?></option>
                                                        <?php foreach ($staff_members as $member) { ?>
                                                            <option value="<?php echo $member['staffid']; ?>">
                                                                <?php echo $member['firstname'] . ' ' . $member['lastname']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="source"><?php echo _l('lead_source'); ?></label>
                                                    <select name="source" id="source" class="form-control selectpicker" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""><?php echo _l('leads_all'); ?></option>
                                                        <?php foreach ($sources as $s) { ?>
                                                            <option value="<?php echo $s['id']; ?>">
                                                                <?php echo $s['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status"><?php echo _l('lead_status'); ?></label>
                                                    <select name="status" id="status" class="form-control selectpicker" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                        <option value=""><?php echo _l('leads_all'); ?></option>
                                                        <?php foreach ($lead_statuses as $status) { ?>
                                                            <option value="<?php echo $status['id']; ?>">
                                                                <?php echo $status['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($custom_fields)) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <hr>
                                                <h5><?php echo _l('custom_fields'); ?></h5>
                                            </div>
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
                                                        <input type="text" id="custom_field_<?php echo $field['id']; ?>" name="<?php echo $field_name; ?>" class="form-control datepicker" autocomplete="off">
                                                    <?php } else { ?>
                                                        <input type="text" id="custom_field_<?php echo $field['id']; ?>" name="<?php echo $field_name; ?>" class="form-control">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
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

$(function() {
    // Initialize datepicker
    $('.datepicker').datepicker({
        dateFormat: app.options.date_format,
        autoclose: true
    });

    // Initialize selectpicker
    if ($.fn.selectpicker) {
        $('.selectpicker').selectpicker('refresh');
    }

    // Load report on page load with no filters
    applyFilters();
});

function toggleFilters() {
    $('#filters-panel').slideToggle();
    var icon = $('#filter-toggle-icon');
    if (icon.hasClass('fa-angle-down')) {
        icon.removeClass('fa-angle-down').addClass('fa-angle-up');
    } else {
        icon.removeClass('fa-angle-up').addClass('fa-angle-down');
    }
}

function applyFilters() {
    $('#report-loading').show();
    $('#report-container').hide();

    var filterData = {
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val(),
        staff_id: $('#staff_id').val(),
        source_id: $('#source').val(),
        status_id: $('#status').val(),
        custom_fields: {}
    };

    // Collect custom field values
    $('[name^="custom_fields["]').each(function() {
        var matches = $(this).attr('name').match(/custom_fields\[(\d+)\]/);
        if (matches && $(this).val()) {
            filterData.custom_fields[matches[1]] = $(this).val();
        }
    });

    $.ajax({
        url: admin_url + 'staff_report/get_report_data',
        type: 'POST',
        data: filterData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                reportData = response;
                renderReport(response);
            } else {
                alert(response.message || '<?php echo _l('error_loading_report'); ?>');
            }
            $('#report-loading').hide();
            $('#report-container').show();
        },
        error: function(xhr, status, error) {
            console.error('Error loading report:', error);
            alert('<?php echo _l('error_loading_report'); ?>');
            $('#report-loading').hide();
            $('#report-container').show();
        }
    });
}

function renderReport(data) {
    var table = $('#staff-report-table');
    var thead = table.find('thead tr');
    var tbody = table.find('tbody');

    // Clear existing content
    thead.empty();
    tbody.empty();

    // Build header
    var headerHtml = '<th><?php echo _l('staff_member'); ?></th>';
    $.each(data.statuses, function(index, status) {
        headerHtml += '<th style="background-color: ' + status.color + '; color: white;">' + 
                      escapeHtml(status.name) + '</th>';
    });
    headerHtml += '<th><strong><?php echo _l('total'); ?></strong></th>';
    thead.html(headerHtml);

    // Build body
    var bodyHtml = '';
    $.each(data.data, function(index, row) {
        var isTotal = row.staff_id === null;
        var rowClass = isTotal ? 'bg-info text-bold' : '';
        
        bodyHtml += '<tr class="' + rowClass + '">';
        bodyHtml += '<td>' + (isTotal ? '<strong>' : '') + escapeHtml(row.staff_name) + (isTotal ? '</strong>' : '') + '</td>';
        
        $.each(data.statuses, function(sIndex, status) {
            var count = row.status_counts[status.id] || 0;
            bodyHtml += '<td class="text-center">' + (isTotal ? '<strong>' : '') + count + (isTotal ? '</strong>' : '') + '</td>';
        });
        
        bodyHtml += '<td class="text-center"><strong>' + row.total + '</strong></td>';
        bodyHtml += '</tr>';
    });
    
    tbody.html(bodyHtml);
}

function resetFilters() {
    $('#date_from').val('');
    $('#date_to').val('');
    $('#staff_id').val('').selectpicker('refresh');
    $('#source').val('').selectpicker('refresh');
    $('#status').val('').selectpicker('refresh');
    $('[name^="custom_fields["]').val('');
    $('.selectpicker').selectpicker('refresh');
    applyFilters();
}

function exportReport() {
    var params = new URLSearchParams({
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val(),
        staff_id: $('#staff_id').val(),
        source_id: $('#source').val(),
        status_id: $('#status').val()
    });

    // Add custom fields
    $('[name^="custom_fields["]').each(function() {
        var matches = $(this).attr('name').match(/custom_fields\[(\d+)\]/);
        if (matches && $(this).val()) {
            params.append('custom_fields[' + matches[1] + ']', $(this).val());
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
