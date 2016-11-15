#MoviesApp

MoviesApp is a web application that stores information about movies. This app was created using PHP(without frameworks), jQuery, Bootstrap. Also I've used Twig template engine to get rid of `php` code in `html` files stored in `MoviesApp/views` folder. 

### Structure
* `css/` - contains .css files
* `database/` - contains `movies.sql` file, which has to be imported into MySQL before starting MoviesApp
* `fonts/` - contains fonts for Bootstrap
* `js/` - contains .js files
* `uploads/` - containts `.txt` files uploaded through UI for further import into DB
* `views/` - contains html files for rendering via server side `.php` scrips


### Server side
* `database.php` - establishes connection with MySQL `movies` database
* `index.php` - grabs movies data from DB
* `create.php` - creates a new movie
* `read.php` - shows information about a certain movie
* `delete.php` - deletes a certain movie from the database
* `upload.php` - uploads into the app a txt file with movies information
* `remove.php` - deletes a txt file with movies information from the app
* `upload_to_db.php` - adds data from a certain .txt file with movies information into DB 

### Installation
You can download this app manually as a .zip file or clone it using command line.
```sh
$ git clone https://github.com/MaxPenderuk/MoviesApp.git
```
Before running the MoviesApp you have to:
* install Twig using Composer `$ composer require twig/twig`
* import its `/MoviesApp/database/movies.sql` into your MySQL 
* change the configuration of the access to the local DB (`$dbUsername` and `$dbUserPassword` values in `MoviesApp/database.php` file) 

To import `.sql` file into MySQL follow the next steps:

```sh
$ mysql -u user_name - p your_password;
  mysql> create database movies;
  mysql> quit; 
$ mysql -u `your_name` -p movies < MoviesApp/database/movies.sql
```

Finally you can run the app using PHP Built-in web server. Just type this link `http://localhost:8000/index.php` in your browser's url field after running the next command:
```sh 
$ php -S localhost:8000 -t MoviesApp/
```