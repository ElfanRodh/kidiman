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
				<div id="infoMessage"><?php echo $message; ?></div>

				<div class="wrap-input100 validate-input" data-validate="Username is required">
					<input class="input100" type="text" name="identity" placeholder="Username">
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-user" aria-hidden="true"></i>
					</span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Password is required">
					<input class="input100" type="password" name="password" placeholder="Password">
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</span>

				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn font-weight-bold" type="submit">
						Login
					</button>
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
</script>
<!--===============================================================================================-->
<script src="<?php echo base_url(); ?>assets/login/js/main.js"></script>