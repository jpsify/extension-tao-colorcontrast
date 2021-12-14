<?php
/**
 * Copyright (c) 2018 (original work) Open Assessment Technologies SA ;
 */

namespace oat\taoColorContrast\scripts\install;

use common_report_Report as Report;
use oat\oatbox\extension\InstallAction;
use oat\taoTests\models\runner\plugins\PluginRegistry;
use oat\taoTests\models\runner\plugins\TestPlugin;

/**
 * Class RegisterTestRunnerPlugins
 * Install action that registers the test runner plugins
 * @package oat\taoColorContrast\scripts\install
 */
class RegisterTestRunnerPlugins extends InstallAction
{
    protected $plugins = [
        'tools' => [
            [
                'id' => 'itemThemeSwitcher',
                'name' => 'Item themes switcher',
                'module' => 'taoColorContrast/runner/plugins/tools/itemThemeSwitcher',
                'bundle' => 'taoColorContrast/loader/testPlugins.min',
                'description' => 'Allow to switch between themes',
                'category' => 'tools',
                'active' => true,
                'tags' => [ 'core' ]
            ]
        ]
    ];

    protected $configs = [];

    /**
     * @return Report
     * @throws \common_exception_InconsistentData
     */
    protected function registerPlugins()
    {
        $registry = PluginRegistry::getRegistry();
        $count = 0;

        foreach ($this->plugins as $categoryPlugins) {
            foreach ($categoryPlugins as $pluginData) {
                if ($registry->register(TestPlugin::fromArray($pluginData))) {
                    $count++;
                }
            }
        }

        return new Report(Report::TYPE_SUCCESS, $count . ' plugins registered.');
    }

    /**
     * @return Report
     */
    protected function configurePlugins()
    {
        $extension = $this->getServiceLocator()->get(\common_ext_ExtensionsManager::SERVICE_ID)->getExtensionById('taoQtiTest');
        $config = $extension->getConfig('testRunner');
        $count = 0;

        foreach ($this->configs as $pluginName => $pluginConfig) {
            $configured = false;

            if (isset($pluginConfig['id'])) {
                $pluginName = $pluginConfig['id'];
            }

            if (isset($pluginConfig['shortcuts']) && count($pluginConfig['shortcuts'])) {
                $config['shortcuts'][$pluginName] = $pluginConfig['shortcuts'];
                $configured = true;
            }

            if (isset($pluginConfig['config']) && count($pluginConfig['config'])) {
                $config['plugins'][$pluginName] = $pluginConfig['config'];
                $configured = true;
            }

            if ($configured) {
                $count ++;
            }
        }

        $extension->setConfig('testRunner', $config);

        return new Report(Report::TYPE_SUCCESS, $count . ' plugins configured.');
    }

    /**
     * Run the install action
     * @param $params
     * @return Report
     * @throws \common_exception_Error
     * @throws \common_exception_InconsistentData
     */
    public function __invoke($params)
    {
        $registered = $this->registerPlugins();
        $configured = $this->configurePlugins();

        $overall = new Report(Report::TYPE_SUCCESS, 'Plugins registration done!');
        $overall->add($registered);
        $overall->add($configured);
        if ($overall->containsError()) {
            $overall->setType(Report::TYPE_ERROR);
        }

        return $overall;
    }
}
