# Domčíkův Zpěvník - Server
This is the server application for my songbook. You can find the android app [here](https://github.com/tragram/DomcikuvZpevnik).

## Installation
1. Clone the GitHub repository:
```
git clone git@github.com:tragram/DomcikuvZpevnik-Server.git
```
2. Create your own SQLite database
```
CREATE TABLE `Songs` (
	`_id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`Artist`	TEXT NOT NULL,
	`Title`	TEXT NOT NULL UNIQUE,
	`AddedOn`	INTEGER NOT NULL,
	`Lang`	TEXT NOT NULL,
	`hasGen`	INTEGER NOT NULL,
	`hasChordPro`	INTEGER
);
```
3. Enable SQLite3 in your PHP settings - in the 'php.ini' uncomment these lines:
```
extension=php_pdo.dll
extension=php_pdo_sqlite.dll
```
4. Configure your server - edit 'config.ini'
```
files_location = [where you are going to be saving the files]
database_location = [path to the database]
server_root = [URL of the root of your server (used by the Android application)]
```
5. Set the password - in 'lib/password_protect.php'
```
$LOGIN_INFORMATION = array(
  'login' => [your password]
);
```

## Brief description of important files and directories
* **api.php** - "Translates" the *config.ini* into JSON for the Android client
* **chordpro.php** - Parses the ChordPro data for the user to see
* **config.ini** - Server configuration file
* **database.php** - Load the DB, allows the user to filter the table, contains all the links to the files
* **delete.php** - Deletes a record from the DB
* **index.php** - The 'home' page
* **makeTextNiceAgain.php** - Removes the accents from a string and makes it lowercase
* **song.php** - The song class, saves all the data from the DB and also provides a means to generate buttons with links
* **submit.php** - Page that lets the user add a new song/edit an old one.
* **template.php** - <head> and navbar
* **upload.php** - Checks that what the user submitted is OK and then uploads files and writes the record into the DB
* **css/custom.css** - This is where changes to the theme (custom styles) should be made, not in the bootstrap file.
* **lib/** - Where the libraries are stored
* **doc/** - All the documentation
The files themselves are also commented, for further info. 

## Libraries
* [Bootstrap Framework](http://getbootstrap.com/)
* [Flatly Theme for Bootstrap](https://bootswatch.com/flatly/)
* [Zubrag's Password Protect](http://www.zubrag.com/scripts/password-protect.php)
* [ChordPro JS Parser](http://github.com/jperkin/chordpro.js/)
* [Paques Alexis's Simple Json for PHP](https://github.com/AlexisTM/Simple-Json-PHP)
