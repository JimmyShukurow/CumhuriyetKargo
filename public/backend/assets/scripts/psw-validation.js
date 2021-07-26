var myInput = document.getElementById("psw");
var inputPassAgion = document.getElementById("passwordNewAgain");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var special = document.getElementById("special");
var again = document.getElementById("special");
var number = document.getElementById("number");
var length = document.getElementById("length");
var passAgain = document.getElementById("again");


inputPassAgion.onkeyup = function () {

    if (inputPassAgion.value == myInput.value) {
        passAgain.classList.remove("invalid");
        passAgain.classList.add("valid");
        passAgain.classList.add("text-success");
        passAgain.classList.remove("text-danger");
    } else {
        passAgain.classList.remove("valid");
        passAgain.classList.add("invalid");
        passAgain.classList.remove("text-success");
        passAgain.classList.add("text-danger");
    }
}

// When the user starts to type something inside the password field
myInput.onkeyup = function () {
    // Validate lowercase letters
    event();
}

function event() {
    var lowerCaseLetters = /[a-z]/g;
    if (myInput.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
        letter.classList.add("text-success");
        letter.classList.remove("text-danger");
    } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
        letter.classList.remove("text-success");
        letter.classList.add("text-danger");
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if (myInput.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
        capital.classList.add("text-success");
        capital.classList.remove("text-danger");
    } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
        capital.classList.remove("text-success");
        capital.classList.add("text-danger");
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if (myInput.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
        number.classList.add("text-success");
        number.classList.remove("text-danger");
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
        number.classList.remove("text-success");
        number.classList.add("text-danger");
    }

    // Validate special
    var numbers = /(?=.*?[#?!@$%^&_*.-])/g;
    if (myInput.value.match(numbers)) {
        special.classList.remove("invalid");
        special.classList.add("valid");
        special.classList.add("text-success");
        special.classList.remove("text-danger");
    } else {
        special.classList.remove("valid");
        special.classList.add("invalid");
        special.classList.remove("text-success");
        special.classList.add("text-danger");
    }

    // Validate length
    if (myInput.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
        length.classList.add("text-success");
        length.classList.remove("text-danger");
    } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
        length.classList.remove("text-success");
        length.classList.add("text-danger");
    }

    if (inputPassAgion.value == myInput.value) {
        passAgain.classList.remove("invalid");
        passAgain.classList.add("valid");
        passAgain.classList.add("text-success");
        passAgain.classList.remove("text-danger");
    } else {
        passAgain.classList.remove("valid");
        passAgain.classList.add("invalid");
        passAgain.classList.remove("text-success");
        passAgain.classList.add("text-danger");
    }
}

$(document).ready(function () {
    event();
})
