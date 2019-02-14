<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION["email"]);
$page_title="";
$page_subtitle="";
$exist=true;

if(isset($_GET['EventID']))
{
   $dbAccess = new DBAccess();
   $dbOpen = $dbAccess->openDBConnection();
   if($dbOpen)
   {
      $result = $dbAccess->getFEvent($_GET['EventID']);
      if($result && mysqli_num_rows($result)==1)
      {
          $Evento = mysqli_fetch_assoc($result);
          $Evento['orario'] = substr($Evento['orario'], 0,-3);
      }
      else {
        $Evento['Titolo'] = "Evento non trovato";
        $Evento['desc_b'] = "L'evento selezionato non è stato trovato, torna alla pagina
        <a href='eventi.php'>eventi</a> e riprova";
        $exist=false;
      }
   }
   else
   {
      //errore connessione database
      $page_title="Errore di connsessione";
      $page_subtitle="Non è stato possibile ricavare le informazioni, torna alla pagina
      <a href='eventi.php'>eventi</a> e riprova";
   }
}
else
{
   //tentativo di connettersi alla pagina senza get
   	header("Location: eventi.php");
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

   <div id="small_header">
      <img id="img_header" src="img/eventi.jpg" alt="Immagine del banner"/>
      <h1 xml:lang="it">Eventi</h1>
   </div>

   <div id="main">
      <p id="breadcrumb">Ti trovi in: <a href="eventi.php">Eventi</a> / <?php echo $Evento['Titolo']?></p>
      <div id="resize" class="clearfix">Dimensioni testo:
         <div>
            <a href="javascript: setFontSize(1)">1</a>
            <a href="javascript: setFontSize(2)">2</a>
            <a href="javascript: setFontSize(3)">3</a>
            <a href="javascript: setFontSize(4)">4</a>
            <a href="javascript: setFontSize(5)">5</a>
         </div>
      </div>
      <h1 id="title"><?php echo $Evento['Titolo']?></h1>
      <h2 id="subtitle"><?php echo $Evento['desc_b']?></h2>
      <?php
        if($exist)
        {
            echo "<div class=\"container-event\">
                    <img id=\"img_event\" src=\"img/$Evento[img]\" alt=\"$Evento[desc_b]\"/>
                    <div class=\"text-block\">
                      <h3>Orario di inizio</h3>
                      <p>$Evento[orario]</p>
                    </div>
                  </div>
                <p id=\"corpo_main\">$Evento[descrizione]
                </p>";
        }
      ?>
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
