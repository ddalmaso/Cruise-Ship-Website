<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION['email']);
$errore="";
$page_title="Ristoranti disponibili";
$page_subtitle="Prenota una cena in uno dei nostri ristoranti, basta premere il tasto per la prenotazione relativo all'orario che preferisci!";
$prenotabile="";
$dbAccess = new DBAccess();
$dbOpen = $dbAccess->openDBConnection();
if($dbOpen)
{
  if(isset($_POST['submit1']))
  {
    if (!isset($_SESSION['email']) || empty($_SESSION['email']))
    {
      //non c'è un username salvato nella session quindi si passa al login
      header("Location: login.php");
      die();
    }
    $turno = "primo";
    $ristorante = $_POST['submit1'];
    $email = $_SESSION['email'];
    $prenotazione = $dbAccess->insertPrenotazione($email,$ristorante,$turno);
  }

  if(isset($_POST['submit2']))
  {
    if (!isset($_SESSION['email']) || empty($_SESSION['email']))
    {
      //non c'è un username salvato nella session quindi si passa al login
      header("Location: login.php");
      die();
    }
    $turno = "secondo";
    $ristorante = $_POST['submit2'];
    $email = $_SESSION['email'];
    $prenotazione = $dbAccess->insertPrenotazione($email,$ristorante,$turno);
  }

  //controllo se i ristoranti sono prenotabili
  $errore = "";
  $prenotabile=false;
  //è stato effettuato il login
  if($login)
  {
    $email = $_SESSION['email'];
    $orarioLimite = "13:00:00";
    //prenotazione già effettuata
    if($dbAccess->checkPrenotazioni($email))
    {
      //ho già effettuato una prenotazione ma è passato l'orario entro cui si possono cambiare prenotazioni
      if (time()>strtotime($orarioLimite))
      {
        $errore = '<p id = "warning">Hai già effettuato una prenotazione per oggi!</p>';
      }
      else
      {
        $errore = '<p id = "warning">Hai già effettuato una prenotazione per oggi! Per cambiarla, visita la pagina <a href="account.php" xml:lang = "en">ACCOUNT</a> ed elimina la precedente prenotazione prima di effettuarne un\'altra. </p>';
      }
    }
    else
    {
      //non è più possibile effettuare una prenotazione
      if(time()>strtotime($orarioLimite))
      {
        $errore = '<p id = "warning">Non si possono effettuare prenotazioni passate le ore 13:00</p>';
      }
      else
      {
        //posso effettuare una prenotazione
        $prenotabile=true;
        $tastiPrenotazione = array();
        for($i=1; $i<6; $i++)
        {
          $tastiPrenotazione[$i] = '<form action="ristoranti.php" method="post">
                                      <button type="submit" value="'.$i.'" name="submit1">Prenota per le 19:00</button>
                                      <button type="submit" value="'.$i.'" name="submit2">Prenota per le 21:00</button>
                                    </form>';
        }
      }
    }
  }
  else
  {
    $errore = '<p id = "warning">Attenzione! Devi effettuare il <a href="login.php" xml:lang = "en">LOGIN</a> per prenotare. </p>';
  }
}
else
{
  $page_title="Servizio prenotazione non disponibile";
  $page_subtitle="Ci scusiamo del disagio e consigliamo di riprovare più tardi.";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Ristoranti - Progetto Crociera</title>
  <meta name="title" content="Crociera" />
  <meta name="description" content="Pagina dei ristoranti disponibili" />
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
             <li class="current_page">Ristoranti</li>
             <li><a href="itinerario.php">Itinerario</a></li>
             <li><a href="recensioni.php">Recensioni</a></li>
             <?php
               if($login) {
                 echo '<li><a href="account.php" xml:lang = "en"><span xml:lang="en">Account</span></a></li>';
               } else {
                 echo '<li><a href="login.php">Accedi</a></li>';
               }
             ?>
          </ul>
      </div>
    </div>
  </div>
  <div id="small_header">
    <img id="img_header" src="img/ristoranti.jpg" alt="Immagine del banner" />
    <h1 xml:lang="it">Ristoranti a bordo</h1>
  </div>
  <div id="main">
    <p id="breadcrumb">Ti trovi in: Ristoranti</p>
    <div id="resize" class="clearfix">Dimensioni testo:
      <div>
          <a href="javascript: setFontSize(1)">1</a>
          <a href="javascript: setFontSize(2)">2</a>
          <a href="javascript: setFontSize(3)">3</a>
          <a href="javascript: setFontSize(4)">4</a>
          <a href="javascript: setFontSize(5)">5</a>
      </div>
    </div>
    <h1 id="title"><?php echo $page_title?></h1>
    <p id="corpo_main"><?php echo $page_subtitle?></p>
    <?php if($errore) echo $errore;?>
    <div id="centerDiv">
      <div class="ristorante">
        <img src="img/rist_ita.jpg" alt="Immagine del ristorante" width="600" height="400"/>
        <p class="nome_ristorante">Grande Italia</p>
        <p class="desc">La vera cucina Italiana</p>
        <?php if($prenotabile) {
            echo $tastiPrenotazione[1];
          } ?>
      </div>
      <div class="ristorante">
        <img src="img/rist_gourmet.jpg" alt="Immagine del ristorante" width="600" height="400"/>
        <p class="nome_ristorante" xml:lang = "fr">Gourmet</p>
        <p class="desc">Piatti unici dei migliori chef del mondo</p>
        <?php if($prenotabile) {
            echo $tastiPrenotazione[2];
          } ?>
      </div>
      <div class="ristorante">
        <img src="img/rist_jap.jpg" alt="Immagine del ristorante" width="600" height="400"/>
        <p class="nome_ristorante">Sapori D'Oriente</p>
        <p class="desc">Scopri i sapori della cucina orientale</p>
        <?php if($prenotabile) {
            echo $tastiPrenotazione[3];
          } ?>
      </div>
      <div class="ristorante">
        <img src="img/rist_pesce.jpg" alt="Immagine del ristorante" width="600" height="400"/>
        <p class="nome_ristorante">Mare Blu</p>
        <p class="desc">La migliore cucina mediterranea</p>
        <?php if($prenotabile) {
            echo $tastiPrenotazione[4];
          } ?>
      </div>
      <div class="ristorante">
        <img src="img/rist_sa.jpg" alt="Immagine del ristorante" width="600" height="400"/>
        <p class="nome_ristorante">Il Carnevale</p>
        <p class="desc">Divertiti con la cucina Sud Americana</p>
        <?php if($prenotabile) {
            echo $tastiPrenotazione[5];
          } ?>
      </div>
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
