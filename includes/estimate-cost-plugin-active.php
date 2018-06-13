<?php
/**
 * @package Estimate-Cost-Wp
 */

class EstimeCostPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}