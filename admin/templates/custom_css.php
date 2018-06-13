<?php settings_errors(); ?>

<form id="save-custom-css-form" method="post" action="options.php" class="">
	<?php settings_fields( 'devis_custom_css_group' ); ?>
	<?php do_settings_sections( 'options_submenu_css' ); ?>
	<?php submit_button(); ?>
</form>
