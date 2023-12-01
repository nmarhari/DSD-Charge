/* toggle between adding and removing the responsive class
to navbar when clicking the icon, showing or hiding the navbar */

function showNav() {
    var x = document.getElementById("nav-id");
    if (x.className === "nav-class") {
        x.className += " responsive";
    } else {
        x.classname = "nav-class";
    }
}