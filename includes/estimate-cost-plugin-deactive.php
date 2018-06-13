<?php
/**
 * @package Estimate-Cost-Wp
 */

class EstimeCostPluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}