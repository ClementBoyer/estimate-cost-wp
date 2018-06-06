<?php settings_errors(); ?>

<form id="save-custom-css-form" method="post" action="options.php" class="">
	<?php settings_fields( 'custom-css-section' ); ?>
	<?php do_settings_sections( 'options-submenu-css' ); ?>
	<?php submit_button(); ?>
</form>