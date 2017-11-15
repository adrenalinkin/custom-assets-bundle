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

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class LinkinCustomAssetsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config       = $this->processConfiguration(new Configuration(), $configs);
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
     * @param string $path
     *
     * @return mixed|string
     */
    private function preparePath($path)
    {
        return str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $path);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $sources
     *
     * @return array
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
