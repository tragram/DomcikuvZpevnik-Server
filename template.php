<?php
if (empty($page)){$page='home';}
if (empty($page_title)){$page_title='Domčíkův Zpěvník';}
$ini = parse_ini_file('config.ini');
?>

<head>
    <title> <?php echo $page_title; ?></title>
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <!-- Custom styles for this template -->
    <link href="css/custom.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="favicon.png"/>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#my-navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        <a class="navbar-brand" href="index.php">Domčíkův Zpěvník</a>
      </div>

      <div class="collapse navbar-collapse" id="my-navbar">
        <ul class="nav navbar-nav">
        <!-- Use $page=home for 'Home', db for 'Databáze' and docs for 'Dokumentace'-->
          <li <?php echo ($page == 'home') ? 'class="active"' : '';?>><a href="index.php">Home<span class="sr-only"></span></a></li>
          <li <?php echo ($page == 'db') ? 'class="active"' : '';?>><a href="database.php">Databáze</a></li>
          <li class="dropdown" <?php echo ($page == 'docs') ? 'class="active"' : '';?>>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dokumentace<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="doc/uzivatelska_dokumentace.md.pdf">Uživatelská dokumentace</a></li>
              <li class="divider"></li>
              <li><a href="https://github.com/tragram/DomcikuvZpevnik-Server" target="_blank">Zdrojový kód - server</a></li>
              <li><a href="doc/server_doc.md.pdf" target="_blank">Vývojářská dokumentace serveru - readme</a></li>
              <li class="divider"></li>
              <li><a href="https://github.com/tragram/DomcikuvZpevnik" target="_blank">Zdrojový kód - aplikace</a></li>
              <li><a href="doc/app_doc.md.pdf" target="_blank">Vývojářská dokumentace aplikace - readme</a></li>
              <li><a href="doc/JavaDoc/" target="_blank">Vývojářská dokumentace - aplikace (JavaDoc)</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>