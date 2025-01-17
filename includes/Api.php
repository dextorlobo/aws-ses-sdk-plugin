<?php

namespace Imarun\AwsSesSdkPlugin;
use WP_Error;
use WP_REST_Response;
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class Api {

	public function __construct() {
		$options            = get_option( 'wp_ses_general_options', false );
		$this->access_key   = ( isset( $options['wp_ses_general_access_key'] ) ) ? $options['wp_ses_general_access_key'] : '';
		$this->secret_key   = ( isset( $options['wp_ses_general_secret_key'] ) ) ? $options['wp_ses_general_secret_key'] : '';
		$this->sender_email = ( isset( $options['wp_ses_general_sender_email'] ) ) ? $options['wp_ses_general_sender_email'] : '';
	}

	public function send_order_email( $data ) {
		if (  empty( $this->access_key ) || empty( $this->secret_key ) || empty( $this->sender_email ) ) {
			return new WP_Error( 'error', 'Please enter all the required fields.' );
		}
		// Create an SesClient. Change the value of the region parameter if you're 
		// using an AWS Region other than US West (Oregon). Change the value of the
		// profile parameter if you want to use a profile in your credentials file
		// other than the default.
		$SesClient = new SesClient([
			'credentials' => [
				'key'    => $this->access_key,
				'secret' => $this->secret_key
			],
			'version' => '2010-12-01',
			'region'  => 'ap-south-1'
		]);

		// Replace sender@example.com with your "From" address.
		// This address must be verified with Amazon SES.

		// Specify a configuration set. If you do not want to use a configuration
		// set, comment the following variable, and the
		// 'ConfigurationSetName' => $configuration_set argument below.
		$configuration_set = 'ConfigSet';

		$wp_order_id = $data['wp_order_id'];
		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$email = $data['email'];
		$phone = $data['phone'];
		$seat_no = $data['seat_no'];
		$product_id = $data['product_id'];
		$pass_name = $data['pass_name'];
		$amount = $data['amount'];
		$transaction_id = $data['transaction_id'];
		$status = $data['status'];
		$order_date = $data['order_date'];

		$subject = 'TecHouse Library - Order Details - '. $status;
		// Replace these sample addresses with the addresses of your recipients. If
		// your account is still in the sandbox, these addresses must be verified.
		$recipient_emails = [ $email ];

		$html_body =  '<table border="1" cellpadding="5" cellspacing="0" width="500">
			<tr>
				<th colspan="2" style="background-color: #f0f0f0; font-weight: bold; text-align: center;">Order Details</th>
			</tr>
			<tr>
				<td style="width: 30%; font-weight: bold;">OrderID:</td>
				<td style="width: 70%;">'. $wp_order_id .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Pass Name:</td>
				<td>'. $pass_name .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Price:</td>
				<td>₹ '.$amount .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Name:</td>
				<td>'. $first_name. ' ' .$last_name .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Email:</td>
				<td>'. $email .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Phone:</td>
				<td>'. $phone .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Seat #:</td>
				<td>'. $seat_no .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">TransactionID:</td>
				<td>'. $transaction_id .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Status:</td>
				<td>'. $status .'</td>
			</tr>
			<tr>
				<td style="font-weight: bold;">Date:</td>
				<td>'. $order_date .'</td>
			</tr>
		</table>';
		$char_set = 'UTF-8';

		try {
			$result = $SesClient->sendEmail([
				'Destination' => [
					'ToAddresses' => $recipient_emails,
				],
				'ReplyToAddresses' => [$this->sender_email],
				'Source' => $this->sender_email, // Replace sender@example.com with your "From" address. This address must be verified with Amazon SES.
				'Message' => [
				'Body' => [
					'Html' => [
						'Charset' => $char_set,
						'Data' => $html_body,
					],
					/* 'Text' => [
						'Charset' => $char_set,
						'Data' => $plaintext_body,
					], */
				],
				'Subject' => [
					'Charset' => $char_set,
					'Data' => $subject,
				],
				],
				// If you aren't using a configuration set, comment or delete the
				// following line
				'ConfigurationSetName' => $configuration_set,
			]);
			$messageId = $result['MessageId'];

			return new WP_REST_Response( $messageId, 200 );
		} catch (AwsException $e) {
			return new WP_Error( 'error', $e->getAwsErrorMessage() );
		}
	}
}
