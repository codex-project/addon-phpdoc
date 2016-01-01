Codex Phpdoc Hook
=====================

[![Build Status](https://img.shields.io/travis/codex/phpdoc-hook.svg?&style=flat-square)](https://travis-ci.org/codex/phpdoc-hook)
[![Scrutinizer coverage](https://img.shields.io/scrutinizer/coverage/g/codex/phpdoc-hook.svg?&style=flat-square)](https://scrutinizer-ci.com/g/codex/phpdoc-hook)
[![Scrutinizer quality](https://img.shields.io/scrutinizer/g/codex/phpdoc-hook.svg?&style=flat-square)](https://scrutinizer-ci.com/g/codex/phpdoc-hook)
[![Source](http://img.shields.io/badge/source-codex/phpdoc-hook-blue.svg?style=flat-square)](https://github.com/codex/phpdoc-hook)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

The Phpdoc Hook provides Codex the means to parse the phpdoc generated xml file and display it in a user-friendly way:


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
