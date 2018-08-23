<?php

namespace GinoPane\BlogTimeToRead\Updates;

use DB;
use Schema;
use System\Classes\PluginManager;
use October\Rain\Database\Updates\Migration;
use GinoPane\BlogTimeToRead\Models\Settings;

/**
 * Class PopulateDefaultSettings
 *
 * @package GinoPane\BlogTimeToRead\Updates
 */
class PopulateDefaultSettings extends Migration
{
    /**
     * Execute migrations
     */
    public function up()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            $this->addSettings();
        }
    }

    /**
     * Rollback migrations
     */
    public function down()
    {
        if (PluginManager::instance()->hasPlugin('RainLab.Blog')) {
            $this->removeSettings();
        }
    }

    /**
     * Rollback Tags migration
     */
    private function removeSettings()
    {
        if (Schema::hasTable('system_settings')) {
            DB::table('system_settings')->whereItem(Settings::SETTINGS_CODE)->delete();
        }
    }

    /**
     * Create Tags table
     */
    private function addSettings()
    {
        if (Schema::hasTable('system_settings')) {
            $settings = [
               'default_reading_speed' => Settings::DEFAULT_READING_SPEED,
               'rounding_up_enabled' => Settings::DEFAULT_ROUNDING_UP_ENABLED
            ];

            DB::table('system_settings')->insert(
                ['item' => Settings::SETTINGS_CODE, 'value' => json_encode($settings)]
            );
        }
    }
}
