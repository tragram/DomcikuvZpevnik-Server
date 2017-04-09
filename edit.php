<?php
include('lib/password_protect.php');
$page='db';
$page_title='Upravit';
require_once 'template.php';
?>

<div class="well">
<form class="form-horizontal" _lpchecked="1" action="upload.php" method="post" enctype="multipart/form-data">
  <fieldset>
    <legend>Submit song info</legend>
    <div class="form-group">
      <div class="col-lg-10">
        <label class="col-lg-2 control-label">Název</label>
        <input type="text" class="form-control" name="inputTitle" placeholder="Název" style="cursor: auto;">
      </div>
      <div class="col-lg-10">
        <label class="col-lg-2 control-label">Interpret</label>
        <input type="text" class="form-control" name="inputArtist" placeholder="Interpret" style="cursor: auto;">
      </div>
      <div class="col-lg-10">
        <label class="col-lg-2 control-label">Datum přidáno (YYMM)</label>
        <input type="text" class="form-control" name="inputDate" placeholder="např. '1705' pro květen 2017" style="cursor: auto;">
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-10">
        <label class="col-lg-2 control-label">Vyberte jazyk</label>
        <select class="form-control" name="inputLanguage">
        <option value="CZECH">Čeština</option>
        <option value="ENGLISH">Angličtina</option>
        <option value="SPANISH">Španělština</option>
        <option value="SLOVAK">Slovenština</option>
        <option value="OTHER">Ostatní</option>
        </select>
      </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Vyberte sken s nejvyšší kvalitou:</label>
        <input type="file" name="best" id="best">

        <label class="col-lg-2 control-label">Vyberte zkompresovaný sken:</label>
        <input type="file" name="compressed" id="compressed">
        
        <label class="col-lg-2 control-label">Vyberte vygenerované PDF (nepovinné):</label>
        <input type="file" name="gen" id="gen">
    </div>
    <div class="form-group">
      <div class="col-lg-10 col-lg-offset-2">
         <button type="submit" class="btn btn-primary">Nahrát</button>
         <button type="reset" class="btn btn-default">Zrušit</button>
      </div>
    </div>
  </fieldset>
</form>
</div>
</body>
</html>