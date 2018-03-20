<?php

/*
 * This file is part of the LinkinCustomAssetsBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\CustomAssetsBundle\DependencyInjection;

use Linkin\Component\ConfigHelper\Extension\AbstractExtension;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class LinkinCustomAssetsExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'linkin_custom_assets';
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load configuration from the bundles
        $configFromBundles = $this->getConfigurationsFromFile('custom_assets.yml', $container, false);

        // Merge with global configuration (global configuration have higher priority)
        $config = array_merge($configFromBundles, $configs);

        // Process configurations into final representation
        $config = $this->processConfiguration(new Configuration(), $config);

        // Prepare sources and get list of the incorrect sources
        $wrongSources = $this->prepareSources($container, $config['sources']);

        if (!empty($wrongSources)) {
            throw new InvalidConfigurationException(sprintf(
                'All sources should be configure based on the symfony kernel.root_dir path.
                The next sources does not found by the configure path: %s.',
                json_encode($wrongSources)
            ));
        }

        $container->setParameter('linkin_custom_assets.sources', $config['sources']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Returns safety path
     *
     * @param string $path
     *
     * @return mixed|string
     */
    private function preparePath($path)
    {
        return str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
    }

    /**
     * Process received sources by adding kernel.root_dir and returns list of the wrong sources
     *
     * @param ContainerBuilder $container Instance of the ContainerBuilder
     * @param array            $sources   List of the configured sources
     *
     * @return array List of the wrong sources
     */
    private function prepareSources(ContainerBuilder $container, array &$sources)
    {
        $wrongDirs = [];
        $rootDir   = $this->preparePath($container->getParameter('kernel.root_dir'));

        foreach ($sources as $name => &$path) {
            $path = $rootDir.DIRECTORY_SEPARATOR.$this->preparePath(rtrim($path, DIRECTORY_SEPARATOR));

            if (!is_dir($path)) {
                $wrongDirs[$name] = $path;
            }
        }

        return $wrongDirs;
    }
}
