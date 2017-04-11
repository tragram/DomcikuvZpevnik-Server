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
3. Enable SQLite3 in your PHP settings - in 'php.ini' uncomment these lines:
```
extension=php_pdo.dll
extension=php_pdo_sqlite.dll
```
4. Configure your server - edit 'config.ini'
```
files_location = [where you are going to be saving the files]
database_location = [path to the database]
server_root = [URL of the root of your server (used afterwards by the Android application)]
```
5. Set the password - in 'lib/password_protect.php'
```
$LOGIN_INFORMATION = array(
  'login' => [your password]
);
```

## Brief description of important files and directories
* **api.php** - "Translates" the *config.ini* into JSON for the Android client (don't touch unless you plan to edit it as well)
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
* **css/custom.css** - This is where changes to the theme (custom styles) should be made, not in the bootstrap file
* **lib/** - Where the libraries are stored
* **doc/** - All the documentation
If you are interested in how exactly each file works, there's quite a lot of inline comments in the files themselves. ;)

## Backups
When a user deletes a record, it will stay on the server but won't be shown unless accessed directly. Using your favorite FTP-client, you can access you files location, deleted files will be prefixed by an underscore and containing the word "deleted" at the end.

When a user uploads a new file to an existing song, the old one will also stay on the server. Current microtime at the time of deletion will appended to it, in case you need to restore it.

## Possible upgrades
* Rewrite it all using javascript and jQuery, doing search by AJAX, without the need to reload the page
* Transposing the chords in ChordPro
* Better UI in the administration (after editing a song/submitting a new one)

## Libraries and Honorable mentions
* [Bootstrap Framework](http://getbootstrap.com/)
* [Flatly Theme for Bootstrap](https://bootswatch.com/flatly/)
* [Zubrag's Password Protect](http://www.zubrag.com/scripts/password-protect.php)
* [ChordPro JS Parser](http://github.com/jperkin/chordpro.js/) - with a tiny modification by Erik Cupal
* [Paques Alexis's Simple Json for PHP](https://github.com/AlexisTM/Simple-Json-PHP)
* [Bootstrap Inline Checkboxes](http://bootsnipp.com/snippets/ZkMKE)

## Special thanks
* **Jonáš Kareis:** for helping me create a complete list of all of my songs
* **Šimon Appelt:** for letting me use his hosting and domain