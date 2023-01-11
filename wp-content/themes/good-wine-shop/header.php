<?php
/**
 * The Header: Logo and main menu
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(good_wine_shop_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>

    <?php wp_body_open(); ?>

	<?php do_action( 'good_wine_shop_before' ); ?>

	<div class="body_wrap">

		<div class="page_wrap">

			<?php
			// Desktop header
			get_template_part( 'templates/'.good_wine_shop_get_theme_option("header_style"));

			// Mobile header
			get_template_part( 'templates/header-mobile');
			?>

			<div class="page_content_wrap scheme_<?php echo esc_attr(good_wine_shop_get_theme_option('color_scheme')); ?>">

				<?php if (good_wine_shop_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					good_wine_shop_create_widgets_area('widgets_above_page');
                    // Header for single posts
                    get_template_part( 'templates/header-single' );
					?>

					<div class="content">
						<?php
						// Widgets area inside page content
						good_wine_shop_create_widgets_area('widgets_above_content');
						?>				
