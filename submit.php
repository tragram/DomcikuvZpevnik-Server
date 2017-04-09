<?php
include("lib/password_protect.php");

$page_title="Submit";
$page = "db";
require_once 'template.php';
require_once 'song.php';

//default for a new submission
$title="Název";
$artist="Interpret";
$added_on="např. '1705' pro květen 2017";
$language="CZECH";
$languagesArray = array("CZECH" => "Čeština", "ENGLISH" => "Angličtina", "SPANISH" => "Španělština", "SLOVAK" => "Slovenština", "OTHER" => "Ostatní");
$updating = false;
$chordpro_text="";
$delete_btn = "";


//Runs if we are editing a record
if(isset($_GET['id'])){
  $GLOBALS['updating'] = true; 
  $id = $_GET['id'];
  $db = new SQLite3($ini['database_location']);
  $sql = 'SELECT * FROM Songs WHERE _id=:id LIMIT 1';
  $stmt = $db->prepare($sql);
  $stmt->bindValue(':id', $id);
  $result=$stmt->execute();
  if ($result != null) {
      $row = $result->fetchArray(SQLITE3_ASSOC);
      $song = new Song($row['_id'], $row['Title'], $row['Artist'], $row['hasGen'], $row['AddedOn'], $row['Lang']);
      $title=$song->getTitle();
      $artist=$song->getArtist();
      $added_on=$song->getDateAdded();
      $language=$song->getLanguage();
      if(file_exists($song->getChordProURL())){
        $chp_file=fopen($song->getChordProURL(), "r") or die("Unable to open file!");
        $chordpro_text = fread($chp_file, filesize($song->getChordProURL()));
      }
  }
}

//Generate the language selector with the correct option pre-selected
function generateSelect($options, $optionToSelect) {
    foreach ($options as $english => $czech) {
      $html .= "<option value=\"$english\"";
        if($english == $optionToSelect){
            $html.= " selected=\"selected\"";
        }
        $html .= ">".$czech."</option>";
    }
    return $html;
}

$delete_btn;
if($updating){
  $delete_btn = 
    "<form action=\"delete.php\" method=\"post\">
      <div class=\"row\">
        <div class=\"col-sm-1 col-centered\">
            <button class=\"btn btn-danger\" name=\"deleteId\" value=\"$id\">VYMAZAT TENTO ZÁZNAM</button>
        </div>
      </div>
    </form>";
}
?>

<div class="container">
    <form class="form-horizontal" _lpchecked="1" action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="updating" value="<?php echo $updating;?>">
    <input type="hidden" name="id" value="<?php echo $id;?>">
      <fieldset>
        <div class="container">
            <div class="well" style="padding: 20px">
                <legend>Zadejte informace o písni</legend>
                <div class="form-group">
                  <div class="form-group">
                      <div class="col-sm-11 col-centered">
                        <label class="control-label">Název:</label>
                        <input type="text" class="form-control" name="inputTitle" placeholder="<?php echo $title;?>" style="cursor: auto;">
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-sm-11 col-centered">
                        <label class="control-label">Interpret:</label>
                        <input type="text" class="form-control" name="inputArtist" placeholder="<?php echo $artist;?>" style="cursor: auto;">
                      </div>
                  </div>

                  <div class="form-group">
                      <div class="col-sm-11 col-centered">
                        <label class="control-label">Datum přidání:</label>
                        <input type="text" class="form-control" name="inputDate" placeholder="<?php echo $added_on;?>" style="cursor: auto;">
                      </div>
                  </div>

                <div class="form-group">
                  <div class="col-sm-11  col-centered">
                    <label class="control-label">Vyberte jazyk:</label>
                    <select class="form-control" name="inputLanguage">
                      <?php echo generateSelect($languagesArray, $language);?>
                    </select>
                  </div>
                </div>
                <div class="col-sm-10 col-centered">
                    <div class="form-group">
                        <label class="control-label">Vyberte originální sken:</label>
                        <input type="file" name="best" id="best">
                        <br>
                        <label class="control-label">Vyberte zkompresovaný sken:</label>
                        <input type="file" name="compressed" id="compressed">
                        <br>
                        <label class="control-label">Vyberte vygenerovaný soubor PDF:</label>
                        <input type="file" name="gen" id="gen">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-11 col-centered">
                        <label class="control-label">Vložte text ve formátu ChordPro:</label>
                        <textarea class="form-control" rows="3" name="chordpro"><?php echo htmlspecialchars($chordpro_text); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-11 col-centered">
                     <button type="submit" class="btn btn-primary">Nahrát</button>
                     <button type="reset" class="btn btn-default">Zrušit</button>
                  </div>
                </div>
            </div>
        </div>
      </fieldset>
    </form>
</div>

<?php echo $delete_btn;?>
</body>
</html>