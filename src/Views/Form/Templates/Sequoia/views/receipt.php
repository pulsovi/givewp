<?php

use Give\Receipt\Detail;
use Give\Receipt\DetailGroup;
use Give\Views\Form\Templates\Sequoia\Sequoia;
use Give\Views\IframeContentView;
use function Give\Helpers\Form\Template\get as getTemplateOptions;
use function Give\Helpers\Form\Template\Utils\Frontend\getPaymentId;
use Give_Payment as Payment;

$payment = new Payment( getPaymentId() );
$options = getTemplateOptions();

/* @var Sequoia $sequoiaTemplate */
$sequoiaTemplate = Give()->templates->getTemplate();

$receiptDetails = $sequoiaTemplate->getReceiptDetails( $payment->ID );


ob_start();
?>
<div class="give-receipt-wrap give-embed-receipt">
	<div class="give-section receipt">
		<?php if ( ! empty( $options['thank-you']['image'] ) ) : ?>
			<div class="image">
				<img src="<?php echo $options['thank-you']['image']; ?>" />
			</div>
		<?php else : ?>
			<div class="checkmark">
				<i class="fas fa-check"></i>
			</div>
		<?php endif; ?>
		<h2 class="headline">
			<?php echo $receiptDetails->heading; ?>
		</h2>
		<p class="message">
			<?php echo $receiptDetails->message; ?>
		</p>
		<?php require 'social-sharing.php'; ?>
		<?php
		/* @global DetailGroup $group */
		foreach ( $receiptDetails->getDetailGroupList() as $groupId ) {
			$group = $receiptDetails->get( $groupId );

			echo '<div class="details">';
			if ( $group->heading ) {
				printf( '<h3 class="headline">%1$s</h3>', $group->heading );
			}

			if ( $detailList = $group->getDetailsList() ) {
				echo '<div class="details-table">';

				/* @var Detail $detail */
				foreach ( $detailList as $detailId ) {
					$detail = $group->get( $detailId );
					$value  = $detail->getValue();

					if ( ! $value ) {
						continue;
					}

					// This class is required to highlight total donation amount in receipt.
					$detailRowClass = $detailId === Detail\Donation\TotalAmount::class ? ' total' : '';

					printf(
						'<div class="details-row%1$s">',
						$detailRowClass
					);

					echo $detail->getIcon();

					printf(
						'<div class="detail">%1$s</div><div class="value">%2$s</div>',
						$detail->getLabel(),
						print_r( $value, true )
					);

					echo '</div>';
				}
				echo '</div>';
			}
			echo '</div>';
		}

		require 'subscription-details.php';
		?>
		<!-- Download Receipt TODO: make this conditional on presence of pdf receipts addon -->
		<button class="give-btn download-btn">
			<?php _e( 'Donation Receipt', 'give' ); ?> <i class="fas fa-file-pdf"></i>
		</button>
	</div>
	<div class="form-footer">
		<div class="secure-notice">
			<i class="fas fa-lock"></i>
			<?php _e( 'Secure Donation', 'give' ); ?>
		</div>
	</div>
</div>


<?php
$iframeView = new IframeContentView();

echo $iframeView->setTitle( __( 'Donation Receipt', 'give' ) )
				->setBody( ob_get_clean() )
				->render();
?>
