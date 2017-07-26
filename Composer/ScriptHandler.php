<?php

namespace Linkin\Bundle\CustomAssetsBundle\Composer;

use Composer\Plugin\CommandEvent;
use Composer\Script\Event;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ScriptHandler extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
{
    /**
     * @param Event|CommandEvent $event
     */
    public static function installCustomAssets($event)
    {
        if (!$event instanceof Event && !$event instanceof CommandEvent) {
            throw new \RuntimeException(
                'event should be an instance of \Composer\Plugin\CommandEvent or \Composer\Script\Event'
            );
        }

        $options    = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install custom assets');

        if (null === $consoleDir) {
            return;
        }

        $webDir  = $options['symfony-web-dir'];
        $symlink = '';

        if ($options['symfony-assets-install'] == 'symlink') {
            $symlink = '--symlink ';
        } elseif ($options['symfony-assets-install'] == 'relative') {
            $symlink = '--symlink --relative ';
        }

        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'install custom assets')) {
            return;
        }

        static::executeCommand(
            $event,
            $consoleDir,
            'custom_assets:install ' . $symlink . escapeshellarg($webDir),
            $options['process-timeout']
        );
    }
}
