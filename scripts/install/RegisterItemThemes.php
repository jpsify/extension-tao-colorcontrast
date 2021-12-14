<?php
/**
 * Copyright (c) 2018 Open Assessment Technologies, S.A.
 */

declare(strict_types=1);

namespace oat\taoColorContrast\scripts\install;

use oat\oatbox\extension\InstallAction;
use oat\tao\model\ThemeNotFoundException;
use oat\tao\model\ThemeRegistry;

/**
 * Class RegisterTextHelpCategoryPresetProviders
 * @package oat\taoColorContrast\scripts\install
 */
class RegisterItemThemes extends InstallAction
{
    private function getThemesList(): array
    {
        return [
            __('White on Blue (TAO standard theme)'),
            __('Black on White'),
            __('Black on Cream'),
            __('Black on Light Blue'),
            __('Black on Light Magenta'),
            __('White on Black'),
            __('Yellow on Blue'),
            __('Gray on Green'),
        ];
    }

    private function buildThemeId(string $themeName): string
    {
        $cleared = preg_replace('#\([^)]+\)#', '', $themeName);

        return str_replace(' ', '', lcfirst(ucwords($cleared)));
    }

    public function __invoke($params)
    {
        $themes = $this->getThemesList();

        // default (first) theme should be registered manually as an exception
        $defaultTheme = ThemeRegistry::getRegistry()->getDefaultTheme('items');
        try {
            ThemeRegistry::getRegistry()->unregisterTheme($defaultTheme['id']);
        } catch (ThemeNotFoundException $e) {}

        $defaultThemeName = array_shift($themes);
        $defaultThemeId = $this->buildThemeId($defaultThemeName);
        ThemeRegistry::getRegistry()->registerTheme(
            $defaultThemeId,
            $defaultThemeName,
            $defaultTheme['path'],
            ['items']
        );
        ThemeRegistry::getRegistry()->setDefaultTheme('items', $defaultThemeId);

        foreach($themes as $themeName) {
            $themeId = $this->buildThemeId($themeName);
            try {
                ThemeRegistry::getRegistry()->unregisterTheme($themeId);
            } catch (ThemeNotFoundException $e) {}

            ThemeRegistry::getRegistry()->registerTheme(
                $themeId,
                $themeName,
                implode(DIRECTORY_SEPARATOR, ['taoColorContrast', 'views', 'css', 'themes', 'items', $themeId, 'theme.css']),
                ['items']
            );
        }

        return new \common_report_Report(\common_report_Report::TYPE_SUCCESS, 'Item themes registered');
    }
}
