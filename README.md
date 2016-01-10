Codex Phpdoc Hook
=====================

[![Documentation](https://img.shields.io/badge/documentation-codex--project.ninja%2Fphpdoc--hook-orange.svg?style=flat-square)](https://codex-project.ninja/phpdoc-hook)
[![Source](http://img.shields.io/badge/source-phpdoc--hook-blue.svg?style=flat-square)](https://github.com/codex-project/phpdoc-hook)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

The Phpdoc Hook provides Codex the means to parse the phpdoc generated xml file and display it in a user-friendly way.

Installation
------------
1. Add to composer

		composer require codex/phpdoc-hook

2. Add service provider

		Codex\Hooks\Phpdoc\HookServiceProvider::class

3. Publish and configure the configuration file

		php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=config

4. Publish the asset files

        php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=public
        
5. Publish the view files (optional)        

        php artisan vendor:publish --provider=Codex\Hooks\Phpdoc\HookServiceProvider --tag=views

6. Check the [documentation](http://codex-project.ninja/phpdoc-hook) for more!
