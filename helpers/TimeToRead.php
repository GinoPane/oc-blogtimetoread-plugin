<?php

namespace GinoPane\BlogTimeToRead\Helpers;

use GinoPane\BlogTimeToRead\Models\Settings;

class TimeToRead
{
    /** @var Settings */
    private $settings = null;

    /**
     * TimeToRead constructor
     *
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return TimeToRead
     */
    public static function get()
    {
        return new self(Settings::instance());
    }

    /**
     * @param string $text
     * @param array  $options
     *
     * @return int
     */
    public function calculate(string $text, array $options = []): int
    {
        $readingSpeed = abs(
            isset($options[Settings::READING_SPEED_KEY])
            ? (int)$options[Settings::READING_SPEED_KEY]
            : $this->settings->readingSpeed()
        );

        $isRoundingUpEnabled = isset($options[Settings::ROUNDING_UP_ENABLED_KEY])
            ? (bool)$options[Settings::ROUNDING_UP_ENABLED_KEY]
            : $this->settings->isRoundingUpEnabled();

        if (!$readingSpeed) {
            return 0;
        } else {
            $rawTimeToRead = str_word_count(strip_tags($text)) / $readingSpeed;

            if ($isRoundingUpEnabled) {
                return ceil($rawTimeToRead);
            } else {
                return (int)round($rawTimeToRead);
            }
        }
    }
}