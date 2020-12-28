<?php include("includes/a_config.php");?>
<!DOCTYPE html>
<html>
<head>
    <?php include("includes/head-tag-contents.php");?>
</head>
<body>

<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>

<div class="container" id="main-content">
    <div class="cart--margin"></div>
    <div class="cart__wrapper">
        <h1 class="cart__wrapper--title">Корзина</h1>
        <script>
            let grandTotal;
            function recalculate() {
                let total = 0;
                let allPrices = document.getElementsByClassName("disabled__price");
                for (let i = 0; i < allPrices.length; i++) {
                    total += parseInt(allPrices[i].innerText);
                }
                document.getElementById("cartGrandTotal").innerText = grandTotal - total;
            }
            async function deleteItem(item_id) {
                document.getElementById("itemN" + item_id).classList.add('disabled__item');
                document.getElementById("priceN" + item_id).classList.add('disabled__price');
                recalculate();
                let request = {
                    pizza_id: item_id,
                };

                await fetch('/pizza-service/post_services/delete_pizza.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8',
                        'Accept': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify(request)
                }).then(response => response.json()).then(response => {
                    if (response[0] === "notLoggedIn") {
                        myFunction();
                    } else if (response[10] === "order_removed") {
                        console.log(response);
                    }
                });
            }
            async function changeAmount(item_id, type) {
                let request = {
                    pizza_id: item_id,
                    change_type: type
                };
                let grandy = document.getElementById("cartGrandTotal");
                let amount = document.getElementById("amountN" + item_id);
                let price = document.getElementById("priceN" + item_id);
                let forOne = (amount.innerText != 1) ? parseInt(price.innerText) / parseInt(amount.innerText) : parseInt(price.innerText);
                if (amount.innerText <= 1 && type === 'dec') {
                    document.getElementById("itemN" + item_id).classList.add("disabled__item");
                    document.getElementById("priceN" + item_id).classList.add("disabled__price");
                    price.classList.add('disabled__price');
                    grandy.innerText = parseInt(grandy.innerText) - forOne;
                } else {
                    if (type === 'inc') {
                        amount.innerText = parseInt(amount.innerText) + 1;
                        price.innerText = parseInt(price.innerText) + forOne;
                        grandy.innerText = parseInt(grandy.innerText) + forOne;
                    }
                    if (type === 'dec') {
                        amount.innerText = parseInt(amount.innerText) - 1;
                        price.innerText = parseInt(price.innerText) - forOne;
                        grandy.innerText = parseInt(grandy.innerText) - forOne;
                    }
                }
                if (grandy.innerText == 0) {
                    document.getElementById("goNextButton").disabled = true;
                }
                await fetch('/pizza-service/post_services/change_pizza.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8',
                        'Accept': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify(request)
                }).then(response => response.json()).then(response => {
                    if (response[0] === "notLoggedIn") {
                        myFunction();
                    } else if (response[10] === "order_changed") {
                        console.log(response);
                    }
                });
            }
        </script>
        <?php
        if (isset($_SESSION["pizzas_name"])) {
        foreach($_SESSION["pizzas_name"] as $key => $value) { ?>
            <div class="cart__wrapper__pizza" id="<?php echo "itemN" . $key;?>">
                <img src="<?php echo $_SESSION["pizzas_image"][$key]; ?>" alt="<?php echo $value; ?>" class="cart__wrapper__pizza--image">
                <div class="cart__wrapper__pizza__description">
                    <h3 class="cart__wrapper__pizza__description--title">
                        <?php echo $value;?>
                    </h3>
                    <div class="cart__wrapper__pizza__description--note">
                        <div>Средняя 30 см, традиционное тесто</div>
                    </div>
                </div>
                <div class="cart__wrapper__pizza__amount">
                    <div class="cart__wrapper__pizza__amount__wrapper">
                        <button onclick="changeAmount(<?php echo $key;?>,'dec')" type="button" class="cart__wrapper__pizza__amount__wrapper--button">
                            <svg width="10" height="10" class="icon">
                                <rect fill="#454B54" y="4" width="10" height="2" rx="1"></rect>
                            </svg>
                        </button>
                        <div id="amountN<?php echo $key;?>" class="cart__wrapper__pizza__amount__wrapper--amount"><?php echo $_SESSION["pizzas_amount"][$key];?></div>
                        <button onclick="changeAmount(<?php echo $key;?>,'inc')" type="button" class="cart__wrapper__pizza__amount__wrapper--button">
                            <svg width="10" height="10" class="icon"><g fill="#454B54">
                                    <rect x="4" width="2" height="10" ry="1"></rect><rect y="4" width="10" height="2" rx="1"></rect></g>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="cart__wrapper__pizza__price">
                    <span class="cart__wrapper__pizza__price__wrapper">
                        <span id="<?php echo 'priceN' . $key;?>" class="cart__wrapper__pizza__price__wrapper--value"><?php echo $_SESSION["pizzas_price"][$key] * $_SESSION["pizzas_amount"][$key];?></span>
                        <span class="cart__wrapper__pizza__price__wrapper--currency"> ₽</span>
                    </span>
                </div>
                <svg width="20" height="20" onclick="deleteItem(<?php echo $key;?>)" fill="none" class="cart__wrapper__pizza--delete">
                    <path d="M14.75 6h-9.5l.66 9.805c.061 1.013.598 1.695 1.489 1.695H12.6c.89 0 1.412-.682 1.49-1.695L14.75 6z" fill="#373536"></path>
                    <path d="M13.85 3.007H6.196C4.984 2.887 5.021 4.365 5 5h9.992c.024-.62.07-1.873-1.142-1.993z" fill="#373535"></path>
                </svg>
            </div>
        <?php } }?>
        <div class="cart__total">
            <div class="">
                <div class="cart__total__table">
                    <div class="cart__total__table__tr">
                        <div class="cart__total__table__tr__label">Сумма заказа:</div>
                        <div class="cart__total__table__tr__value">
                            <span class="cart__wrapper__pizza__price__wrapper">
                                <span id="cartGrandTotal" class="cart__wrapper__pizza__price__wrapper--value">
                                    <?php
                                    $total = 0;

                                    foreach ($_SESSION["pizzas_price"] as $key => $value) {
                                        $total += ($value * $_SESSION["pizzas_amount"][$key]);
                                    }
                                    echo $total;
                                    ?>
                                </span>
                                <span class="cart__wrapper__pizza__price__wrapper--currency"> ₽</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function unprettyNumber(ourNumber) {
                return ourNumber.replaceAll(' ', '').replaceAll('-', '').replace('+', '');
            }
            async function makeOrder(number) {
                if (document.getElementById("goNextButton").disabled === true) {
                    return false;
                }
                let request = {
                    user_number: number
                };
                await fetch('/pizza-service/post_services/add_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8',
                        'Accept': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify(request)
                }).then(response => response.json()).then(response => {
                    if (response[0] === "notLoggedIn") {
                        myFunction();
                    } else if (response[10] === "order_removed") {
                        console.log(response);
                    }
                });
            }
        </script>
        <div class="cart__buttons">
            <button type="button" class="cart__buttons--button1" onclick="window.location.href='index.php';">
                Вернуться в меню
                <svg width="24" height="24" fill="none" class="button-arrow-left"><path d="M10 18l6-6-6-6" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <button id="goNextButton" onclick="makeOrder('<?php echo $_SESSION["unpretty_number"];?>')" type="button" class="cart__buttons--button2">Оформить заказ
                <svg width="24" height="24" fill="none" class="button-arrow-right"><path d="M10 18l6-6-6-6" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<script>
    let grandy = document.getElementById("cartGrandTotal").innerText;
    grandTotal = parseInt(grandy);
    if (grandy == 0) {
        document.getElementById("goNextButton").disabled = true;
    }
</script>

<?php include("includes/footer.php");?>

</body>
</html>