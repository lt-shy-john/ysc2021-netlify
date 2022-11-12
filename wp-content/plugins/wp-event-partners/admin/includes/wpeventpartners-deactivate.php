<?php
/**
 * Fired during plugin deactivation
 */

class WepPluginDeactivate
{
    public static function deactivate() {
        flush_rewrite_rules();
    }
}