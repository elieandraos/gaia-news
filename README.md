## gaia-news package
News admin module for the Gaia CMS project


#### Installation
Run the following command in your terminal 
```
composer require eandraos/gaia-news
```

Then register this service provider with Laravel in config/app.php
```
Gaia\News\GaiaNewsServiceProvider
```

Publish the different files
```
php artisan vendor:publish
```

#### Usage
Add PSR-4 autoload in the composer.json 
```
"Gaia\\": "app/Gaia"
```

Dump the class autoload in the terminal 
```
composer dump-autoload -o
```

Create the tables and seeds
```
php artisan migrate
php artisan db:seed --class=NewsTableSeeder
```
