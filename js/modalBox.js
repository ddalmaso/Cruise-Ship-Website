function openModal(id) {
   // Ottiene l'elemento del box modale
   document.getElementById('myModal').style.display = "block";

   // Ottiene l'immagine e la inserisce all'interno del box - usa il tag "alt" come didascalia
   var img = document.getElementsByClassName("myImg")[id];
   document.getElementById("img01").src= img.src;
   document.getElementById("caption").innerHTML = img.alt;
}

function closeModal() {
   // Quando l'utente clicca sopra l'elemento <span> (x), chiude il box
   document.getElementById('myModal').style.display = "none";
}
