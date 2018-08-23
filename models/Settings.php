<?php

namespace GinoPane\BlogTimeToRead\Models;

use Model;
use System\Behaviors\SettingsModel;

/**
 * Class Settings
 *
 * @package GinoPane\BlogTimeToRead\Models
 */
class Settings extends Model {

    const DEFAULT_READING_SPEED = 300;
    const DEFAULT_ROUNDING_UP_ENABLED = true;

    const READING_SPEED_KEY = 'default_reading_speed';
    const ROUNDING_UP_ENABLED_KEY = 'rounding_up_enabled';

    const SETTINGS_CODE = 'ginopane_blogtimetoread';

    public $implement = [SettingsModel::class];

    public $settingsCode = self::SETTINGS_CODE;

    public $settingsFields = 'fields.yaml';

    protected $cache = [];

    /**
     * @return int
     */
    public function readingSpeed() : int
    {
        return (int)$this->{self::READING_SPEED_KEY};
    }

    /**
     * @return bool
     */
    public function isRoundingUpEnabled(): bool
    {
        return (bool)$this->{self::ROUNDING_UP_ENABLED_KEY};
    }
} 