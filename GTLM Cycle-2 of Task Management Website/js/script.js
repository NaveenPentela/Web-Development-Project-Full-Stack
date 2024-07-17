/* View Task Sorting */
function myFunction() {
  var x = document.getElementById("sortpopup");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
function myFunctionn() {
  var x = document.getElementById("sortpopupp");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

/* Edit Task Popup */
$ = function(id) {
  return document.getElementById(id);
}

var show = function(id) {
	$(id).style.display ='block';
}
var hide = function(id) {
	$(id).style.display ='none';
}

/* Checkbox */
function onlyOne(checkbox) {
    var checkboxes = document.getElementsByName('check')
    checkboxes.forEach((item) => {
        if (item !== checkbox) item.checked = false
    })
}

//reference- W3Schools (2023). W3Schools Online Web Tutorials. [online] W3schools.com. Available at: https://www.w3schools.com/.