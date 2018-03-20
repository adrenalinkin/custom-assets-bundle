custom_assets:install [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](../ru/command_install.md)
=====================

The command provides installation of the all configured assets.

Parameters
----------

### Arguments

| Argument   | Required | Type     | By default | Description                    |
|:-----------|:--------:|:--------:|:----------:|--------------------------------|
| **target** | No       | `string` | `web`      | Path of to the original source |

### Options

| Option       | Required | Type   | By default | Description                   |
|:-------------|:--------:|:------:|:----------:|-------------------------------|
| **symlink**  | No       | `bool` | `false`    | Use symlink instead hard copy |
| **relative** | No       | `bool` | `false`    | Use relative symlink          |


Usage examples
--------------

**Note**: In that case when you use Symfony 2 ou should replace `bin/console` by `app/console`.

 * Install assets into standard directory `web` by using hard copy: 
```text
    php bin/console custom_assets:install
```

 * Install assets into standard directory `web` by using absolute symlink: 
```text
    php bin/console custom_assets:install --symlink
```

 * Install assets into standard directory `web` by using relative symlink: 
```text
    php bin/console custom_assets:install --symlink --relative
```

 * Install assets into `custom_web` directory by using relative symlink: 
```text
    php bin/console custom_assets:install custom_web --symlink --relative
```