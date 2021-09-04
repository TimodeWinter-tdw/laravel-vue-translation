# Laravel translation in VueJS
Vue 3 package to use your Laravel translations in your vue templates.

### Get started
 install the package via composer
 ```bash
 composer require timodw-nl/laravel-vue-translation
 ```

 ### Publish the package assets by running the command
 ```bash
 php artisan vendor:publish --provider="Timodw\Translation\TranslationServiceProvider"
 ```
 This will publish the **translations.js** file in **resources/js/plugin/translations** directory  
 
 ### Run the artisan command
 ```bash
 php artisan translation:generate
 ```
This will compile down all the translation files in the **resources/lang** directory in the file **resources/js/plugins/translations/translations.json**.

**This command is also run automatically in local environments everytime the application boots.**
 
### Add to vue
 ```js
import {createApp} from "vue";
import translationsPlugin from "./plugins/translations/translations";

const app = createApp(App);
app.use(translationsPlugin);
app.mount('#app');
```

### How to switch the languages?
This will get the current language form the document **lang** attribute
```html
<html lang="en">
```
### How to use?
Imagine this is the directory structure of **resources/lang** 
<pre>
|--en
   |--auth.php
   |--pagination.php
   |--passwords.php
   |--validation.php
   |--messages.php
|--nl
   |--auth.php
   |--pagination.php
   |--passwords.php
   |--validation.php
   |--messages.php  
</pre>
And the **messages.php** file is something like
```php
return [
    'foo' => [
        'bar' => 'Some message'
    ]
];
```
You can get the value by calling the **$t** directive
```js
$t('messages.foo.bar')
```
#### Uses Fallback Locale
To interact same like **Laravel** trans() insert in your layout:
```html
<meta name="fallback_locale" content="{{ config('app.fallback_locale') }}">
```

#### Replace attributes
> It's not recommended to use this package for showing validation errors but if you want you can replace :attribute, :size
etc. by passing the second argument as an object.
```js
$t('validation.required',{
    attribute:$t('validation.attributes.title')
});
```
> **Notice:** just like in laravel if it could not find the value for the key you passed it will return the exact key
 
