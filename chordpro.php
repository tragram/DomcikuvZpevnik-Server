<?php 
    $page_title="ChordPro";
    $page = "db";
    require_once 'template.php';
?>

<head>
<link rel="stylesheet" type="text/css" href="css/chordpro.css">
</head>
<body>
<div class="container">
    <p id="song"></p>   
</div>
<div id="cheatField" style="display: none;">
    <!--Copy the contents of the file to a hidden div-->
    <?php 
        $chp_file=fopen($_GET['file'], "r") or die("Unable to open file!");
        $chordpro_text = fread($chp_file, filesize($_GET['file']));
        echo htmlspecialchars($chordpro_text);
    ?>
</div>
<script src="lib/chordpro.js"></script>
<script>
    var song = document.getElementById("cheatField").textContent;
    var output = chordPro.toTxt(song);
    document.getElementById("song").innerHTML = '<pre><center>' + output + '</center></pre>';
</script>

</body>
</html> 