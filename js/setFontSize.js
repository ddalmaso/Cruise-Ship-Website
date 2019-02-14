/* Quando viene caricata una pagina
 * viene letto il valore della variabile size
 * e viene richiamata la funzione setFontSize().
 * Se non è memorizzato nessun valore valido,
 * viene preso il valore di default.
 */

window.onload = function() {
	document.getElementById("resize").style.visibility = "visible";
	size = readCookie('dimScelta');
	setFontSize(size);
}

/* Crea un semplice cookie che scade dopo tot giorni (specificati in argomento) dalla sua creazione */
function createCookie(name, value, days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = '; expires='+date.toGMTString();
  }
  else expires = '';
  document.cookie = name+'='+value+expires+'; path=/';
}

/* Individua il valore assegnato al cookie specificato in argomento. */
function readCookie(name) {
  var nameEQ = name + '=';
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

/* Imposta la dimensione del font del main in base al valore della variabile size */
function setFontSize(size) {
	var main = document.getElementById('main');
	var percentuale = "110%"; // default
	if (size == 1) percentuale = "100%";
	if (size == 2) percentuale = "110%";
	if (size == 3) percentuale = "120%";
	if (size == 4) percentuale = "130%";
	if (size == 5) percentuale = "140%";
	main.style.fontSize = percentuale;
	createCookie('dimScelta', size, 30); /* memorizza il valore in un cookie */
}
