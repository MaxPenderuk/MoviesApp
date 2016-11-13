#MoviesApp

MoviesApp is a web application that stores information about movies. This app was created using PHP(without frameworks), jQuery, Bootstrap. Also I've used Twig template engine to get rid of `php` code in `html` files stored in `MoviesApp/views` folder. 

### Server side
* database.php - establishes connection with MySQL `movies` database
* index.php - grabs movies data from DB
* create.php - creates a new movie
* read.php - shows information about a certain movie
* delete.php - deletes a certain movie from the database
* upload.php - uploads into an app a txt file with movies information
* remove.php - deletes a txt file with movies information from an app
* upload_to_db.php - adds data from a certain .txt file with movies information into DB 

### Installation
You can download this app manually as a .zip file or clone it using command line.
```sh
$ git clone https://github.com/MaxPenderuk/MoviesApp.git
```
> Before running the MoviesApp you have to import its `/MoviesApp/database/movies.sql` into your MySQL, and also you have to change `$dbUsername` and `$dbUserPassword` values in `MoviesApp/database.php` file. To import `.sql` file into MySQL follow the next steps. 

```sh
$ mysql -u user_name - p your_password;
  mysql> create database movies;
  mysql> quit; 
$ mysql -u `your_name` -p movies < MoviesApp/database/movies.sql 
$ php -S localhost:8000 -t MoviesApp/
```