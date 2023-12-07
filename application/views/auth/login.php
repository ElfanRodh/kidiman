<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>
<link rel="stylesheet" href="<?= base_url("assets/login/css/main.css"); ?>">
<div class="limiter">
	<div class="container-login100">
		<div class="wrap-login100">
			<div class="login100-pic js-tilt" data-tilt>
				<img class="rounded-pill" src="<?php echo base_url(); ?>assets/login/images/landing.jpg" alt="IMG">
			</div>

			<form class="login100-form validate-form" method="POST" action="<?= site_url("auth/login"); ?>">
				<h1 class="text-app text-center mb-4">
					KIDIMAN
				</h1>
				<!-- <div id="infoMessage"><?php echo $message; ?></div> -->
				<div class="text-muted text-center text-danger fw-bold py-3" id="infoMessage">
					<?= ($this->session->flashdata('message')) ? $this->session->flashdata('message') : $this->session->flashdata('message_success') ?>
				</div>

				<div class="wrap-input100 validate-input form-group" data-validate="Username is required">
					<!-- <input class="input100" type="text" name="identity" placeholder="Username"> -->
					<?= form_input($identity, '', 'class="input100" placeholder="Username"') ?>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-user" aria-hidden="true"></i>
					</span>
					<div class="invalid-feedback">
						<?= form_error('identity') ?>
					</div>
				</div>

				<div id="show_hide_password" class="wrap-input100 validate-input form-group" data-validate="Password is required">
					<!-- <input class="input100" type="password" name="password" placeholder="Password"> -->
					<?= form_input($password, '', 'class="input100" placeholder="Password"') ?>
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</span>
					<span class="symbol-input100-right">
						<i class="fa fa-eye" aria-hidden="true"></i>
					</span>
					<div class="invalid-feedback">
						<?= form_error('password') ?>
					</div>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn font-weight-bold" type="submit">
						Login
					</button>
				</div>
				<div class="container-login101-form-btn">
					<a href="<?= site_url('/'); ?>" class="login101-form-btn font-weight-bold">
						Beranda
					</a>
				</div>

			</form>
		</div>
	</div>
</div>

<?php $this->load->view('dist/_partials/js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js"></script>
<script>
	$(document).ready(function() {
		$('.js-tilt').tilt({
			scale: 1.1
		})
	});

	$("#show_hide_password .symbol-input100-right").on("click", function(event) {
		event.preventDefault();
		if ($("#show_hide_password input").attr("type") == "text") {
			$("#show_hide_password input").attr("type", "password");
			$("#show_hide_password .symbol-input100-right i").removeClass("fa-eye-slash");
			$("#show_hide_password .symbol-input100-right i").addClass("fa-eye");
		} else if ($("#show_hide_password input").attr("type") == "password") {
			$("#show_hide_password input").attr("type", "text");
			$("#show_hide_password .symbol-input100-right i").addClass("fa-eye-slash");
			$("#show_hide_password .symbol-input100-right i").removeClass("fa-eye");
		}
	});
</script>
<!--===============================================================================================-->
<script src="<?php echo base_url(); ?>assets/login/js/main.js"></script>