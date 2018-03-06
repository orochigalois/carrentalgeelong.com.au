<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
if($objSettings->getSetting('conf_load_datepicker_from_plugin') == 1):
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-datepicker-locale');
endif;

// Styles
if($objSettings->getSetting('conf_load_datepicker_from_plugin') == 1):
    wp_enqueue_style('datepicker');
endif;
wp_enqueue_style('car-rental-frontend');
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        var unavailableDates = [<?php print($closedDates); ?>];
        function unavailable(date)
        {
            var ymd = date.getFullYear() + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + ("0"+date.getDate()).slice(-2);
            var day = new Date(ymd).getDay();
            if (jQuery.inArray(ymd, unavailableDates) < 0 )
            {
                return [true, "enabled", ""];
            } else
            {
                return [false,"disabled","<?php print($objLang->getText('NRS_CLOSED_TEXT')); ?>"];
            }
        }
        jQuery('.pickup-date').datepicker({
            minDate: "+<?php print($minDate); ?>D",
            maxDate: "+730D",
            beforeShowDay: unavailable,
            numberOfMonths: 2,
            dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
            firstDay: <?php print(get_option('start_of_week')); ?>,
            onSelect: function(selected) {
                var date = jQuery(this).datepicker('getDate');
                if(date){
                    date.setDate(date.getDate());
                }
                jQuery('.return-date').datepicker("option","minDate", date)
            }
        });

        jQuery('.return-date').datepicker({
            minDate: 0,
            maxDate:"+730D",
            beforeShowDay: unavailable,
            numberOfMonths: 2,
            dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
            firstDay: <?php print(get_option('start_of_week')); ?>,
            onSelect: function(selected) {
                jQuery('.pickup-date').datepicker("option","maxDate", selected)
            }
        });
        jQuery('.pickup-date-datepicker').click(function()
        {
            jQuery('.pickup-date').datepicker('show');
        });
        jQuery(".return-date-datepicker").click(function()
        {
            jQuery('.return-date').datepicker('show');
        });

        jQuery('.car-rental-do-search').click(function()
        {
            var objCouponCode = jQuery('.coupon-code');
            var objPickupLocation = jQuery('.pickup-location');
            var objReturnLocation = jQuery('.return-location');
            var objPickupDate = jQuery('.pickup-date');
            var objReturnDate = jQuery('.return-date');
            var objBookingPeriod = jQuery('.booking-period');

            var couponCode = "SKIP";
            var pickupLocationId = "SKIP";
            var returnLocationId = "SKIP";
            var pickupDate = "SKIP";
            var returnDate = "SKIP";
            var bookingPeriod = "SKIP";

            <?php if($couponCodeRequired): ?>
            if(objCouponCode.length)
            {
                couponCode = objCouponCode.val();
            }
            <?php endif; ?>

            <?php if($pickupLocationRequired): ?>
                if(objPickupLocation.length)
                {
                    pickupLocationId = objPickupLocation.val();
                }
            <?php endif; ?>

            <?php if($returnLocationRequired): ?>
                if(objReturnLocation.length)
                {
                    returnLocationId = objReturnLocation.val();
                }
            <?php endif; ?>

            <?php if($pickupDateRequired): ?>
                if(objPickupDate.length)
                {
                    pickupDate = objPickupDate.val();
                }
            <?php endif; ?>

            <?php if($returnDateRequired): ?>
                if(objReturnDate.length)
                {
                    returnDate = objReturnDate.val();
                }
            <?php endif; ?>

            <?php if($returnDateRequired): ?>
                if(objBookingPeriod.length)
                {
                    bookingPeriod = objBookingPeriod.val();
                }
            <?php endif; ?>
            //alert('pickupDate[len]:' + objPickupDate.length + ', pickupDate[val]:' + pickupDate);

            if(couponCode == "" || couponCode == "<?php print($objLang->getText('NRS_I_HAVE_COUPON_CODE_TEXT')); ?>")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_COUPON_CODE_ALERT_TEXT'))); ?>');
                return false;
            } else if(pickupLocationId == "")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_PICKUP_LOCATION_ALERT_TEXT'))); ?>');
                return false;
            } else if(returnLocationId == "")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_RETURN_LOCATION_ALERT_TEXT'))); ?>');
                return false;
            } else if(pickupDate == "" || pickupDate == "<?php print($objLang->getText('NRS_SELECT_BOOKING_DATE_TEXT')); ?>")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_PICKUP_DATE_ALERT_TEXT'))); ?>');
                return false;
            } else if(returnDate == "" || returnDate == "<?php print($objLang->getText('NRS_SELECT_BOOKING_DATE_TEXT')); ?>")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_RETURN_DATE_ALERT_TEXT'))); ?>');
                return false;
            } else if(bookingPeriod == "")
            {
                alert('<?php print(esc_html($objLang->getText('NRS_BOOKING_PERIOD_ALERT_TEXT'))); ?>');
                return false;
            } else
            {
                return true;
            }
        });
    });
</script>
<div class="car-rental-wrapper car-rental-single-booking">
    <form id="formElem" name="formElem" action="<?php print($actionPageURL); ?>" method="post">
        <input type="hidden" name="pickup_location_id" value="<?php print($pickupLocationId); ?>" />
        <input type="hidden" name="return_location_id" value="<?php print($returnLocationId); ?>" />
        <div class="booking-item">
            <div class="booking-item-body">
                <?php if($pickupDateVisible): ?>
                    <div class="inline-div">
                        <input type="text" name="pickup_date" value="<?php print($objLang->getText('NRS_SELECT_BOOKING_DATE_TEXT')); ?>" class="pickup-date" readonly="readonly" AUTOCOMPLETE=OFF />
                    </div>
                    <div class="inline-div">
                        <img src="<?php print($objConf->getExtensionFrontImagesURL('Month.png')); ?>" alt="datepicker" class="pickup-date-datepicker date-selector-image" />
                    </div>
                    <div class="styled-select-dropdown narrow-dropdown">
                        <select name="pickup_time" class="pickup-time">
                            <?php print($pickupTimeDropDownOptions); ?>
                        </select>
                    </div>
                <?php endif; ?>
                <?php if($partnerVisible): ?>
                    <?php if($partnerId > 0): ?>
                        <input type="hidden" name="partner_id" class="partner" value="<?php print($partnerId); ?>" />
                    <?php else: ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="partner_id" class="partner">
                                <?php print($partnersDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($manufacturerVisible): ?>
                    <?php if($manufacturerId > 0): ?>
                        <input type="hidden" name="manufacturer_id" class="manufacturer" value="<?php print($manufacturerId); ?>" />
                    <?php else: ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="manufacturer_id" class="manufacturer">
                                <?php print($manufacturersDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($bodyTypeVisible): ?>
                    <?php if($bodyTypeId > 0): ?>
                        <input type="hidden" name="body_type_id" value="<?php print($bodyTypeId); ?>" class="body-type" />
                    <?php else: ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="body_type_id" class="body-type">
                                <?php print($bodyTypesDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($couponCodeVisible): ?>
                    <div class="inline-div">
                        <input type="text" name="coupon_code" value="<?php print($objLang->getText('NRS_I_HAVE_COUPON_CODE_TEXT')); ?>" class="coupon-code"
                               onfocus="if(this.value == '<?php print($objLang->getText('NRS_I_HAVE_COUPON_CODE_TEXT')); ?>') {this.value=''}"
                               onblur="if(this.value == ''){this.value ='<?php print($objLang->getText('NRS_I_HAVE_COUPON_CODE_TEXT')); ?>'}" />
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="booking-item">
            <div class="booking-item-body">
                <?php if($returnDateVisible): ?>
                    <?php if($objSettings->getSetting('conf_price_calculation_type') == 2): ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="booking_period" class="booking-period home-select">
                                <?php print($bookingPeriodsDropDownOptions); ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="inline-div">
                            <input type="text" name="return_date" value="<?php print($objLang->getText('NRS_SELECT_BOOKING_DATE_TEXT')); ?>" class="return-date" readonly="readonly" AUTOCOMPLETE=OFF />
                        </div>
                        <div class="inline-div">
                            <img src="<?php print($objConf->getExtensionFrontImagesURL('Month.png')); ?>" alt="datepicker" class="return-date-datepicker date-selector-image" />
                        </div>
                        <div class="styled-select-dropdown narrow-dropdown">
                            <select name="return_time" class="return-time">
                                <?php print($returnTimeDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($transmissionTypeVisible): ?>
                    <?php if($transmissionTypeId > 0): ?>
                        <input type="hidden" name="transmission_type_id" value="<?php print($transmissionTypeId); ?>" class="transmission-type" />
                    <?php else: ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="transmission_type_id" class="transmission-type">
                                <?php print($transmissionTypesDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($fuelTypeVisible): ?>
                    <?php if($fuelTypeId > 0): ?>
                        <input type="hidden" name="fuel_type_id" value="<?php print($fuelTypeId); ?>" class="fuel-type" />
                    <?php else: ?>
                        <div class="styled-select-dropdown wide-dropdown">
                            <select name="fuel_type_id" class="fuel-type">
                                <?php print($fuelTypesDropDownOptions); ?>
                            </select>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="booking-item">
            <div class="booking-item-body">
                <input type="hidden" name="booking_code" value="" />
                <div class="top-padded-submit">
                    <?php
                    if($objSettings->getSetting('conf_universal_analytics_events_tracking') == 1):
                        // Note: Do not translate events to track well inter-language events
                        $onClick = "ga('send', 'event', 'Car Rental', 'Click', '1. Search for single car');";
                        print('<input type="submit" name="car_rental_do_search" value="'.$objLang->getText('NRS_CONTINUE_TEXT').'" class="car-rental-do-search" onClick="'.esc_js($onClick).'" />');
                    else:
                        print('<input type="submit" name="car_rental_do_search" value="'.$objLang->getText('NRS_CONTINUE_TEXT').'" class="car-rental-do-search" />');
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>