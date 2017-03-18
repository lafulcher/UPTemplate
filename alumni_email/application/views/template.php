<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<!-- TODO SEO Tags -->

<title><?php echo isset($title) ? $title . ' | ' : '' ?>UP Alumni Email</title>

<!-- Favicon http://iconifier.net -->
<link rel="shortcut icon" href="<?php echo base_url('favicon.ico') ?>" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo base_url('apple-touch-icon.png') ?>" />
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('apple-touch-icon-57x57.png') ?>" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('apple-touch-icon-72x72.png') ?>" />
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('apple-touch-icon-76x76.png') ?>" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('apple-touch-icon-114x114.png') ?>" />
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('apple-touch-icon-120x120.png') ?>" />
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('apple-touch-icon-144x144.png') ?>" />
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('apple-touch-icon-152x152.png') ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('apple-touch-icon-180x180.png') ?>" />

<!-- Bootstrap -->
<link href="<?php echo base_url('public/bootstrap/css/bootstrap.css') ?>" rel="stylesheet" />

<!-- Font Awesome -->
<link href="<?php echo base_url('public/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" />

<!-- Bootstrap Datetime Picker -->
<link href="<?php echo base_url('public/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') ?>" rel="stylesheet" />

<!-- Custom Styles -->
<link href="<?php echo base_url('public/css/custom-navbar.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/up-colors.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/boxed-fields.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/panel-green.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/panel-maroon.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/btn-green.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/btn-maroon.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/btn-yellow.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/bs-back-to-top/bs-back-to-top.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/' . ($this->session->userdata('is_logged_in') ? 'private' : 'public') . '.css') ?>" rel="stylesheet" />

<!-- jQuery -->
<script src="<?php echo base_url('public/js/jquery-1.12.4.min.js') ?>"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>

<?php if ($this->session->userdata('is_logged_in')) { ?>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo site_url('dashboard') ?>">UP Alumni Email</a>
		</div>
		<div id="navbar" class="collapse navbar-collapse">
			<p class="navbar-text">Logged in as <strong><?php echo $this->session->userdata('username') ?></strong></p>
			<p class="navbar-text" id="clock"><?php echo date('l, F j, Y g:i:s A') ?></p>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="<?php echo site_url('admins') ?>"><i class="fa fa-users"></i> Admin Accounts</a></li>
				<li><a href="<?php echo site_url('logs') ?>"><i class="fa fa-history"></i> System Logs</a></li>
				<li><a href="#" class="logout"><i class="fa fa-sign-out"></i> Log Out</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>

<?php } else { // not logged in ?>

<div style="border: solid 3px #014421;"></div>
<div style="border: solid 1px #F3AA2C;"></div>
<div style="border: solid 5px #7B1113;"></div>
<div style="border: solid 1px #F3AA2C;"></div>
<div style="border: solid 3px #014421; margin-bottom: 20px;"></div>

<?php } // end of if is_logged_in ?>

<div class="container<?php echo $this->session->userdata('is_logged_in') ? '-fluid' : '' ?>">
<div class="row">
<div class="col-md-12">

<noscript>
<div class="alert alert-warning" role="alert">
<strong>For full functionality of this site it is necessary to enable JavaScript.</strong> Here are the <a class="alert-link" href="http://www.enable-javascript.com/" target="_blank">instructions how to enable JavaScript in your web browser</a>.
</div>
</noscript>

<?php echo $body ?>

<hr/>
<footer>
<strong class="up-maroon">UP Alumni Email</strong><br/>
Office of Alumni Relations<br/>
University of the Philippines
</footer>

</div> <!-- End of col-md-12 -->
</div> <!-- End of row -->
</div> <!-- End of container -->

<!-- JS Dependencies -->
<script src="<?php echo base_url('public/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('public/js/bootbox.min.js') ?>"></script>
<script src="<?php echo base_url('public/js/moment.min.js') ?>"></script>
<script src="<?php echo base_url('public/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?php echo base_url('public/js/bootstrap3-typeahead.min.js') ?>"></script>
<script src="<?php echo base_url('public/js/jquery.are-you-sure.js') ?>"></script>
<script src="<?php echo base_url('public/bs-back-to-top/bs-back-to-top.js') ?>"></script>

<script type="text/javascript">
String.prototype.format = function() {
	var content = this;
	for (var i=0; i < arguments.length; i++)
	{
		var replacement = '{' + i + '}';
		content = content.replace(replacement, arguments[i]);  
	}
	return content;
};

var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
};

function escapeHtml(string) {
	return String(string).replace(/[&<>"'\/]/g, function (s) {
		return entityMap[s];
	});
}

function NASort(a, b) {
	return (a.innerHTML > b.innerHTML) ? 1 : -1;
}

</script>

<?php if ($this->session->userdata('is_logged_in')) { ?>
<script type="text/javascript">
// https://gist.github.com/speier/7143927
function displayTime() {
	var time = moment().format('dddd, MMMM D, YYYY h:mm:ss A');
	$('#clock').html(time);
	setTimeout(displayTime, 1000);
}

$(document).ready(function() {

displayTime();

$('.logout').click(function(e) {
	e.preventDefault();
	var msg = '<?php custom_print_prompt('Are you sure you want to log out?', 'Log Out', 'sign-out') ?>';
	bootbox.confirm(msg, function(result) {
		if (result) {
			window.location.replace('<?php echo site_url('logout') ?>');
		}
	});
});

});
</script>
<?php } ?>

<script>
var $buoop = {vs:{i:10,f:-4,o:-4,s:7,c:-4},api:4}; 
function $buo_f(){ 
 var e = document.createElement("script"); 
 e.src = "//browser-update.org/update.min.js"; 
 document.body.appendChild(e);
};
try {document.addEventListener("DOMContentLoaded", $buo_f,false)}
catch(e){window.attachEvent("onload", $buo_f)}
</script> 

</body>
</html>