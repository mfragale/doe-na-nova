<?php //include(dirname(__FILE__) . '/doe-na-nova-nav.php'); 
?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>


<?php
global $current_user;

if (is_user_logged_in()) {
	$current_user_email = $current_user->user_email;
	$current_user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
}
?>

<?php if (is_user_logged_in()) { ?>

	<img src="<?php echo esc_url(get_avatar_url($current_user->ID)); ?>" class="rounded-circle ms-auto me-auto d-block border border-primary border-3" />


	<?php echo do_shortcode('[wppb-edit-profile]'); ?>
	<?php echo do_shortcode('[wppb-logout]'); ?>
<?php
} else {
	include(dirname(__FILE__) . '/parts/part-login-and-register.php');
} ?>


<?php include(dirname(__FILE__) . '/parts/part-wrap-end.php'); ?>