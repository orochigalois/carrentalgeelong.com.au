<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-datatables');
wp_enqueue_script('datatables-responsive');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Load Nice Admin Tabs CSS
wp_enqueue_style('font-awesome');
wp_enqueue_style('car-rental-admin-tabs');

// Styles
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-ui');
wp_enqueue_style('jquery-datatables');
wp_enqueue_style('datatables-responsive');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<div class="car-rental-list-admin customer-search-results car-rental-tabbed-admin car-rental-tabbed-admin-wide bg-cyan">
	<div class="body">
		<!-- tabs -->
		<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
			<input type="radio" name="car-rental-admin-tabs" checked="checked" id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
			<label for="car-rental-admin-tab1"><span><span><i class="fa fa-car"></i>Search Results</span></span></label>
			<ul>
				<li class="car-rental-admin-tab-content-1">
					<div class="typography">
						<h1 class="search-results-title">
							<?php print($bookingsTableTitle.($customerDescription ? '<br />'.$customerDescription : '')); ?>
						</h1>
						<div class="col-search">
                            <?php if($backToTab == "pickups"): ?>
                                &nbsp;&nbsp; <input class="back-to" type="button" value="Back to Today&#39;s Pick-up List"
                                                    onClick="window.location.href='admin.php?page=<?php print($objConf->getURLPrefix()); ?>booking-manager&tab=pickups'"
                                />
                            <?php elseif($backToTab == "returns"): ?>
                                &nbsp;&nbsp; <input class="back-to" type="button" value="Back to Today&#39;s Return List"
                                                    onClick="window.location.href='admin.php?page=<?php print($objConf->getURLPrefix()); ?>booking-manager&tab=returns'"
                                />
                            <?php else: ?>
                                &nbsp;&nbsp; <input class="back-to" type="button" value="Back to Today&#39;s Reservation List"
                                                    onClick="window.location.href='admin.php?page=<?php print($objConf->getURLPrefix()); ?>booking-manager&tab=bookings'"
                                />
                            <?php endif; ?>
						</div>
						<?php include('partial.Bookings.php'); ?>
					</div>
				</li>
			</ul>
		</div>
		<!--/ tabs -->
	</div>
</div>