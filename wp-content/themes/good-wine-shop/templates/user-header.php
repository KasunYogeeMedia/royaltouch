<?php
/**
 * The template to display "User Header"
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
?>

<div class="user-header">
    <div class="user-header_inner clearfix">
        <?php
            if (good_wine_shop_get_theme_option('header_greetings') != '') {
        ?>
            <div class="user-header_greetings">
                <?php echo esc_html(good_wine_shop_get_theme_option('header_greetings')); ?>
            </div>
        <?php
            }
        if ( good_wine_shop_exists_woocommerce() ) {
        ?>

        <div class="user-header_cart">
            <?php
            if ( function_exists('good_wine_shop_exists_woocommerce') && good_wine_shop_exists_woocommerce() &&
                !( is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART') )
            ) {
            $cart_items = WC()->cart->get_cart_contents_count();
            $cart_summa = strip_tags(WC()->cart->get_cart_subtotal());
            ?>
            <a href="#" class="top_panel_cart_button" data-items="<?php echo esc_attr($cart_items); ?>" data-summa="<?php echo esc_attr($cart_summa); ?>">
                <span class="contact_icon icon-shopping-cart"></span>
                <span class="contact_label contact_cart_label"><?php esc_html_e('Shopping Cart:', 'good-wine-shop'); ?></span>
                <span class="contact_cart_totals">
                    <span class="cart_items"><?php
                        echo esc_html($cart_items) . ' ' . ($cart_items == 1 ? esc_html__('item', 'good-wine-shop') : esc_html__('items', 'good-wine-shop'));
                        ?></span>
                    -
                    <span class="cart_summa"><?php good_wine_shop_show_layout($cart_summa); ?></span>
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
            <?php } ?>
        </div>
        <?php } ?>
        <div class="user-header_login">
            <?php do_action('trx_addons_action_login'); ?>
        </div>
        <?php
            if (good_wine_shop_get_theme_option('header_info') != '') {
        ?>
            <div class="user-header_contacts">
                <?php echo esc_html(good_wine_shop_get_theme_option('header_info')); ?>
            </div>
        <?php
        }
        ?>
    </div>
</div>