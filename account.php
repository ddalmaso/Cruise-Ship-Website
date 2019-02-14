
<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;
session_start();
$login = isset($_SESSION["email"]);
$page_title="Il tuo <span xml:lang=\"en\">account";
if(!isset($_SESSION['email']))
{
  header('Location: login.php');
  die;
}
else
{
  $htmlPrenotazioni = array();
  $nome = $_SESSION["nome"];
  $cognome = $_SESSION["cognome"];
  $email = $_SESSION["email"];
  $ruolo = $_SESSION["ruolo"];
  $dbAccess = new DBAccess();
	$dbOpen = $dbAccess->openDBConnection();

  if($dbOpen)
  {

    //se è stato cliccato il bottone annulla cancello la prenotazione di oggi
    if(isset($_POST["submit"]) && $_POST["submit"]=="Annulla")
    {
      $oggi = date("Y-m-d");
      $dbAccess->removePrenotazione($email,$oggi);
    }

    $prenotazioni = $dbAccess->getPrenotazioni($email);
    $stampa = false;
    if(mysqli_num_rows($prenotazioni)>0)
    {
      $orarioLimite = "13:00:00";
      $row = mysqli_fetch_assoc($prenotazioni);
      $ora = ($row["Turno"] == "primo") ? "19:00" : "21:00";
      //controllo se non ho già fatto una prenotazione oggi
      if($row["Data"]!=date("Y-m-d")) {
        $htmlPrenotazioni[0] = '<div class="prenotazione">
                                   <p>Nessuna prenotazione per questa sera</p>';
        //se è entro il tempo limite aggiungo il tasto per effettuare una prenotazione
        if(time()<=strtotime($orarioLimite)) {
        $htmlPrenotazioni[0] = $htmlPrenotazioni[0].'<form action = "ristoranti.php#breadcrumb">
                                     <div><input type="submit" name="submit" value="Prenota ora"/></div>
                                   </form>';
         }

         $htmlPrenotazioni[0] = $htmlPrenotazioni[0].'</div>';
         //la prenotazione non è di oggi quindi dopo devo stamparla
         $stampa = true;
       }
       else //ho fatto una prenotazione oggi
       {
         $htmlPrenotazioni[0] = '<div class="prenotazione">
                                    <p class="dataOggi">Oggi '.$ora.'</p>
                                    <p>"'.$row["Ristorante"].'"</p>';
         //se è entro il tempo limite aggiungo il tasto per cancellare la prenotazione di oggi
         if(time()<=strtotime($orarioLimite))
         {
           $htmlPrenotazioni[0] = $htmlPrenotazioni[0].'
                        <form action = "account.php" method="post">
                          <div><input type="submit" name="submit" value="Annulla"/></div>
                        </form>';
         }
         //chiudo il codice della prenotazione
         $htmlPrenotazioni[0] = $htmlPrenotazioni[0].'</div>';
       }
       $i=1;
       //stampo tutte le prenotazioni rimanenti
       do {
         //se stampa è false ho già stampato la prima riga e non la devo ristampare
         if($stampa) {

         $ora = ($row["Turno"] == "primo") ? "19:00" : "21:00";
         $originalDate = $row["Data"];
         $newDate = date("d-m-Y", strtotime($originalDate));
         $htmlPrenotazioni[$i] = '<div class="prenotazione">
                                    <p class="dataP">'.$newDate.' '.$ora.'</p>
                                    <p>"'.$row["Ristorante"].'"</p>
                                  </div>';
         $i++;
         }
         else {
           //saltato il primo posso ricominciare a stampare
           $stampa = true;
         }
      } while($row = mysqli_fetch_assoc($prenotazioni));
    }
    else //nessuna prenotazione presente nel database
    {
      $htmlPrenotazioni[0] = '<div class="prenotazione">
                                 <p>Nessuna prenotazione per questa sera</p>
                                 <form action = "ristoranti.php#breadcrumb">
                                   <div><input type="submit" value="Prenota ora"/></div>
                                 </form>
                               </div>';
    }
  }
  else
  {
      $page_title="Servizio non disponibile, ci scusiamo del disagio.";
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Progetto Crociera</title>
   <meta name="title" content="Account - Progetto Crociera" />
   <meta name="description" content="Pagina per la gestione dell'accout" />
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
               <li class="current_page"><span xml:lang="en">Account</span></li>
            </ul>
         </div>
      </div>
   </div>

   <div id="main">
     <div id="fixBanner">
       <p id="breadcrumb">Ti trovi in: <span xml:lang="en">Account</span></p>
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
         <h1 id="title"><?php echo $page_title?></span></h1>
         <?php
            echo "<h2>Benvenuto $nome, qui puoi controllare le tue prenotazioni.";
            if(isset($ruolo) && $ruolo=="amministratore") echo '<br/> Visita la pagina <a href="eventi.php">EVENTI</a> per gestire tutti gli eventi della crociera!';
            echo "</h2>";
         ?>
        <div class="prenotazioni">
          <?php
            foreach ($htmlPrenotazioni as $p) {
              echo $p;
            }
          ?>
        </div>
        <p id="logout"><a href="logout.php"><span xml:lang="en">Logout</span></a></p>
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
