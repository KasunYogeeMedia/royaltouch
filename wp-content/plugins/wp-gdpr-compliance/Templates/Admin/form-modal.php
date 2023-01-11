<?php

use WPGDPRC\Utils\Template;
use WPGDPRC\WordPress\Config;

?>

<div class="wpgdprc wpgdprc-modal modal" id="wpgdprc-form-modal" aria-hidden="true">
    <div class="wpgdprc-modal__overlay" tabindex="-1" data-form-close>
        <div class="wpgdprc-modal__inner" role="dialog" aria-modal="true">
            <div class="wpgdprc-modal__header">
                <p class="wpgdprc-modal__title">
                    <?php echo _x('Would you like to be kept up to date about Cookie Information?', 'admin', 'wp-gdpr-compliance'); ?>
                </p>
                <button class="wpgdprc-modal__close" aria-label="<?php esc_attr_e('Close popup', 'wp-gdpr-compliance'); ?>" data-form-close>
                    <?php
                    Template::renderSvg('icon-fal-times.svg'); ?>
                </button>
            </div>

            <div class="wpgdprc-modal__body wpgdprc-form-modal__body">
                <!--[if lte IE 8]>
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
                <![endif]-->
                <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js"></script>
                <script>
                    hbspt.forms.create({
                        region: "na1",
                        portalId: "5354868",
                        formId: "2dbbd79d-e520-4223-a8ae-2978f7234624"
                    });
                </script>
            </div>
        </div>
    </div>
</div>
