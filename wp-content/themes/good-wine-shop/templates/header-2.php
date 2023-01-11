<?php
/**
 * The template to display "Header 1"
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */

$header_css = $header_image = '';
$header_video = wp_is_mobile() ? '' : good_wine_shop_get_theme_option('header_video');
if (true || empty($header_video)) {
    $header_image = get_header_image();
    if (good_wine_shop_is_on(good_wine_shop_get_theme_option('header_image_override')) && apply_filters('good_wine_shop_filter_allow_override_header_image', true)) {
        if (is_category()) {
            if (($cat_img = good_wine_shop_get_category_image()) != '')
                $header_image = $cat_img;
        } else if ((is_singular() || good_wine_shop_storage_isset('blog_archive')) && has_post_thumbnail()) {
            $header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            if (is_array($header_image)) $header_image = $header_image[0];
        }
    }
}
$header_css = $header_image!='' ? ' style="background-image: url('.esc_url($header_image).')"' : '';

// Store header image for navi
set_query_var('good_wine_shop_header_image', $header_image || $header_video);

?><header class="top_panel top_panel_style_2<?php
echo !empty($header_image) || !empty($header_video) ? ' with_bg_image' : ' without_bg_image';
if ($header_video!='') echo ' with_bg_video';
if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
if (good_wine_shop_is_on(good_wine_shop_get_theme_option('header_fullheight'))) echo ' header_fullheight trx-stretch-height';
?> scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme'))
    ? good_wine_shop_get_theme_option('color_scheme')
    : good_wine_shop_get_theme_option('header_scheme')); ?>"
    <?php good_wine_shop_show_layout($header_css); ?>>

    <?php
    // Navigation panel
    $header_image = get_query_var('good_wine_shop_header_image');
    ?>
    <div class="top_panel_fixed_wrap"></div>
    <div class="top_panel_navi
			<?php if ($header_image!='') echo ' with_bg_image'; ?>
			scheme_<?php echo esc_attr(good_wine_shop_is_inherit(good_wine_shop_get_theme_option('menu_scheme'))
        ? (good_wine_shop_is_inherit(good_wine_shop_get_theme_option('header_scheme'))
            ? good_wine_shop_get_theme_option('color_scheme')
            : good_wine_shop_get_theme_option('header_scheme'))
        : good_wine_shop_get_theme_option('menu_scheme')); ?>">
        <div class="menu_main_wrap clearfix">
            <div class="content_wrap">
                <div class="top_panel_buttons">
                    <?php
                    set_query_var('good_wine_shop_search_in_header', true);
                    get_template_part( 'templates/search-field' );
                    ?>
                    <div class="menu_main_cart top_panel_icon">
                        <?php
                        $cart_items = WC()->cart->get_cart_contents_count();
                        $cart_summa = strip_tags(WC()->cart->get_cart_subtotal());
                        ?>
                        <a href="#" class="top_panel_cart_button" data-items="<?php echo esc_attr($cart_items); ?>" data-summa="<?php echo esc_attr($cart_summa); ?>">
                            <span class="contact_icon icon-shopping-cart"></span>
                            <span class="contact_cart_totals">
                                <span class="cart_items"><?php echo esc_html($cart_items); ?></span>
                            </span>
                        </a>
                        <div class="sidebar_cart">
                            <?php
                            do_action( 'good_wine_shop_before_sidebar' );
                            good_wine_shop_storage_set('current_sidebar', 'cart');
                            if ( !dynamic_sidebar( 'sidebar-cart' ) ) {
                                the_widget( 'WC_Widget_Cart', 'title=&hide_if_empty=1' );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                // Side menu
                if (good_wine_shop_get_theme_option('menu_style') == 'side') {
                    get_template_part( 'templates/header-navi-side' );
                }
                // Logo
                get_template_part( 'templates/header-logo' );
                // Mobile menu button
                ?><a class="menu_mobile_button"></a>
                <?php
                if (good_wine_shop_get_theme_option("menu_style") != 'side') {
                    // Main menu
                    ?><nav class="menu_main_nav_area menu_hover_<?php echo esc_attr(good_wine_shop_get_theme_option('menu_hover')); ?>"><?php
                    $good_wine_shop_menu_main = good_wine_shop_get_nav_menu('menu_main');
                    if (empty($good_wine_shop_menu_main)) $good_wine_shop_menu_main = good_wine_shop_get_nav_menu();
                    good_wine_shop_show_layout($good_wine_shop_menu_main);
                    // Store menu layout for the mobile menu
                    set_query_var('good_wine_shop_menu_main', $good_wine_shop_menu_main);

                    ?></nav><?php
                }
                ?>
            </div>

        </div>
    </div><!-- /.top_panel_navi -->

    <?php
    // Page title and breadcrumbs area
    get_template_part( 'templates/header-title');
    // Header widgets area
    get_template_part( 'templates/header-widgets' );
    ?>

</header>