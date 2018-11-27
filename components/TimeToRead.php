<?php

namespace GinoPane\BlogTimeToRead\Components;

use RainLab\Blog\Models\Post;
use Cms\Classes\ComponentBase;
use GinoPane\BlogTimeToRead\Plugin;
use GinoPane\BlogTimeToRead\Models\Settings;
use GinoPane\BlogTimeToRead\Helpers\TimeToRead as TimeToReadHelper;


/**
 * Class TimeToRead
 *
 * @package GinoPane\BlogTaxonomy\Components
 */
class TimeToRead extends ComponentBase
{
    const NAME = 'timeToRead';

    /**
     * @var int
     */
    private $readingSpeed;

    /**
     * @var bool
     */
    private $isRoundingUpEnabled;

    /**
     * @var string
     */
    private $postSlug;

    /**
     * @var int
     */
    public $minutes;

    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name'        => Plugin::LOCALIZATION_KEY . 'components.timetoread.name',
            'description' => Plugin::LOCALIZATION_KEY . 'components.timetoread.description'
        ];
    }

    /**
     * Component Properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'postSlug' => [
                'title'       => Plugin::LOCALIZATION_KEY . 'components.timetoread.post_slug_title',
                'description' => Plugin::LOCALIZATION_KEY . 'components.timetoread.post_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],

            'readingSpeed' => [
                'title'         => Plugin::LOCALIZATION_KEY . 'settings.default_reading_speed',
                'description'   => Plugin::LOCALIZATION_KEY . 'settings.default_reading_speed_comment',
                'default'       => Settings::DEFAULT_READING_SPEED,
                'type'          => 'string',
                'validationPattern' => '^(0+)?[1-9]\d*$',
                'showExternalParam' => false
            ],

            'isRoundingUpEnabled' => [
                'title'       =>    Plugin::LOCALIZATION_KEY . 'settings.rounding_up_enabled',
                'description' =>    Plugin::LOCALIZATION_KEY . 'settings.rounding_up_enabled_comment',
                'type'        =>    'checkbox',
                'default'     =>    Settings::DEFAULT_ROUNDING_UP_ENABLED,
                'showExternalParam' => false
            ],
        ];
    }

    /**
     * Query the tag and posts belonging to it
     */
    public function onRun()
    {
        $this->prepareVars();

        $this->calculateReadingTime();
    }

    /**
     * Prepare variables
     *
     * @return void
     */
    private function prepareVars()
    {
        $this->postSlug = (string)$this->property('postSlug');
        $this->readingSpeed = (int)$this->property('readingSpeed');
        $this->isRoundingUpEnabled = (bool)$this->property('isRoundingUpEnabled');
    }

    /**
     * @return void
     */
    private function calculateReadingTime()
    {
        $post = Post::whereSlug($this->postSlug)->first();

        if (!$post) {
            $this->minutes = 0;
            return;
        }

        $this->minutes = TimeToReadHelper::get()->calculate(
            $post->content,
            array_filter([
                Settings::READING_SPEED_KEY => $this->readingSpeed,
                Settings::ROUNDING_UP_ENABLED_KEY => $this->isRoundingUpEnabled
            ])
        );
    }
}
