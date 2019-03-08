# One Page RSS Reader (Laravel based)

### Requirements

PHP >= 7.1; MySQL >= 5.5 

### Installation

1. Clone this repository ```$ git clone https://github.com/alexivanenko/rss_reader.git```
2. Go to your working directory. 
3. Run ```$ composer install```
4. Directories within the ```storage``` and the ```bootstrap/cache``` directories should be writable by your web server.
5. Add new host to the web server if necessary. 
6. Create new **MySQL DB**.
7. Run ```cp .env.example .env```
8. Update ```.env``` file using your DB credentials and your URL. 
9. Run ```php artisan migrate```

### Development Scope

* */resources/views/auth/register.blade.php* - jQuery AJAX call for email unique validation
* */app/Http/Controllers/Auth/RegisterController.php* - checkEmailUnique method
* */app/Http/Controllers/HomeController.php* - main controller
* */resources/views/home.blade.php* - main template
* */app/Feed/RssFeedReader.php* - RSS feed reader with method for getting most frequent words 
* */app/Wiki/MostCommonWordsParser.php* - Parse 'Most common words in English' wiki page and grab words 
* */tests/Unit/* - unit tests
