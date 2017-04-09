<?php
include('lib/password_protect.php');
$page_title="Smazat píseň";
$page = "db";
require_once 'template.php';
require_once 'makeTextNiceAgain.php';

//this wont delete the files so as not to lose them by accident/an attack
$id= filter_input(INPUT_POST, 'deleteId', FILTER_SANITIZE_NUMBER_INT);
$message;

//first we log the song in a file to be able to recover it easier
$db = new SQLite3($ini['database_location']);
$sql = 'SELECT * FROM Songs WHERE _id=:id LIMIT 1';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id);
$result=$stmt->execute();
if ($result != null) {
    $row = $result->fetchArray(SQLITE3_ASSOC);
    $old_song = $row['_id'] . "-" . $row['Title']. "-"  . $row['Artist']. "-"  . $row['hasGen']. "-" . $row['AddedOn']. "-"  . $row['Lang'];
    file_put_contents('delete_log.txt', $old_song . "\n", FILE_APPEND);

    $basename = makeTextNiceAgain($row['Artist']) . "_" . makeTextNiceAgain($row['Title']);
    rename($ini['files_location'] . $basename . "-sken.pdf", $ini['files_location'] ."_"  . $basename . "-sken_deleted.pdf");
    rename($ini['files_location'] . $basename . "-comp.pdf", $ini['files_location'] ."_"  . $basename . "-comp_deleted.pdf");

    //Try to update only when files exist
    if(file_exists($basename . "-gen.pdf")){
        rename($ini['files_location'] ."_"  . $basename. "-gen.pdf", $ini['files_location'] ."_"  . $basename . "-gen_deleted.pdf");
    }
    if(file_exists($basename . "-chordpro.txt")){
        rename($ini['files_location'] ."_"  . $basename . "-chordpro.txt", $ini['files_location'] ."_"  . $basename . "-chordpro_deleted.txt");
    }
} else {
    $message .= "Chyba s databází. Že by špatné id písně?";
}

//now delete it
$stmt = $db->prepare('DELETE FROM Songs WHERE _id = :id');
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$stmt->execute();

if(empty($message)){
    $message = "Úspěch!";
}
?>
<h1>
<?php echo $message;?>
</h1>