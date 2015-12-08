Docit Phpdoc Hook
=====================

[![Build Status](https://img.shields.io/travis/docit/phpdoc-hook.svg?&style=flat-square)](https://travis-ci.org/docit/phpdoc-hook)
[![Scrutinizer coverage](https://img.shields.io/scrutinizer/coverage/g/docit/phpdoc-hook.svg?&style=flat-square)](https://scrutinizer-ci.com/g/docit/phpdoc-hook)
[![Scrutinizer quality](https://img.shields.io/scrutinizer/g/docit/phpdoc-hook.svg?&style=flat-square)](https://scrutinizer-ci.com/g/docit/phpdoc-hook)
[![Source](http://img.shields.io/badge/source-docit/phpdoc-hook-blue.svg?style=flat-square)](https://github.com/docit/phpdoc-hook)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

The Phpdoc Hook provides Docit the means to parse the phpdoc generated xml file and display it in a user-friendly way:


Installation
------------
1. Add to composer

		composer require docit/phpdoc-hook

2. Add service provider

		Docit\Hooks\Phpdoc\HookServiceProvider::class

3. Publish and configure the configuration file

		php artisan vendor:publish --provider=Docit\Hooks\Phpdoc\HookServiceProvider --tag=config

4. Publish the asset files

        php artisan vendor:publish --provider=Docit\Hooks\Phpdoc\HookServiceProvider --tag=public
        
5. Publish the view files (optional)        

        php artisan vendor:publish --provider=Docit\Hooks\Phpdoc\HookServiceProvider --tag=views
