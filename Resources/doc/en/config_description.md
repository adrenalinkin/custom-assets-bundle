Configuration description [![На Русском](https://img.shields.io/badge/Перейти_на-Русский-green.svg?style=flat-square)](../ru/config_description.md)
=========================

To organize the transfer of necessary resources from specific locations to the public project directory, a special
configuration is provided. You can register configuration in the global project configuration `app/config/config.yml`.
Also you can move your configuration into special file `custom_assets.yml`, which can be located in any bundle of
you project under `/Resources/config` path.

**Recommended** create separate folder `/Resources/config/linkin` and put configuration file here to provide separation
of the Symfony configurations and extra configuration.

**Note**: bundle functionality allows you to combine both way of the configuration storing. In that case when your
configuration contains duplicated path keys - global configuration always have higher priority. When you have conflict
between several configurations `custom_assets.yml`, which has been stored in the bundles - configuration of the last
registered bundle will be applied.

Configuration fields
--------------------

**linkin_custom_assets** - root offset, which defines relations to the `CustomAssetsBundle`.
Any configuration file `custom_assets.yml` should be start from this key.

On the second level of the configuration placed single offset `sources`:

| Param       | Required  | Type    | By default | Description                                                |
|:------------|:---------:|:-------:|:----------:|------------------------------------------------------------|
| **sources** | Yes       | `array` |            | List of the all registered named path to the sources       |

Offset `sources` contains associative array where **value** - path to the necessary asset and
**key** - folder name where assets will be transfer.

**Attention**: Path based on the value of the `kernel.root_dir`.

### Full list of the configuration offsets

```yaml
linkin_custom_assets:                          # root offset, which defines relations to the CustomAssetsBundle
    sources:                                   # list of the all registered named path to the sources
        jquery: '../vendor/components/jquery'  # path to the JQuery source, which will be transfer into jquery folder
```

After run command [custom_assets:install](./command_install.md) required assets will be transfer into public part
of the project. Path `web/custom_assets/jquery/<transfered_source>`.

### Names conflict examples

**Global config conflict**:

```yaml
# app/config/config.yml
linkin_custom_assets:
    sources:
        jquery: '../vendor/components/jquery'
```

```yaml
# AppBundle/Resources/config/custom_assets.yml
linkin_custom_assets:
    sources:
        jquery: '../vendor/components/jquery_app'
```

```yaml
# AdminBundle/Resources/config/custom_assets.yml
linkin_custom_assets:
    sources:
        jquery: '../vendor/components/jquery_admin'
```

As result will be using path from the global config: `'../vendor/components/jquery'`

**Conflict between bundles**:

```yaml
# AppBundle/Resources/config/custom_assets.yml
linkin_custom_assets:
    sources:
        jquery: '../vendor/components/jquery_app'
```

```yaml
# AdminBundle/Resources/config/custom_assets.yml
linkin_custom_assets:
    sources:
        jquery: '../vendor/components/jquery_admin'
```

As result will be using path from the `AppBundle` config: `'../vendor/components/jquery_app'`.
It's happens because `AppBundle` has been register later than `AdminBundle`.

**Note**: rely on the order of registration of bands is a bad practice, since this is an internal mechanism of Symfony,
which can be changed at any time. Instead, assign unique values to the conflicting keys.
