<!--
title: Installation
subtitle: Getting Started
-->

# Installation

1 - **Composer install.** Begin by installing the package through Composer.

```bash
composer require codex/addon-phpdoc
```



2 - **Enable the plugin.** Add `phpdoc` to the `plugins` array inside the `codex.php` configuration file.
```php
return [
    //...
    'plugins' => [ 'phpdoc', '...'],
    //...
]
```



3 - **Enable the Vue plugin.** This requires you to have published the `codex/core` views. In `resources/views/vendor/codex/document.blade.php`:

_Default_
```javascript
Vue.use(CodexPlugin)
var app = new codex.App({
    el    : '#app'
})
```


_With PHPDoc Vue plugin_
```javascript
Vue.use(CodexPlugin)
Vue.use(CodexPhpdocPlugin)
var app = new codex.App({
    el    : '#app',
    mixins: [Vue.codex.phpdoc.mixins.phpdocDocument]
})
```



4 - (Optional) **Publish** the config and/or view files
```bash
php artisan vendor:publish --tag=config --provider="Codex\Addon\Phpdoc\PhpdocPlugin"
php artisan vendor:publish --tag=views --provider="Codex\Addon\Phpdoc\PhpdocPlugin"
```
