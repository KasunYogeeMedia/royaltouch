<?php
/**
 * The template for displaying team archive
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

get_header(); 

if (have_posts()) {

	?><div class="sc_team sc_team_default">
		
		<div class="sc_team_columns_wrap <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?>" columns_padding_bottom><?php

	set_query_var('trx_addons_args_sc_team', array(
			'columns' => 3,
			'slider' => false
		)
	);

	while ( have_posts() ) { the_post(); 
		if (($fdir = trx_addons_get_file_dir('cpt/team/tpl.default-item.php')) != '') { include $fdir; }
	}
	
	?></div><!-- .trx_addons_team_columns_wrap --></div><!-- .sc_team --><?php

	the_posts_pagination( array(
		'mid_size'  => 2,
		'prev_text' => esc_html__( '<', 'trx_addons' ),
		'next_text' => esc_html__( '>', 'trx_addons' ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'trx_addons' ) . ' </span>',
	) );

}

get_footer();
?>