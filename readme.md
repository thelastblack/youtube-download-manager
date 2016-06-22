# Youtube Download Manager

## What is it
This is a web application to easily download Youtube videos. It is written with Laravel, so any backend supported by Laravel may be used for queuing or database. For more information about these, visit Laravel docs, which is pretty straight-forward.

The simple UI is built with Bootstrap and Google fonts.

## Installation
Getting this up and running is pretty easy as it is an standard Laravel application.

 * Create folder for uploads
   ```
   mkdir public/uploads
   chmod 755 public/uploads
   ```
 * Create database file (If using SQLite, or configure your own database)
   ```
   touch database/database.sqlite
   ```
 * Run migrations
   ```
   php artisan migrate
   ```
 * Point webserver to `public` directory
 * Visit `http://example.com/where-ever`
 * Configure Laravel queue workers, mentioned in Laravel docs. You can use as many workers as you want.

## What does it do
It gets a Youtube url from user, makes a download job out of it, then downloads
it to `public/uploads` folder. The download part relies of web server to serve this folder properly, so make sure of it.

It was developed to be used in private environments, and as it is possible to deploy a public app with it, I did not even think about possible problems. So you are on your own.

It uses all facilities provided by Laravel to make sure it works with different backends and with different throughput.

The download part is done using `masih/youtubedownloader`, so a thanks goes to this great package.

## License
I have not though a lot about it, but think of it as free software. Use it for whatever you want, just give me some credits when you use this.
