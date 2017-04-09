<?php
    $ini = parse_ini_file('config.ini');
    include('lib/json.php');
  
    use \Simple\json;
    $json = new json();
  
    // Ojects to send
    $object = new stdClass();
    $object->files_location  = $ini['server_root'] . $ini['files_location'];
    $object->database_location = $ini['server_root'] . $ini['database_location'];
    
    // Forge the JSON
    $json->data = $object;
    
    // Send the JSON
    $json->send();
?>