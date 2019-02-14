/* Controllo client-side sul form della login */
function checkLogin() {
   var mail = document.getElementById("username").value;
   var psw = document.getElementById("password").value;

   //Reset dei campi di errore
   resetErrors();

   var validEmail = checkEmail(mail, 0);
   var validPassword = checkPassword(psw, 1);

   /* se tutti i test sono passati ritorna true, false altrimenti */
   if(validPassword && validEmail){
      return true;
   } else {
      return false;
   }
}

/* Controllo client-side sul form delle recensioni */
function checkReview() {
   var titolo = document.getElementById("title_review").value;
   var punteggio = document.getElementsByName("rating");
   var descrizione = document.getElementById("review").value;

   //Reset dei campi di errore
   resetErrors();

   var check1 = checkEmpty(titolo, 0);
   var check2 = checkRating(punteggio, 1);
   var check3 = checkEmpty(descrizione, 2);

   /* se tutti i test sono passati ritorna true, false altrimenti */
   if(!check1 && check2 && !check3){
      return true;
   } else {
      return false;
   }
}

/* Controlla la validità di un indirizzo email mediante RegEx
 * attraverso un espressione regolare verifica che prima della chiocciola
 * ci sia un blocco di caratteri alfanumerici,
 * che dopo di essa invece ci sia almeno un blocco di caratteri alfanumerici,  separati da un “.”,
 * ed infine un’estensione di almeno due lettere.
 */
function checkEmail(email, i) {
    if(checkEmpty(email,i)) { return false; }

    var pattern = /^[A-z0-9\.\+_-]+@[A-z0-9\._-]+\.[A-z]{2,6}$/;
    if(!pattern.test(email)){
      document.getElementsByClassName("asterisk")[i].innerHTML = " (*) Inserisci una email corretta.";
      return false
    }

    return true;
}

/* Controlla se la password non è vuota e contiene almeno 8 caratteri */
function checkPassword(psw, i) {
    if(checkEmpty(psw,i)){ return false; }

    var pattern = /.{8,}/;
    if(!pattern.test(psw)){
      document.getElementsByClassName("asterisk")[i].innerHTML = " (*) La password deve contenere almeno 8 caratteri.";
      return false
    }

    return true;
}

/* Controlla se un campo è vuoto o contiene solo spazi */
function checkEmpty(field, i) {
   if(!field || field.length === 0 || /^\s*$/.test(field)){
      document.getElementsByClassName("asterisk")[i].innerHTML = " (*) Il campo è richiesto.";
      return true;
   }
   return false;
}


/* Controlla se è stato selezionato un radio button (se è stato dato un punteggio a stelle) */
function checkRating(punteggio, i) {
   for(var j = 0; j < 5; j++) {
      if(punteggio[j].checked) {
         return true;
      }
   }
   document.getElementsByClassName("asterisk")[i].innerHTML = " (*) Dai un voto da 1 a 5."
   return false;
}

/* Resetta i messaggi di errore */
function resetErrors(){
  var x = document.getElementsByClassName("asterisk");
  for(var i = 0; i < x.length; i++) {
     x[i].innerHTML = "";
  }
}
