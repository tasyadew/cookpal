var x = document.getElementById("login-form");
var y = document.getElementById("register-form");
var z = document.getElementById("btn");

function registerBtn() {
    x.style.left = "-400px";
    y.style.left = "50px";
    z.style.left = "110px";
    z.style.width = "130px";
}

function loginBtn() {
    x.style.left = "50px";
    y.style.left = "450px";
    z.style.left = "0px";
    z.style.width = "105px";
}

function showPass(checkbox, id, id2) {
    var x = document.getElementById(id);
    if (checkbox.checked) {
        x.type = "text";
    } else {
        x.type = "password";
    }

    // For "Reenter password" (Optional)
    if (id2) {
        var x2 = document.getElementById(id2);
        if (checkbox.checked) {
            x2.type = "text";
        } else {
            x2.type = "password";
        }
    }
}