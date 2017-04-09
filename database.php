<?php
    $page_title="Databáze";
    $page = "db";
    require_once 'song.php';
    require_once 'template.php';
    $search_value = "";

    //Making sortBy query safe
    $sortBy = @$_GET['sortBy'];
    if($sortBy != "Artist" && $sortBy != "Title" && $sortBy != "AddedOn"){
        $sortBy = "Title";
    }

    function generateSortBy(){
        foreach (array("Title" => "Názvu", "Artist" => "Interpreta", "AddedOn" => "Data přidání") as $english => $czech){
            echo "<option value=\"$english\"";
            if ($english == $GLOBALS['sortBy']){echo " selected";}
            echo  ">". $czech . "</option>";
        } 
    }

    $ascDesc = @$_GET['ascDesc'];
    if($ascDesc!="asc" && $ascDesc!="desc"){
        $ascDesc="asc";
    }

    function generateAscDesc(){
        echo "<option value=\"desc\"";
        if($GLOBALS['ascDesc'] == "desc"){echo " selected";}
        echo ">Sestupně</option>";

        echo "<option value=\"asc\"";
        if($GLOBALS['ascDesc'] == "asc"){echo " selected";}
        echo ">Vzestupně</option>";
    }

    function queryDB($query = null)
    {
        $db = new SQLite3($GLOBALS['ini']['database_location']);
        $sql = 'SELECT * FROM Songs ';

        if (!empty($_GET)) {
            $languages = "";
            if (isset($_GET['czech'])) {
                if ($languages != "") {
                    $languages .= " OR Lang=";
                }
                $languages .= "'CZECH'";
            }
            if (isset($_GET['english'])) {
                if ($languages != "") {
                    $languages .= " OR Lang=";
                }
                $languages .= "'ENGLISH'";
            }
            if (isset($_GET['slovak'])) {
                if ($languages != "") {
                    $languages .= " OR Lang=";
                }
                $languages .= "'SLOVAK'";
            }
            if (isset($_GET['spanish'])) {
                if ($languages != "") {
                    $languages .= " OR Lang=";
                }
                $languages .= "'SPANISH'";
            }
            if (isset($_GET['other'])) {
                if ($languages != "") {
                    $languages .= " OR Lang=";
                }
                $languages .= "'OTHER'";
            }
            if ($languages != "" && $languages != "'CZECH' OR Lang='SPANISH' OR Lang='ENGLISH' OR Lang='SLOVAK' OR Lang='OTHER'") {
                $sql .= "WHERE (Lang=" . $languages . ")";
            }
        }

        @$query = $_GET['query'];
        if ($query == null) {
            $sql .= ' ORDER BY ' . $GLOBALS['sortBy'] . " " . $GLOBALS['ascDesc'];
            $result = $db->query($sql);
        } else {
            $query = htmlspecialchars($query);
            $GLOBALS['search_value'] = "value=\"$query\"";
            $sql .= ' AND (Title LIKE :query OR Artist LIKE :query) ORDER BY '. $GLOBALS['sortBy'] . ' ' . $GLOBALS['ascDesc'];
            //echo $sql;
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':query', '%' . $query . '%', SQLITE3_TEXT);
            $result = $stmt->execute();
        }
        if ($result != null) {
            $i = 0;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $songs[$i] = new Song($row['_id'], $row['Title'], $row['Artist'], $row['hasGen'], $row['AddedOn'], $row['Lang']);
                $i++;
            }
        }
        if (!empty($songs)){
            return $songs;
        } else
            return null;
    }

    $czech = @$_GET["czech"];
    $english = @$_GET["english"];
    $spanish = @$_GET["spanish"];
    $slovak = @$_GET["slovak"];
    $other = @$_GET["other"];

    //On first page load, we want to start with all langauges checked
    if (empty($_GET)) {
        $czech = "checked";
        $spanish = "checked";
        $english = "checked";
        $slovak = "checked";
        $other = "checked";
    }

    $songs = queryDB();
    $page_title = "Domčíkův Zpěvník";
?>
<form class="form-horizontal" action="database.php" method="GET">
    <div class="container">
        <div class="search-and-add-song-group">
            <div class="song-search-group">
                <input type="text" class="song-search-input" placeholder="Název/Interpret" name="query">
                <button class="song-search-button light-gray-button" type="submit">Hledat</button>
            </div>
            <div class="add-song-button-group">
                <a href="http://elitanaroda.org/zpevnik/Zpevnik/submit.php">
                    <button class="light-gray-button">Přidat písničku</button>
                </a>
            </div>
        </div>
        <div class="checkbox checkbox-inline">
            <input type="checkbox" id="czech" name="czech" value="checked" <?php echo $czech; ?>>
            <label for="czech"> Čeština </label>
        </div>
        <div class="checkbox checkbox-inline">
            <input type="checkbox" id="english" name="english" value="checked" <?php echo $english; ?>>
            <label for="english"> Angličtina </label>
        </div>
        <div class="checkbox checkbox-inline">
            <input type="checkbox" id="spanish" name="spanish" value="checked" <?php echo $spanish; ?>>
            <label for="spanish"> Španělština </label>
        </div>
        <div class="checkbox checkbox-inline">
            <input type="checkbox" id="slovak" name="slovak" value="checked" <?php echo $slovak; ?>>
            <label for="slovak"> Slovenština </label>
        </div>
        <div class="checkbox checkbox-inline">
            <input type="checkbox" id="other" name="other" value="checked" <?php echo $other; ?>>
            <label for="other"> Ostatní </label>
        </div>
        <br>
        <div style="margin-top: 10px;">
            <label>Seřadit podle:</label>
            <select name="sortBy">
                <?php generateSortBy();?>
            </select>
            <select name="ascDesc">
                <?php generateAscDesc();?>
            </select>
        </div>
    </div>
</form>
<?php
    if($songs != null){ 
        echo '
<div class="table-responsive">
    <div class="container">
<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Název</th>
        <th>Interpret</th>
        <th>Jazyk</th>
        <th>Přidáno (YYMM)</th>
        <th>Generované</th>
        <th>Originální</th>
        <th>Kompresované</th>
        <th>ChordPro</th>
        <th></th>
    </tr>
    </thead>';

        foreach ($songs as $song) {
            ?>
            <tr>
                <td><?php echo $song->getTitle(); ?></td>
                <td><?php echo $song->getArtist(); ?></td>
                <td><?php echo $song->getLanguageCzech(); ?></td>
                <td><?php echo $song->getDateAdded(); ?></td>
                <td><?php echo $song->getGenLink(); ?></td>
                <td><?php echo $song->getSkenButton(); ?></td>
                <td><?php echo $song->getCompButton(); ?></td>
                <td><?php echo $song->getChordProButton(); ?></td>
                <td>
                    <a href="submit.php?id=<?php echo $song->getId()?>" class="btn btn-primary btn-xs">Upravit</a>
                </td>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<div class='container'><h1>S lítostí vám musím oznámit, že žádný záznam nevyhovuje zadání. :(</h1></div>";
    }
    echo '
</table>
</div>
</div>';
?>
</body>
</html>