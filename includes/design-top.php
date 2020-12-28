<?php session_start(); ?>
<div class="jumbotron pizzatop">
    <div class="pizzatop__container">
        <div class="pizzatop__logo">
            <img src="images/facebook_cover_photo_1.png" height="150px" alt="logo"><br>
            <div class="pizzatop__logo--slogan">Доставка пиццы по Нижнему Новгороду в один клик!</div>
        </div>
        <script>
            let unchangedNumber;
            function prettyNumber(ourNumber) {
                return ourNumber.substring(0, 2) + " " + ourNumber.substring(2, 5) + " " + ourNumber.substring(5, 8) + "-" + ourNumber.substring(8, 10) + "-" + ourNumber.substring(10, ourNumber.length)
            }
            function myFunction() {
                let x = document.getElementById("loginDarkThing");
                if (x.style.display === "none") {
                    x.style.display = "flex";
                } else {
                    x.style.display = "none";
                }
            }
            function sessionActivated(name) {
                let profileButton = document.getElementById("loggedIn");
                profileButton.style.display = "block";
                profileButton.innerText = name;
                document.getElementById("logInOnly").style.display = "none";

                async function setSessionVars() {
                    let user = {
                        username: name,
                        number: prettyNumber(unchangedNumber),
                        sqlnumber: unchangedNumber
                    };
                    await fetch('/pizza-service/post_services/session_set.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json;charset=utf-8',
                            'Accept': 'application/json;charset=utf-8'
                        },
                        body: JSON.stringify(user)
                    }).then(response => response.json()).then(response => {
                        console.log(response);
                    });
                }
                setSessionVars();
            }
        </script>
        <script>
            function setInputFilter(textbox, inputFilter) {
                ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
                    textbox.addEventListener(event, function() {
                        if (inputFilter(this.value)) {
                            this.oldValue = this.value;
                            this.oldSelectionStart = this.selectionStart;
                            this.oldSelectionEnd = this.selectionEnd;
                        } else if (this.hasOwnProperty("oldValue")) {
                            this.value = this.oldValue;
                            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                        } else {
                            this.value = "";
                        }
                    });
                });
            }
        </script>
        <a id="loggedIn" class="pizzatop__login" style="display:
            <?php if (isset($_SESSION["firstName"])) {echo "block";} else {echo "none";} ?>" href="profile.php">
            <?php if (isset($_SESSION["firstName"])) {echo $_SESSION["firstName"];} ?>
        </a>
        <div id="logInOnly" class="pizzatop__login" onclick="myFunction()" style="display:
            <?php if (isset($_SESSION["firstName"])) {echo "none";} else {echo "block";}?>">
            Войти
        </div>
        <script>
            let only_once = 0;
            let code;
            let doesExist;
            let name;
            function invoke(isFoundBool) {
                console.log(isFoundBool);
                code = Math.floor(1000 + Math.random() * 9000);
                let numberInput = document.getElementById("intLimitTextBox");
                unchangedNumber = numberInput.value;
                numberInput.value = prettyNumber(numberInput.value);
                numberInput.disabled = true;
                document.getElementById("enterButton").style.display = "none";
                document.getElementById("legalThing").style.display = "none";

                doesExist = isFoundBool;

                alert(code);

                document.getElementById('timer').innerHTML = 005 + ":" + 00;
                startTimer();

                function startTimer() {
                    let presentTime = document.getElementById('timer').innerHTML;
                    let timeArray = presentTime.split(/[:]+/);
                    let m = timeArray[0];
                    let s = checkSecond((timeArray[1] - 1));
                    if(s == 59) {m = m - 1}
                    if(m < 0){ alert('Время истекло.') }

                    document.getElementById('timer').innerHTML =
                        m + ":" + s;
                    setTimeout(startTimer, 1000);
                }

                function checkSecond(sec) {
                    if (sec < 10 && sec >= 0) {sec = "0" + sec} // add zero in front of numbers < 10
                    if (sec < 0) {sec = "59"}
                    return sec;
                }
            }
            function checkCode() {
                let value = document.getElementById("uintCode").value;
                if (value.length === 4 && value == code) {
                    document.getElementById("uintCode").disabled = true;
                    if (doesExist == 1) {
                        alert("Тех.инфо: Вход");
                        myFunction();
                        sessionActivated(name);
                    } else {
                        alert("Тех.инфо: Регистрация");
                        document.getElementById("loginWrapper").style.display = "none";
                        document.getElementById("signupWrapper").style.display = "block";
                        document.getElementById("telNumber").value = document.getElementById("intLimitTextBox").value;
                    }
                }
            }
            function postForm() {
                let formData = new FormData(document.forms.verification);
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/pizza-service/post_services/number_verification.php", true);
                xhr.send(formData);

                xhr.onload = function() {
                    let isFound = JSON.parse(xhr.response);
                    name = isFound[1];
                    invoke(isFound[0]);
                }
            }
            function makeVisible() {
                let y = document.getElementById("intLimitTextBox");//79999999999
                let value = parseInt(y.value);
                if (value <= 7999999999) {
                    alert("Неверно набран номер.")
                    return false;
                }
                let x = document.getElementById("validateCode");
                if (x.style.visibility === "hidden" && only_once === 0) {
                    x.style.visibility = "visible";
                    postForm();
                    only_once = 1;
                }
            }
        </script>
        <div id="loginDarkThing" class="login_dark_thing" style="display:none;">
            <div class="login_dark_thing-1"></div>
            <div class="login_thing">
                <div class="login_thing__form">
                    <i onclick="myFunction()" class="close_icon"><svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M9.84606 12.4986L0.552631 3.20519C-0.1806 2.47196 -0.1806 1.28315 0.552631 0.549923C1.28586 -0.183308 2.47466 -0.183308 3.20789 0.549923L12.5013 9.84335L21.792 0.552631C22.5253 -0.1806 23.7141 -0.1806 24.4473 0.552631C25.1805 1.28586 25.1805 2.47466 24.4473 3.20789L15.1566 12.4986L24.45 21.792C25.1832 22.5253 25.1832 23.7141 24.45 24.4473C23.7168 25.1805 22.528 25.1805 21.7947 24.4473L12.5013 15.1539L3.20519 24.45C2.47196 25.1832 1.28315 25.1832 0.549923 24.45C-0.183308 23.7168 -0.183308 22.528 0.549923 21.7947L9.84606 12.4986Z" fill="white"></path>
                        </svg></i>
                    <div class="login_thing__form__content">
                        <div id="loginWrapper" class="login__thing__wrapper">
                            <h1 class="enter_website">Вход на сайт</h1>
                            <div class="enter_website_number">
                            <div class="enter_website_number--title">Номер телефона</div>
                            <form name="verification" style="display: inline-block;">
                                <div class="phone-input__wrapper">
                                    <input id="intLimitTextBox" type="text" name="telephone" class="phone-input__wrapper__in" placeholder="+7 999 999-99-99" required>
                                </div>
                            </form>
                        </div>
                            <script>
                            function checkItOut() {
                                console.log(code);
                                let for_compare = document.getElementById("uintCode").value;
                                if (parseInt(for_compare) === code) {
                                    let x = document.getElementById("loginDarkThing");
                                    x.style.display = "none";
                                } else {
                                    alert("Неверно введен код.");
                                }
                            }
                        </script>
                            <div id="validateCode" class="enter_website_number" style="visibility: hidden;">
                                <div class="enter_website_number--title">Код из СМС</div>
                                <div class="phone-input__wrapper">
                                    <input id="uintCode" type="text" class="phone-input__wrapper__in" placeholder="0000" required onkeyup="checkCode()">
                                </div>
                                <div id="timer"></div>
                            </div>
                            <div id="enterButton" class="enter_button" onclick="makeVisible()">Выслать код</div>
                            <div id="legalThing" class="legal_thing">Продолжая, вы соглашаетесь <a href="https://ru.wikipedia.org/wiki/%D0%9F%D1%80%D0%B0%D0%B2%D0%BE%D0%B2%D0%B0%D1%8F_%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%82%D0%B8%D0%BA%D0%B0" target="_blank">со сбором и обработкой персональных данных и пользовательским соглашением</a></div>
                        </div>
                        <div id="signupWrapper" class="signup__thing__wrapper" style="display: none;">
                            <script>
                                let ifOk;
                                async function registerForm() {
                                    let personInput = document.getElementById("nameOfPerson");

                                    if (personInput.value.length === 0) {
                                        alert("Введите ваше имя!");
                                        return false;
                                    }
                                    personInput.disabled = true;

                                    let user = {
                                        firstName: personInput.value,
                                        mobile: parseInt(unchangedNumber)
                                    };

                                    await fetch('/pizza-service/post_services/register.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                            'Accept': 'application/json;charset=utf-8'
                                        },
                                        body: JSON.stringify(user)
                                    }).then(response => response.json()).then(response => {
                                        console.log(response[0]);
                                        if (response[0] === "Success") {
                                            myFunction();
                                            sessionActivated(response[1]);
                                        } else {
                                            alert("Произошла непредвиденная ошибка.");
                                        }
                                    });
                                }
                            </script>
                            <form name="register">
                                <h1 class="enter_website">Регистрация</h1>
                                <div class="profile__row">
                                    <label class="profile__inputto">
                                        <span class="label">Имя</span>
                                        <div class="input-containerino">
                                            <input id="nameOfPerson" type="text" name="firstName" class="inputterino" placeholder="Ivan">
                                        </div>
                                    </label>
                                </div>
                                <div class="profile__row">
                                    <label class="profile__inputto">
                                        <span class="label">Номер телефона</span>
                                        <div class="input-containerino">
                                            <input id="telNumber" type="text" name="mobile" disabled="" class="inputterino">
                                        </div>
                                    </label>
                                </div>
                                <div class="enter_button signup_button" onclick="registerForm()">Зарегистрироваться</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let regex = /^\+(?:[0-9] ?){6,14}[0-9]$/;
    setInputFilter(document.getElementById("intLimitTextBox"), function(value) {
        return /^\+\d*$/.test(value) && (value === "+" || parseInt(value) <= 79999999999); });
    setInputFilter(document.getElementById("uintCode"), function(value) {
        return /^\d*$/.test(value) && (value === "" || parseInt(value) <= 9999); });
    setInputFilter(document.getElementById("nameOfPerson"), function(value) {
        return /^[a-z]*$/i.test(value); });
</script>