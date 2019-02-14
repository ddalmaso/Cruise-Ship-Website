<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION["email"]);
$page_title="Lasciaci la tua recensione!";
$page_subtitle="La tua opinione è importante per noi e ti invitiamo a condividerla. Conoscere la tua opinione ci consentirà di offrirti un'esperienza ancora più straordinaria, per una prossima crociera davvero eccezionale!";
$titolo="";
$descrizione="";
$dbAccess = new DBAccess();
$dbOpen = $dbAccess->openDBConnection();
$punteggio="";
$cerror=false;
$htmlRecensioni=array();
if($dbOpen)
{
  if(isset($_POST['submit']))
  {
    //controllo che tutti i campi siano stati riempiti
    if(!empty($_POST['title']) && !empty($_POST['rating']) && !empty($_POST['review']))
    {
      $nome = $_SESSION['nome'];
      $titolo = $_POST['title'];
      $punteggio = $_POST['rating'];
      $descrizione = $_POST['review'];
      $dbAccess->insertRecensione($nome, $titolo, $punteggio, $descrizione);

      //cancello i cookie
      if(isset($_COOKIE['title_review'])){
        unset($_COOKIE['title_review']);
        setcookie('title_review','',time()-3600,'/');
      }
      if(isset($_COOKIE['star'])){
        unset($_COOKIE['star']);
        setcookie('star','',time()-3600,'/');
      }
      if(isset($_COOKIE['review'])){
        unset($_COOKIE['review']);
        setcookie('review','',time()-3600,'/');
      }
    }
    else
      $errore = true;
  }

  $erroreRecensioni = "";
  $result = $dbAccess->getRecensioni(4);
  if(!$result)
  {
    $erroreRecensioni="<div><p>Nessuna recensione trovata</p></div>";
  }
  else
  {

    $htmlRecensioni = array();
    $j=0;
    while($row = mysqli_fetch_assoc($result))
    {
      $timestamp = strtotime($row["dataAttuale"]);
      $data = date('d/m/Y', $timestamp);
      $stelle =  $row['Punteggio'];

      $htmlStelle = "";
      for ($i = 0; $i < $stelle; $i++)
      {
        $htmlStelle = $htmlStelle.'<span class="iconaStella"></span>';
      }

      if($stelle > 1)
        $testoStelle = $stelle." stelle";
      else
        $testoStelle = $stelle." stella";


      $htmlRecensioni[$j] = '<div class="box-review">
          <div class="clearfix">
            <div class="fixedRating">
              '.$htmlStelle.'
              <span class="visuallyhidden">'.$testoStelle.'</span>
            </div>
          </div>
          <h1 class="title-review">'.$row["Titolo"].'</h1>
          <p class="data-review">'.$data.'</p>
          <p class="descr-review">'.$row["Descrizione"].'</p>
          <p class="author-review">'.$row["Nome"].'</p>
        </div>';

        $j++;
     }
  }
  $titolo="";
  if(isset($_COOKIE['title_review']))
    $titolo=$_COOKIE['title_review'];

  $punteggio = 5;
  if(isset($_COOKIE['star']))
    $punteggio=$_COOKIE['star'];

  $descrizione = "";
  if(isset($_COOKIE['review']))
    $descrizione=$_COOKIE['review'];

}
else
{
  $page_title="Servizio non disponibile";
  $page_subtitle="Ci scusiamo del disagio e consigliamo di riprovare più tardi.";
  $cerror=true;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Recensioni - Progetto Crociera</title>
   <meta name="title" content="Crociera" />
   <meta name="description" content="Pagina con le opinioni degli utenti" />
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
   <script type="text/javascript" src="js/saveField.js"></script>
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
               <li class="current_page">Recensioni</li>
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
			<p id="breadcrumb">Ti trovi in: Recensioni</p>
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
      <div class="clearfix">
         <div class="column left">
               <h1 id="title"><?php echo $page_title?></h1>
               <p><?php echo $page_subtitle?></p>
               <form id="contact" method="post" action="recensioni.php" onsubmit="return checkReview()">
                 <div>
                  <label for="title_review">Titolo recensione:</label><span class="asterisk"></span>
                  <input type="text" name="title" id="title_review"  onblur="saveRating()" value="<?php echo $titolo?>"/>
                </div>
                  <div>
                     <fieldset class="rating">
                        <legend>Punteggio: <span class="asterisk"></span></legend>
                        <input type="radio" id="star1" class="visuallyhidden" name="rating" value="1"  onblur="saveRating()"<?php if($punteggio==1)echo "checked=\"checked\""?>/>
                        <label for="star1"><span class="iconaStella"></span><span class="visuallyhidden">1 stella</span></label>
                        <input type="radio" id="star2" class="visuallyhidden" name="rating" value="2"  onblur="saveRating()"<?php if($punteggio==2)echo "checked=\"checked\""?>/>
                        <label for="star2"><span class="iconaStella"></span><span class="visuallyhidden">2 stelle</span></label>
                        <input type="radio" id="star3" class="visuallyhidden" name="rating" value="3"  onblur="saveRating()"<?php if($punteggio==3)echo "checked=\"checked\""?>/>
                        <label for="star3"><span class="iconaStella"></span><span class="visuallyhidden">3 stelle</span></label>
                        <input type="radio" id="star4" class="visuallyhidden" name="rating" value="4"  onblur="saveRating()"<?php if($punteggio==4)echo "checked=\"checked\""?>/>
                        <label for="star4"><span class="iconaStella"></span><span class="visuallyhidden">4 stelle</span></label>
                        <input type="radio" id="star5" class="visuallyhidden" name="rating" value="5"  onblur="saveRating()"<?php if($punteggio==5)echo "checked=\"checked\""?>/>
                        <label for="star5"><span class="iconaStella"></span><span class="visuallyhidden">5 stelle</span></label>
                     </fieldset>
                  </div>
                  <div>
                    <label for="review">Descrizione recensione:</label><span class="asterisk"></span>
                    <textarea name="review" id="review" rows="8" cols="60"  onblur="saveRating()"><?php echo $descrizione?></textarea>
                  </div>
									<div><?php
                     if(isset($errore) && $errore) {
                       echo '<p class="asterisk">Riempi tutti i campi.</p>';
                     }
										 if($login) {
											 echo '<p>Stai lasciando una recensione come '.$_SESSION["nome"].'.</p>';
										 }
										 else {
											 echo '<p>Per lasciare una recensione effettua il <a href="login.php" xml:lang = "en">login</a></p>';
										 }
									?></div>
                  <div>
                    <input type="submit" name="submit" value="Invia" <?php if(!$login||$cerror){ echo 'disabled="disabled"';}?>/>
                  </div>
               </form>
         </div>
         <div class="column right">
           <p id='lastReview'>Le ultime quattro recensioni ricevute</p>
            <?php
             if(isset($erroreRecensioni) && $erroreRecensioni!="")
             {
                 echo $erroreRecensioni;
             }
             else
             {
                foreach ($htmlRecensioni as $recensione)
                  echo $recensione;
             }
	          ?>
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
