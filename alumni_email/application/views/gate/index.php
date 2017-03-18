<?php
$sections = array(
	'A' => 'A. ABOUT YOURSELF',
	'B' => 'B. YOUR MOST RECENT UP DEGREE/CERTIFICATE',
	'C' => 'C. CONTACT INFORMATION',
	'D' => 'D. ABOUT YOUR WORK',
	'E' => 'E. MEMBERSHIP IN UP ALUMNI ASSOCIATION CHAPTERS',
	'F' => 'F. CIVIL SERVICE ELIGIBILITY/BOARD/BAR',
	'G' => 'G. MEMBERSHIP IN HONOR, PROFESSIONAL, OR OTHER ORGANIZATIONS (INCLUDING BOARDS, CLUBS, FRATERNITIES, SORORITIES)',
	'H' => 'H. AWARDS, CITATIONS, FELLOWSHIPS, SCHOLARSHIPS, GRANTS, AND OTHER PRIZES RECEIVED',
	'I' => 'I. FAMILY MEMBERS WHO ALSO STUDIED IN UP',
);
?>

<img width="100" height="100" src="<?php echo base_url('public/img/seal-256x256.png') ?>" class="img-responsive center-block" />

<div class="text-center" style="margin-bottom: 20px">

<h1 class="up-maroon no-top-margin">UP Alumni Email</h1>
<h4 class="no-top-margin">
<span class="up-green">OFFICE OF ALUMNI RELATIONS</span><br/>
<span class="up-red">UNIVERSITY OF THE PHILIPPINES</span>
</h4>

</div>

<?php custom_print_alert($alert ? $alert : $this->session->flashdata('alert')) ?>

<div class="row">
<div class="col-md-8">

<div class="panel panel-maroon">
<div class="panel-body">

<?php if (validation_errors()) { ?>
<div class="alert alert-danger alert-dismissible" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<?php
$ctr = 1;
foreach ($errors as $section_id => $error) {
	echo '<p><strong>'.$sections[$section_id].' <a href="#section_'.$section_id.'" class="alert-link"><i class="fa fa-hand-o-down"></i></a></strong></p>';
	echo '<ul class="list-unstyled" style="margin-bottom: '.(($ctr == count($errors) && ! form_error('captcha')) ? '0' : '10px').'">';
	foreach ($error as $e) {
		echo $e;
	}
	echo '</ul>';
	$ctr++;
}
if (form_error('captcha')) {
	echo '<p>'.form_error('captcha','<strong>','</strong>').' <a href="#captcha" class="alert-link"><i class="fa fa-hand-o-down"></i></a></p>';
}
?>
</div>
<?php } ?>

<?php echo form_open(current_url(), array('role' => 'form')) ?>

<a name="section_A"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['A'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<div class="row">
<?php
custom_print_dropdown(array(
	'name' => 'title_simple',
	'options' => $personal_titles,
	'selected' => set_value('title_simple', PERSONAL_TITLE_MR),
	'label' => 'Title',
	'form_group_class' => 'col-sm-3'
))
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'title_preferred',
		'value' => set_value('title_preferred', '', FALSE),
		'placeholder' => 'e.g. Atty., Dr.',
		'maxlength' => 20,
	),
	'label' => 'Preferred Title',
	'form_group_class' => 'col-sm-9'
));
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'first_name',
		'value' => set_value('first_name', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'First Name *'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'middle_name',
		'value' => set_value('middle_name', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Middle Name'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'last_name',
		'value' => set_value('last_name', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Last Name *',
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'maiden_name',
		'value' => set_value('maiden_name', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Maiden Name',
	'help_block' => 'Please indicate your maiden name if you are an alumna and you subsequently married.'
));
?>

<?php $birthdate_error = (form_error('birthdate') OR form_error('birth_month') OR form_error('birth_day') OR form_error('birth_year')); ?>

<div style="margin-bottom: 5px" <?php echo $birthdate_error ? 'class="text-danger"' : '' ?>><strong>Date of Birth *</strong></div>

<div class="row">
<?php
custom_print_dropdown(array(
	'name' => 'birth_month',
	'options' => $months,
	'selected' => set_value('birth_month',1),
	'has_error' => $birthdate_error,
	'form_group_class' => 'col-sm-4'
))
?>

<?php
custom_print_dropdown(array(
	'name' => 'birth_day',
	'options' => $days,
	'selected' => set_value('birth_day',1),
	'has_error' => $birthdate_error,
	'form_group_class' => 'col-sm-4'
))
?>

<?php
custom_print_dropdown(array(
	'name' => 'birth_year',
	'options' => $birth_years,
	'selected' => set_value('birth_year',date('Y')),
	'has_error' => $birthdate_error,
	'form_group_class' => 'col-sm-4'
))
?>
</div>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'birth_place',
		'value' => set_value('birth_place', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Place of Birth *',
	'form_group_class' => 'col-sm-8'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'nickname',
		'value' => set_value('nickname', '', FALSE),
		'maxlength' => 20,
	),
	'label' => 'Nickname *',
	'form_group_class' =>'col-sm-4'
));
?>
</div>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_B"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['B'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'student_no',
		'value' => set_value('student_no', '', FALSE),
		'placeholder' => 'YYYYDDDDD',
		'maxlength' => 9,
	),
	'label' => 'Student No.',
	'form_group_class' => 'col-sm-3'
));
?>

<?php
custom_print_dropdown(array(
	'name' => 'campus_id',
	'options' => $campuses,
	'selected' => set_value('campus_id'),
	'label' => 'Campus *',
	'form_group_class' => 'col-sm-9'
))
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'college',
		'value' => set_value('college', '', FALSE),
		'maxlength' => 100,
		'data-provide' => 'typeahead',
		'autocomplete' => 'off'
	),
	'label' => 'College, School, or Institute *'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'course',
		'value' => set_value('course', '', FALSE),
		'maxlength' => 100,
		'data-provide' => 'typeahead',
		'autocomplete' => 'off'
	),
	'label' => 'Degree/Certificate & Major *'
));
?>

<div class="row">
<?php
custom_print_dropdown(array(
	'name' => 'year_started',
	'options' => $acad_years,
	'selected' => set_value('year_started',date('Y')),
	'label' => 'Year Started *',
	'form_group_class' => 'col-sm-3'
))
?>

<?php
custom_print_dropdown(array(
	'name' => 'year_graduated',
	'options' => $acad_years,
	'selected' => set_value('year_graduated',date('Y')),
	'label' => 'Year Graduated',
	'form_group_class' => 'col-sm-3'
))
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'honor',
		'value' => set_value('honor', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Honor',
	'form_group_class' => 'col-sm-6'
));
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'other_degrees',
		'value' => set_value('other_degrees', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Other UP Degrees/Certificates',
	'help_block' => 'Please indicate other degrees/certificates earned in UP, if any. Include the campus, year, and other relevant information.'
));
?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_C"></a>
<h3 class="up-maroon"><?php echo $sections['C'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'email',
		'value' => set_value('email', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Personal Email Address *',
	'help_block' => 'We will send an email to this email address with instructions on how to confirm your request. Only confirmed requests will be processed.'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'email_conf',
		'value' => set_value('email_conf', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Retype Email Address'
));
?>

<p><strong class="text-muted">HOME ADDRESS</strong></p>

<?php
$address_lines = array(
	1 => array(
		'label' => 'Number and Street',
		'length' => 100
	),
	2 => array(
		'label' => 'Subdivision/Village, Barangay, District',
		'length' => 100
	),
	3 => array(
		'label' => 'Town/City',
		'length' => 50
	),
	4 => array(
		'label' => 'Province, Region, Country',
		'length' => 50
	)
);
?>

<?php
for ($i = 1; $i <= 3; $i++) {
	custom_print_input(array(
		'input' => array(
			'name' => 'address_'.$i,
			'value' => set_value('address_'.$i, '', FALSE),
			'maxlength' => $address_lines[$i]['length']
		),
		'label' => $address_lines[$i]['label'] . ' *'
	));
}
?>

<div class="row">

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'address_4',
		'value' => set_value('address_4', '', FALSE),
		'maxlength' => $address_lines[4]['length']
	),
	'label' => $address_lines[4]['label'] . ' *',
	'form_group_class' => 'col-sm-9'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'address_zip',
		'value' => set_value('address_zip', '', FALSE),
		'maxlength' => 15
	),
	'label' => 'Postal/ZIP Code',
	'form_group_class' => 'col-sm-3'
));
?>
</div>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'tel_no',
		'value' => set_value('tel_no', '', FALSE),
		'maxlength' => 50
	),
	'label' => 'Telephone No.',
	'form_group_class' => 'col-sm-6'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'mobile_no',
		'value' => set_value('mobile_no', '', FALSE),
		'maxlength' => 50
	),
	'label' => 'Mobile No.',
	'form_group_class' => 'col-sm-6'
));
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'website',
		'value' => set_value('website', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Personal Website',
));
?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_D"></a>
<h3 class="up-maroon"><?php echo $sections['D'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'company_name',
		'value' => set_value('company_name', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Company Name *'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'occupation',
		'value' => set_value('occupation', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Present Position or Occupation *'
));
?>

<p><strong class="text-muted">BUSINESS/WORK ADDRESS</strong></p>

<?php
for ($i = 1; $i <= 3; $i++) {
	custom_print_input(array(
		'input' => array(
			'name' => 'work_address_'.$i,
			'value' => set_value('work_address_'.$i, '', FALSE),
			'maxlength' => $address_lines[$i]['length']
		),
		'label' => $address_lines[$i]['label']
	));
}
?>

<div class="row">

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'work_address_4',
		'value' => set_value('work_address_4', '', FALSE),
		'maxlength' => $address_lines[4]['length']
	),
	'label' => $address_lines[4]['label'],
	'form_group_class' => 'col-sm-9'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'work_address_zip',
		'value' => set_value('work_address_zip', '', FALSE),
		'maxlength' => 15
	),
	'label' => 'Postal/ZIP Code',
	'form_group_class' => 'col-sm-3'
));
?>
</div>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'work_tel_no',
		'value' => set_value('work_tel_no', '', FALSE),
		'maxlength' => 50
	),
	'label' => 'Telephone No.',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'work_mobile_no',
		'value' => set_value('work_mobile_no', '', FALSE),
		'maxlength' => 50
	),
	'label' => 'Mobile No.',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'fax_no',
		'value' => set_value('fax_no', '', FALSE),
		'maxlength' => 50
	),
	'label' => 'Fax No.',
	'form_group_class' => 'col-sm-4'
));
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'work_website',
		'value' => set_value('work_website', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Business Website',
));
?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_E"></a>
<h3 class="up-maroon"><?php echo $sections['E'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php for ($row = 1; $row <= 3; $row++) { ?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][organization]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][organization]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => '#'.$row.' Name of Chapter',
));
?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][period]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][period]', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Period of Membership',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][position]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_ALUMNI.']['.$row.'][position]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Highest Position',
	'form_group_class' => 'col-sm-8'
));
?>
</div>

<?php } // end of for row 1-X ?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_F"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['F'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'exam_name',
		'value' => set_value('exam_name', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Name of Examination'
));
?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'exam_place',
		'value' => set_value('exam_place', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Place of Examination',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'exam_date',
		'value' => set_value('exam_date', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Date',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'rating',
		'value' => set_value('rating', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Rating',
	'form_group_class' => 'col-sm-4'
));
?>
</div>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_G"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['G'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php for ($row = 1; $row <= 3; $row++) { ?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][organization]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][organization]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => '#'.$row.' Name of Organization',
));
?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][period]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][period]', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Period of Membership',
	'form_group_class' => 'col-sm-4'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][position]',
		'value' => set_value('membership['.MEMBERSHIP_CAT_OTHERS.']['.$row.'][position]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => 'Highest Position',
	'form_group_class' => 'col-sm-8'
));
?>
</div>

<?php } // end of for row 1-X ?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_H"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['H'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php for ($row = 1; $row <= 3; $row++) { ?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'award['.$row.'][award_name]',
		'value' => set_value('award['.$row.'][award_name]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => '#'.$row.' Name of Award/Prize',
	'form_group_class' => 'col-sm-8'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'award['.$row.'][award_date]',
		'value' => set_value('award['.$row.'][award_date]', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Date Given',
	'form_group_class' => 'col-sm-4'
));
?>
</div>

<?php } // end of for row 1-X ?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<a name="section_I"></a>
<h3 class="up-maroon no-top-margin"><?php echo $sections['I'] ?></h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<?php for ($row = 1; $row <= 3; $row++) { ?>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'family['.$row.'][name]',
		'value' => set_value('family['.$row.'][name]', '', FALSE),
		'maxlength' => 100,
	),
	'label' => '#'.$row.' Complete Name of Family Member',
	'form_group_class' => 'col-sm-8'
));
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'family['.$row.'][relation]',
		'value' => set_value('family['.$row.'][relation]', '', FALSE),
		'maxlength' => 50,
	),
	'label' => 'Relation',
	'form_group_class' => 'col-sm-4'
));
?>
</div>

<div class="row">
<?php
custom_print_dropdown(array(
	'name' => 'family['.$row.'][campus_id]',
	'options' => $campuses,
	'selected' => set_value('family['.$row.'][campus_id]'),
	'label' => 'Campus',
	'form_group_class' => 'col-sm-4'
))
?>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'family['.$row.'][college]',
		'value' => set_value('family['.$row.'][college]', '', FALSE),
		'maxlength' => 100,
		'data-provide' => 'typeahead',
		'autocomplete' => 'off'
	),
	'label' => 'College, School, or Institute',
	'form_group_class' => 'col-sm-8'
));
?>

</div>

<div class="row">
<?php
custom_print_input(array(
	'input' => array(
		'name' => 'family['.$row.'][course]',
		'value' => set_value('family['.$row.'][course]', '', FALSE),
		'maxlength' => 100,
		'data-provide' => 'typeahead',
		'autocomplete' => 'off'
	),
	'label' => 'Degree/Certificate & Major',
	'form_group_class' => 'col-sm-8'
));
?>

<?php
custom_print_dropdown(array(
	'name' => 'family['.$row.'][year_graduated]',
	'options' => array('' => 'Select year') + $acad_years,
	'selected' => set_value('family['.$row.'][year_graduated]'),
	'label' => 'Year Graduated',
	'form_group_class' => 'col-sm-4'
))
?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'family['.$row.'][address]',
		'value' => set_value('family['.$row.'][address]', '', FALSE),
		'type' => 'text'
	),
	'label' => 'Address'
));
?>

<script type="text/javascript">
$(document).ready(function(){

$('#family\\[<?php echo $row ?>\\]\\[college\\]').typeahead({
	minLength: 1,
	source: function(query, process) {
	  	$.get(
			'<?php echo site_url('services/get_colleges') ?>',
			{
				q: query,
				campus_id : $('#family\\[<?php echo $row ?>\\]\\[campus_id\\]').val(),
			},
			function(data) {
				process(JSON.parse(data));
			}
		);
	}
});

$('#family\\[<?php echo $row ?>\\]\\[course\\]').typeahead({
	minLength: 1,
	source: function(query, process) {
	  	$.get(
			'<?php echo site_url('services/get_courses') ?>',
			{
				q: query,
				campus_id : $('#family\\[<?php echo $row ?>\\]\\[campus_id\\]').val(),
				college : $('#family\\[<?php echo $row ?>\\]\\[college\\]').val(),
			},
			function(data) {
				process(JSON.parse(data));
			}
		);
	}
});

});
</script>

<?php } // end of for row 1-X ?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<h3 class="up-maroon">WAIVER</h3>

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<p><strong>Information indicated in this form can be used for the following purposes:</strong></p>

<div style="margin-left: 20px">
<div class="checkbox">
<label><input type="checkbox" name="waiver_1" value="TRUE" <?php echo set_checkbox('waiver_1', 'TRUE', TRUE); ?> /> to receive news, updates, messages, and invitations for alumni activities through UP OAR</label>
</div>
<div class="checkbox">
<label><input type="checkbox" name="waiver_2" value="TRUE" <?php echo set_checkbox('waiver_2', 'TRUE', TRUE); ?> /> to receive other print materials from the University</label>
</div>
</div>

<div class="checkbox">
<label><input type="checkbox" name="waiver_3" value="TRUE" <?php echo set_checkbox('waiver_3', 'TRUE'); ?> /> <strong>This information should not be given to any group other than the UP Alumni Association.</strong></label>
</div>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<div class="panel panel-default boxed-fields">
<div class="panel-body">

<a name="captcha"></a>
<div style="margin-bottom: 10px">
<?php echo $captcha ?>
</div>

<?php
custom_print_input(array(
	'input' => array(
		'name' => 'captcha',
		'placeholder' => 'Type the numbers you see above',
		'maxlength' => 4
	),
	'label' => 'CAPTCHA'
));
?>

</div> <!-- end of inner panel-body -->
</div> <!-- end of inner panel -->

<button type="submit" class="btn btn-maroon btn-lg btn-block submit">SUBMIT REQUEST</button>

<?php echo form_close(); ?>

</div>
</div>

</div> <!-- end of col-md-8 -->
<div class="col-md-4">

<div class="panel panel-maroon">
<div class="panel-body">
<h4 class="up-maroon no-top-margin">Want your own @alum.up.edu.ph account?</h4>

<p>Fill out the form to request for your very own <strong class="up-maroon">@alum.up.edu.ph</strong> email account.</p>

<p>We will verify your information and let you know you as soon as your account is ready. Information you provide will also be used to <strong class="up-maroon">update your records with the UPS-OAR</strong>.</p>

<hr/>

<h4 class="up-maroon no-top-margin">UP System<br/>Office of Alumni Relations <a style="color: #7B1113" target="_blank" href="http://alum.up.edu.ph"><i class="fa fa-external-link"></i></a></h4>

<p><strong class="up-maroon">Mission:</strong> The UP System-Office of Alumni Relations (UPS-OAR) serves as an active link between UP alumni and the rest of the academic community in order to encourage the maximum participation, involvement, support and commitment of these individuals to the goals and mission of the University of the Philippines (UP).</p>
	
<p><strong class="up-maroon">Vision:</strong> To effectively act as liaison between UP and its external publics, especially its alumni--to ensure their active participation, involvement, support and commitment vis-à-vis UP's social mission.</p>
	
<p><strong class="up-maroon">Office Address:</strong><br/>
Rooms 1127-1130 Pavilion I, Palma Hall<br/>
Quirino cor. Velasquez Sts.,<br/>
UP Diliman, Quezon City<br/>
Telefax # (02) 9298-226;<br/>
Trunkline # (02) 981-8500 voip# 4251; 4252</p>

<p><strong class="up-maroon">Email Address:</strong> oar@up.edu.ph</p>

<hr/>

<h4 class="up-maroon no-top-margin">Give to UP <a style="color: #7B1113" target="_blank" href="https://giveto.up.edu.ph"><i class="fa fa-external-link"></i></a></h4>

<p><strong class="up-maroon">Give back to the university</strong> using your VISA credit card! You can also contribute to various campaigns via cash, check, and bank deposit.</p>

</div>
</div>

</div> <!-- end of col-md-4 -->
</div> <!-- end of row -->

<script type="text/javascript">
$(document).ready(function(){

$('.submit').click(function(e) {
	e.preventDefault();
	var msg = '<?php custom_print_prompt('Are you sure you\\\'re ready to submit this request? Please review your answers before confirming.') ?>';
	bootbox.confirm(msg, function(result) {
		if (result) {
			$('form').submit();
		}
	});
});

$("#college").typeahead({
	minLength: 1,
	source: function(query, process) {
	  	$.get(
			'<?php echo site_url('services/get_colleges') ?>',
			{
				q: query,
				campus_id : $('#campus_id').val()
			},
			function(data) {
				process(JSON.parse(data));
			}
		);
	}
});

$("#course").typeahead({
	minLength: 1,
	source: function(query, process) {
	  	$.get(
			'<?php echo site_url('services/get_courses') ?>',
			{
				q: query,
				campus_id : $('#campus_id').val(),
				college : $('#college').val(),
			},
			function(data) {
				process(JSON.parse(data));
			}
		);
	}
});

});
</script>