<?php
/**
 * Customer (Donors)
 *
 * @package     Give
 * @subpackage  Admin/Customers
 * @copyright   Copyright (c) 2015, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Processes a custom edit
 *
 * @since  2.3
 *
 * @param  array $args The $_POST array being passeed
 *
 * @return array $output Response messages
 */
function give_edit_customer( $args ) {
	$customer_edit_role = apply_filters( 'give_edit_customers_role', 'edit_shop_payments' );

	if ( ! is_admin() || ! current_user_can( $customer_edit_role ) ) {
		wp_die( __( 'You do not have permission to edit this customer.', 'give' ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$customer_info = $args['customerinfo'];
	$customer_id   = (int) $args['customerinfo']['id'];
	$nonce         = $args['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'edit-customer' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'give' ) );
	}

	$customer = new Give_Customer( $customer_id );
	if ( empty( $customer->id ) ) {
		return false;
	}

	$defaults = array(
		'name'    => '',
		'email'   => '',
		'user_id' => 0
	);

	$customer_info = wp_parse_args( $customer_info, $defaults );

	if ( ! is_email( $customer_info['email'] ) ) {
		give_set_error( 'give-invalid-email', __( 'Please enter a valid email address.', 'give' ) );
	}

	if ( (int) $customer_info['user_id'] != (int) $customer->user_id ) {

		// Make sure we don't already have this user attached to a customer
		if ( ! empty( $customer_info['user_id'] ) && false !== EDD()->customers->get_customer_by( 'user_id', $customer_info['user_id'] ) ) {
			give_set_error( 'give-invalid-customer-user_id', sprintf( __( 'The User ID %d is already associated with a different customer.', 'give' ), $customer_info['user_id'] ) );
		}

		// Make sure it's actually a user
		$user = get_user_by( 'id', $customer_info['user_id'] );
		if ( ! empty( $customer_info['user_id'] ) && false === $user ) {
			give_set_error( 'give-invalid-user_id', sprintf( __( 'The User ID %d does not exist. Please assign an existing user.', 'give' ), $customer_info['user_id'] ) );
		}

	}

	// Record this for later
	$previous_user_id = $customer->user_id;

	if ( give_get_errors() ) {
		return;
	}

	// Setup the customer address, if present
	$address = array();
	if ( intval( $customer_info['user_id'] ) > 0 ) {

		$current_address = get_user_meta( $customer_info['user_id'], '_give_user_address', true );

		if ( false === $current_address ) {
			$address['line1']   = isset( $customer_info['line1'] ) ? $customer_info['line1'] : '';
			$address['line2']   = isset( $customer_info['line2'] ) ? $customer_info['line2'] : '';
			$address['city']    = isset( $customer_info['city'] ) ? $customer_info['city'] : '';
			$address['country'] = isset( $customer_info['country'] ) ? $customer_info['country'] : '';
			$address['zip']     = isset( $customer_info['zip'] ) ? $customer_info['zip'] : '';
			$address['state']   = isset( $customer_info['state'] ) ? $customer_info['state'] : '';
		} else {
			$current_address    = wp_parse_args( $current_address, array(
				'line1',
				'line2',
				'city',
				'zip',
				'state',
				'country'
			) );
			$address['line1']   = isset( $customer_info['line1'] ) ? $customer_info['line1'] : $current_address['line1'];
			$address['line2']   = isset( $customer_info['line2'] ) ? $customer_info['line2'] : $current_address['line2'];
			$address['city']    = isset( $customer_info['city'] ) ? $customer_info['city'] : $current_address['city'];
			$address['country'] = isset( $customer_info['country'] ) ? $customer_info['country'] : $current_address['country'];
			$address['zip']     = isset( $customer_info['zip'] ) ? $customer_info['zip'] : $current_address['zip'];
			$address['state']   = isset( $customer_info['state'] ) ? $customer_info['state'] : $current_address['state'];
		}

	}

	// Sanitize the inputs
	$customer_data            = array();
	$customer_data['name']    = strip_tags( stripslashes( $customer_info['name'] ) );
	$customer_data['email']   = $customer_info['email'];
	$customer_data['user_id'] = $customer_info['user_id'];

	$customer_data = apply_filters( 'give_edit_customer_info', $customer_data, $customer_id );
	$address       = apply_filters( 'give_edit_customer_address', $address, $customer_id );

	$customer_data = array_map( 'sanitize_text_field', $customer_data );
	$address       = array_map( 'sanitize_text_field', $address );

	do_action( 'give_pre_edit_customer', $customer_id, $customer_data, $address );

	$output         = array();
	$previous_email = $customer->email;

	if ( $customer->update( $customer_data ) ) {

		if ( ! empty( $customer->user_id ) && $customer->user_id > 0 ) {
			update_user_meta( $customer->user_id, '_give_user_address', $address );
		}

		// Update some payment meta if we need to
		$payments_array = explode( ',', $customer->payment_ids );

		if ( $customer->email != $previous_email ) {
			foreach ( $payments_array as $payment_id ) {
				give_update_payment_meta( $payment_id, 'email', $customer->email );
			}
		}

		if ( $customer->user_id != $previous_user_id ) {
			foreach ( $payments_array as $payment_id ) {
				give_update_payment_meta( $payment_id, '_give_payment_user_id', $customer->user_id );
			}
		}

		$output['success']       = true;
		$customer_data           = array_merge( $customer_data, $address );
		$output['customer_info'] = $customer_data;

	} else {

		$output['success'] = false;

	}

	do_action( 'give_post_edit_customer', $customer_id, $customer_data );

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		header( 'Content-Type: application/json' );
		echo json_encode( $output );
		wp_die();
	}

	return $output;

}

add_action( 'give_edit-customer', 'give_edit_customer', 10, 1 );

/**
 * Save a customer note being added
 *
 * @since  2.3
 *
 * @param  array $args The $_POST array being passeed
 *
 * @return int         The Note ID that was saved, or 0 if nothing was saved
 */
function give_customer_save_note( $args ) {

	$customer_view_role = apply_filters( 'give_view_customers_role', 'view_shop_reports' );

	if ( ! is_admin() || ! current_user_can( $customer_view_role ) ) {
		wp_die( __( 'You do not have permission to edit this customer.', 'give' ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$customer_note = trim( sanitize_text_field( $args['customer_note'] ) );
	$customer_id   = (int) $args['customer_id'];
	$nonce         = $args['add_customer_note_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'add-customer-note' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'give' ) );
	}

	if ( empty( $customer_note ) ) {
		give_set_error( 'empty-customer-note', __( 'A note is required', 'give' ) );
	}

	if ( give_get_errors() ) {
		return;
	}

	$customer = new Give_Customer( $customer_id );
	$new_note = $customer->add_note( $customer_note );

	do_action( 'give_pre_insert_customer_note', $customer_id, $new_note );

	if ( ! empty( $new_note ) && ! empty( $customer->id ) ) {

		ob_start();
		?>
		<div class="customer-note-wrapper dashboard-comment-wrap comment-item">
			<span class="note-content-wrap">
				<?php echo stripslashes( $new_note ); ?>
			</span>
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			echo $output;
			exit;
		}

		return $new_note;

	}

	return false;

}

add_action( 'give_add-customer-note', 'give_customer_save_note', 10, 1 );

/**
 * Delete a customer
 *
 * @since  2.3
 *
 * @param  array $args The $_POST array being passeed
 *
 * @return int         Wether it was a successful deletion
 */
function give_customer_delete( $args ) {

	$customer_edit_role = apply_filters( 'give_edit_customers_role', 'edit_shop_payments' );

	if ( ! is_admin() || ! current_user_can( $customer_edit_role ) ) {
		wp_die( __( 'You do not have permission to delete this customer.', 'give' ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$customer_id = (int) $args['customer_id'];
	$confirm     = ! empty( $args['give-customer-delete-confirm'] ) ? true : false;
	$remove_data = ! empty( $args['give-customer-delete-records'] ) ? true : false;
	$nonce       = $args['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'delete-customer' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'give' ) );
	}

	if ( ! $confirm ) {
		give_set_error( 'customer-delete-no-confirm', __( 'Please confirm you want to delete this customer', 'give' ) );
	}

	if ( give_get_errors() ) {
		wp_redirect( admin_url( 'edit.php?post_type=download&page=give-customers&view=overview&id=' . $customer_id ) );
		exit;
	}

	$customer = new Give_Customer( $customer_id );

	do_action( 'give_pre_delete_customer', $customer_id, $confirm, $remove_data );

	$success = false;

	if ( $customer->id > 0 ) {

		$payments_array = explode( ',', $customer->payment_ids );
		$success        = EDD()->customers->delete( $customer->id );

		if ( $success ) {

			if ( $remove_data ) {

				// Remove all payments, logs, etc
				foreach ( $payments_array as $payment_id ) {
					give_delete_purchase( $payment_id, false, true );
				}

			} else {

				// Just set the payments to customer_id of 0
				foreach ( $payments_array as $payment_id ) {
					give_update_payment_meta( $payment_id, '_give_payment_customer_id', 0 );
				}

			}

			$redirect = admin_url( 'edit.php?post_type=download&page=give-customers&give-message=customer-deleted' );

		} else {

			give_set_error( 'give-customer-delete-failed', __( 'Error deleting customer', 'give' ) );
			$redirect = admin_url( 'edit.php?post_type=download&page=give-customers&view=delete&id=' . $customer_id );

		}

	} else {

		give_set_error( 'give-customer-delete-invalid-id', __( 'Invalid Customer ID', 'give' ) );
		$redirect = admin_url( 'edit.php?post_type=download&page=give-customers' );

	}

	wp_redirect( $redirect );
	exit;

}

add_action( 'give_delete-customer', 'give_customer_delete', 10, 1 );

/**
 * Disconnect a user ID from a customer
 *
 * @since  2.3
 *
 * @param  array $args Array of arguements
 *
 * @return bool        If the disconnect was sucessful
 */
function give_disconnect_customer_user_id( $args ) {

	$customer_edit_role = apply_filters( 'give_edit_customers_role', 'edit_shop_payments' );

	if ( ! is_admin() || ! current_user_can( $customer_edit_role ) ) {
		wp_die( __( 'You do not have permission to edit this customer.', 'give' ) );
	}

	if ( empty( $args ) ) {
		return;
	}

	$customer_id = (int) $args['customer_id'];
	$nonce       = $args['_wpnonce'];

	if ( ! wp_verify_nonce( $nonce, 'edit-customer' ) ) {
		wp_die( __( 'Cheatin\' eh?!', 'give' ) );
	}

	$customer = new Give_Customer( $customer_id );
	if ( empty( $customer->id ) ) {
		return false;
	}

	do_action( 'give_pre_customer_disconnect_user_id', $customer_id, $customer->user_id );

	$customer_args = array( 'user_id' => 0 );

	if ( $customer->update( $customer_args ) ) {
		global $wpdb;

		if ( ! empty( $customer->payment_ids ) ) {
			$wpdb->query( "UPDATE $wpdb->postmeta SET meta_value = 0 WHERE meta_key = '_give_payment_user_id' AND post_id IN ( $customer->payment_ids )" );
		}

		$output['success'] = true;

	} else {

		$output['success'] = false;
		give_set_error( 'give-disconnect-user-fail', __( 'Failed to disconnect user from customer', 'give' ) );
	}

	do_action( 'give_post_customer_disconnect_user_id', $customer_id );

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		header( 'Content-Type: application/json' );
		echo json_encode( $output );
		wp_die();
	}

	return $output;

}

add_action( 'give_disconnect-userid', 'give_disconnect_customer_user_id', 10, 1 );
