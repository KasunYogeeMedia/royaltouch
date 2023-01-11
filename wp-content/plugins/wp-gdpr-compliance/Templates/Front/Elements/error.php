<?php

use WPGDPRC\WordPress\Plugin;

/**
 * @var string $message
 */

?>

<div class="wpgdprc-message wpgdprc-message--error">
	<p><?php printf(__('<strong>ERROR</strong>: %1s', 'wp-gdpr-compliance'), esc_html($message)); ?></p>
</div>
