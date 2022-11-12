<?php
/**
 * Fired during plugin activation
 */

class WepPluginActivate
{
    public static function activate() {
        flush_rewrite_rules();
    }
}
