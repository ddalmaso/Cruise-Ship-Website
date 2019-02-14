<?php
namespace DB;

class DBAccess {
	const HOST_DB = 'localhost';
	const USERNAME = 'root';
	const PASSWORD = '';
	const DATABASE_NAME = 'crociera';

	public $connection;

	public function openDBConnection()
	{

		$this->connection = mysqli_connect(static::HOST_DB,static::USERNAME,static::PASSWORD,static::DATABASE_NAME);
		return ($this->connection);
	}

	public function checkLogin($email, $password) {
		if($this->connection) {
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);
			$password = mysqli_real_escape_string($this->connection,$password);
			//calcolo l'hash della password
			$hash = md5($password);
			$query = "SELECT * FROM Utenti WHERE Email='$email' AND Password='$hash'";

			$result = mysqli_query($this->connection,$query);
			if($result && mysqli_num_rows($result)==1) {
				return true;
			}
		}
		else
			return false;
	}

	public function insertRecensione($nome, $titolo, $punteggio, $descrizione) {
		if($this->connection) {
			//sanitizzazione input
			$nome = mysqli_real_escape_string($this->connection,$nome);
			$titolo = mysqli_real_escape_string($this->connection,$titolo);
			$punteggio = mysqli_real_escape_string($this->connection,$punteggio);
			$descrizione = mysqli_real_escape_string($this->connection,$descrizione);

			$query = "INSERT INTO Recensioni (Nome, Titolo, Punteggio, Descrizione) VALUES ('$nome', '$titolo', '$punteggio', '$descrizione')";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function insertPrenotazione($email,$ristorante,$turno) {
		if($this->connection) {
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);
			$ristorante = mysqli_real_escape_string($this->connection,$ristorante);
			$turno = mysqli_real_escape_string($this->connection,$turno);

			$result = mysqli_query($this->connection,"SELECT Id FROM Utenti WHERE Email='$email'");
			$utente = mysqli_fetch_assoc($result);
			$idUtente = $utente["Id"];
			$currentDate = date("Y-m-d");
			$query = "INSERT INTO Prenotazioni VALUES ('$idUtente','$ristorante','$currentDate','$turno')";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	//ritorna le $n recensioni più recenti
	public function getRecensioni($n) {
		if($this->connection) {
			//sanitizzazione input
			$n = mysqli_real_escape_string($this->connection,$n);

			$query = "SELECT * FROM Recensioni ORDER BY dataAttuale DESC LIMIT $n";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	//ritorna i dati dell' utente associato a $email
	public function getUtente($email) {
		if($this->connection) {
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);

			$query = "SELECT Id,Email,Nome,Cognome,Ruolo FROM Utenti WHERE Email='$email'";
			$result = mysqli_query($this->connection,$query);
			return mysqli_fetch_assoc($result);
		}
		else
			return false;
	}

	public function getEventi() {
		if($this->connection)
		{
			$query = "SELECT * FROM Eventi";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function getSpecEventi($Tag,$Mat,$Pom,$Ser) {
		if($this->connection) {
			//sanitizzazione input
			$Tag = mysqli_real_escape_string($this->connection,$Tag);
			$Mat = mysqli_real_escape_string($this->connection,$Mat);
			$Pom = mysqli_real_escape_string($this->connection,$Pom);
			$Ser = mysqli_real_escape_string($this->connection,$Ser);
			if($Tag=="all")
				$Tag=TRUE;
			else
				$Tag="Tags.Nome='".$Tag."'";
			$Time="";
			if($Mat)
      {
        $Time=$Time."Eventi.orario < '12:00'";
        if($Pom||$Ser)
          $Time=$Time." || ";
      }
      if($Pom)
      {
        $Time=$Time."(Eventi.orario>='12:00'&&Eventi.orario<'18:00')";
        if($Ser)
          $Time=$Time." || ";
      }
      if($Ser)
          $Time=$Time."Eventi.orario>='18:00'";
      if(!$Mat&&!$Pom&&!$Ser)
      	$Time=TRUE;
			$query = "SELECT Eventi.Id, Eventi.Titolo, Eventi.img, Eventi.desc_b FROM Eventi LEFT JOIN Tags ON(Eventi.Id=Tags.Id)WHERE $Tag && ($Time) GROUP BY Eventi.Id";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function getFEvent($Id){
		if($this->connection){
			//sanitizzazione input
			$Id = mysqli_real_escape_string($this->connection,$Id);

			$query ="SELECT * FROM Eventi WHERE Id=$Id";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function getTags() {
		if($this->connection){
			$query ="SELECT DISTINCT Nome FROM Tags";
			$result=mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function getFTags($id)
	{
		if($this->connection)
		{
			$id = mysqli_real_escape_string($this->connection,$id);
			$query ="SELECT Nome,Id FROM (SELECT DISTINCT Nome FROM Tags) AS T1 LEFT JOIN (SELECT Nome as N,Id FROM Tags WHERE Id=$id) AS T2 ON (T1.Nome=T2.N)";
			$result=mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function editTags($id,$taged,$i,$tags)
	{
		if($this->connection)
		{
			$id = mysqli_real_escape_string($this->connection,$id);
			foreach($taged as $key=>$a)
			{
				$taged[$key] = mysqli_real_escape_string($this->connection,$a);
			}
			$i = mysqli_real_escape_string($this->connection,$i);
			foreach($tags as $key=>$a)
			{
				$tags[$key] = mysqli_real_escape_string($this->connection,$a);
			}
			for($j=0;$j<$i;$j++)
			{
				if($taged[$j])
				{
					$query = "INSERT INTO Tags VALUES ('$tags[$j]',$id)";
					$result=mysqli_query($this->connection,$query);
				}
			}
			return true;
		}
		else
			return false;
	}

	public function newTags($id,$tag)
	{
		if($this->connection)
		{
			$id = mysqli_real_escape_string($this->connection,$id);
			$tag = mysqli_real_escape_string($this->connection,$tag);
			$query = "INSERT INTO Tags VALUES ('$tag',$id)";
			$result=mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

public function deleteEvento($Id)
{
	if($this->connection)
	{
		$Id = mysqli_real_escape_string($this->connection,$Id);
		$query ="DELETE FROM Eventi WHERE Id=$Id";
		$result=mysqli_query($this->connection,$query);
		return $result;
	}
	else
		return false;
}

public function editEvento($Id,$Titolo,$img,$desc_breve,$desc_lunga,$orario)
{
	if($this->connection)
	{
		$Id = mysqli_real_escape_string($this->connection,$Id);
		$Titolo = mysqli_real_escape_string($this->connection,$Titolo);
		$img = mysqli_real_escape_string($this->connection,$img);
		$desc_breve = mysqli_real_escape_string($this->connection,$desc_breve);
		$desc_lunga = mysqli_real_escape_string($this->connection,$desc_lunga);
		$orario = mysqli_real_escape_string($this->connection,$orario);
		$query ="DELETE FROM Eventi WHERE Id=$Id";
		$result=mysqli_query($this->connection,$query);
		$query = "INSERT INTO Eventi VALUES ('$Id','$Titolo','$img','$desc_breve','$desc_lunga','$orario')";
		$result = mysqli_query($this->connection,$query);
		return $result;
	}
	else
		return false;
}

public function getnextid()
{
	if($this->connection)
	{
		$query ="SELECT Id FROM Eventi ORDER BY Id DESC LIMIT 1";
		$result=mysqli_query($this->connection,$query);
		return $result;
	}
	else
		return false;
}

	public function removePrenotazione($email,$date) {
		if($this->connection)
		{
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);
			$date = mysqli_real_escape_string($this->connection,$date);

			$result = mysqli_query($this->connection,"SELECT Id FROM Utenti WHERE Email='$email'");
			$utente = mysqli_fetch_assoc($result);
			$idUtente = $utente["Id"];
			$query = "DELETE FROM Prenotazioni WHERE Utente='$idUtente' AND Data='$date'";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}

	public function getPrenotazioni($email) {
		if($this->connection)
		{
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);

			$query = "SELECT R.Nome AS Ristorante, P.Data AS Data, P.Turno AS Turno
			FROM Prenotazioni P,Utenti C,Ristoranti R
			WHERE P.Utente = C.Id AND P.Ristorante = R.Id AND C.Email = '$email'
			ORDER BY P.Data DESC";
			$result = mysqli_query($this->connection,$query);
			return $result;
		}
		else
			return false;
	}
	public function checkPrenotazioni($email){
		if($this->connection)
		{
			//sanitizzazione input
			$email = mysqli_real_escape_string($this->connection,$email);

			$currentDate = date("Y-m-d");
			$query = "SELECT P.Ristorante AS ristorante
			FROM Prenotazioni P, Utenti C
			WHERE P.Utente = C.Id AND C.Email = '$email' AND P.Data = '$currentDate'";
			$result = mysqli_query($this->connection, $query);
			if($result && mysqli_num_rows($result)==1)
			{
				return true;
			}
			else return false;
		}
		else
			return false;
	}
}
?>
