<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('good_wine_shop_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'good_wine_shop_woocommerce_theme_setup1', 1 );
	function good_wine_shop_woocommerce_theme_setup1() {
		add_filter( 'good_wine_shop_filter_list_sidebars', 'good_wine_shop_woocommerce_list_sidebars' );

        add_theme_support( 'woocommerce' );

        // Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
        add_theme_support( 'wc-product-gallery-slider' );

        // Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
        add_theme_support( 'wc-product-gallery-lightbox' );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('good_wine_shop_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'good_wine_shop_woocommerce_theme_setup3', 3 );
	function good_wine_shop_woocommerce_theme_setup3() {
		if (good_wine_shop_exists_woocommerce()) {
			good_wine_shop_storage_merge_array('options', '', array(
				// Section 'WooCommerce' - settings for show pages
				'shop' => array(
					"title" => esc_html__('Shop', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'good-wine-shop') ),
					"type" => "section"
					),
				'expand_content_shop' => array(
					"title" => esc_html__('Expand content', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'good-wine-shop') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
				'shop_mode' => array(
					"title" => esc_html__('Shop mode', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select style for the products list', 'good-wine-shop') ),
					"std" => 'thumbs',
					"options" => array(
						'thumbs'=> esc_html__('Thumbnails', 'good-wine-shop'),
						'list'	=> esc_html__('List', 'good-wine-shop'),
					),
					"type" => "select"
					),
				'header_widgets_shop' => array(
					"title" => esc_html__('Header widgets', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on the shop pages', 'good-wine-shop') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_widgets_shop' => array(
					"title" => esc_html__('Sidebar widgets', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select sidebar to show on the shop pages', 'good-wine-shop') ),
					"std" => 'woocommerce_widgets',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					),
				'sidebar_position_shop' => array(
					"title" => esc_html__('Sidebar position', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select position to show sidebar on the shop pages', 'good-wine-shop') ),
					"refresh" => false,
					"std" => 'left',
					"options" => good_wine_shop_get_list_sidebars_positions(),
					"type" => "select"
					),
				'widgets_above_page_shop' => array(
					"title" => esc_html__('Widgets above the page', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select widgets to show above page (content and sidebar)', 'good-wine-shop') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_above_content_shop' => array(
					"title" => esc_html__('Widgets above the content', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'good-wine-shop') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_content_shop' => array(
					"title" => esc_html__('Widgets below the content', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'good-wine-shop') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					),
				'widgets_below_page_shop' => array(
					"title" => esc_html__('Widgets below the page', 'good-wine-shop'),
					"desc" => wp_kses_data( __('Select widgets to show below the page (content and sidebar)', 'good-wine-shop') ),
					"std" => 'hide',
					"options" => array_merge(array('hide'=>esc_html__('- Select widgets -', 'good-wine-shop')), good_wine_shop_get_list_sidebars()),
					"type" => "select"
					)
				)
			);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('good_wine_shop_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'good_wine_shop_woocommerce_theme_setup9', 9 );
	function good_wine_shop_woocommerce_theme_setup9() {
		
		// One-click importer support
		if (good_wine_shop_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 										'good_wine_shop_woocommerce_frontend_scripts', 1100 );
			add_filter( 'good_wine_shop_filter_merge_styles',						'good_wine_shop_woocommerce_merge_styles' );
			add_filter( 'good_wine_shop_filter_get_css',							'good_wine_shop_woocommerce_get_css', 10, 3 );
			if (is_admin()) {
				add_filter( 'good_wine_shop_filter_importer_options',				'good_wine_shop_woocommerce_importer_set_options' );
				add_action( 'good_wine_shop_action_importer_after_import_posts',	'good_wine_shop_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'good_wine_shop_action_importer_params',				'good_wine_shop_woocommerce_importer_show_params', 10, 1 );
				add_action( 'good_wine_shop_action_importer_import',				'good_wine_shop_woocommerce_importer_import', 10, 2 );
				add_action( 'good_wine_shop_action_importer_import_fields',		'good_wine_shop_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'good_wine_shop_action_importer_export',				'good_wine_shop_woocommerce_importer_export', 10, 1 );
				add_action( 'good_wine_shop_action_importer_export_fields',		'good_wine_shop_woocommerce_importer_export_fields', 10, 1 );
                add_action( 'save_post',                                            'good_wine_shop_woocommerce_product_tags_to_meta');
			} else {
				add_filter( 'good_wine_shop_filter_detect_blog_mode',				'good_wine_shop_woocommerce_detect_blog_mode' );
				add_filter( 'good_wine_shop_filter_post_type_taxonomy',			'good_wine_shop_woocommerce_post_type_taxonomy', 10, 2 );
				add_filter( 'good_wine_shop_filter_get_blog_all_posts_link', 		'good_wine_shop_woocommerce_get_blog_all_posts_link');
				add_filter( 'good_wine_shop_filter_get_blog_title', 				'good_wine_shop_woocommerce_get_blog_title');
				add_filter( 'good_wine_shop_filter_need_page_title', 				'good_wine_shop_woocommerce_need_page_title');
				add_filter( 'good_wine_shop_filter_sidebar_present',				'good_wine_shop_woocommerce_sidebar_present' );
				add_filter( 'good_wine_shop_filter_get_post_categories', 			'good_wine_shop_woocommerce_get_post_categories');
				add_filter( 'good_wine_shop_filter_allow_override_header_image',	'good_wine_shop_woocommerce_allow_override_header_image' );
			}
		}
		if (is_admin()) {
			add_filter( 'good_wine_shop_filter_importer_required_plugins',			'good_wine_shop_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'good_wine_shop_filter_tgmpa_required_plugins',				'good_wine_shop_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (good_wine_shop_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);

			// Remove add_to_cart button
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_add_to_cart', 10);
			
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
			
			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'good_wine_shop_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'good_wine_shop_woocommerce_wrapper_end', 10);

			// Close header section
			add_action( 'woocommerce_archive_description',				'good_wine_shop_woocommerce_archive_description', 15 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'good_wine_shop_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'good_wine_shop_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','good_wine_shop_woocommerce_add_to_cart_text' );

			// Set columns number for the products loop
			add_filter(    'product_cat_class',							'good_wine_shop_woocommerce_loop_shop_columns_class', 10, 3 );
			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'good_wine_shop_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'good_wine_shop_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'good_wine_shop_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'good_wine_shop_woocommerce_title_wrapper_start', 20 );

			// Add tags before title
			add_action(    'woocommerce_before_shop_loop_item_title',	'good_wine_shop_woocommerce_title_tags', 30 );

			// Wrap product title into link
			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'good_wine_shop_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'good_wine_shop_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'good_wine_shop_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'good_wine_shop_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'good_wine_shop_woocommerce_show_product_id', 10);

			// Add awards into single image
			add_filter(    'woocommerce_single_product_image_html',		'good_wine_shop_woocommerce_single_product_image_html', 10, 2 );
			
			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'good_wine_shop_woocommerce_product_thumbnails_columns' );

			// Set columns number for the related products
			add_filter(    'woocommerce_output_related_products_args',	'good_wine_shop_woocommerce_output_related_products_args' );

			// Decorate price
			add_filter(    'woocommerce_get_price_html',				'good_wine_shop_woocommerce_get_price_html', 10, 2 );

            // Wrap category title into link
            remove_action(  'woocommerce_shop_loop_subcategory_title',   'woocommerce_template_loop_category_title', 10 );
            add_action(     'woocommerce_shop_loop_subcategory_title',          'good_wine_shop_woocommerce_shop_loop_subcategory_title', 9, 1);



            // Detect current shop mode
			if (!is_admin()) {
				$shop_mode = good_wine_shop_get_value_gpc('good_wine_shop_shop_mode');
				if (empty($shop_mode) && good_wine_shop_check_theme_option('shop_mode'))
					$shop_mode = good_wine_shop_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				good_wine_shop_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}

// Wrap category title into link
if ( !function_exists( 'good_wine_shop_woocommerce_shop_loop_subcategory_title' ) ) {
    //Handler of the add_filter( 'woocommerce_shop_loop_subcategory_title', 'good_wine_shop_woocommerce_shop_loop_subcategory_title' );
    function good_wine_shop_woocommerce_shop_loop_subcategory_title($cat) {
        $cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
        ?>
            <h2 class="woocommerce-loop-category__title">
        <?php
            echo $cat->name;
            if ( $cat->count > 0 ) {
            echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $cat->count ) . ')</mark>', $cat ); // WPCS: XSS ok.
            }
        ?>
        </h2><?php
    }
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'good_wine_shop_exists_woocommerce' ) ) {
	function good_wine_shop_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'good_wine_shop_is_woocommerce_page' ) ) {
	function good_wine_shop_is_woocommerce_page() {
		$rez = false;
		if (good_wine_shop_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'good_wine_shop_woocommerce_detect_blog_mode' ) ) {
	
	function good_wine_shop_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'good_wine_shop_woocommerce_post_type_taxonomy' ) ) {
	
	function good_wine_shop_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return current page title
if ( !function_exists( 'good_wine_shop_woocommerce_get_blog_title' ) ) {
	
	function good_wine_shop_woocommerce_get_blog_title($title='') {
		if (is_woocommerce() && is_shop()) $title = esc_html__('Shop', 'good-wine-shop');
		return $title;
	}
}

// Return link to main shop page for the breadcrumbs
if ( !function_exists( 'good_wine_shop_woocommerce_get_blog_all_posts_link' ) ) {
	
	function good_wine_shop_woocommerce_get_blog_all_posts_link($link='') {
		if (empty($link) && good_wine_shop_is_woocommerce_page() && !is_shop())
			$link = '<a href="'.esc_url(good_wine_shop_woocommerce_get_shop_page_link()).'">'.esc_html__('Shop', 'good-wine-shop').'</a>';
		return $link;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'good_wine_shop_woocommerce_need_page_title' ) ) {
	
	function good_wine_shop_woocommerce_need_page_title($need=false) {
		if (!$need)
			$need = is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_product();
		return $need;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'good_wine_shop_woocommerce_allow_override_header_image' ) ) {
	
	function good_wine_shop_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'good_wine_shop_woocommerce_get_shop_page_id' ) ) {
	function good_wine_shop_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'good_wine_shop_woocommerce_get_shop_page_link' ) ) {
	function good_wine_shop_woocommerce_get_shop_page_link() {
		$url = '';
		$id = good_wine_shop_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'good_wine_shop_woocommerce_get_post_categories' ) ) {
	
	function good_wine_shop_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = good_wine_shop_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add Woocommerce 'Product Tag' to meta fields
if ( !function_exists( 'good_wine_shop_woocommerce_product_tags_to_meta' ) ) {
    
    function good_wine_shop_woocommerce_product_tags_to_meta() {
        if (get_post_type()=='product') {
            $args = array( 'hide_empty' => 0 );
            $terms = get_the_terms( get_the_ID(), 'product_tag' );
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $term_list = '';
                foreach ( $terms as $term ) {
                    $term_list .= ' <span class="product_tag_link">' . $term->name . '</span>';

                }
                $term_list = trim( $term_list );
            }
            update_post_meta( get_the_ID(), '_product_tag', $term_list );
        }
    }
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'good_wine_shop_woocommerce_frontend_scripts' ) ) {
	
	function good_wine_shop_woocommerce_frontend_scripts() {
			if (good_wine_shop_is_on(good_wine_shop_get_theme_option('debug_mode')) && file_exists(good_wine_shop_get_file_dir('css/plugin.woocommerce.css')))
                wp_enqueue_style( 'good-wine-shop-plugin-woocommerce',  good_wine_shop_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'good_wine_shop_woocommerce_merge_styles' ) ) {
	
	function good_wine_shop_woocommerce_merge_styles($css) {
		return $css . good_wine_shop_fgc(good_wine_shop_get_file_dir('css/plugin.woocommerce.css'));
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'good_wine_shop_woocommerce_tgmpa_required_plugins' ) ) {
	
	function good_wine_shop_woocommerce_tgmpa_required_plugins($list=array()) {
		if (in_array('woocommerce', good_wine_shop_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> esc_html__('WooCommerce', 'good-wine-shop'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'good_wine_shop_woocommerce_importer_required_plugins' ) ) {
	
	function good_wine_shop_woocommerce_importer_required_plugins($not_installed='', $list='') {
		if (strpos($list, 'woocommerce')!==false && !good_wine_shop_exists_woocommerce() )
			$not_installed .= '<br>' . esc_html__('WooCommerce', 'good-wine-shop');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'good_wine_shop_woocommerce_importer_set_options' ) ) {
	
	function good_wine_shop_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', good_wine_shop_storage_get('required_plugins')) && good_wine_shop_exists_woocommerce() ) {
			$options['additional_options'][]	= 'shop_%';					// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'woocommerce_%';
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('posts', 'woocommerce', $v['file_with_posts']);
				}
			}
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'good_wine_shop_woocommerce_importer_after_import_posts' ) ) {
	
	function good_wine_shop_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'good_wine_shop_woocommerce_importer_show_params' ) ) {
	
	function good_wine_shop_woocommerce_importer_show_params($importer) {
		?>
		<input type="checkbox" <?php echo in_array('woocommerce', good_wine_shop_storage_get('required_plugins')) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_woocommerce" id="import_woocommerce" /> <label for="import_woocommerce"><?php esc_html_e('Import WooCommerce', 'good-wine-shop'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'good_wine_shop_woocommerce_importer_import' ) ) {
	
	function good_wine_shop_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['result'] = $importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'good-wine-shop'));
		}
	}
}

// Display import progress
if ( !function_exists( 'good_wine_shop_woocommerce_importer_import_fields' ) ) {
	
	function good_wine_shop_woocommerce_importer_import_fields($importer) {
		?>
		<tr class="import_woocommerce">
			<td class="import_progress_item"><?php esc_html_e('WooCommerce meta', 'good-wine-shop'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'good_wine_shop_woocommerce_importer_export' ) ) {
	
	function good_wine_shop_woocommerce_importer_export($importer) {
		good_wine_shop_storage_set('export_woocommerce', serialize( array(
			"woocommerce_attribute_taxomonies"				=> $importer->export_dump("woocommerce_attribute_taxomonies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'good_wine_shop_woocommerce_importer_export_fields' ) ) {
	
	function good_wine_shop_woocommerce_importer_export_fields($importer) {
		?>
		<tr>
			<th align="left"><?php esc_html_e('WooCommerce', 'good-wine-shop'); ?></th>
			<td><?php good_wine_shop_fpc(good_wine_shop_get_file_dir('importer/export/woocommerce.txt'), good_wine_shop_storage_get('export_woocommerce')); ?>
				<a download="woocommerce.txt" href="<?php echo esc_url(good_wine_shop_get_file_url('importer/export/woocommerce.txt')); ?>"><?php esc_html_e('Download', 'good-wine-shop'); ?></a>
			</td>
		</tr>
		<?php
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'good_wine_shop_woocommerce_list_sidebars' ) ) {
	
	function good_wine_shop_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = esc_html__('WooCommerce Widgets', 'good-wine-shop');
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Before main content
if ( !function_exists( 'good_wine_shop_woocommerce_wrapper_start' ) ) {
	
	function good_wine_shop_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !good_wine_shop_storage_empty('shop_mode') ? good_wine_shop_storage_get('shop_mode') : 'thumbs'; ?>">
				<div class="list_products_header">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'good_wine_shop_woocommerce_wrapper_end' ) ) {
	
	function good_wine_shop_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'good_wine_shop_woocommerce_archive_description' ) ) {
	
	function good_wine_shop_woocommerce_archive_description() {
		?>
		</div><!-- /.list_products_header -->
		<?php
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'good_wine_shop_woocommerce_loop_shop_columns_class' ) ) {
	
	
    function good_wine_shop_woocommerce_loop_shop_columns_class($class, $class2='', $cat='') {
        if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
            $cols = function_exists('wc_get_default_products_per_row') ? wc_get_default_products_per_row() : 2;
            $class[] = 'column-1_' . $cols;
        }
        return $class;
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'good_wine_shop_woocommerce_item_wrapper_start' ) ) {
	
	
	function good_wine_shop_woocommerce_item_wrapper_start($cat='') {
		good_wine_shop_storage_set('in_product_item', true);
		?>
		<div class="post_item post_layout_<?php echo esc_attr(good_wine_shop_storage_get('shop_mode')); ?>">
			<div class="post_featured hover_shop">
        <a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'good_wine_shop_woocommerce_open_item_wrapper' ) ) {
	
	
	function good_wine_shop_woocommerce_title_wrapper_start($cat='') {
				?>
    </a>
    <?php
    good_wine_shop_hovers_add_icons('shop', array('cat'=>$cat));
    // Awards
    good_wine_shop_show_layout(good_wine_shop_woocommerce_get_awards_tag());
    ?>
    </div><!-- /.post_featured -->
    <div class="post_data">
    <div class="post_header entry-header">
    <?php
}
}

// Add awards into single image
if ( !function_exists( 'good_wine_shop_woocommerce_single_product_image_html' ) ) {
	
	function good_wine_shop_woocommerce_single_product_image_html($html='', $id=0) {
		return str_replace('</a>', good_wine_shop_woocommerce_get_awards_tag().'</a>', $html);
	}
}

// Add awards
if ( !function_exists( 'good_wine_shop_woocommerce_get_awards_tag' ) ) {
	function good_wine_shop_woocommerce_get_awards_tag() {
		global $product;
		$product = wc_get_product();
		$attributes = $product->get_attributes();
		$output = '';
		if (is_array($attributes)) {
			foreach ( $attributes as $attribute ) {
				if (strtolower($attribute['name']) == strtolower(esc_html__('Awards', 'good-wine-shop'))) {
					$output = '<div class="product_awards"><span>' . esc_html($attribute['value']) . '</span></div>';
				}
			}
		}
		return $output;
	}
}


// Display product's tags before the title
if ( !function_exists( 'good_wine_shop_woocommerce_title_tags' ) ) {
	
	function good_wine_shop_woocommerce_title_tags() {
		global $product;
        good_wine_shop_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title into link
if ( !function_exists( 'good_wine_shop_woocommerce_the_title' ) ) {
	
	function good_wine_shop_woocommerce_the_title($title) {
		if (good_wine_shop_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'good_wine_shop_woocommerce_title_wrapper_end' ) ) {
	
	function good_wine_shop_woocommerce_title_wrapper_end() {
		?>
			</div><!-- /.post_header -->
		<?php
		if (good_wine_shop_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			?>
			<div class="post_content entry-content"><?php good_wine_shop_show_layout($excerpt); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'good_wine_shop_woocommerce_title_wrapper_end2' ) ) {
	
	function good_wine_shop_woocommerce_title_wrapper_end2($category) {
		?>
			</div><!-- /.post_header -->
		<?php
		if (good_wine_shop_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
			?>
			<div class="post_content entry-content"><?php good_wine_shop_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'good_wine_shop_woocommerce_close_item_wrapper' ) ) {
	
	
	function good_wine_shop_woocommerce_item_wrapper_end($cat='') {
		?>
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		good_wine_shop_storage_set('in_product_item', false);
	}
}


// Change text on 'Add to cart' button
if ( ! function_exists( 'good_wine_shop_woocommerce_add_to_cart_text' ) ) {
    function good_wine_shop_woocommerce_add_to_cart_text( $text = '' ) {
        global $product;
        return is_object( $product ) && $product->is_in_stock()
        && 'grouped' !== $product->get_type()
        && ( 'external' !== $product->get_type() || $product->get_button_text() == '' )
            ? esc_html__( 'Buy now', 'good-wine-shop' )
            : $text;
    }
}

// Decorate price
if ( !function_exists( 'good_wine_shop_woocommerce_get_price_html' ) ) {
	
	function good_wine_shop_woocommerce_get_price_html($price='') {
		if (!empty($price)) {
			$sep = get_option('woocommerce_price_decimal_sep');
			if (empty($sep)) $sep = '.';
			$parts = explode($sep, $price);
			if (count($parts) < 2) $parts[] = '00';
			if (($pos = strpos($parts[1], '<')) !== false)
				$parts[1] = substr($parts[1], 0, $pos) . '</span>' . substr($parts[1], $pos);
			else
				$parts[1] .= '</span>';
			$price = join('<span class="decimals">', $parts);
		}
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Hide sidebar on the single products and pages
if ( !function_exists( 'good_wine_shop_woocommerce_sidebar_present' ) ) {
	
	function good_wine_shop_woocommerce_sidebar_present($present) {
		return is_product() || is_cart() || is_checkout() || is_account_page() ? false : $present;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'good_wine_shop_woocommerce_show_product_id' ) ) {
	
	function good_wine_shop_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'good-wine-shop');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'good-wine-shop') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'good_wine_shop_woocommerce_product_thumbnails_columns' ) ) {
	
	function good_wine_shop_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set columns number for the related products
if ( !function_exists( 'good_wine_shop_woocommerce_output_related_products_args' ) ) {
	
	function good_wine_shop_woocommerce_output_related_products_args($args) {
		$ccc_add = in_array(good_wine_shop_get_theme_option('body_style'), array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc = good_wine_shop_sidebar_present() ? 3+$ccc_add : 4+$ccc_add;
		$args['posts_per_page'] = $ccc;
		$args['columns'] = $ccc;
		return $args;
	}
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'good_wine_shop_woocommerce_get_product_search_form' ) ) {
	
	function good_wine_shop_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'good-wine-shop') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'good-wine-shop') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}



// Add WooCommerce specific styles into color scheme
//------------------------------------------------------------------------

// Add styles into CSS
if ( !function_exists( 'good_wine_shop_woocommerce_get_css' ) ) {
	
	function good_wine_shop_woocommerce_get_css($css, $colors, $fonts) {
		if (isset($css['colors'])) {
			$css['fonts'] .= <<<CSS
#btn-buy,
.woocommerce ul.products li.product .post_header, .woocommerce-page ul.products li.product .post_header,
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price,
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a,
.woocommerce ul.products li.product .button, .woocommerce div.product form.cart .button,
.woocommerce .woocommerce-message .button,
.woocommerce #review_form #respond p.form-submit input[type="submit"], .woocommerce-page #review_form #respond p.form-submit input[type="submit"],
.woocommerce .button, .woocommerce-page .button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button
.woocommerce #respond input#submit,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"],
.woocommerce .shop_table th,
.woocommerce span.onsale,
.woocommerce nav.woocommerce-pagination ul li a,
.woocommerce nav.woocommerce-pagination ul li span.current,
.woocommerce div.product p.price,
.woocommerce div.product .summary .stock,
.woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-content .woocommerce-Address-title a {
	font-family: {$fonts['h5']['family']};
}
.woocommerce ul.products li.product .post_header .post_tags,
.woocommerce .shop_mode_list ul.products li.product .price, .woocommerce-page .shop_mode_list ul.products li.product .price {
	font-family: {$fonts['h6']['family']};
}


.shop_slider_content, .tp-caption.shop_slider_content,
.shop_slider_add_to_cart_button, .tp-caption.shop_slider_add_to_cart_button {
	font-family: {$fonts['h5']['family']};
}
.shop_slider_tags, .tp-caption.shop_slider_tags,
.shop_slider_title, .tp-caption.shop_slider_title,
.shop_slider_price, .tp-caption.shop_slider_price {
	font-family: {$fonts['h6']['family']};
}

CSS;
		}

		if (isset($css['colors'])) {
			$css['colors'] .= <<<CSS

/* Page header */
.woocommerce .woocommerce-breadcrumb {
	color: {$colors['text']};
}
.woocommerce .woocommerce-breadcrumb a {
	color: {$colors['text_link']};
}
.woocommerce .woocommerce-breadcrumb a:hover {
	color: {$colors['text_hover']};
}
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
	background-color: {$colors['text_link']};
}

/* List and Single product */
.woocommerce .woocommerce-ordering,
.woocommerce .woocommerce-ordering select {
	border-color: {$colors['bd_color']};
}
.woocommerce span.onsale {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}
.woocommerce ul.products li.product .post_item,
.woocommerce .shop_mode_list ul.products li.product + li.product .post_item, .woocommerce-page .shop_mode_list ul.products li.product + li.product .post_item {
	border-color: {$colors['alter_bd_color_alpha_05']};
}
.woocommerce ul.products li.product .post_item:hover {
	border-color: {$colors['text_link']};
}
.woocommerce .shop_mode_list ul.products li.product .post_featured:hover, .woocommerce-page .shop_mode_list ul.products li.product .post_featured:hover {
	border-color: {$colors['alter_bd_color']};
}
.woocommerce .shop_mode_list ul.products li.product .post_featured, .woocommerce-page .shop_mode_list ul.products li.product .post_featured {
	border-color: {$colors['alter_bd_color']};
}
.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3,
.woocommerce ul.products li.product h2.woocommerce-loop-product__title, .woocommerce-page ul.products li.product h2.woocommerce-loop-product__title,
.woocommerce ul.products li.product h2.woocommerce-loop-category__title, .woocommerce-page ul.products li.product h2.woocommerce-loop-category__title {
    color: {$colors['text_link']};
}

.product_awards {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_link']};
}

.woocommerce ul.products li.product .post_header .post_tags a,
.eg-good-wine-product-element-26-a a {
	color: {$colors['text_dark']};
}
.woocommerce ul.products li.product .post_header .post_tags a:hover,
.eg-good-wine-product-element-26-a a:hover {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .post_header a {
	color: {$colors['text_link']};
}
.woocommerce ul.products li.product .post_header a:hover {
	color: {$colors['text_hover']};
}
.woocommerce ul.products li.product .price, .woocommerce-page ul.products li.product .price {
	color: {$colors['text_dark']};
}

/* Products on the homepage */
.home_products .woocommerce ul.products li.product .post_item {
	background-color: {$colors['alter_bg_color']};
}

.woocommerce span.amount, .woocommerce-page span.amount {
	color: {$colors['text_dark']};
}
.woocommerce table.shop_table td span.amount {
	color: {$colors['text_dark']};
}
aside.woocommerce del,
.woocommerce del, .woocommerce del > span.amount, 
.woocommerce-page del, .woocommerce-page del > span.amount {
	color: {$colors['text_light']} !important;
}
.woocommerce .price del:before {
	background-color: {$colors['text_light']};
}
.woocommerce div.product form.cart div.quantity span, .woocommerce-page div.product form.cart div.quantity span,
.woocommerce table.cart div.quantity span, .woocommerce-page table.cart div.quantity span {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}
.woocommerce div.product form.cart div.quantity span:hover, .woocommerce-page div.product form.cart div.quantity span:hover,
.woocommerce table.cart div.quantity span:hover, .woocommerce-page table.cart div.quantity span:hover {
	background-color: {$colors['text_link']};
}
.woocommerce div.product form.cart div.quantity input[type="number"], .woocommerce-page div.product form.cart div.quantity input[type="number"],
.woocommerce table.cart div.quantity input[type="number"], .woocommerce-page table.cart div.quantity input[type="number"] {
	border-color: {$colors['text_dark']};
}

.woocommerce div.product .product_meta span > a,
.woocommerce div.product .product_meta span > span {
	color: {$colors['text_dark']};
}
.woocommerce div.product .product_meta a:hover {
	color: {$colors['text_link']};
}

.woocommerce div.product div.images img {
	border-color: {$colors['bd_color']};
}
.woocommerce div.product div.images a:hover img {
	border-color: {$colors['text_link']};
}

.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a {
	border-color: {$colors['text_dark']};
}
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li.active a {
	color: {$colors['inverse_text']};
	background-color: {$colors['text_dark']};
}

.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a.sc_button_hover_slide_left {		background: linear-gradient(to right,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li.active a.sc_button_hover_slide_left {		background: linear-gradient(to right,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li.active a.sc_button_hover_slide_right {		background: linear-gradient(to left,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li.active a.sc_button_hover_slide_top {		background: linear-gradient(to bottom,	{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li.active a.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a:hover {
	color: {$colors['inverse_text']};
}
.single-product div.product .trx-stretch-width .woocommerce-tabs .wc-tabs li a:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_dark']};
}


/* Rating */
.star-rating span,
.star-rating:before {
	color: {$colors['text_link']};
}
#review_form #respond p.form-submit input[type="submit"] {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}
#review_form #respond p.form-submit input[type="submit"]:not([class*="sc_button_hover_"]),
#review_form #respond p.form-submit input[type="submit"].sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
#review_form #respond p.form-submit input[type="submit"].sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
#review_form #respond p.form-submit input[type="submit"].sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
#review_form #respond p.form-submit input[type="submit"].sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

#review_form #respond p.form-submit input[type="submit"]:hover,
#review_form #respond p.form-submit input[type="submit"]:focus {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
    background-position: left bottom;
}
#review_form #respond p.form-submit input[type="submit"]:not([class*="sc_button_hover_"]):hover,
#review_form #respond p.form-submit input[type="submit"]:not([class*="sc_button_hover_"]):focus {
	background-color: {$colors['text_dark']};
}

/* Buttons */
.good_wine_shop_shop_mode_buttons a {
	color: {$colors['text_dark']};
}
.good_wine_shop_shop_mode_buttons a:hover {
	color: {$colors['text_link']};
}
.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce .button, .woocommerce-page .button,
.woocommerce input[type="button"], .woocommerce-page input[type="button"],
.woocommerce input[type="submit"], .woocommerce-page input[type="submit"],
.woocommerce input.button {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}
.woocommerce #respond input#submit:not([class*="sc_button_hover_"]),
.woocommerce a.button:not([class*="sc_button_hover_"]),
.woocommerce button.button:not([class*="sc_button_hover_"]),
.woocommerce input.button:not([class*="sc_button_hover_"]),
.woocommerce #respond input#submit.sc_button_hover_slide_left,
.woocommerce a.button.sc_button_hover_slide_left,
.woocommerce button.button.sc_button_hover_slide_left,
.woocommerce input.button.sc_button_hover_slide_left {
    background: linear-gradient(to right, {$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0);
}
.woocommerce #respond input#submit.sc_button_hover_slide_right,
.woocommerce a.button.sc_button_hover_slide_right,
.woocommerce button.button.sc_button_hover_slide_right,
.woocommerce input.button.sc_button_hover_slide_right {
    background: linear-gradient(to left, 	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0);
}
.woocommerce #respond input#submit.sc_button_hover_slide_top,
.woocommerce a.button.sc_button_hover_slide_top,
.woocommerce button.button.sc_button_hover_slide_top,
.woocommerce input.button.sc_button_hover_slide_top {	background: linear-gradient(to bottom, 	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.woocommerce #respond input#submit.sc_button_hover_slide_bottom,
.woocommerce a.button.sc_button_hover_slide_bottom,
.woocommerce button.button.sc_button_hover_slide_bottom,
.woocommerce input.button.sc_button_hover_slide_bottom {background: linear-gradient(to top, 	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.woocommerce #respond input#submit:hover,
.woocommerce a.button:hover,
.woocommerce button.button:hover,
.woocommerce input.button:hover {
	color: {$colors['inverse_text']} !important;
	border-color: {$colors['text_link']};
    background-position: left bottom;
}
.woocommerce #respond input#submit:not([class*="sc_button_hover_"]):hover,
.woocommerce a.button:not([class*="sc_button_hover_"]):hover,
.woocommerce button.button:not([class*="sc_button_hover_"]):hover,
.woocommerce input.button:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_dark']};
}

.woocommerce nav.woocommerce-pagination ul li a {
    color: {$colors['text_light']};
    border-color: {$colors['text_light']};
}
.woocommerce nav.woocommerce-pagination ul li a:hover {
    color: {$colors['text_link']};
    border-color: {$colors['text_link']};
}
.woocommerce nav.woocommerce-pagination ul li span.current,
 .woocommerce nav.woocommerce-pagination ul li span.current:hover {
    color: {$colors['text_dark']};
    border-color: {$colors['text_dark']};
}

.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt {
	color: {$colors['text_dark']};
	border-color: {$colors['text_dark']};
}
.woocommerce #respond input#submit.alt:not([class*="sc_button_hover_"]),
.woocommerce a.button.alt:not([class*="sc_button_hover_"]),
.woocommerce button.button.alt:not([class*="sc_button_hover_"]),
.woocommerce input.button.alt:not([class*="sc_button_hover_"]),
.woocommerce #respond input#submit.alt.sc_button_hover_slide_left,
.woocommerce a.button.alt.sc_button_hover_slide_left,
.woocommerce button.button.alt.sc_button_hover_slide_left,
.woocommerce input.button.alt.sc_button_hover_slide_left {	background: linear-gradient(to right,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce #respond input#submit.alt.sc_button_hover_slide_right,
.woocommerce a.button.alt.sc_button_hover_slide_right,
.woocommerce button.button.alt.sc_button_hover_slide_right,
.woocommerce input.button.alt.sc_button_hover_slide_right {	background: linear-gradient(to left,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce #respond input#submit.alt.sc_button_hover_slide_top,
.woocommerce a.button.alt.sc_button_hover_slide_top,
.woocommerce button.button.alt.sc_button_hover_slide_top,
.woocommerce input.button.alt.sc_button_hover_slide_top {	background: linear-gradient(to bottom,	{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.woocommerce #respond input#submit.alt.sc_button_hover_slide_bottom,
.woocommerce a.button.alt.sc_button_hover_slide_bottom,
.woocommerce button.button.alt.sc_button_hover_slide_bottom,
.woocommerce input.button.alt.sc_button_hover_slide_bottom {	background: linear-gradient(to top,		{$colors['text_link']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
	color: {$colors['inverse_text']};
	border-color: {$colors['text_link']};
    background-position: left bottom;
}
.woocommerce #respond input#submit.alt:not([class*="sc_button_hover_"]):hover,
.woocommerce a.button.alt:not([class*="sc_button_hover_"]):hover,
.woocommerce button.button.alt:not([class*="sc_button_hover_"]):hover,
.woocommerce input.button.alt:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['text_hover']};
}

/* Messages */
.woocommerce .woocommerce-message,
.woocommerce .woocommerce-info {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['alter_dark']};
}
.woocommerce .woocommerce-message:before,
.woocommerce .woocommerce-info:before {
	color: {$colors['alter_dark']};
}
#btn-buy,
.woocommerce .woocommerce-message .button,
.woocommerce .woocommerce-info .button {
	color: {$colors['alter_dark']};
	border-color: {$colors['alter_dark']};
}
.woocommerce .woocommerce-message .button:not([class*="sc_button_hover_"]),
.woocommerce .woocommerce-info .button:not([class*="sc_button_hover_"]),
#btn-buy.sc_button_hover_slide_left,
.woocommerce .woocommerce-message .button.sc_button_hover_slide_left,
.woocommerce .woocommerce-info .button.sc_button_hover_slide_left {				background: linear-gradient(to right,	{$colors['alter_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-message .button.sc_button_hover_slide_right,
.woocommerce .woocommerce-info .button.sc_button_hover_slide_right {			background: linear-gradient(to left,	{$colors['alter_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-message .button.sc_button_hover_slide_top,
.woocommerce .woocommerce-info .button.sc_button_hover_slide_top {				background: linear-gradient(to bottom,	{$colors['alter_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-message .button.sc_button_hover_slide_bottom,
.woocommerce .woocommerce-info .button.sc_button_hover_slide_bottom {			background: linear-gradient(to top,		{$colors['alter_dark']} 50%, rgba(0,0,0,0) 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

#btn-buy:hover,
.woocommerce .woocommerce-message .button:hover,
.woocommerce .woocommerce-info .button:hover {
	color: {$colors['inverse_text']};
    background-position: left bottom;
}
.woocommerce .woocommerce-message .button:not([class*="sc_button_hover_"]):hover,
.woocommerce .woocommerce-info .button:not([class*="sc_button_hover_"]):hover {
	background-color: {$colors['alter_dark']};
}
.woocommerce .woocommerce-error {
	background-color: {$colors['alter_bg_color']};
	border-top-color: {$colors['alter_link']};
}
.woocommerce .woocommerce-error:before {
	color: {$colors['alter_link']};
}
.woocommerce .woocommerce-error .button {
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_link']};
}
.woocommerce .woocommerce-error .button:not([class*="sc_button_hover_"]),
.woocommerce .woocommerce-error .button.sc_button_hover_slide_left {			background: linear-gradient(to right,	{$colors['alter_dark']} 50%, {$colors['alter_link']} 50%) no-repeat scroll right bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-error .button.sc_button_hover_slide_right {			background: linear-gradient(to left,	{$colors['alter_dark']} 50%, {$colors['alter_link']} 50%) no-repeat scroll left bottom / 210% 100% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-error .button.sc_button_hover_slide_top {				background: linear-gradient(to bottom,	{$colors['alter_dark']} 50%, {$colors['alter_link']} 50%) no-repeat scroll right bottom / 100% 210% rgba(0, 0, 0, 0); }
.woocommerce .woocommerce-error .button.sc_button_hover_slide_bottom {			background: linear-gradient(to top,		{$colors['alter_dark']} 50%, {$colors['alter_link']} 50%) no-repeat scroll right top / 100% 210% rgba(0, 0, 0, 0); }

.woocommerce .woocommerce-error .button:hover {
	color: {$colors['inverse_text']};
	background-color: {$colors['alter_dark']};
    background-position: left bottom;
}

/* Cart */
.woocommerce table.shop_table td,
.woocommerce table.shop_table th {
	border-color: {$colors['text_link']} !important;
}
.woocommerce table.shop_table tfoot th, .woocommerce-page table.shop_table tfoot th {
	color: {$colors['text_dark']};
}
.woocommerce .quantity input.qty, .woocommerce #content .quantity input.qty, .woocommerce-page .quantity input.qty, .woocommerce-page #content .quantity input.qty {
	color: {$colors['input_dark']};
}
.woocommerce .cart-collaterals .cart_totals table select, .woocommerce-page .cart-collaterals .cart_totals table select {
	color: {$colors['input_light']};
	background-color: {$colors['input_bg_color']};
}
.woocommerce .cart-collaterals .cart_totals table select:focus, .woocommerce-page .cart-collaterals .cart_totals table select:focus {
	color: {$colors['input_text']};
	background-color: {$colors['input_bg_hover']};
}
.woocommerce .cart-collaterals .shipping_calculator .shipping-calculator-button:after, .woocommerce-page .cart-collaterals .shipping_calculator .shipping-calculator-button:after {
	color: {$colors['text_dark']};
}
.woocommerce table.shop_table .cart-subtotal .amount, .woocommerce-page table.shop_table .cart-subtotal .amount,
.woocommerce table.shop_table .shipping td, .woocommerce-page table.shop_table .shipping td {
	color: {$colors['text_dark']};
}
.woocommerce table.cart td+td a, .woocommerce #content table.cart td+td a, .woocommerce-page table.cart td+td a, .woocommerce-page #content table.cart td+td a,
.woocommerce table.cart td+td span, .woocommerce #content table.cart td+td span, .woocommerce-page table.cart td+td span, .woocommerce-page #content table.cart td+td span {
	color: {$colors['text_dark']};
}
.woocommerce table.cart td+td a:hover, .woocommerce #content table.cart td+td a:hover, .woocommerce-page table.cart td+td a:hover, .woocommerce-page #content table.cart td+td a:hover {
	color: {$colors['text_link']};
}

/* Checkout */
.woocommerce-checkout #payment {
	background-color:{$colors['alter_bg_color']};
}
.woocommerce .order_details li strong, .woocommerce-page .order_details li strong {
	color: {$colors['text_dark']};
}

/* My Account */
.woocommerce-account .woocommerce-MyAccount-navigation,
.woocommerce-MyAccount-navigation li+li {
	border-color: {$colors['bd_color']};
}
.woocommerce-MyAccount-navigation li.is-active a {
	color: {$colors['text_dark']};
}

/* Widgets */
.woocommerce.widget_product_search form:after {
	color: {$colors['input_light']};
}
.woocommerce.widget_product_search form:hover:after {
	color: {$colors['input_dark']};
}
.woocommerce.widget_product_search .search_button {
	background-color: {$colors['text_link']};
	color: {$colors['inverse_text']};
}
.woocommerce.widget_shopping_cart .total, .woocommerce .widget_shopping_cart .total, .woocommerce-page.widget_shopping_cart .total, .woocommerce-page .widget_shopping_cart .total {
	color: {$colors['text_dark']};
	border-color: {$colors['bd_color']};
}
.woocommerce .widget_layered_nav ul li.chosen a, .woocommerce-page .widget_layered_nav ul li.chosen a {
	color: {$colors['text_dark']};
}
.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content { 
	background: {$colors['text_light']};
}
.woocommerce .widget_price_filter .price_label span {
	color: {$colors['text_dark']};
}


/* Shop slider */
.shop_slider_price, .tp-caption.shop_slider_price {
	color: {$colors['text_link']};
}
.shop_slider_content, .tp-caption.shop_slider_content {
	color: {$colors['text_dark']};
}
.shop_slider_add_to_cart:before, .tp-caption.shop_slider_add_to_cart:before {
	border-color: {$colors['text_dark']};
}
.tp-bullets.custom .tp-bullet {
	border-color: {$colors['text_light']};
}
.tp-bullets.custom .tp-bullet.selected {
	border-color: {$colors['text_dark']};
	background-color: {$colors['text_dark']};
}

CSS;
		}
		
		return $css;
	}
}
?>