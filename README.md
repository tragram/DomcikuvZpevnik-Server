# Domčíkův Zpěvník - Server
This is the server application for my songbook.

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

## Libraries
* [Bootstrap Framework](http://getbootstrap.com/)
* [Zubrag's Password Protect](http://www.zubrag.com/scripts/password-protect.php)
* [ChordPro JS Parser](http://github.com/jperkin/chordpro.js/)
