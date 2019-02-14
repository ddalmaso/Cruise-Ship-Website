<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;
$page_title="Area Personale";
$page_subtitle="Accedi qui di seguito per prenotare ristoranti, eventi e molto altro!";
$errore=false;
$loginFallito = false;
$dbAccess = new DBAccess();
$dbOpen = $dbAccess->openDBConnection();
if($dbOpen)
{
	if(isset($_POST['submit']))
	{

		$email = $_POST['username'];
		$password = $_POST['password'];
		//echo md5($password);
		$login = $dbAccess->checkLogin($email, $password);
		if($login)
		{
			session_start();
			$datiUtente = $dbAccess->getUtente($email);

			if($datiUtente)
			{
				$_SESSION["id"] = $datiUtente["Id"];
				$_SESSION["email"] = $datiUtente["Email"];
			  $_SESSION["nome"] = $datiUtente["Nome"];
				$_SESSION["cognome"] = $datiUtente["Cognome"];
				$_SESSION["ruolo"] = $datiUtente["Ruolo"];
			}
			header('Location: account.php');
			die;
		}
		else {
			$loginFallito=true;
		}
	}
}
else
{
	$page_title="Servizio non disponibile";
  $page_subtitle="Ci scusiamo del disagio e consigliamo di riprovare più tardi.";
	$errore=true;
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
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta http-equiv="Content-Script-Type" content="text/javascript" />
	<link type="text/css" rel="stylesheet" href="css/style.css" media="screen" />
   <link type="text/css" rel="stylesheet" href="css/max-768px.css" media="screen and (max-width:768px)" />
   <link type="text/css" rel="stylesheet" href="css/max-480px.css" media="handheld, screen and (max-width:480px), only screen and (max-device-width:480px)" />
   <link type="text/css" rel="stylesheet" href="css/stampa.css" media="print" />
	<script type="text/javascript" src="js/scrollEffects.js"></script>
	<script type="text/javascript" src="js/validateForm.js"></script>
	<script type="text/javascript" src="js/setFontSize.js"></script>
</head>

<body>
   <div id="header">
		 <div><a href="#main"  id="skipNav" class="visuallyhidden">Salta al contenuto</a></div>
      <img id="img_logo" src="img/ship.png" alt="Logo del sito" />
		<div id="nav">
         <input type="checkbox" id="menu-toggle"/>
         <label for="menu-toggle" id="menu-label">Apri menù navigazione</label>
         <div id="menu-content">
            <ul>
               <li><a href="home.php"><span xml:lang="en">Home</span></a></li>
               <li><a href="eventi.php">Eventi</a></li>
               <li><a href="ristoranti.php">Ristoranti</a></li>
               <li><a href="itinerario.php">Itinerario</a></li>
               <li><a href="recensioni.php">Recensioni</a></li>
               <li class="current_page">Accedi</li>
            </ul>
         </div>
      </div>
   </div>

   <div id="main">
		<div id="fixBanner">
			<p id="breadcrumb">Ti trovi in: Login</p>
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
         <h1 id="title"><?php echo $page_title?></h1>
         <h2><?php echo $page_subtitle?></h2>
         <form id="login" method="post" action="login.php" onsubmit="return checkLogin()">
					 <div>
						<label for="username" xml:lang = "en">Email:</label><span class="asterisk"></span>
		        <input type="text" id="username" name="username" />

						<label for="password">Password:</label><span class="asterisk"></span>
		        <input type="password" id="password" name="password" />

						<p class="asterisk">
							<?php
								if(!isset($loginFallito) || $loginFallito){
									echo "Le credenziali inserite sono incorrette. Ritenta.";
								}
							?>
						</p>
	          <input type="submit" id="submit" name="submit" value="LOGIN"<?php if($errore) echo "disabled=\"disabled\""?>/>
					</div>
         </form>
      </div>
		<div id="backtoTop">
	   	<button onclick="topFunction()" id="scrollBtn" title="Torna in alto">Top</button>
		</div>
   </div>

	<div id="footer">
      <p>© 2019 Nave Crociera <span xml:lang="en">TecWeb</span> - Tutti i diritti riservati</p>
      <div>
         <a href="https://www.facebook.com/CostaCrociere"><img class="social" src="img/fb.png" xml:lang = "en" alt="Facebook"/></a>
         <a href="https://twitter.com/costacrociere"><img class="social" src="img/tw.png" xml:lang = "en" alt="Twitter"/></a>
         <a href="https://www.youtube.com/costacrociere"><img class="social" src="img/yt.png" xml:lang = "en" alt="YouTube"/></a>
         <a href="https://www.instagram.com/costacruisesofficial/"><img class="social" src="img/ig.png" xml:lang = "en" alt="Instagram"/></a>
      </div>
   </div>

  </body>
</html>
