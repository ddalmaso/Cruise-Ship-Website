<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION["email"]);
if(isset($_SESSION["ruolo"]) && $_SESSION["ruolo"]=="amministratore")
{


	$dbAccess = new DBAccess();
	$dbOpen = $dbAccess->openDBConnection();
	$page_title="";
	$page_subtitle = "";

	if($dbOpen)
	{

		if(isset($_POST['cancella']))
		{
			$result=$dbAccess->deleteEvento($_POST['id']);
			if($result)
			{
				$page_title="Evento eliminato";
				$page_subtitle="torna alla pagina
				<a href='eventi.php'>eventi</a>";
				if(isset($_POST['img'])&&$_POST['img']!="non_disponibile.jpg")
				{
					if(file_exists('img/'.$_POST['img']))
						unlink('img/'.$_POST['img']);
					else
						$page_title=$page_title." ma immagine da eliminare non trovata";
				}
			}
			else
			{
				//Query non andata a buon fine
				$page_title="Evento non eliminato";
				$page_subtitle="Evento non esistente o già eliminato, torna alla pagina
				<a href='eventi.php'>eventi</a>";
			}
		}
		else
		{
			//L'amministratore è arrivato alla pagina senza premere cancella
			//ritorno alla pagina eventi
			//header("Location: eventi.php");
			echo "masdmmsd";
		}
	}
	else
	{
		//errore di connessione al database
		$page_title = "Servizio non disponibile";
		$page_subtitle = "Si è riscontrato un problema durante la modifica di questo evento, ci scusiamo del disagio. Torna alla pagina
		<a href='eventi.php'>eventi</a>";
	}

}
else {
	//L'utente non ha effettuato il login oppure non è amministratore
	//ritorno alla pagina eventi
	header("Location: eventi.php");
	die;

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Progetto Crociera</title>
	<meta name="title" content="Crociera" />
	<meta name="description" content="Pagina home della nave" />
	<meta name="keywords" content="" />
	<meta name="language" content="italian it" />
	<meta name="author" content="Gruppo" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="css/style.css" media="screen" />
	<link type="text/css" rel="stylesheet" href="css/max-768px.css" media="screen and (max-width:768px)" />
	<link type="text/css" rel="stylesheet" href="css/max-480px.css" media="handheld, screen and (max-width:480px), only screen and (max-device-width:480px)" />
   <link type="text/css" rel="stylesheet" href="css/stampa.css" media="print" />
	<script type="text/javascript" src="js/scrollEffects.js"></script>
	<script type="text/javascript" src="js/setFontSize.js"></script>
</head>

<body>
	<div id="header">
    <div><a href="#main"  id="skipNav" class="visuallyhidden">Salta al contenuto</a></div>
		<img id="img_logo" src="img/ship.png" alt="Logo del sito" />
		<div id="nav">
         <input type="checkbox" id="menu-toggle"/>
         <label for="menu-toggle" id="menu-label"></label>
         <div id="menu-content">
            <ul>
               <li><a href="home.php"><span xml:lang="en">Home</span></a></li>
               <li><a href="eventi.php">Eventi</a></li>
               <li><a href="ristoranti.php">Ristoranti</a></li>
               <li><a href="itinerario.php">Itinerario</a></li>
               <li><a href="recensioni.php">Recensioni</a></li>
               <?php
                 if($login) {
                   echo '<li><a href="account.php"><span xml:lang="en">Account</span></a></li>';
                 } else {
                   echo '<li><a href="login.php">Accedi</a></li>';
                 }
               ?>
            </ul>
         </div>
      </div>
	</div>

	<div id="main">
		<div id="fixBanner">
			<p id="breadcrumb">Ti trovi in: <a href="eventi.php">Eventi</a> / cancella Eventi</p>
			<div id="resize" class="clearfix">Dimensioni testo:
				<div>
					<a href="javascript: setFontSize(1)">1</a>
					<a href="javascript: setFontSize(2)">2</a>
					<a href="javascript: setFontSize(3)">3</a>
					<a href="javascript: setFontSize(4)">4</a>
					<a href="javascript: setFontSize(5)">5</a>
				</div>
			</div>
		</div>
		<div id="container_form">
			<h1 id="title"><?php echo $page_title; ?></h1>
			<h2><?php echo $page_subtitle; ?></h2>
		</div>
		<div id="backtoTop">
			<button onclick="topFunction()" id="scrollBtn" title="Torna in alto">Top</button>
		</div>
	</div>

	<div id="footer">
		<p>© 2019 Nave Crociera TecWeb - Tutti i diritti riservati</p>
		<div>
			<a href="https://www.facebook.com/CostaCrociere"><img class="social" src="img/fb.png" xml:lang = "en" alt="Facebook"/></a>
			<a href="https://twitter.com/costacrociere"><img class="social" src="img/tw.png" xml:lang = "en" alt="Twitter"/></a>
			<a href="https://www.youtube.com/costacrociere"><img class="social" src="img/yt.png" xml:lang = "en" alt="YouTube"/></a>
			<a href="https://www.instagram.com/costacruisesofficial/"><img class="social" src="img/ig.png" xml:lang = "en" alt="Instagram"/></a>
		</div>
	</div>
</body>
</html>
