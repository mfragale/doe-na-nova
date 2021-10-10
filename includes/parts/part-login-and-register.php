<div class="login_register">
	<p><?php _e( 'You need to be logged in to make a donation.', 'doenanova' ); ?></p>
	<a class="doenanova-btn doenanova-btn-block" href="<?php echo esc_url( wp_login_url( home_url() ) ); ?>"><?php _e( 'Login', 'doenanova' ); ?></a>
	
	<hr/>
	
	<p><?php _e( 'Not registered yet?', 'doenanova' ); ?></p>
	<a class="doenanova-btn doenanova-btn-block is-light" href="<?php echo esc_url( wp_registration_url() ); ?>"><?php _e( 'Register', 'doenanova' ); ?></a>
</div>