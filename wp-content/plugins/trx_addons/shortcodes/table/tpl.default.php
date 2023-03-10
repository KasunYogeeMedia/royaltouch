<?php
/**
 * The style "default" of the table
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.3
 */

$args = get_query_var('trx_addons_args_sc_table');

?><div id="<?php echo esc_attr($args['id']); ?>_wrap" class="sc_table_wrap"><?php

	trx_addons_sc_show_titles('sc_table', $args);
	
	?><div id="<?php echo esc_attr($args['id']); ?>"
			class="sc_table<?php
					echo (!trx_addons_is_off($args['align']) ? ' align'.esc_attr($args['align']) : '') 
						. (!empty($args['class']) ? ' '.esc_attr($args['class']) : '');
					?>"
			<?php if ($args['css']!='') echo ' style="'.esc_attr($args['css']).'"'; ?>
			><?php echo trim($args['content']); ?>
	</div><?php

	trx_addons_sc_show_links('sc_table', $args);
	
?></div><!-- /.sc_table_wrap -->