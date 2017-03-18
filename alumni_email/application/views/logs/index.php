<?php custom_print_header('<i class="fa fa-history"></i> System Logs', $total_rows, 'class="no-top-margin"'); ?>

<?php
echo form_open(
	current_url(),
	array(
		'class' => 'search-form',
		'role' => 'form',
		'method' => 'get',
		'accept-charset' => 'utf-8'
	)
);
?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'search',
		'value' => $query['search'],
		'autofocus' => 'autofocus',
		'placeholder' => 'Enter search query'
	),
	'label' => 'Search Query',
	'form_group_class' => 'col-sm-3'
));
?>

<?php
custom_print_dropdown(array(
	'name' => 'category',
	'options' => $options['category'],
	'selected' => $query['category'],
	'label' => 'Category',
	'form_group_class' => 'col-sm-2'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'request_no',
		'value' => $query['request_no'],
		'placeholder' => 'Enter request no.'
	),
	'label' => 'Request No.',
	'form_group_class' => 'col-sm-3'
));
?>
</div>

<div class="row">
<?php
custom_print_date_selector(array(
	'name' => 'start',
	'value' => $query['start'],
	'label' => 'Start Date',
	'include_time' => TRUE,
	'form_group_class' => 'col-sm-3'
));
?>

<?php
custom_print_date_selector(array(
	'name' => 'end',
	'value' => $query['end'],
	'label' => 'End Date',
	'include_time' => TRUE,
	'form_group_class' => 'col-sm-3'
));
?>
</div>

<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search System Logs</button>
<?php echo form_close() ?>

<?php if ($logs) { ?>

<?php
$header = array(
	'Timestamp',
	'Username',
	'IP Address',
	'Module',
	'Action',
	'Request No.',
	''
);
$rows = array();
foreach ($logs as $log) {
	$rows[] = array(
		'cells' => array(
			custom_datetime($log['timestamp']),
			$log['username'],
			$log['ip_address'],
			$log['module'],
			$log['action'],
			$log['request_no'],
			'<a href="#" data-toggle="modal" data-target="#modal-log" data-id="'.$log['log_id'].'"><i class="fa fa-window-restore"></i> Details</a>'
		)
	);
}
custom_print_table(array(
	'header' => $header,
	'rows' => $rows,
	'table_class' => 'table-hover table-bordered table-actions'
));
?>

<div class="modal fade" id="modal-log" tabindex="-1" role="dialog" aria-labelledby="modal-log-label">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="modal-log-label"></h4>
</div>
<div class="modal-body">

<?php
custom_print_data(array(
	array(
		'label' => 'Timestamp',
		'value' => '',
		'extra' => 'id="timestamp"'
	),
	array(
		'label' => 'Username',
		'value' => '',
		'extra' => 'id="username"'
	),
	array(
		'label' => 'Session ID',
		'value' => '',
		'extra' => 'id="session_id"'
	),
	array(
		'label' => 'IP Address',
		'value' => '',
		'extra' => 'id="ip_address"'
	),
	array(
		'label' => 'User Agent',
		'value' => '',
		'extra' => 'id="user_agent"'
	),
	array(
		'label' => 'Module',
		'value' => '',
		'extra' => 'id="module"'
	),
	array(
		'label' => 'Action',
		'value' => '',
		'extra' => 'id="action"'
	)
));
?>

<div id="details"></div>

</div> <!-- end of modal-body -->
</div> <!-- end of modal-content -->
</div> <!-- end of modal-dialog -->
</div> <!-- end of modal-log -->

<?php echo $this->pagination->create_links(); ?>

<?php
} else {

	if ($this->input->get()) echo lang('blank_page');
	else echo lang('instructions_how_to_search');

}
?>

<script type="text/javascript">
$(document).ready(function() {

$("#start").on("dp.change", function (e) {
	$('#end').data("DateTimePicker").minDate(e.date);
});

$("#end").on("dp.change", function (e) {
	$('#start').data("DateTimePicker").maxDate(e.date);
});

$('#modal-log').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget);
	var id = button.data('id');
	var modal = $(this);
	$.ajax({
		url: "<?php echo site_url('services/get_log') ?>",
		method: "GET",
		data: { 'log_id' : id },
		dataType: "json"
	})
	.done(function(response) {
		modal.find('.modal-title').text("Log #" + response.log_id);
		modal.find('.modal-body #timestamp').text(response.timestamp);
		modal.find('.modal-body #username').text(response.username);
		modal.find('.modal-body #session_id').text(response.session_id);
		modal.find('.modal-body #ip_address').text(response.ip_address);
		modal.find('.modal-body #user_agent').text(response.user_agent);
		modal.find('.modal-body #module').text(response.module);
		modal.find('.modal-body #action').text(response.action);
		modal.find('.modal-body #details').html(response.details);
  	});
});

$('#modal-log').on('hidden.bs.modal', function (event) {
	var modal = $(this);
	modal.find('.modal-title').text('');
	modal.find('.modal-body #timestamp').text('');
	modal.find('.modal-body #username').text('');
	modal.find('.modal-body #session_id').text('');
	modal.find('.modal-body #ip_address').text('');
	modal.find('.modal-body #user_agent').text('');
	modal.find('.modal-body #module').text('');
	modal.find('.modal-body #action').text('');
	modal.find('.modal-body #details').html('');
});

});
</script>
