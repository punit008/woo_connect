<?php

use Connector\Facades\Option;
use ActiveAnts\LogisticsLib\Clients\DHL\Rest as DHLClient;
use ActiveAnts\LogisticsLib\Clients\PostNL\Rest as PostNLClient;

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    function activeants_shipping_method()
    {
        if (!class_exists('ActiveAnts_Shipping_Dhl')) {
            class ActiveAnts_Shipping_Dhl extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id                 = 'activeants_dhl';
                    $this->method_title       = __('ActiveAnts DHL Service Points Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'NL', // Netherland
                        'BE', // Belgium
                    );

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('ActiveAnts DHL Service Points Shipping', 'activeants');
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields()
                {

                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'activeants'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'activeants'),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __('Title', 'activeants'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'activeants'),
                            'default' => __('ActiveAnts DHL Service Points Shipping', 'activeants')
                        ),
                        'shipping_cost' => array(
                            'title' => __('Shipping Cost', 'activeants'),
                            'type' => 'text',
                            'description' => __('Cost of shipping', 'activeants'),
                            'default' => '10'
                        )
                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array())
                {
                    $rate = array(
                        'label' => $this->title,
                        'cost' => isset($this->settings['shipping_cost']) ? $this->settings['shipping_cost'] : 10,
                        'calc_tax' => 'per_item'
                    );
                    $this->add_rate($rate);
                }
            }
        }
        if (!class_exists('ActiveAnts_Shipping_PostNl')) {
            class ActiveAnts_Shipping_PostNl extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct()
                {
                    $this->id                 = 'activeants_postnl';
                    $this->method_title       = __('ActiveAnts PostNL Service Points Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');

                    // Availability & Countries
                    $this->availability = 'including';
                    $this->countries = array(
                        'NL', // Netherland
                        'BE', // Belgium
                    );

                    $this->init();

                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('ActiveAnts PostNL Service Points Shipping', 'activeants');
                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void 
                 */
                function init_form_fields()
                {

                    $this->form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'activeants'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'activeants'),
                            'default' => 'yes'
                        ),
                        'title' => array(
                            'title' => __('Title', 'activeants'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'activeants'),
                            'default' => __('ActiveAnts Service Points Shipping', 'activeants')
                        ),
                        'sandbox_mode' => array(
                            'title' => __('Sandbox Mode', 'activeants'),
                            'type' => 'checkbox',
                            'description' => __('PostNL Sandbox mode.', 'activeants'),
                            'default' => 'yes'
                        ),
                        'api_key' => array(
                            'title' => __('API Key', 'activeants'),
                            'type' => 'text',
                            'description' => __('PostNL API Key.', 'activeants'),
                            'default' => ''
                        ),
                        'shipping_cost' => array(
                            'title' => __('Shipping Cost', 'activeants'),
                            'type' => 'text',
                            'description' => __('Cost of shipping', 'activeants'),
                            'default' => '10'
                        )
                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array())
                {
                    $rate = array(
                        'label' => $this->title,
                        'cost' => isset($this->settings['shipping_cost']) ? $this->settings['shipping_cost'] : 10,
                        'calc_tax' => 'per_item'
                    );
                    $this->add_rate($rate);
                }
            }
        }
        if (!class_exists('ActiveAnts_Shipping_Dhl_Standard')) {
            class ActiveAnts_Shipping_Dhl_Standard extends WC_Shipping_Flat_Rate
            {
                /**
                 * Constructor.
                 *
                 * @param int $instance_id Shipping method instance ID.
                 */
                public function __construct($instance_id = 1)
                {
                    $this->id                 = 'activeants_dhl_standard';
                    $this->instance_id        = absint($instance_id);
                    $this->method_title       = __('ActiveAnts DHL Standard Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');
                    $this->supports           = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                    );
                    $this->init();

                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Init user set variables.
                 */
                public function init()
                {
                    $this->instance_form_fields = include __DIR__ . '/settings-dhl-standard.php';
                    $this->title                = $this->get_option('title');
                    $this->tax_status           = $this->get_option('tax_status');
                    $this->cost                 = $this->get_option('cost');
                    $this->type                 = $this->get_option('type', 'class');
                }
            }
        }
        if (!class_exists('ActiveAnts_Shipping_PostNL_Standard')) {
            class ActiveAnts_Shipping_PostNl_Standard extends WC_Shipping_Flat_Rate
            {
                /**
                 * Constructor.
                 *
                 * @param int $instance_id Shipping method instance ID.
                 */
                public function __construct($instance_id = 1)
                {
                    $this->id                 = 'activeants_postnl_standard';
                    $this->instance_id        = absint($instance_id);
                    $this->method_title       = __('ActiveAnts PostNL Standard Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');
                    $this->supports           = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                    );
                    $this->init();

                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Init user set variables.
                 */
                public function init()
                {
                    $this->instance_form_fields = include __DIR__ . '/settings-postnl-standard.php';
                    $this->title                = $this->get_option('title');
                    $this->tax_status           = $this->get_option('tax_status');
                    $this->cost                 = $this->get_option('cost');
                    $this->type                 = $this->get_option('type', 'class');
                }
            }
        }

        if (!class_exists('ActiveAnts_Shipping_Dhl_Free_Shipping')) {
            class ActiveAnts_Shipping_Dhl_Free_Shipping extends WC_Shipping_Free_Shipping
            {
                /**
                 * Constructor.
                 *
                 * @param int $instance_id Shipping method instance.
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id                 = 'activeants_dhl_free_shipping';
                    $this->instance_id        = absint($instance_id);
                    $this->method_title       = __('ActiveAnts DHL Free Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');
                    $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->supports           = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                    );

                    $this->init();
                }

                /**
                 * Initialize free shipping.
                 */
                public function init()
                {
                    // Load the settings.
                    $this->init_form_fields();
                    $this->init_settings();

                    // Define user set variables.
                    $this->title            = $this->get_option('title');
                    $this->min_amount       = $this->get_option('min_amount', 0);
                    $this->requires         = $this->get_option('requires');
                    $this->ignore_discounts = $this->get_option('ignore_discounts');

                    // Actions.
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                    add_action('admin_footer', array('WC_Shipping_Free_Shipping', 'enqueue_admin_js'), 10); // Priority needs to be higher than wc_print_js (25).
                }

                /**
                 * Init form fields.
                 */
                public function init_form_fields()
                {
                    $this->instance_form_fields = array(
                        'title'            => array(
                            'title'       => __('Title', 'activeants'),
                            'type'        => 'text',
                            'description' => __('This controls the title which the user sees during checkout.', 'activeants'),
                            'default'     => $this->method_title,
                            'desc_tip'    => true,
                        ),
                        'requires'         => array(
                            'title'   => __('Free shipping requires...', 'activeants'),
                            'type'    => 'select',
                            'class'   => 'wc-enhanced-select',
                            'default' => '',
                            'options' => array(
                                ''           => __('N/A', 'activeants'),
                                'coupon'     => __('A valid free shipping coupon', 'activeants'),
                                'min_amount' => __('A minimum order amount', 'activeants'),
                                'either'     => __('A minimum order amount OR a coupon', 'activeants'),
                                'both'       => __('A minimum order amount AND a coupon', 'activeants'),
                            ),
                        ),
                        'min_amount'       => array(
                            'title'       => __('Minimum order amount', 'activeants'),
                            'type'        => 'price',
                            'placeholder' => wc_format_localized_price(0),
                            'description' => __('Users will need to spend this amount to get free shipping (if enabled above).', 'activeants'),
                            'default'     => '0',
                            'desc_tip'    => true,
                        ),
                        'ignore_discounts' => array(
                            'title'       => __('Coupons discounts', 'activeants'),
                            'label'       => __('Apply minimum order rule before coupon discount', 'activeants'),
                            'type'        => 'checkbox',
                            'description' => __('If checked, free shipping would be available based on pre-discount order amount.', 'activeants'),
                            'default'     => 'no',
                            'desc_tip'    => true,
                        ),
                        'excluded_delivery_days' => array(
                            'title' => __('Excluded Delivery Days'),
                            'type'  => 'text',
                            'placeholder'   =>  '1,2,...7',
                            'description'   =>  __('Excluded delivery days'),
                            'desc_tip'    => true,
                        ),
                        'excluded_delivery_dates' => array(
                            'title' => __('Excluded Delivery Dates'),
                            'type'  => 'text',
                            'placeholder' => 'YYYY-MM-DD, YYYY-MM-DD, ...',
                            'description'   =>  __('Excluded delivery dates'),
                            'desc_tip'    => true,
                        ),
                        'allowed_delivery_days_count' => array(
                            'title' => __('Allowed Delivery Days Count'),
                            'type' => 'number',
                            'placeholder' => '7',
                            'default' => '7',
                            'description' => __('Allowed delivery days'),
                            'desc_tip' => true,
                        ),
                    );
                }
            }
        }

        if (!class_exists('ActiveAnts_Shipping_PostNL_Free_Shipping')) {
            class ActiveAnts_Shipping_PostNL_Free_Shipping extends WC_Shipping_Free_Shipping
            {

                /**
                 * Constructor.
                 *
                 * @param int $instance_id Shipping method instance.
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id                 = 'activeants_postnl_free_shipping';
                    $this->instance_id        = absint($instance_id);
                    $this->method_title       = __('ActiveAnts PostNL Free Shipping', 'activeants');
                    $this->method_description = __('Custom Shipping Method for ActiveAnts', 'activeants');
                    $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->supports           = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                    );

                    $this->init();
                }

                /**
                 * Initialize free shipping.
                 */
                public function init()
                {
                    // Load the settings.
                    $this->init_form_fields();
                    $this->init_settings();

                    // Define user set variables.
                    $this->title            = $this->get_option('title');
                    $this->min_amount       = $this->get_option('min_amount', 0);
                    $this->requires         = $this->get_option('requires');
                    $this->ignore_discounts = $this->get_option('ignore_discounts');

                    // Actions.
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                    add_action('admin_footer', array('WC_Shipping_Free_Shipping', 'enqueue_admin_js'), 10); // Priority needs to be higher than wc_print_js (25).
                }

                /**
                 * Init form fields.
                 */
                public function init_form_fields()
                {
                    $this->instance_form_fields = array(
                        'title'            => array(
                            'title'       => __('Title', 'activeants'),
                            'type'        => 'text',
                            'description' => __('This controls the title which the user sees during checkout.', 'activeants'),
                            'default'     => $this->method_title,
                            'desc_tip'    => true,
                        ),
                        'requires'         => array(
                            'title'   => __('Free shipping requires...', 'activeants'),
                            'type'    => 'select',
                            'class'   => 'wc-enhanced-select',
                            'default' => '',
                            'options' => array(
                                ''           => __('N/A', 'activeants'),
                                'coupon'     => __('A valid free shipping coupon', 'activeants'),
                                'min_amount' => __('A minimum order amount', 'activeants'),
                                'either'     => __('A minimum order amount OR a coupon', 'activeants'),
                                'both'       => __('A minimum order amount AND a coupon', 'activeants'),
                            ),
                        ),
                        'min_amount'       => array(
                            'title'       => __('Minimum order amount', 'activeants'),
                            'type'        => 'price',
                            'placeholder' => wc_format_localized_price(0),
                            'description' => __('Users will need to spend this amount to get free shipping (if enabled above).', 'activeants'),
                            'default'     => '0',
                            'desc_tip'    => true,
                        ),
                        'ignore_discounts' => array(
                            'title'       => __('Coupons discounts', 'activeants'),
                            'label'       => __('Apply minimum order rule before coupon discount', 'activeants'),
                            'type'        => 'checkbox',
                            'description' => __('If checked, free shipping would be available based on pre-discount order amount.', 'activeants'),
                            'default'     => 'no',
                            'desc_tip'    => true,
                        ),
                        'excluded_delivery_days' => array(
                            'title' => __('Excluded Delivery Days'),
                            'type'  => 'text',
                            'placeholder'   =>  '1,2,...7',
                            'description'   =>  __('Excluded delivery days'),
                            'desc_tip'    => true,
                        ),
                        'excluded_delivery_dates' => array(
                            'title' => __('Excluded Delivery Dates'),
                            'type'  => 'text',
                            'placeholder' => 'YYYY-MM-DD, YYYY-MM-DD, ...',
                            'description'   =>  __('Excluded delivery dates'),
                            'desc_tip'    => true,
                        ),
                        'allowed_delivery_days_count' => array(
                            'title' => __('Allowed Delivery Days Count'),
                            'type' => 'number',
                            'placeholder' => '7',
                            'default' => '7',
                            'description' => __('Allowed delivery days'),
                            'id' => 'allowed_days',
                            'desc_tip' => true,
                        ),
                    );
                }
            }
        }
    }
    add_action('woocommerce_shipping_init', 'activeants_shipping_method');

    function add_activeants_shipping_method($methods)
    {
        $methods['activeants_dhl'] = 'ActiveAnts_Shipping_Dhl';
        $methods['activeants_postnl'] = 'ActiveAnts_Shipping_PostNl';
        $methods['activeants_dhl_standard'] = 'ActiveAnts_Shipping_Dhl_Standard';
        $methods['activeants_postnl_standard'] = 'ActiveAnts_Shipping_PostNl_Standard';
        $methods['activeants_dhl_free_shipping'] = 'ActiveAnts_Shipping_Dhl_Free_Shipping';
        $methods['activeants_postnl_free_shipping'] = 'ActiveAnts_Shipping_PostNL_Free_Shipping';
        return $methods;
    }
    add_filter('woocommerce_shipping_methods', 'add_activeants_shipping_method');

    // function activeants_validate_order($posted)
    // {
    //     $packages = WC()->shipping->get_packages();

    //     $chosen_methods = WC()->session->get('chosen_shipping_methods');

    //     // var_dump(in_array('activeants', $chosen_methods));

    //     if (is_array($chosen_methods) && in_array('activeants', $chosen_methods)) {




    //         foreach ($packages as $i => $package) {

    //             if ($chosen_methods[$i] != "activeants") {

    //                 continue;
    //             }

    //             // var_dump(ActiveAnts_Shipping_Method());
    //             $ActiveAnts_Shipping_Method = new ActiveAnts_Shipping_Method();
    //             $weightLimit = (int) $ActiveAnts_Shipping_Method->settings['weight'];
    //             $weight = 0;


    //             foreach ($package['contents'] as $item_id => $values) {
    //                 $_product = $values['data'];
    //                 $weight = $weight + (int)$_product->get_weight() * (int)$values['quantity'];
    //             }

    //             $weight = wc_get_weight($weight, 'kg');

    //             if ($weight > $weightLimit) {

    //                 $message = sprintf(__('Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'activeants'), $weight, $weightLimit, $ActiveAnts_Shipping_Method->title);

    //                 $messageType = "error";

    //                 if (!wc_has_notice($message, $messageType)) {

    //                     wc_add_notice($message, $messageType);
    //                 }
    //             }
    //         }
    //     }
    // }

    add_action('woocommerce_review_order_before_cart_contents', 'activeants_validate_order', 10);
    add_action('woocommerce_after_checkout_validation', 'activeants_validate_order', 10);


    // Add custom fields to a specific selected shipping method
    add_action('woocommerce_after_shipping_rate', 'activeants_carrier_custom_fields', 20, 2);

    function activeants_carrier_custom_fields($method, $index)
    {

        if (!is_checkout()) return; // Only on checkout page

        $customer_carrier_methods = ['activeants_dhl', 'activeants_postnl'];
        $customer_standard_carrier_methods = ['activeants_dhl_standard', 'activeants_postnl_standard', 'activeants_dhl_free_shipping', 'activeants_postnl_free_shipping'];

        // var_dump($method->id);

        if (!in_array($method->method_id, array_merge($customer_carrier_methods, $customer_standard_carrier_methods))) return; // Only display for "activeants"
        $chosen_method_id = explode(':', WC()->session->chosen_shipping_methods[$index])[0];
        
        // var_dump(in_array($chosen_method_id, $customer_carrier_methods));

        // If the chosen shipping method is 'activeants' we display
        if (in_array($chosen_method_id, $customer_carrier_methods) && $method->method_id == $chosen_method_id) :

            echo '<div class="activeants-shipping-method">';
            $postcode = WC()->customer->get_shipping_postcode();
            $country = WC()->customer->get_shipping_country();
            if (!empty($postcode)) {
                try {
                    $service_locations = __getServicePoints($postcode, $country, $chosen_method_id);
                    if (!empty($service_locations)) {
                        $filtered_data = [];
                        foreach ($service_locations as $k => $service_location) {
                            $filtered_data[$service_location->getId()] = htmlspecialchars($service_location->getTitle() . ', ' . $service_location->getStreet() . ', ' . $service_location->getZipcode() . ', ' . $service_location->getCity() . ', ' . $service_location->getCountry());
                        }
                        echo '<style>
                            #checkout-radio label.radio {
                                margin-left: 5px;
                            }
                            #checkout-radio input[type=radio] {
                                margin-left: 15px;
                                float: left;
                                clear: left;
                                margin-top: 5px;
                            }
                            #checkout-radio .woocommerce-input-wrapper {
                                max-height: 200px;
                                overflow-y: scroll;
                                margin-bottom: 20px;
                                display: block;
                            }
                        </style>';
                        echo '<div id="checkout-radio">';
                        woocommerce_form_field(
                            'service_locations',
                            array(
                                'type'          => 'radio',
                                'class'         => array('service_locations form-row-wide'),
                                'label'         => __('Select your service location'),
                                'required'      => true,
                                'options'       => $filtered_data,
                            ),
                            WC()->checkout->get_value('service_locations')
                        );
                        woocommerce_form_field('service_location_data', array(
                            'type'          => 'hidden',
                            'class'         => array('service_location_data form-row-wide'),
                            'required'      => true,
                            'options'       => $filtered_data,
                        ), WC()->checkout->get_value('service_location_data'));
                        echo '</div>';
                    } else {
                        throw new Exception("No serive points found", 1);
                    }
                } catch (Throwable $th) {
                    echo 'Sorry, No Service points found.';
                }
            } else {
                echo 'Please enter postcode first.';
            }
            $gmap_api_key = isset(Option::get('general_options')['gmap_api_key']) ? Option::get('general_options')['gmap_api_key'] : '';

        elseif (in_array($chosen_method_id, $customer_standard_carrier_methods) && $method->method_id == $chosen_method_id) :

            echo '<div id="checkout-radio">';
            woocommerce_form_field('available_dates', array(
                'type'          => 'select',
                'class'         => array('form-row-wide'),
                'label'         => __('Select given dates'),
                'required'      => true,
                'options'       => shipping_date(),
            ));
            echo '</div>';
        // }


        endif;
    }

    // Check custom fields validation
    add_action('woocommerce_checkout_process', 'activeants_carrier_checkout_process');
    function activeants_carrier_checkout_process()
    {
        if (count(array_intersect(['activeants_dhl', 'activeants_postnl'], WC()->session->chosen_shipping_methods)) > 0) {
            if (empty($_POST['service_locations']))
                wc_add_notice(("Please don't forget to select service location."), "error");
        }
        if (!$_POST['available_dates']) {
            wc_add_notice(__('Please select shipping date.'), 'error');
        }
    }

    // Save custom fields to order meta data
    add_action('woocommerce_checkout_update_order_meta', 'activeants_carrier_update_order_meta', 30, 1);
    function activeants_carrier_update_order_meta($order_id)
    {

        if (isset($_POST['service_locations']))
            update_post_meta($order_id, '_service_location', sanitize_text_field($_POST['service_locations']));

        try {
            if (isset($_POST['service_location_data'])) {
                update_post_meta($order_id, '_service_location_data', json_decode(base64_decode(sanitize_text_field($_POST['service_location_data'])), true));
            }
        } catch (Throwable $th) {
            // echo $th->getMessage();
        }
    }

    function activeants_add_gmap_script()
    {

        if (is_checkout()) {
            $gmap_api_key = isset(Option::get('general_options')['gmap_api_key']) ? Option::get('general_options')['gmap_api_key'] : '';
?>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?= (!empty($gmap_api_key) ? $gmap_api_key : 'YOUR_API_KEY_HERE') ?>"></script>
<?php
        }
    }
    add_action('wp_head', 'activeants_add_gmap_script');

    add_action('woocommerce_admin_order_data_after_shipping_address', 'service_location_data_display_admin_order_meta', 10, 1);
    function service_location_data_display_admin_order_meta($order)
    {


        $order_id = method_exists($order, 'get_id') ? $order->get_id() : $order->id;
        $service_location_data = get_post_meta($order_id, '_service_location_data', true);
        if (!empty($service_location_data)) {
            echo '<div style="height:200px; overflow-y:scroll;"><h2>Service Location Info</h2>';
            foreach ($service_location_data as $k => $v) {
                echo '<p style="color: #4CAF50;font-weight: 600;"><strong style="color: #333;font-weight: 600;">' . $k . ': </strong>' . $v . '</p>';
            }
            echo '</div>';
        }
    }

    function __getServicePoints($postcode = '', $country = 'NL', $chosen_method_id = 'activeants_dhl'): array
    {
        $postcode = str_replace(' ', '', $postcode);
        $data = [];
        if ($chosen_method_id == 'activeants_dhl') {
            $dhl_client = new DHLClient();
            $data = $dhl_client->GetLocations($country, $postcode, 1);
        } else {
            $postnl_settings = get_option('woocommerce_activeants_postnl_settings');
            $postnl_client = new PostNLClient([
                'key' => isset($postnl_settings['api_key']) ? $postnl_settings['api_key'] : '',
                'sandbox' => (isset($postnl_settings['sandbox_mode']) && $postnl_settings['sandbox_mode'] == 'yes') ? true : false
            ]);
            $data = $postnl_client->GetLocations($country, $postcode);
        }
        return $data;
    }
    
    function shipping_date() {
        // Here you get (as you already know) the used shipping method reference
        $chosen_methods = WC()->session->get('chosen_shipping_methods');
        $option_value = str_replace(':', '_', $chosen_methods[0]);
        $option_value = 'woocommerce_' . $option_value . '_settings';
        $free_shipping_settings = get_option($option_value);


        $allowed_days_count = $free_shipping_settings['allowed_delivery_days_count'];
        $excluded_delivery_days = explode(',', $free_shipping_settings['excluded_delivery_days']);
        $excluded_delivery_dates = explode(',', $free_shipping_settings['excluded_delivery_dates']);
        $date = date("Y-m-d");
        $store_date = array();
        $final_date = array();
        $array_days = array("Sunday" => "0", "Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6");

        for ($i = 1; $i < $allowed_days_count; $i++) {
            $next_date = date('Y-m-d', strtotime($date . ' +' . $i . 'day'));
            array_push($store_date, $next_date);
        }

        $result_date = array_diff($store_date, $excluded_delivery_dates);

        $get_date = array_intersect($array_days, $excluded_delivery_days);

        foreach ($get_date as $key => $values) {
            $get_days_date = date('Y-m-d', strtotime($key));
            array_push($final_date, $get_days_date);
        }

        return $final_result = array_diff($result_date, $final_date);
    }

}
