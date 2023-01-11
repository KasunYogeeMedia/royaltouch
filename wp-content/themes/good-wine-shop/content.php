<?php
/**
 * The default template for displaying content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage GOOD_WINE_SHOP
 * @since GOOD_WINE_SHOP 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())) 
												. ' itemscope'
												); ?>
		itemscope itemtype="//schema.org/<?php echo esc_attr(is_single() ? 'BlogPosting' : 'Article'); ?>">

	<?php

	// Featured image
	if ( !get_query_var('good_wine_shop_featured_showed', false) )
		good_wine_shop_show_post_featured();

	// Title and post meta
	if ( !get_query_var('good_wine_shop_title_showed', false) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( '<h2 class="post_title entry-title" itemprop="headline">', '</h2>' );
			// Post meta
			good_wine_shop_show_post_meta(array(
				'seo' => true,
				'share' => false,
				'counters' => 'comments,views,likes'
				)
			);
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	?>
	<div class="post_content entry-content" itemprop="articleBody">
		<?php
			the_content( );

			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'good-wine-shop' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'good-wine-shop' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

        // Taxonomies and share
        if ( is_single() && !is_attachment() ) {
            ?>
            <div class="post_meta post_meta_single"><?php

                // Post taxonomies
                ?><span class="post_meta_item post_tags"><span class="post_meta_label"><?php esc_html_e('Tags:', 'good-wine-shop'); ?></span> <?php the_tags( '', ', ', '' ); ?></span><?php

                ?>
            </div>
        <?php
        }

		?>
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if ( is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
			get_template_part( 'templates/author-bio' );
		}
	?>

</article>
