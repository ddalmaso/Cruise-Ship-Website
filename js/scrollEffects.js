/* Quando l'utente scorre in basso la pagina di 80 pixel, fa due cose:
 - rimpicciolisce il menu
 - visualizza il pulsante di scrolling verso l'alto */

window.onscroll = function() { scrollFunction() };

function scrollFunction() {
   if ((document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) || window.matchMedia("handheld, screen and (max-width:480px), only screen and (max-device-width:480px)").matches) {
      document.getElementById("header").style.padding = "0em";
      document.getElementById("img_logo").style.width = "5em";
      document.getElementById("nav").style.padding = "0em";
      document.getElementById("scrollBtn").style.display = "block";
   }
   else {
      document.getElementById("header").style.padding = "1em 0";
      document.getElementById("img_logo").style.width = "8em";
      document.getElementById("nav").style.padding = "1em 0 0 0";
      document.getElementById("scrollBtn").style.display = "none";
   }
}

// Scrolling della pagina verso l'alto
function topFunction() {
  document.body.scrollTop = 0; // Per Safari
  document.documentElement.scrollTop = 0; // Per Chrome, Firefox, IE e Opera
}
