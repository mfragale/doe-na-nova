<?php if ($stripe_is_TEST_mode) { ?>
	<span class="stripe_is_test_mode tag is-warning"><?php _e('Test mode', 'doenanova'); ?></span>
	
	<span class="doenanova-how-to-use tag"><a><?php _e('How to use this?', 'doenanova'); ?></a></span>
	
	<div id="doenanova-how-to-use">
		<section>
	    <span class="tag is-warning doenanova-how-to-use-close"><a class=""><?php _e('Close', 'doenanova'); ?></a></span>
	    
	    <p>
		    <small>
			    <?php _e('To test out this platform you can use the TEST MODE card number which will emulate exactly the experience you would have with a real card but without making any real transactions.', 'doenanova'); ?>
		    </small>
			</p>
			<p>
				<small>
			    <strong><?php _e('Test card number', 'doenanova'); ?></strong>: <?php _e('4242424242424242', 'doenanova'); ?>
		    </small>
			</p>
			<p>
				<small>
			    <strong><?php _e('Test card CVC', 'doenanova'); ?></strong>: <?php _e('Any 3 digits', 'doenanova'); ?>
		    </small>
			</p>
			<p>
				<small>
			    <strong><?php _e('Test card date', 'doenanova'); ?></strong>: <?php _e('Any future date', 'doenanova'); ?>
		    </small>
			</p>
			
			<p>
				<small>
			    <?php _e('To learn more click here', 'doenanova'); ?>: <a href="https://stripe.com/docs/testing#cards" target="_blank">https://stripe.com/docs/testing#cards</a>
		    </small>
			</p>
			
		</section>
	</div>
<?php } ?>

	

<?php if ($show_report) { ?>
	<span class="doenanova-report tag is-warning"><a><?php _e('Show report', 'doenanova'); ?></a></span>
		  
  <div id="doenanova-report">
    <section>
	    <span class="tag is-warning doenanova-report-close"><a class=""><?php _e('Close report', 'doenanova'); ?></a></span>
	    
	    <p>
		    <small>
		    	<strong><?php _e('Mode', 'doenanova'); ?></strong>: 
			    <?php if ( $stripe_is_TEST_mode ) { ?> 
				    <span class="tag is-danger"><?php _e('Test', 'doenanova'); ?></span>
			    <?php } else { ?>
				    <span class="tag is-success"><?php _e('Live', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>
			
			<p>
		    <small>
		    	<strong><?php _e('State', 'doenanova'); ?></strong>: 
			    <?php if ( is_user_logged_in() ) { ?> 
				    <span class="tag is-success"><?php _e('Logged in', 'doenanova'); ?></span>
			    <?php } else { ?>
				    <span class="tag is-danger"><?php _e('Logged out', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>
			
			<p>
		    <small>
		    	<strong><?php _e('Status', 'doenanova'); ?></strong>: 
			    <?php if ( isset($latest_charge_data) ) { ?> 
				    <span class="tag is-success"><?php _e('Veteran', 'doenanova'); ?></span>
			    <?php } else { ?>
				    <span class="tag is-danger"><?php _e('Virgin', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>
			
			<p>
		    <small>
		    	<strong><?php _e('WP User ID', 'doenanova'); ?></strong>: 
			    <?php if ( is_user_logged_in() ) { ?> 
				    <span class="tag is-success"><?php echo $current_user->ID; ?></span>
			    <?php } else { ?>
				    <span class="tag is-danger"><?php _e('Not a WP User', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>
			
			<p>
		    <small>
		    	<strong><?php _e('Stripe Customer ID', 'doenanova'); ?></strong>: 
			    <?php if ( $customer_id ) { ?> 
				    <span class="tag is-success"><?php echo $customer_id; ?></span>
			    <?php } else { ?>
				    <span class="tag is-danger"><?php _e('Not a Stripe Customer', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>
			
			<p>
		    <small>
		    	<strong><?php _e('Payment method', 'doenanova'); ?></strong>: 
			    <?php if ( $customer_source ) { $card = $customer->sources->retrieve($customer_source); ?> 
				    <span class="tag is-success"><i class="fab fa-cc-<?php echo strtolower( $card->brand ); ?>"></i> <span><?php echo $card->last4; ?></span></span>
			    <?php } else { ?>
				    <span class="tag is-danger"><?php _e('No card', 'doenanova'); ?></span>
			    <?php } ?>
				</small>
			</p>   
			
    </section>
	    
	</div>
<?php } ?>