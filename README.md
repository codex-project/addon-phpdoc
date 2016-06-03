Codex Phpdoc Hook
=====================

[![Documentation](https://img.shields.io/badge/documentation-codex--project.ninja-orange.svg?style=flat-square)](https://codex-project.ninja/codex/master/addons/phpdoc)
[![Source](http://img.shields.io/badge/source-addon--phpdoc-blue.svg?style=flat-square)](https://github.com/codex-project/addon-phpdoc)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

The Phpdoc Hook provides Codex the means to parse the phpdoc generated xml file and display it in a user-friendly way.

Installation
------------
1. Add to composer

		composer require codex/addon-phpdoc

2. Add service provider

		Codex\Addon\Phpdoc\PhpdocServiceProvider::class

3. Publish and configure the configuration file

		php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=config

4. Publish the asset files

        php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=public
   
5. (optional) Add to composer.json `post-update-cmd` to auto-update public assets on new version 
```json
{
    "scripts": {
        "post-update-cmd": [
            "php artisan vendor:publish --provider=Codex\\Addon\\Phpdoc\\PhpdocServiceProvider --tag=public --force"
        ]
    }
}
```
5. (optional) Publish the view files        

        php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=views

6. (recommended) Check the [documentation](http://codex-project.ninja/codex/master/addons/phpdoc) for more!
