function saveRating()
{
  var title=document.getElementById("title_review").value;
  var review=document.getElementById("review").value;
  var star=0;
  if(document.getElementById("star1").checked)
    star=1;
  if(document.getElementById("star2").checked)
    star=2;
  if(document.getElementById("star3").checked)
    star=3;
  if(document.getElementById("star4").checked)
    star=4;
  if(document.getElementById("star5").checked)
      star=5;
  document.cookie = "title_review="+title +"; path=/";
  document.cookie = "review="+review +"; path=/";
  document.cookie = "star="+star +"; path=/";
}
