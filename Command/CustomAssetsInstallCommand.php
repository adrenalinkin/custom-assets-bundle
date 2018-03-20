<?php

/*
 * This file is part of the LinkinCustomAssetsBundle package.
 *
 * (c) Viktor Linkin <adrenalinkin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Linkin\Bundle\CustomAssetsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class CustomAssetsInstallCommand extends ContainerAwareCommand
{
    /**
     * Console command name
     */
    const NAME = 'custom_assets:install';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDefinition([
                new InputArgument('target', InputArgument::OPTIONAL, 'The target directory', 'web'),
            ])
            ->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the components instead of copying it')
            ->addOption('relative', null, InputOption::VALUE_NONE, 'Make relative symlinks')
            ->setDescription(
                'Installs web assets under a public web directory from the custom places.
                Work a same as the standard Symfony <info>assets:install</info> command'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $DS     = DIRECTORY_SEPARATOR;
        $target = rtrim($input->getArgument('target'), $DS);

        if (!is_dir($target)) {
            throw new \InvalidArgumentException(sprintf('The target directory "%s" does not exist.', $target));
        }

        if ($input->getOption('symlink') && !function_exists('symlink')) {
            throw new \InvalidArgumentException(
                'The symlink() function is not available on your system. 
                You need to install the assets without the --symlink option.'
            );
        }

        $filesystem      = $this->getContainer()->get('filesystem');
        $customAssetsDir = $target.$DS.'custom_assets';
        $isSymlink       = $input->getOption('symlink');
        $isRelative      = $input->getOption('relative');

        if (!is_dir($customAssetsDir)) {
            $filesystem->mkdir($customAssetsDir, 0777);

            $output->writeln('<info>Directory for the custom assets has been created.</info>');
        }

        $output->write(sprintf('Custom assets will be installed under the <comment>%s</comment>', $customAssetsDir));

        if ($isSymlink) {
            $output->write(' as <comment>symlinks</comment>');
            $output->writeln(sprintf(' with <comment>%s</comment> path', $isRelative ? 'relative' : 'absolute'));
        } else {
            $output->writeln(' as <comment>hard copies</comment>');
        }

        $customAssetsSources = $this->getContainer()->getParameter('linkin_custom_assets.sources');

        foreach ($customAssetsSources as $name => $path) {
            $originDir = rtrim($path, $DS);

            if (!is_dir($originDir)) {
                $output->writeln(sprintf('Path <comment>%s</comment> not found. <error>Skip.</error>', $originDir));

                continue;
            }

            $targetDir = $customAssetsDir.$DS.$name;

            $output->writeln(sprintf(
                'Installing custom assets named as <comment>%s</comment> into <comment>%s</comment> directory',
                $name,
                $targetDir
            ));

            $filesystem->remove($targetDir);

            if ($isSymlink) {
                if ($input->getOption('relative')) {
                    $relativeOriginDir = $filesystem->makePathRelative($originDir, realpath($customAssetsDir));
                } else {
                    $relativeOriginDir = $originDir;
                }

                $filesystem->symlink($relativeOriginDir, $targetDir);
            } else {
                $filesystem->mkdir($targetDir, 0777);
                $filesystem->mirror(
                    $originDir,
                    $targetDir,
                    Finder::create()->ignoreDotFiles(false)->in($originDir)
                );
            }
        }
    }
}
