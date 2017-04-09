<?php
include('lib/password_protect.php');
$page_title='Nahrát píseň';
$page = 'db';
require_once 'template.php';
require_once 'makeTextNiceAgain.php';
require_once 'song.php';

$db = new SQLite3($ini['database_location']);

$artist;
$title;
$nice_artist;
$nice_title;
$addedOn;
$lang;
$hasGen;

$target_file_original;
$target_file_compressed;
$target_file_gen;
$target_file_chordpro;
$old_song;
$message="";

$updating = $_POST['updating'];
//Having some troubles letting php do it on its own, so I better do it myself
if($updating!=true && $updating!=false){
    if($updating == "true"){
        $updating = true;
    } else {
        $updating = false;
    }
}
$updating_title = !empty($_POST['inputTitle']);
$updating_artist = !empty($_POST['inputArtist']);
$updating_date = !empty($_POST['inputDate']);
$updating_chordpro = !empty($_POST['chordpro']);
$uploading_sken = is_uploaded_file($_FILES['best']['tmp_name']);
$uploading_comp = is_uploaded_file($_FILES['compressed']['tmp_name']);
$uploading_gen = is_uploaded_file($_FILES['gen']['tmp_name']);


//Populate all variables
//Only allow for letters, apostrophes, commas and dots
$title = preg_replace('/[^0-9a-zA-ZáčďéěíňóřšťůúýžÁČĎÉĚÍŇÓŘŠŤŮÚÝŽĺĹñÑ+ \.\']+/', "", $_POST['inputTitle']);
$artist = preg_replace('/[^0-9a-zA-ZáčďéěíňóřšťůúýžÁČĎÉĚÍŇÓŘŠŤŮÚÝŽĺĹñÑ+ \.\']+/', "", $_POST['inputArtist']);
$addedOn = $_POST['inputDate'];
$lang = $_POST['inputLanguage'];
$basename = $ini['files_location'];

//If updating, fill in the old values that were not updated
if($updating == true){
    $stmt = $db->prepare('SELECT * FROM Songs WHERE _id=:id LIMIT 1');
    $stmt->bindValue(':id', $_POST['id']);
    $result = $stmt->execute();
    if ($result != null) {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $old_song = new Song($row['_id'], $row['Title'], $row['Artist'], $row['hasGen'], $row['AddedOn'], $row['Lang']);
     } else {
         die ("Chyba při připojování se k databázi!");
     }
    $basename .= (makeTextNiceAgain($old_song->getArtist()) . "_" . makeTextNiceAgain($old_song->getTitle()));

     //Title 
     if ($updating_title == false) {
         $title = $old_song->getTitle();
     }

     //Artist
     if($updating_artist == false) {
         $artist=$old_song->getArtist();
     }
     
     //Date AddedOn
     if ($updating_date == false) {
         $addedOn = $old_song->getDateAdded();
     }

     $hasGen = $old_song->hasGen();
}

$old_target_file_original = $basename . "-sken.pdf";
$old_target_file_compressed = $basename . "-comp.pdf";
$old_target_file_gen = $basename . "-gen.pdf";
$old_target_file_chordpro = $basename . "-chordpro.txt";

//Set new filenames
$nice_artist = makeTextNiceAgain($artist);
$nice_title = makeTextNiceAgain($title);
$basename = $GLOBALS['ini']['files_location'] . $nice_artist . "_" . $nice_title;
$target_file_original = $basename . "-sken.pdf";
$target_file_compressed = $basename . "-comp.pdf";
$target_file_gen = $basename . "-gen.pdf";
$target_file_chordpro = $basename . "-chordpro.txt";

//Only allow for the correct format
if (strlen($addedOn) != 4) {
    $addedOn = date("y") . date("m");
    $GLOBALS['message'] .= "Nesprávný formát data, nastaveno na " . $addedOn . " <br>";
}

//When not updating, we need to check that everything is set
if(inputOK($artist, $title)){
    if(filesOK()){
        if(checkTitle($db, $title)){
            uploadFiles($target_file_original, $target_file_compressed, $target_file_gen, $target_file_chordpro);
            writeIntoDB($db);
            $GLOBALS['message'] .= "Soubor " . basename($_FILES["best"]["name"]) . " byl nahrán. <br>";
        }
    }
}

function inputOK($artist, $title)
{
    if (empty($artist) || empty($title)) {
        $GLOBALS['message'] .= "You need to provide an artist and a title! </br>";
        return false;
    }

    //Check if comp and orig are set
    if (($_FILES['best'] == null || $_FILES['compressed'] == null) && $GLOBALS['updating'] == false) {
        $GLOBALS['message'] .= "Je nutné nahrát alespoň skeny (originální a kompresovaný) k nové písni!</br>";
        return false;
    }
    return true;
}


//Check if the record already exists
function checkTitle($db, $title){
    //Title has to be always unique
    $stmt = $db->prepare('SELECT * FROM Songs WHERE Title = :title LIMIT 1');
    $stmt->bindValue('title', $title, SQLITE3_TEXT);
    $result=$stmt->execute();
    if ($result != null) {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        //When there is a result that's different to what we had
        if($row != false && $row['_id'] != $_POST['id']){
            $GLOBALS['message'] .= "Tento název bohužel již existuje. Zkuste zvolit jiný. :) <br>";
            return false;
        }
        return true;
    } else {
        echo "Error datábaze při kontrole jedinečnosti názvu.";
    }
}

function filesOK(){
        // Check file sizes
        if ($_FILES["best"]["size"] > 50000000) {
            $GLOBALS['message'] .= "Originální sken přesahuje maximální povovlenou velikost (50MB). </br>";
            return false;
        }
        if ($_FILES["compressed"]["size"] > 5000000) {
            $GLOBALS['message'] .= "Kompresovaný sken přesahuje maximální povovlenou velikost (5MB). </br>";
            return false;
        }
        if ($_FILES["gen"]["size"] > 5000000) {
            $GLOBALS['message'] .= "Generovaný soubor přesahuje maximální povovlenou velikost (5MB). </br>";
            return false;
        }

        // Allow only PDFs, check only if it was submitted
        if ((pathinfo(basename($_FILES["best"]["name"]), PATHINFO_EXTENSION) != "pdf") && $GLOBALS['uploading_sken'] == true) {
            $GLOBALS['message'] .= "Originální sken není PDF. Zkuste to znovu a lépe. :) </br>";
            return false;
        }
        if ((pathinfo(basename($_FILES["compressed"]["name"]), PATHINFO_EXTENSION) != "pdf") && $GLOBALS['uploading_comp'] == true) {
            $GLOBALS['message'] .= "Kompresovaný sken není PDF. Zkuste to znovu a lépe. :) </br>";
            return false;
        }
        if ((pathinfo(basename($_FILES["gen"]["name"]), PATHINFO_EXTENSION) != "pdf") && $GLOBALS['uploading_gen'] == true) {
            $GLOBALS['message'] .= "Generovaný soubor není PDF. Zkuste to znovu a lépe. :) </br>";
            return false;
        }
        return true;
    }

//TODO: backup old files
function uploadFiles($target_file_original, $target_file_compressed, $target_file_gen, $target_file_chordpro)
{
    
    if($GLOBALS['updating'] == true){
            //Rename files to new artist and title
            if($GLOBALS['updating_artist']  == true || $GLOBALS['updating_title'] == true){
                rename($GLOBALS['old_target_file_original'], $GLOBALS['target_file_original']);
                rename($GLOBALS['old_target_file_compressed'], $GLOBALS['target_file_compressed']);

                //Try to update only when files exist
                if(file_exists($GLOBALS['old_target_file_gen'])){
                    rename($GLOBALS['old_target_file_gen'], $GLOBALS['target_file_gen']);
                }
                if(file_exists($GLOBALS['old_target_file_chordpro'])){
                    rename($GLOBALS['old_target_file_chordpro'], $GLOBALS['target_file_chordpro']);
                }
            }
    }

    //Try to upload files only if they are provided, backup old files
    if($GLOBALS['uploading_sken'] == true){
        if(file_exists($target_file_original)){
            rename($target_file_original, microtime() . "-" . $target_file_original);
        }

        if(!move_uploaded_file($_FILES['best']['tmp_name'], $target_file_original)){
            $GLOBALS['message'] .= "Jejda! Došlo k problému při nahrávání originálního skenu. :(<br>";
            return;
        }
    }
    if($GLOBALS['uploading_comp'] == true){
        if(file_exists($target_file_compressed)){
            rename($target_file_compressed, microtime(). "-" . $target_file_compressed );
        }

        if(!move_uploaded_file($_FILES['compressed']['tmp_name'], $target_file_compressed)){
            $GLOBALS['message'] .= "Jejda! Došlo k problému při nahrávání kompresovaného skenu. :(<br>";
            return;
        }
    }
    if ($GLOBALS['uploading_gen'] == true) {
        if(file_exists($target_file_gen)){
            rename($target_file_gen, microtime(). "-" .$target_file_gen);
        }

        if(!move_uploaded_file($_FILES['gen']['tmp_name'], $target_file_gen)){
            $GLOBALS['message'] .= "Jejda! Došlo k problému při nahrávání generovaného PDF. :(<br>";
            return;
        }
    }

    if ($GLOBALS['updating_chordpro'] == true){
        if(file_exists($target_file_chordpro)){
            rename($target_file_chordpro, microtime() . "-" . $target_file_chordpro);
        }

        $chordpro_file = fopen($target_file_chordpro, "w") or die ("Nepodařilo se otevřít databázi!");
        fwrite ($chordpro_file, $_POST['chordpro']);
        fclose ($chordpro_file);
    }
}

function writeIntoDB($db)
    {
        if($GLOBALS['updating'] == true){
            $sql='UPDATE Songs SET Lang=:lang,';
            if ($GLOBALS['uploading_gen'] == true) {
                $sql .= " hasGen=1,";
            }
            
            //Only add when changed
            if($GLOBALS['updating_artist'] == true){
                $sql .= " Artist=:artist,";
            }
            if($GLOBALS['updating_title'] == true){
                $sql .= " Title=:title,";
            }
            if($GLOBALS['updating_date'] == true){
                $sql .= " AddedOn=:addedon,";
            }
            if($GLOBALS['updating_chordpro'] == true){
                $sql .= " hasChordPro = 1";
            }
            //Remove last comma
            $sql = substr($sql, 0, -1);

            //Add condition
            $sql .= " WHERE _id=:id";
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':lang', $GLOBALS['lang'], SQLITE3_TEXT);
            
            //Only bind when changed
            if($GLOBALS['updating_artist'] == true){
                $stmt->bindValue(':artist', $GLOBALS['artist'], SQLITE3_TEXT);
            }
            if($GLOBALS['updating_title'] == true){
                $stmt->bindValue(':title', $GLOBALS['title'], SQLITE3_TEXT);
            }
            if($GLOBALS['updating_date'] == true){
                $stmt->bindValue(':addedon', $GLOBALS['addedOn'], SQLITE3_INTEGER);
            }

            $stmt->bindValue(':id', $_POST['id'], SQLITE3_INTEGER);
            $stmt->execute();
        } else {
            $stmt = $db->prepare('INSERT INTO Songs (Title, Artist, Lang, HasGen, AddedOn, hasChordPro) VALUES (:title, :artist, :lang, :hasgen, :addedon, :haschordpro)');
            $stmt->bindValue(':artist', $GLOBALS['artist'], SQLITE3_TEXT);
            $stmt->bindValue(':title', $GLOBALS['title'], SQLITE3_TEXT);
            $stmt->bindValue(':lang', $GLOBALS['lang'], SQLITE3_TEXT);
            $stmt->bindValue(':addedon', $GLOBALS['addedOn'], SQLITE3_INTEGER);
            if ($GLOBALS['updating_chordpro'] == true) {
                $chordpro = 1;
            } else {
                $chordpro = 0;
            }
            $stmt->bindValue(':haschordpro', $chordpro, SQLITE3_INTEGER);

            if ($GLOBALS['uploading_gen'] == true) {
                $hasGen = 1;
            } else {
                $hasGen = 0;
            }
            $stmt->bindValue(':hasgen', $hasGen, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
    echo $message;