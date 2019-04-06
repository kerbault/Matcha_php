let valid0 = true;
let valid1 = true;
let valid2 = true;
let valid3 = true;
let valid4 = true;
let valid5 = true;
let valid6 = true;

const RgxRealName = /^[a-zA-Zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž∂ð]+\.?(([',. -][a-zA-Zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž∂ð]\.?)?[a-zA-Zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž∂ð]*\.?)*[a-zA-Zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž∂ð]?\.?$/;
const RgxUserName = /^[a-zA-Z0-9]{2,50}$/;
const RgxPassword = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/;
const RgxEmail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

function validateEmail(email) {
    return RgxEmail.test(String(email).toLowerCase());
}

function validateBorndate(birthDate) {
    const getAge = birthDate => Math.floor((new Date() - new Date(birthDate).getTime()) / 3.15576e+10)

    if (getAge(birthDate) >= 18 && getAge(birthDate) <= 100) {
        return true;
    }
    return false;
}

function checkPassword(inputtxt) {
    if (inputtxt.match(RgxPassword)) {
        return true;
    } else {
        return false;
    }
}

function checkRealName(inputtxt) {
    if (inputtxt.match(RgxRealName)) {
        return true;
    } else {
        return false;
    }
}

function checkUserName(inputtxt) {
    if (inputtxt.match(RgxUserName)) {
        return true;
    } else {
        return false;
    }
}

function validateForm() {
    if (!valid0 || !valid1 || !valid2 || !valid3 || !valid4 || !valid5 || !valid6) {
        alert("Please review your info");
        return false;
    }
}

window.onload = function () {
    let fname = document.getElementById("inputFname");
    let lname = document.getElementById("inputLname");
    let born = document.getElementById("inputDate");
    let username = document.getElementById("inputUsername");
    let email = document.getElementById("inputEmail");
    let password1 = document.getElementById("inputPassword1");
    let password2 = document.getElementById("inputPassword2");

    fname.onchange = function () {
        if (!checkRealName(fname.value)) {
            valid0 = false;
            fname.className = "form-control is-invalid";
        } else {
            valid0 = true;
            fname.className = "form-control is-valid";
        }
    };
    lname.onchange = function () {
        if (!checkRealName(lname.value)) {
            valid1 = false;
            lname.className = "form-control is-invalid";
        } else {
            valid1 = true;
            lname.className = "form-control is-valid";
        }
    };
    born.onchange = function () {
        if (validateBorndate(born.value)) {
            valid2 = true;
            born.className = 'form-control is-valid';
        } else {
            valid2 = false;
            born.className = 'form-control is-invalid';
        }
    };
    username.onchange = function () {
        if (!checkUserName(username.value)) {
            valid3 = false;
            username.className = 'form-control is-invalid';
        } else {
            valid3 = true;
            username.className = 'form-control is-valid';
        }
    };
    email.onchange = function () {
        if (validateEmail(email.value)) {
            valid4 = true;
            email.className = 'form-control is-valid';
        } else {
            valid4 = false;
            email.className = 'form-control is-invalid';
        }
    };
    password1.onchange = function () {
        if (checkPassword(password1.value)) {
            valid5 = true;
            password1.className = 'form-control is-valid';
        } else {
            valid5 = false;
            password1.className = 'form-control is-invalid';
        }
    };
    password2.onchange = function () {
        if (password1.value == password2.value) {
            valid6 = true;
            password2.className = 'form-control is-valid';
        } else {
            valid6 = false;
            password2.className = 'form-control is-invalid';
        }
    }
};
