Custom Assets Bundle [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](./README.RU.md)
====================

Introduction
------------

Bundle allows transfer required assets from the custom folders into public directory. Business logic similar to
standard Symfony command - `assets:install`. Also required YAML-configuration, which register one or more path
to the custom sources. After installation sources will be transfer into `custom_assets` folder under public part
of the project.

Installation
------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable
version of this bundle:
```text
    composer require adrenalinkin/custom-assets-bundle
```
*This command requires you to have [Composer](https://getcomposer.org) install globally.*

### Step 2: Enable the Bundle

Then, enable the bundle by updating your `app/AppKernel.php` file to enable the bundle:

```php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    // ...

    public function registerBundles()
    {
        $bundles = [
            // ...

            new Linkin\Bundle\CustomAssetsBundle\LinkinCustomAssetsBundle(),
        ];

        return $bundles;
    }

    // ...
}
```

### Step 3: Run command by Composer

Register the script in `composer.json` for call custom assets installation every time  when composer requirements
has been install/update. To provide this behaviour add string
`"Linkin\\Bundle\\CustomAssetsBundle\\Composer\\ScriptHandler::installCustomAssets",`
after standard `installAssets` call. Example:

```json
{
    "scripts": {
        "post-root-package-install": [
            "SymfonyStandard\\Composer::hookRootPackageInstall"
        ],
        "symfony-scripts": [
            "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::buildBootstrap",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::clearCache",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installAssets",

            "Linkin\\Bundle\\CustomAssetsBundle\\Composer\\ScriptHandler::installCustomAssets",

            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::installRequirementsFile",
            "Sensio\\Bundle\\DistributionBundle\\Composer\\ScriptHandler::prepareDeploymentTarget"
        ],
        "post-install-cmd": [
            "@symfony-scripts"
        ],
        "post-update-cmd": [
            "@symfony-scripts"
        ]
    }
}
```

Configuration
-------------

To start using Bundle your need to create configuration in the global project configuration `app/config/config.yml`
or register configuration in the `custom_assets.yml` in the any bundle of you project.
Simple configuration, which should transfer `Bootstrap` asset:

```yaml
linkin_custom_assets:
    sources:
        bootstrap_dir: '../vendor/twbs/bootstrap/dist'
```

More information about configuration and configuration file in the part
[Configuration description](Resources/doc/en/config_description.md).

Usage
-----

Usage example expect using simple configuration from the previous part.
After run the command [custom_assets:install](Resources/doc/en/command_install.md) assets from folder
`vendor/twbs/bootstrap/dist` will be transfer into public folder of you project and will be available from the
`web/custom_assets/bootstrap_dir`.

Thus you you can access to the required filed, for example `bootstrap.min.css`:

```twig
    <link type="text/css" rel="stylesheet" href="{{ asset('custom_assets/bootstrap_dir/css/bootstrap.min.css') }}">
```

Commands
--------

 * [custom_assets:install](Resources/doc/en/command_install.md) - install assets by created configuration.

License
-------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
