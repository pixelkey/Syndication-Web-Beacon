<?php

namespace Tracker;

/**
 * Class Settings
 *
 * Handles reading settings from an environment file
 */
class Settings {
    public static function getSettings() {
        $settings = [];
        $envFile = file_get_contents('.env');
        $settingLines = explode(PHP_EOL, $envFile);

        foreach($settingLines as $setting) {
            $settingPair = explode('=', $setting);
            $settingName = $settingPair[0] ?? false;
            $settingValue = $settingPair[1] ?? '';

            if(!$settingName || $settingValue === '') {
                continue;
            }

            // Remove problematic characters from setting value
            $settingValue = str_replace(array(' ', "\n", "\t", "\r"), '', $settingValue);

            $settings[$settingName] = $settingValue;
        }

        return $settings;
    }
}
