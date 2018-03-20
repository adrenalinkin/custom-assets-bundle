Custom Assets Bundle [![In English](https://img.shields.io/badge/Switch_To-English-green.svg?style=flat-square)](./README.md)
====================

Введение
--------

Бандл предназначен для переноса необходимых ресурсов проекта из специфических мест в публичную директорию. Принцип
работы основан на стандартной команде Symfony - `assets:install`. При помощи YAML-конфигурации регистрируется путь
или список путей, по которым будут найдены необходимые ресурсы и перенесены в папку `custom_assets`
в публичную часть проекта.

Установка
---------

### Шаг 1: Загрузка бандла

Откройте консоль и, перейдя в директорию проекта, выполните следующую команду для загрузки наиболее подходящей
стабильной версии этого бандла:
```text
    composer require adrenalinkin/custom-assets-bundle
```
*Эта команда подразумевает что [Composer](https://getcomposer.org) установлен и доступен глобально.*

### Шаг 2: Подключение бандла

После включите бандл добавив его в список зарегистрированных бандлов в `app/AppKernel.php` файл вашего проекта:

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

### Шаг 3: Запуск команды вместе с Composer

Зарегистрируйте скрипт в `composer.json` для вызова установки ресурсов после каждой установки/обновлении зависимостей.
Для этого добавьте строку `"Linkin\\Bundle\\CustomAssetsBundle\\Composer\\ScriptHandler::installCustomAssets",`
после стандартного вызова установки ресурсов `installAssets` как показано в примере ниже:

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

Конфигурация
------------

Чтобы начать использовать бандл необходимо создать конфигурацию в глобальном конфиге проекта `app/config/config.yml`
или зарегистрироваться посредством файла `custom_assets.yml` создав его в необходимом бандле.
Простейшая конфигурация для перенесения `Bootstrap`:

```yaml
linkin_custom_assets:
    sources:
        bootstrap_dir: '../vendor/twbs/bootstrap/dist'
```

Подробнее с конфигурацией и файлом конфигурации можно ознакомиться в разделе
[Configuration description](Resources/doc/ru/config_description.md).

Использование
-------------

Пример использования предполагает использование простейшей конфигурации, составленной в предыдущем разделе.
После выполнения команды [custom_assets:install](Resources/doc/ru/command_install.md)
ресурсы из папки `vendor/twbs/bootstrap/dist` будут скопированы в публичную папку проекта и станут доступны
в папке `web/custom_assets/bootstrap_dir`.

Таким образом вы можете получить доступ к необходимым файлам, например `bootstrap.min.css`:

```twig
    <link type="text/css" rel="stylesheet" href="{{ asset('custom_assets/bootstrap_dir/css/bootstrap.min.css') }}">
```

Команды
-------

 * [custom_assets:install](Resources/doc/ru/command_install.md) - установки ресурсов согласно созданной конфигурации.

Лицензия
--------

[![license](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](./LICENSE)
