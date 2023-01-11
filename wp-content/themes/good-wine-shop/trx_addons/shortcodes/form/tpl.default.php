<?php
/**
 * The style "default" of the Contact form
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.2
 */

$args = get_query_var('trx_addons_args_sc_form');
$form_style = $args['style'] = empty($args['style']) || trx_addons_is_inherit($args['style']) ? trx_addons_get_option('input_hover') : $args['style'];
?>
<div
    <?php if (!empty($args['id'])) echo ' id="'.esc_attr($args['id']).'"'; ?>
        class="sc_form sc_form_default<?php
        if (!empty($args['class'])) echo ' '.esc_attr($args['class']);
        if (!empty($args['align']) && !trx_addons_is_off($args['align'])) echo ' sc_align_'.esc_attr($args['align']);
        ?>"
    <?php if (!empty($args['css'])) echo ' style="'.esc_attr($args['css']).'"'; ?>
>
    <?php trx_addons_sc_show_titles('sc_form', $args); ?>
    <form class="sc_form_form <?php if ($form_style != 'default') echo 'sc_input_hover_'.esc_attr($form_style); ?>" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
        <div class="sc_form_details <?php echo esc_attr(trx_addons_get_columns_wrap_class()); ?>"><?php
            // Contact form. Attention! Column's tags can't start with new line
            ?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, 2)); ?>"><?php
                set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
                    'field_name'  => 'name3',
                    'field_type'  => 'text',
                    'field_req'   => true,
                    'field_icon'  => 'trx_addons_icon-user-alt',
                    'field_title' => esc_html__('Name', 'good-wine-shop'),
                    'field_placeholder' => esc_html__('Your name', 'good-wine-shop')

                )));
                if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.form-field.php')) != '') { include $fdir; }
                ?>
            </div><div class="<?php echo esc_attr(trx_addons_get_column_class(1, 2)); ?>"><?php
                set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
                    'field_name'  => 'email3',
                    'field_type'  => 'text',
                    'field_req'   => true,
                    'field_icon'  => 'trx_addons_icon-mail',
                    'field_title' => esc_html__('E-mail', 'good-wine-shop'),
                    'field_placeholder' => esc_html__('Your e-mail', 'good-wine-shop')

                )));
                if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.form-field.php')) != '') { include $fdir; }
                ?>
            </div><?php
            ?></div><?php
        set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
            'field_name'  => 'website',
            'field_type'  => 'text',
            'field_req'   => false,
            'field_icon'  => 'trx_addons_icon-desktop',
            'field_title' => esc_html__('Website', 'good-wine-shop'),
            'field_placeholder' => esc_html__('Your website', 'good-wine-shop')

        )));
        if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.form-field.php')) != '') { include $fdir; }
        set_query_var('trx_addons_args_sc_form_field', array_merge($args, array(
            'field_name'  => 'message',
            'field_type'  => 'textarea',
            'field_req'   => false,
            'field_icon'  => 'trx_addons_icon-feather',
            'field_title' => esc_html__('Message', 'good-wine-shop'),
            'field_placeholder' => esc_html__('Your message', 'good-wine-shop')

        )));
        if (($fdir = trx_addons_get_file_dir('shortcodes/form/tpl.form-field.php')) != '') { include $fdir; }
        static $cnt = 0;
        $cnt++;
        $privacy = trx_addons_get_privacy_text();
        if (!empty($privacy)) {
            ?><div class="sc_form_field sc_form_field_checkbox"><?php
            ?><input type="checkbox" id="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>" name="i_agree_privacy_policy" class="sc_form_privacy_checkbox" value="1">
            <label for="i_agree_privacy_policy_sc_form_<?php echo esc_attr($cnt); ?>"><?php trx_addons_show_layout($privacy); ?></label>
            </div><?php
        }
        ?>
        <div class="sc_form_field sc_form_field_button"><button <?php
            if (!empty($privacy)) echo ' disabled="disabled"'
            ?>><?php esc_html_e('Send Message', 'good-wine-shop'); ?></button></div>
        <div class="trx_addons_message_box sc_form_result"></div>
    </form>
</div><!-- /.sc_form -->