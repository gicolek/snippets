<?php
/**
 * Validate data and send mail.
 *
 * @see http://codex.wordpress.org/Function_Reference/wp_mail
 * @return {int} Status of message:
 * -2 => Invalid data
 * -1 => Failed to send
 *  1 => OK
 */
function sendMail() {

	$name = esc_attr($_POST['name']);
	$email = $_POST['email'];
	$message = esc_textarea($_POST['message']);

	$response = array(
		'status' => -2,
		'errors' => []
	);

	if ( ! strlen($name) )
		$response['errors']['name'] = "C'mon, what's your name?";

	if ( ! is_email($email) )
		$response['errors']['email'] = "Please, give us valid email.";

	if ( ! strlen($message) )
		$response['errors']['message'] = "No message, huh?";

	if ( empty($response['errors']) ) {

		$to = get_bloginfo('admin_email');
		$subject = 'Contact from ' . get_bloginfo('name');

		$headers[] = "From: $name <$email>";

		$isSent = wp_mail( $to, $subject, $message, $headers );

		$response['status'] = $isSent ? 1 : -1;

	}

	echo json_encode($response);

	die();
}

add_action('wp_ajax_sendMail', 'sendMail');
add_action('wp_ajax_nopriv_sendMail', 'sendMail');