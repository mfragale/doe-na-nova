<div class="card my-5 p-5">
	<div class="card-body">
		<div class="row">
			<div class="col text-center">
				<h1>Doe com cartão</h1>
			</div>
		</div>
		<div class="row my-3">
			<div class="col text-center">
				<p class="text-muted">Para doar utilizando seu cartão de crédito ou débito, primeiro se cadastre ou então faça seu login.</p>
			</div>
		</div>
		<div class="row">
			<div class="col mb-3">
				<div class="d-grid gap-2">
					<a class="btn btn-primary btn-lg" href="<?php echo esc_url(wp_registration_url()); ?>"><?php _e('Register', 'doenanova'); ?></a>
				</div>
			</div>
			<div class="col">
				<div class="d-grid gap-2">
					<a class="btn btn-outline-secondary btn-lg" href="<?php echo esc_url(wp_login_url(home_url())); ?>"><?php _e('Login', 'doenanova'); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>