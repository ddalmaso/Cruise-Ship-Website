<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . "dbaccess.php";
use DB\DBAccess;

session_start();
$login = isset($_SESSION["email"]);

if(isset($_SESSION["ruolo"]) && $_SESSION["ruolo"]=="amministratore")
{
	$dbAccess = new DBAccess();
	$dbOpen = $dbAccess->openDBConnection();

	$errore=false;
	$inserterror=false;
	$id="";
	$page_title="";
	$page_subtitle="";
	$disabled="";
	$el=false;
	$ore = 8;
	$minuti = 0;
	$titolo = "";
	$desc_breve = "";
	$desc_lunga = "";
	$img="non_disponibile.jpg";
	$new=false;
	$i=0;
	$imgerror=false;
	if($dbOpen)
	{
		//arrivo da eventi modifica evento
		if(isset($_GET["Modifica"]))
		{
			$page_title="Modifica Evento";
			$page_subtitle="In questa pagina puoi modificare tutti i contenuti dell'evento selezionato";
			$id = $_GET['Modifica'];
			$result = $dbAccess->getFEvent($id);
			if($result && mysqli_num_rows($result)==1)
			{
				//prendo tutti i valori per popolare il form
				$evento = mysqli_fetch_assoc($result);
				$ore = date("H",strtotime($evento["orario"]));
				$minuti = date("i",strtotime($evento["orario"]));
				$titolo = $evento["Titolo"];
				$desc_breve = $evento["desc_b"];
				$desc_lunga = $evento["descrizione"];
				$img=$evento["img"];
			}
			else
			{
				//Evento non presente nel database
				$page_title = "Evento non trovato";
				$page_subtitle = "L'evento selezionato non è stato trovato, torna alla pagina
				<a href='eventi.php'>eventi</a> e riprova";
				$errore = true;
			}
		}

		//arrivo da edit conferma evento
		else if(isset($_POST['conferma']))
		{
			$id=$_POST['id'];
			$page_title="Evento creato/modificato";
			$page_subtitle="Torna alla pagina
			<a href='eventi.php'>eventi</a>";
			$ore=$_POST['ore'];
			$minuti =$_POST['minuti'];
			$orario=$_POST['ore'].":".$_POST['minuti'];
			$titolo =$_POST['titolo'];
			$desc_breve=$_POST['desc_breve'];
			$desc_lunga=$_POST['desc_lunga'];
			$img=$_POST['img'];
			if($ore==""||$minuti==""||$titolo==""||$desc_breve==""||$desc_lunga=="")
			{

				$page_title="Evento non inserito";
				$page_subtitle="Devi compilare tutti i campi con *";
			}
			else if(!ctype_digit($ore) || !ctype_digit($minuti) || intval($ore)>23 || intval($ore)<0 || intval($minuti)>59 || intval($minuti)<0)
			{
				$page_title="Evento non inserito";
				$page_subtitle="I campi ore e minuti devono avere un valore numerico valido";
			}
		  else
			{
				$result=$dbAccess->getTags();
				$i=0;
				if($result && mysqli_num_rows($result)>=1)
				{
					$tags=array();
					while ($row = mysqli_fetch_assoc($result))
					{
						$tags[$i]=$row['Nome'];
						$i++;
					}
					$tags[$i]=false;
					if (isset($_FILES['immagine'])&& is_uploaded_file($_FILES['immagine']['tmp_name']))
					{
						$uploaddir = 'img/';
						$userfile_tmp = $_FILES['immagine']['tmp_name'];
						$userfile_name =$_FILES['immagine']['name']=$titolo.".jpg";
						$userfile_name = str_replace(" ", "_", $userfile_name);
						if($img!="non_disponibile.jpg"&&file_exists('img/'.$img))
								unlink('img/'.$img);
						else
						{
							if($img!="non_disponibile.jpg")
								$page_title=$page_title." ma errore in eliminazione immagine precedente";
						}
						move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name);
						$img=$userfile_name;
					}
					else
				  {
						$page_title=$page_title." ma nessun immagine caricata";
						$page_subtitle="Se si è tentato di caricare un <span xml:lang=\"en\">file</span>, verificare dimensione(minore di 2MB) e tipologia(immagine) e riprovare.<br/> Altrimenti puoi tornare alla pagina
						<a href='eventi.php'>eventi</a>";
					}
					$taged=array();
					$i=0;
					while($tags[$i])
					{
						if(isset($_POST["$tags[$i]"]))
							$taged[$i]=true;
						else
							$taged[$i]=false;
						$i++;
					}
					$result1 = $dbAccess->editEvento($id,$titolo,$img,$desc_breve,$desc_lunga,$orario);
					$result2 = $dbAccess->editTags($id,$taged,$i,$tags);
					if(!$result1||(!$result2&&!$tags[0]))
					{
						$page_title="Non si è riusciti ad aggiungere/modificare l'evento";
						$page_subtitle="Si prega di riprovare";
					}
					if(isset($_POST['altro'])&&$_POST['altro']!="")
					{
						$result=$dbAccess->newTags($id,$_POST['newtag']);
						if(!$result)
						{
							$page_title="Non si è riusciti ad aggiungere l'evento";
							$page_subtitle="Si prega di riprovare";
						}
					}
				}
				else
				{
					$errore=true;
					$page_title="Errore di connsessione";
					$page_subtitle="Non è stato possibile ricavare le informazioni, torna alla pagina
					<a href='eventi.php'>eventi</a> e riprova";
				}
			}
		}

		//arrivo da edit annulla
		else if(isset($_POST['annulla']))
			header("Location: eventi.php");

		//arrivo da eventi pulsante crea evento
		//admin logato che accede alla pagina direttamente senza pulsanti
		else
		{
			$new=true;
			$page_title="Crea Evento";
			$page_subtitle="In questa pagina puoi creare tutti i contenuti dell'evento";
			$result=$dbAccess->getnextid();
			if($result && mysqli_num_rows($result)>=1)
			{
				$id = (mysqli_fetch_assoc($result)["Id"])+1;
			}
		}
		//richieste per campi compilazione
		$result=$dbAccess->getFTags($id);
		if($result)
		{
			$Tags_array = array();
			$i=0;
			while ($row = mysqli_fetch_assoc($result))
			{
				$Tags_array[$i]= "<input type=\"checkbox\" class=\"tag\" id=\"tag$i\" name=\"$row[Nome]\"";
				if($row['Id'])
					$Tags_array[$i]=$Tags_array[$i].'checked="checked"';
				$Tags_array[$i]=$Tags_array[$i]."/> <label for=\"tag$i\">$row[Nome]</label>";
				$i++;
			}
		}
		else
		{
			//problema di ricezione tag
			$errore=true;
			$page_title="Errore di connsessione";
			$page_subtitle="Non è stato possibile ricavare le informazioni, torna alla pagina
			<a href='eventi.php'>eventi</a> e riprova";
		}
		$Tags_array[$i] = false;
	}
	else
	{
		//errore di connessione al database
		$page_title = "Servizio non disponibile";
		$page_subtitle = "Si è riscontrato un problema durante la modifica di questo evento, ci scusiamo del disagio.";
	}
}
else
{
	//L'utente non ha effettuato il login come amministratore
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

	<div id="main">
		<div id="fixBanner">
			<p id="breadcrumb">Ti trovi in: <a href="eventi.php">Eventi</a> / Edit Eventi</p>
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
			<form id="edit-form" method="post" enctype="multipart/form-data" action="edit_event.php">
				<div><input type="hidden" value="<?php echo $id?>" name="id" id="id_event" /></div>
				<div>
				<label for="title_event">*Titolo:</label>
				<?php echo '<input type="text" id="title_event" name="titolo" value="'.$titolo.'"/>'; ?>
			</div>

				<div>
				<label for="image_event">Immagine dell'evento:</label>
				<input type="hidden" value="<?php echo $img?>" name="img" id="img_event" />
				<input type="file" accept="image/*" id="image_event" name="immagine"/>
			</div>
			<div>
				<p>Orario di inizio dell'evento:</p>
				<label for="time_hour">*Ore</label>
				<?php echo '<input type="text" id="time_hour" name="ore" value="'.$ore.'"/>'; ?>
				<label for="time_minutes">*Minuti</label>
				<?php echo '<input type="text" id="time_minutes" name="minuti" value="'.$minuti.'"/>'; ?><br/>
			</div>
			<div>
				<label for="desc_short">*Descrizione breve:</label>
				<?php echo '<input type="text" id="desc_short" name="desc_breve" value="'.$desc_breve.'"/>'; ?>
</div>
<div>
				<label for="desc_long">*Descrizione lunga:</label>
				<?php echo '<textarea id="desc_long" name="desc_lunga" rows="6" cols="50">'.$desc_lunga.'</textarea>'; ?>
</div>
<div>
				<label for="tags">Tags:</label>
				<div id="tags">
				<?php
						$i=0;
						while($Tags_array[$i])
						{
							echo $Tags_array[$i];
							$i++;
						}
				 ?>
				 <label><input type="checkbox" name="altro"/> altro(inserisci nuovo tag nella prossima casella)</label>
			 </div>
		 </div>
			 <div>
				 <label>Nuovo tag:<input type="text" id="newtag" name="newtag"/></label>
			 </div>

				<div class="tasti_form">
					<input type="submit" name="annulla" value="Annulla"<?php if($errore) echo "disabled=\"disabled\""?>/>
					<input type="submit" name="conferma" value="Conferma"<?php if($errore) echo "disabled=\"disabled\""?>/>
				</div>
			</form>
				<?php
				if($errore) {
					$disabled = "disabled=\"disabled\"";
				}
				if(!$new) {
					 echo "<form method=\"post\" action=\"delete_event.php\">
					 				<div>
		 							<input type=\"hidden\" value=\"$id\" name=\"id\"/>
					 			 	<input type=\"submit\" name=\"cancella\" id=\"elimina_btn\" value=\"Elimina evento\"  $disabled/>
									</div>
								 </form>";
				 }?>
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
