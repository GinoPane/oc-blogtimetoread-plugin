<?php

return [
    'plugin' => [
        'name' => 'Blog Time to Read',
        'description' => 'Display time to read for your blog posts'
    ],

    'settings' => [
        'name' => 'Blog Time to Read',
        'description' => 'Set default reading options',
        'main_settings_title' => 'Main Settings',
        'default_reading_speed' => 'Default reading speed',
        'default_reading_speed_comment' => 'Words per minute by default',
        'rounding_up_enabled' => 'Rounding up',
        'rounding_up_enabled_comment' => 'Round fractional values to the next highest integer value'
    ],

    'components' => [
        'timetoread' => [
            'name' => 'Time to Read',
            'description' => 'Display time to read for post content with formatting and overridden parameters',
            'post_slug_title' => 'Post slug',
            'post_slug_description' => 'Get time to read for the post specified by the slug value from URL parameter',
        ]
    ]
];
