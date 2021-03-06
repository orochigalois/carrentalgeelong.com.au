<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<script type="text/javascript">
// Add Enhanced commerce library code
ga('require', 'ec');

<?php foreach ($items AS $item): if($item['item_sku'] != ""): ?>
// Note: we should provide item name only for tracking and it should not be translated
// The following code measures the impression (view) of a product in a list of search results:
ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
    'id': '<?php print($item['item_sku']); ?>',                                     // Product ID (string).
    'name': '<?php print($item['print_manufacturer_title'].' '.$item['print_model_name']); ?>', // Product name (string).
    'category': '<?php print($item['print_body_type_title']); ?>',                        // Product category (string).
    'brand': '<?php print($item['print_manufacturer_title']); ?>',                        // Product brand (string).
    'list': 'Search Results'         // Product list (string).
});
<?php endif; endforeach; ?>

<?php foreach ($extras AS $extra): if($extra['extra_sku'] != ""): ?>
// Note: we should provide extra name only for tracking and it should not be translated
// The following code measures the impression (view) of a product in a list of search results:
ga('ec:addImpression', {                                // Provide product details in an impressionFieldObject.
    'id': '<?php print($extra['print_extra_sku']); ?>',       // Product ID (string).
    'name': '<?php print($extra['print_extra_name']); ?>',    // Product name (string).
    'category': 'Extras',                               // Product category (string).
    'brand': 'Extra',                                   // Product brand (string).
    'list': 'Search Results'                            // Product list (string).
});
<?php endif; endforeach; ?>

// Ecommerce data can only be sent with an existing hit, for example a pageview or event. If you use ecommerce commands
// but do not send any hits, or the hit is sent before the ecommerce command then the ecommerce data will not be sent.
// Note: we can't use 'pageview' hit here, because it is already sent in site headers and we don't want to count it twice
// But we still want to process these impressions, so we call non-interactive pageview
// Note 2: Do not translate events to track well inter-language events
ga('send', 'event', '<?php print($extensionName.' Enhanced Ecommerce'); ?>', 'Add Impression', {'nonInteraction': true});
</script>