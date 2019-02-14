<?php
  session_start();
  $login = isset($_SESSION["email"]);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Itinerario - Progetto Crociera</title>
   <meta name="title" content="Crociera" />
   <meta name="description" content="Pagina dell'itinerario della crociera" />
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
             <li class="current_page">Itinerario</li>
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
     <img id="img_header" src="img/route.jpg" alt="Immagine del banner" />
     <h1 xml:lang="it">Itinerario</h1>
  </div>

  <div id="main">
     <p id="breadcrumb">Ti trovi in: Itinerario</p>
     <div id="resize" class="clearfix">Dimensioni testo:
        <div>
           <a href="javascript: setFontSize(1)">1</a>
           <a href="javascript: setFontSize(2)">2</a>
           <a href="javascript: setFontSize(3)">3</a>
           <a href="javascript: setFontSize(4)">4</a>
           <a href="javascript: setFontSize(5)">5</a>
        </div>
     </div>
     <h1 id="title">Itinerario della Crociera</h1>
     <div id="centerTable">
        <table title="Primo giorno">
           <tr>
              <th></th>
              <th scope="col">Primo giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Napoli</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>14:00</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>20:00</td>
           </tr>
        </table>

        <table title="Secondo giorno">
           <tr>
              <th></th>
              <th scope="col">Secondo giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Catania</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>13:00</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>20:00</td>
           </tr>
        </table>

        <table title="Terzo giorno">
           <tr>
              <th></th>
              <th scope="col">Terzo giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>La Valletta</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>8:00</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>15:00</td>
           </tr>
        </table>

        <table title="Quarto giorno">
           <tr>
              <th></th>
              <th scope="col">Quarto giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>navigazione</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>navigazione</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>navigazione</td>
           </tr>
        </table>

        <table title="Quinto giorno">
           <tr>
              <th></th>
              <th scope="col">Quinto giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Barcellona</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>9:00</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>17:00</td>
           </tr>
        </table>

        <table title="Sesto giorno">
           <tr>
              <th></th>
              <th scope="col">Sesto giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Marsiglia</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>9:00</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>17:00</td>
           </tr>
        </table>

        <table title="Settimo giorno">
           <tr>
              <th></th>
              <th scope="col">Settimo giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Savona</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>8:30</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>17:30</td>
           </tr>
        </table>

        <table title="Ottavo giorno">
           <tr>
              <th></th>
              <th scope="col">Ottavo giorno</th>
           </tr>
           <tr>
              <th scope="row">Tappa</th>
              <td>Napoli</td>
           </tr>
           <tr>
              <th scope="row">Arrivo</th>
              <td>13:30</td>
           </tr>
           <tr>
              <th scope="row">Partenza</th>
              <td>20:00</td>
           </tr>
        </table>
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
