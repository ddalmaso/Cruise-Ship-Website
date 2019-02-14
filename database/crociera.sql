

DROP TABLE IF EXISTS Utenti;
DROP TABLE IF EXISTS Ristoranti;
DROP TABLE IF EXISTS Prenotazioni;
DROP TABLE IF EXISTS Eventi;
DROP TABLE IF EXISTS Recensioni;
DROP TABLE IF EXISTS Tags;

CREATE TABLE Recensioni (
	Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Nome VARCHAR(30) NOT NULL,
	Titolo VARCHAR(50) NOT NULL,
	Punteggio INT NOT NULL,
	Descrizione TEXT,
	dataAttuale timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE Eventi (
	Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Titolo VARCHAR(30) NOT NULL,
	img TEXT NOT NULL,
	desc_b TEXT NOT NULL,
	descrizione TEXT NOT NULL,
	orario TIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Tags (
	Nome VARCHAR(30) NOT NULL,
	Id INT NOT NULL,
	PRIMARY KEY (Nome,Id),
	FOREIGN KEY (Id) REFERENCES Eventi(Id)
	ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Utenti (
	Id INT PRIMARY KEY NOT NULL,
	Email VARCHAR(30) UNIQUE NOT NULL,
	Nome VARCHAR(30) NOT NULL,
	Cognome VARCHAR(30) NOT NULL,
	Password VARCHAR(32) NOT NULL,
	Ruolo ENUM("cliente","amministratore") NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Ristoranti (
	Id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Nome VARCHAR(20) UNIQUE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE Prenotazioni (
	Utente INT,
	Ristorante INT,
	Data DATE,
	Turno ENUM('primo','secondo'),
	PRIMARY KEY (Utente,Ristorante,Data),
	FOREIGN KEY (Utente) REFERENCES Utenti(Id),
	FOREIGN KEY (Ristorante) REFERENCES Ristoranti(Id)
) ENGINE=InnoDB;

INSERT INTO Recensioni VALUES
(1, "Flavia", "Esperienza indimenticabile!", 5, "Niente da suggerire, in quanto e' stato pienamente all'altezza delle aspettative; eccellente, in particolare, la posizione e la tranquillita' della camera da letto.", "2018-01-27"),
(2, "Paolo", "Equipaggio professionale", 4, "Tutto perfetto ,servizio ottimo, equipaggio professionale e disponibile sempre con il sorriso a qualsiasi ora.", "2018-01-28");

INSERT INTO Utenti VALUES
(1,"user@user.it","Enrico","Muraro","25d55ad283aa400af464c76d713c07ad","cliente"),
(2,"b@b.bb","Matteo","Pasqualin","5e8667a439c68f5145dd2fcbecf02209","cliente"),
(3,"admin@admin.it","Admin","Admin","25d55ad283aa400af464c76d713c07ad","amministratore");

INSERT INTO Ristoranti VALUES
(1, "Italiano"),
(2, "Gourmet"),
(3, "Giapponese"),
(4, "Pesce"),
(5, "SudAmericano");

INSERT INTO Prenotazioni VALUES
(1,1,'2019-01-28','secondo'),
(1,1,'2019-01-29','secondo'),
(1,2,'2019-01-31','primo'),
(1,4,'2019-02-01','primo'),
(1,5,'2019-02-02','primo'),
(1,3,'2019-02-03','secondo'),
(2,3,'2019-01-01','primo'),
(2,4,'2019-01-02','secondo'),
(2,5,'2019-01-03','primo'),
(3,1,'2019-01-29','secondo'),
(3,2,'2019-01-31','primo');

INSERT INTO Eventi VALUES
(1,"Limbo in piscina","Limbo_in_piscina.jpg","Divertimento in piscina","Scivoli d'acqua altissimi. Piscine. Splash Park. Vasche idromassaggio. Lasciati sommergere dal divertimento all'Acqua Park, sui nostri ponti superiori. Perfeziona i tuoi tuffi a bomba o lanciati nel Free Fall, lo scivolo d'acqua più veloce in mare, sulle nostre navi di classe Breakaway. Prova l'ebbrezza di scivolare per 200 piedi sull'Epic Plunge della Norwegian Epic. Oppure gioca tutto il giorno tra gli spruzzi dell'Acqua Park per bambini. Vivi un'avventura bellissima di straripante divertimento.","8:30"),
(2,"Lezioni di cucina","cracco.jpg","Impara a cucinare insieme a Carlo Cracco","L'esperienza e i segreti di Carlo Cracco ora in un'esclusiva collana di libri. Tecniche e ricette divise per livelli di difficoltà, dalle più semplici alle più complesse, spiegate nel dettaglio e accompagnate da fotografie scattate direttamente dalla sua cucina, per illustrare passo passo i piatti della gastronomia italiana e i passaggi che fanno la differenza. E una ricetta originale dello chef in ogni volume. Ti aspettiamo in cucina!","11:00"),
(3,"Show a teatro","show.jpg", "Uno spettacolo emozionante per grandi e piccini.", "La magia di Broadway scintilla sul mare. Ogni sera uno spettacolo emozionante e coinvolgente per grandi e piccini.Ogni sera Costa ti propone uno spettacolo diverso. Tutte le esibizioni in cartellone sono grandiose produzioni curate in ogni particolare, dai costumi alle scenografie. I cast sono composti da professionisti di primo livello nazionale e internazionale che sanno come farti ridere, sognare ed emozionare. Tutti gli show sono godibili da un pubblico internazionale. È per questo motivo che il momento dello spettacolo a teatro è il più atteso da tutti. Ti ritrovi comodamente in poltrona ad applaudire maghi, cantanti, acrobati e ballerini, artisti internazionali che si esibiscono solo per Costa. Ogni spettacolo nel grande teatro ti regala le stesse emozioni di uno show di Broadway. E, tu stesso, puoi essere il protagonista salendo sul palco ad esibirti durante i nostri talent show.", "21:30"),
(4,"Discoteca", "disco.jpg","Scatenati al ritmo della nave","I bassi che pompano al massimo e fuori dalle vetrate il silenzio del mare di notte, non esiste nulla di simile se non su una nave Costa. Per te che ami ballare e fare le ore piccole, la discoteca è l'ultimo appuntamento della giornata. Qui la musica è davvero coinvolgente e chi ama la dance music può divertirsi e ballare sul mare fino all'alba. Niente di più divertente e romantico. Si fanno nuove amicizie al ritmo scatenato della notte. I decibel del divertimento suonano al massimo e ti fanno sentire parte di una bellissima nottata e poi, mentre tutti dormono, si guarda insieme sorgere il sole dal mare.","23:00"),
(5,"Cinema 4D", "cinema.jpg","La tua proiezione in 4D sulla nave", "Lo spettacolare cinema 4D coinvolge i tuoi cinque sensi grazie a effetti speciali che ti fanno vivere il tuo film “da protagonista”. Vivi l'incredibile esperienza di vedere un film e trovarti coinvolto nelle azioni con tutti i tuoi sensi. Le immagini si avvicinano a te e puoi quasi toccarle, il suono è avvolgente, l'olfatto è sollecitato da profumi che vengono sprigionati nell'aria. La tua proiezione 4D su Costa è davvero molto coinvolgente ed è un'occasione da non perdere, soprattutto se in gruppo per farsi quattro risate assieme.", "17:00"),
(6,"Star Laser", "laser.jpg","Percorso laser da attraversare","Su una nave Costa c'è di tutto, anche il percorso laser da superare per una avventura all'insegna del divertimento. Location ideale per festeggiare compleanni originali ed eventi privati grazie ai suoi 80 mq di effetti speciali: monitor da 60”, luci laser, un impianto sonoro di altissima qualità per far scatenare grandi e piccini. Un ambiente divertente e high tech con tante opzioni per le tue diverse richieste e per ogni età. Puoi inoltre giocare a sfidare gli amici nel labirinto Laser Maze, un gioco interattivo con un flusso di fasci laser che i giocatori devono attraversare nel più breve tempo possibile facendo attenzione a non infrangerli. L'obiettivo è segnare il maggior numero di punti e vincere la sfida con gli altri giocatori. Divertentissimo!","9:00"),
(7,"Corsi Nuoto","nuoto.jpg","Corsi per tutte le età","Gli obiettivi che perseguiamo nella programmazione delle attività in acqua dei Corsi Nuoto sono diversi se rivolti ai bambini piuttosto che agli adulti. L'apprendimento ed il perfezionamento degli stili natatori è l'obiettivo didattico principale dei Corsi Nuoto cui fine giungere: quello che chiaramente diversifica le due classi di allievi è la metodologia e le tappe intermedie per arrivare al risultato finale. L'attività sportiva è un grande strumento educativo nelle mani degli istruttori che con il loro operato possono infondere sicurezza, serenità, consapevolezza delle proprie capacità motorie ed espressive nei giovani allievi. Corsi Nuoto di Crocera TecWeb sono adatti a tutti, ti aspettiamo!.", "8:00");

INSERT INTO Tags VALUES
('piscina',1),
('piscina',7),
('lezioni',2),
('lezioni',7),
('intrattenimento',1),
('intrattenimento',3),
('intrattenimento',4),
('intrattenimento',5),
('intrattenimento',6);
