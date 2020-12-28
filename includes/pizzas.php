<script>
    async function postAddPizza(id, name, price, image) {
        let order = {
            pizza_id: id,
            pizza_name: name,
            pizza_price: price,
            pizza_image: image
        };

        await fetch('/pizza-service/post_services/add_pizza.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'Accept': 'application/json;charset=utf-8'
            },
            body: JSON.stringify(order)
        }).then(response => response.json()).then(response => {
            if (response[0] === "notLoggedIn") {
                myFunction();
            } else if (response[10] === "order_is_added") {
                console.log(response);
            }
        });
    }
</script>
<main id="pizzasMenu" class="main-pizzas">
    <h1 class="name-tag">Пицца</h1>
    <section>

<?php
$sql = "SELECT t.* FROM `pizza-hut`.pizzas t";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
?>

<article class="section__article">
    <main class="section__article__main">
        <figure class="section__article__main__figure">
            <img src="<?php echo $row["image"];?>"
                alt="<?php echo $row["name"]; ?>" title="<?php echo $row["name"]; ?>">
        </figure>
        <h2 class="section__article__main__h2"><?php echo $row["name"]; ?></h2>
    </main>
    <?php echo $row["desc"]; ?>
    <div class="section__article__main__footer">
        <div class="section__article__main__footer__price">
            от
            <span class="section__article__main__footer__price__money">
                <span class="section__article__main__footer__price__money--value"><?php echo $row["price"];?></span>
                <span class="section__article__main__footer__price__money--currency">₽</span>
            </span>
        </div>
        <div class="section__article__main__footer--button" onclick="postAddPizza(<?php echo $row["pizza_id"].",'".$row["name"]."',".$row["price"].",'".$row["image"]."'";?>);">Добавить</div>
    </div>
</article>

<?php
    }
}
?>

</section>
</main>
