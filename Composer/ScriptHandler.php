<?php

namespace Linkin\Bundle\CustomAssetsBundle\Composer;

use Composer\Script\Event;

use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as Handler;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ScriptHandler extends Handler
{
    /**
     * @param Event $event
     */
    public static function installCustomAssets(Event $event)
    {
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
