var x = document.getElementById("login-form");
var y = document.getElementById("register-form");
var z = document.getElementById("btn");

function registerBtn() {
    x.style.left = "-400px";
    y.style.left = "50px";
    z.style.left = "110px";
}

function loginBtn() {
    x.style.left = "50px";
    y.style.left = "450px";
    z.style.left = "0px";
}

function showPass() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}