<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION["email"]);

$dbAccess = new DBAccess();
$dbOpen = $dbAccess->openDBConnection();
$oresoult="";
$Event_array = false;
$Tag="";
$Mat=$Pom=$Ser=false;
$result ="";
$errore=false;
if($dbOpen)
{
   if(isset($_GET['submit']))
   {
      $Tag=$_GET['tag'];
      if(isset($_GET['mat']))
      $Mat=TRUE;
      if(isset($_GET['pom']))
      $Pom=TRUE;
      if(isset($_GET['ser']))
      $Ser=TRUE;
      $result = $dbAccess->getSpecEventi($Tag,$Mat,$Pom,$Ser);
   }
   else
   {
     //nessun filtro rchiesto
      $result = $dbAccess->getEventi();
   }
   if($result && mysqli_num_rows($result)>=1)
   {
      $Event_array = array();
      $i=0;
      while ($row = mysqli_fetch_assoc($result))
      {
         $modifica="";
         if((isset($_SESSION["ruolo"]) && $_SESSION["ruolo"]=="amministratore"))
         {
            $modifica='<form method="get" action="edit_event.php">
                      <div><button class="edit_btn" title="Modifica '.$row["Titolo"].'" name="Modifica" type="submit" value="'.$row["Id"].'" /></div>
                      </form>';
         }
         $Event_array[$i]= "<div class=\"event_cards\">
         <img src=\"img/$row[img]\" alt=\"Immagine evento\"/>
         <h2>$row[Titolo]</h2>
         <p>$row[desc_b]</p>
         <form method=\"get\" action=\"focused_event.php\">
          <div><button name=\"EventID\" type=\"submit\" value=\"$row[Id]\" title=\"$row[Titolo]\">Scopri di più</button></div>
         </form>
         $modifica
         </div>";
         $i++;
      }
      $Event_array[$i] = false;
   }
   else if(mysqli_num_rows($result)<1)
   {
     //query con 0 risultati
      $oresoult="Nessun risultato";
   }
   else
   {
     //errore di connessione in risposta dal DB
     $oresoult="Qualcosa è andato storto, la preghiamo di riprovare";
   }
   $result=$dbAccess->getTags();
   $i=0;
   if($result && mysqli_num_rows($result)>=1)
   {
      $Tags_array = array();
      while ($row = mysqli_fetch_assoc($result))
      {
        if(isset($_GET['tag'])&&$row['Nome']==$_GET['tag'])
          $Tags_array[$i]= "<option value=\"$row[Nome]\" selected>$row[Nome]</option>";
        else
          $Tags_array[$i]= "<option value=\"$row[Nome]\">$row[Nome]</option>";
        $i++;
      }
   }
   $Tags_array[$i] = false;
}
else
{
  //errore di connessione al DB
   $oresoult="Servizio non disponibile, ci scusiamo del disagio.";
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
               <li class="current_page">Eventi</li>
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
      <img id="img_header" src="img/eventi.jpg" alt="Immagine del banner" />
      <h1 xml:lang="it">Eventi</h1>
   </div>

   <div id="main">
      <p id="breadcrumb">Ti trovi in: Eventi</p>
      <div id="resize" class="clearfix">Dimensioni testo:
         <div>
            <a href="javascript: setFontSize(1)">1</a>
            <a href="javascript: setFontSize(2)">2</a>
            <a href="javascript: setFontSize(3)">3</a>
            <a href="javascript: setFontSize(4)">4</a>
            <a href="javascript: setFontSize(5)">5</a>
         </div>
      </div>
      <div class="row">
         <div  id="filter_bar" class="column left-bar">
            <form method="get" action="eventi.php">
               <div>
                  <label for="tag_select">Filtra eventi per:</label>
                  <select id="tag_select" name="tag">
                     <option value="all">qualsiasi</option>
                     <?php
                     $i=0;
                     while($Tags_array[$i])
                     {
                        echo $Tags_array[$i];
                        $i++;
                     }?>
                  </select>
               </div>
               <div>
                  <p>Fascia oraria:</p>
                  <label>
                     <input type="checkbox" name="mat" <?php if(isset($Mat)&&$Mat) echo 'checked'?>/> Mattino(8-12)
                  </label>
                  <label>
                     <input type="checkbox" name="pom" <?php if(isset($Pom)&&$Pom) echo 'checked'?>/> Pomeriggio(12-18)
                  </label>
                  <label>
                     <input type="checkbox" name="ser" <?php if(isset($Ser)&&$Ser) echo 'checked'?>/> Sera(18-24)
                  </label>
                 <input type="submit" name="submit" value="Cerca" <?php if($errore) echo "disabled=\"disabled\""?>/>
               </div>
            </form>
            <?php if((isset($_SESSION["ruolo"]) && $_SESSION["ruolo"]=="amministratore"))
            {
               echo '<form method="get" action="edit_event.php">';
               echo "<div><button  type=\"submit\" class=\"new_btn\" name=\"new\"";
               if($errore)
                echo "disabled=\"disabled\"";
               echo ">Crea nuovo evento</button></div>";
               echo '</form>';
            } ?>
         </div>
         <div class="column right-event">
            <div id="events">
                  <?php
                     if(isset($oresoult)&&$oresoult!="") {echo "<h2 id=\"\">$oresoult</h2>";}
                     $i=0;
                     while($Event_array[$i])
                     {
                        echo $Event_array[$i];
                        $i++;
                     }
                  ?>
            </div>
         </div>
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
