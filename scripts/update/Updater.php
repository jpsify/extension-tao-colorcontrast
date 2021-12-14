<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA ;
 */

namespace oat\taoColorContrast\scripts\update;

use oat\tao\model\ThemeNotFoundException;
use oat\tao\model\ThemeRegistry;
use oat\taoColorContrast\scripts\install\RegisterItemThemes;
use oat\taoTests\models\runner\plugins\PluginRegistry;
use oat\taoTests\models\runner\plugins\TestPlugin;

/**
 * Class Updater
 * @package oat\taoColorContrast\scripts\update
 * @deprecated use migrations instead. See https://github.com/oat-sa/generis/wiki/Tao-Update-Process
 */
class Updater extends \common_ext_ExtensionUpdater
{
    /**
     * Perform update from $initialVersion to $versionUpdatedTo.
     *
     * @param string $initialVersion
     * @return string $versionUpdatedTo
     */
    public function update($initialVersion)
    {
        if ($this->isVersion('0.0.0')) {

            $this->runExtensionScript(RegisterItemThemes::class);

            $registry = PluginRegistry::getRegistry();
            $registry->register(TestPlugin::fromArray([
                'id' => 'itemThemeSwitcher',
                'name' => 'Item themes switcher',
                'module' => 'taoColorContrast/runner/plugins/tools/itemThemeSwitcher',
                'bundle' => 'taoColorContrast/loader/testPlugins.min',
                'description' => 'Allow to switch between themes',
                'category' => 'tools',
                'active' => true,
                'tags' => ['core']
            ]));

            $this->setVersion('0.1.0');
        }

        $this->skip('0.1.0', '1.0.1');

        if ($this->isVersion('1.0.1')) {

            $this->runExtensionScript(RegisterItemThemes::class);

            $this->setVersion('1.1.0');
        }
        
        //Updater files are deprecated. Please use migrations.
        //See: https://github.com/oat-sa/generis/wiki/Tao-Update-Process

        $this->setVersion($this->getExtension()->getManifest()->getVersion());
    }
}
