<div class="container pizzanav" style="max-width: 1241px;">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link <?php if ($CURRENT_PAGE == "Index") {?>active<?php }?>" href="index.php">Меню</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if ($CURRENT_PAGE == "About") {?>active<?php }?>" href="about.php">О нас</a>
        </li>
    </ul>
    <script>
        async function checkLogging() {
            await fetch('/pizza-service/get_services/check_logged.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Accept': 'application/json;charset=utf-8'
                }
            }).then(response => response.json()).then(response => {
                console.log(response[0]);
                if (response[0] === "notLoggedIn") {
                    myFunction();
                } else if (response[0] === "logged") {
                    window.open("cart.php","_self");
                }
            });
        }
    </script>
    <button class="pizzanav__cart" onclick="checkLogging()">
        Корзина
    </button>
</div>