<?php

namespace GinoPane\BlogTimeToRead;

use System\Classes\PluginBase;
use RainLab\Blog\Models\Post as PostModel;
use GinoPane\BlogTimeToRead\Models\Settings;
use GinoPane\BlogTimeToRead\Helpers\TimeToRead;
use GinoPane\BlogTimeToRead\Components\TimeToRead as TimeToReadComponent;

/**
 * Class Plugin
 *
 * @package GinoPane\BlogTimeToRead
 */
class Plugin extends PluginBase
{
    const DEFAULT_ICON = 'icon-clock-o';

    const LOCALIZATION_KEY = 'ginopane.blogtimetoread::lang.';

    /**
     * @var array   Require some plugins
     */
    public $require = [
        'RainLab.Blog',
        'RainLab.Translate'
    ];

    /** @var TimeToRead  */
    private $helper;

    /**
     * Returns information about this plugin
     *
     * @return  array
     */
    public function pluginDetails()
    {
        return [
            'name'        => self::LOCALIZATION_KEY . 'plugin.name',
            'description' => self::LOCALIZATION_KEY . 'plugin.description',
            'author'      => 'Siarhei <Gino Pane> Karavai',
            'icon'        => self::DEFAULT_ICON,
            'homepage'    => 'https://github.com/GinoPane/oc-blogtimetoread-plugin'
        ];
    }

    /**
     * Boot method, called right before the request route
     */
    public function boot()
    {
        $this->helper = new TimeToRead(Settings::instance());

        // extend the post model
        $this->extendModel();
    }

    /**
     * Register components
     *
     * @return  array
     */
    public function registerComponents()
    {
        return [
            TimeToReadComponent::class => TimeToReadComponent::NAME,
        ];
    }

    /**
     * Register plugin settings
     *
     * @return array
     */
    public function registerSettings(){
        return [
            'settings' => [
                'label'       => self::LOCALIZATION_KEY . 'settings.name',
                'description' => self::LOCALIZATION_KEY . 'settings.description',
                'icon'        => self::DEFAULT_ICON,
                'class'       => Settings::class,
                'order'       => 800,
                'category'    => 'rainlab.blog::lang.blog.menu_label'
            ]
        ];
    }

    /**
     * @return array
     */
    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'timeToRead' => [$this, 'getTimeToRead']
            ]
        ];
    }

    /**
     * @param      $text
     * @param null $speed
     * @param null $roundingEnabled
     *
     * @return int
     */
    public function getTimeToRead($text, $speed = null, $roundingEnabled = null)
    {
        return $this->helper->calculate(
            $text,
            array_filter([
                Settings::READING_SPEED_KEY => $speed,
                Settings::ROUNDING_UP_ENABLED_KEY => $roundingEnabled
            ])
        );
    }

    /**
     * Extend RainLab Post model
     */
    private function extendModel()
    {
        PostModel::extend(function ($model) {
            $model->addDynamicMethod('getTimeToReadAttribute', function() use ($model) {
                return $this->helper->calculate($model->content);
            });
        });
    }
}
