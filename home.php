<?php
  session_start();
  $login = isset($_SESSION["email"]);

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
   <script type="text/javascript" src="js/modalBox.js"></script>
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
               <li class="current_page"><span xml:lang="en">Home</span></li>
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
      <img id="img_header" src="img/cruise.jpg" alt="Immagine del banner" />
      <h1 xml:lang="it">Nave Crociera <span xml:lang="en">TecWeb</span></h1>
   </div>

   <div id="main">
      <p id="breadcrumb">Ti trovi in: Home</p>
      <div id="resize" class="clearfix">Dimensioni testo:
         <div>
            <a href="javascript: setFontSize(1)">1</a>
            <a href="javascript: setFontSize(2)">2</a>
            <a href="javascript: setFontSize(3)">3</a>
            <a href="javascript: setFontSize(4)">4</a>
            <a href="javascript: setFontSize(5)">5</a>
         </div>
      </div>
      <h1 id="title">Crociera <span xml:lang="en">TecWeb</span></h1>
      <h2 id="subtitle">Una lunga scia di emozioni</h2>
      <p id="corpo_main">La nave <span xml:lang="en">TecWeb</span> è dotata di caratteristiche straordinarie che offrono una esperienza perfetta in mare in ogni stagione. A bordo potrai divertirti, sperimentare cene squisite e vivere un intrattenimento straordinario in nuove aree panoramiche, godere di una spettacolare vista mare dal salone di poppa della nave, e di una promenade interna su due piani con un soffitto LED di 480 metri quadrati e di un fantastico parco divertimenti collegato ad un parco acquatico all'aperto. Benvenuto a bordo!</p>
      <div id="ship-facts">
         <div class="item">
            <img class="icon" src="img/company.png" alt="Numero Ospiti" />
            <p class="ship-number">2.826</p>
            <p class="ship-caption">Nr. Ospiti</p>
         </div>
         <div class="item">
            <img class="icon" src="img/crew.png" alt="Numero Equipaggio" />
            <p class="ship-number">934</p>
            <p class="ship-caption">Nr. Equipaggio</p>
         </div>
         <div class="item">
            <img class="icon" src="img/size.png" alt="Lunghezza in metri" />
            <p class="ship-number">294</p>
            <p class="ship-caption">Lunghezza m.</p>
         </div>
      </div>

      <div id="centerDiv">
         <div class="div_img">
            <img class="myImg" src="img/balcone.jpg" onclick="openModal(0)" alt="Balconata"/>
            <p>Entra in sintonia con il mare</p>
         </div>
         <div class="div_img">
            <img class="myImg" src="img/interno.jpg" onclick="openModal(1)" alt="Interno della nave"/>
            <p>Tre ponti sempre pieni di azione, giorno e notte</p>
         </div>
         <div class="div_img">
            <img class="myImg" src="img/sala.jpg" onclick="openModal(2)" alt="Sala ristorante"/>
            <p>Piatti tradizionali e viste mozzafiato</p>
         </div>
         <div class="div_img">
            <img class="myImg" src="img/suite.jpg" onclick="openModal(3)" alt="Suite con camera da letto"/>
            <p xml:lang = "en">Suite spaziose con spazio a sufficienza per ricordi infiniti</p>
         </div>
         <div class="div_img">
            <img class="myImg" src="img/terrazza.jpg" onclick="openModal(4)" alt="Terrazza della piscina"/>
            <p>Rilassati tutto il giorno sulla terrazza della piscina</p>
         </div>
         <div class="div_img">
            <img class="myImg" src="img/vista.jpg" onclick="openModal(5)" alt="Salotto con vista mare"/>
            <p>Viste imbattibili</p>
         </div>
      </div>

      <div id="myModal" class="modal">
         <button class="close" onclick="closeModal()" title="Chiudi immagine">&times;</button>
         <img class="modal-content" id="img01" src="" alt="Immagine all'interno del box" />
         <div id="caption"></div>
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
