<?php
    $ini = parse_ini_file('config.ini');
    include('lib/json.php');
  
    use \Simple\json;
    $json = new json();
  
    //Take the config.ini info and write them into an object for the Android client to use
    $object = new stdClass();
    $object->files_location  = $ini['server_root'] . $ini['files_location'];
    $object->database_location = $ini['server_root'] . $ini['database_location'];
    
    // Forge and send the JSON
    $json->data = $object;
    $json->send();
?>