<?php include("includes/a_config.php");?>
<!DOCTYPE html>
<html>
<head>
    <?php include("includes/head-tag-contents.php");?>
</head>
<body>

<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>

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
    function prettyNumber(ourNumber) {
        return ourNumber.substring(0, 2) + " " + ourNumber.substring(2, 5) + " " + ourNumber.substring(5, 8) + "-" + ourNumber.substring(8, 10) + "-" + ourNumber.substring(10, ourNumber.length)
    }
    function unprettyNumber(ourNumber) {
        return ourNumber.replaceAll(' ', '').replaceAll('-', '').replace('+', '');
    }
    function sessionDeactivated() {
        window.open("index.php","_self")
    }
    async function exitSession() {
        await fetch('/pizza-service/get_services/session_end.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'Accept': 'application/json;charset=utf-8'
            }
        }).then(response => response.json()).then(response => {
            console.log(response);
            sessionDeactivated();
        });
    }
</script>
<div class="container rr" id="main-content">
    <h2 class="personal__data--title" style="margin-top: 50px;">Личные данные</h2>
    <div class="profile__row">
        <label class="profile__inputto">
            <span class="label">Имя</span>
            <div class="input-containerino">
                <input type="text" name="firstName" disabled="" class="inputterino" value="<?php echo $_SESSION["firstName"];?>">
            </div>
        </label>
    </div>
    <div class="profile__row">
        <label class="profile__inputto">
            <span class="label">Номер телефона</span>
            <div class="input-containerino">
                <input type="text" name="mobile" disabled="" class="inputterino" value="<?php echo $_SESSION["number"];?>" >
            </div>
        </label>
    </div>
    <?php
        if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1) { ?>
            <h2 class="personal__data--title">Управление персоналом</h2>
            <div class="table__wrapper">
                <div class="table__wrapper__union">
                    <div class="table__wrapper__union--head">
                        <table>
                            <thead>
                            <tr>
                                <th class="column1 col_head">Имя</th>
                                <th class="column2 col_head">Номер</th>
                                <th class="column3 col_head">Адрес</th>
                                <th class="column4 col_head">Водитель?</th>
                                <th class="column5 col_head">Админ?</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="table__wrapper__union--table">
                        <table>
                            <tbody>
                            <script>
                                async function changeData(input, id, type) {
                                    let bulk = document.getElementById(input).innerText;
                                    if (type === 'number') {
                                        bulk = unprettyNumber(bulk);
                                    } else if (type === 'address') {
                                        bulk = bulk.substring(20);
                                    }
                                    let request = {
                                        user_id: id,
                                        data: bulk,
                                        type: type
                                    };

                                    await fetch('/pizza-service/post_services/change_profile_data.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                            'Accept': 'application/json;charset=utf-8'
                                        },
                                        body: JSON.stringify(request)
                                    }).then(response => response.json()).then(response => {
                                        if (response[0] === "notLoggedIn") {
                                            myFunction();
                                        } else if (response[10] === "Success") {
                                            console.log(response);
                                        } else if (response[10] === "error") {
                                            console.log("Cannot update mysql table");
                                        }
                                    });
                                }
                                function editValue(input, id, type) {
                                    let spanElement = document.getElementById("rawBulk" + input);
                                    let inputElement = document.getElementById("inputBulk" + input);
                                    let temp_bulk = spanElement.innerText;

                                    if (!spanElement.classList.contains('hidden_case')) {
                                        spanElement.classList.add('hidden_case');
                                        inputElement.classList.remove('hidden_case');
                                    } else if (inputElement.classList.contains('number_cell')) {
                                        if (inputElement.value === '+' || inputElement.value === '') {
                                            alert("Поле не может быть пустым!");
                                            inputElement.value = temp_bulk;
                                            return false;
                                        }
                                        if (unprettyNumber(inputElement.value) <= 7999999999) {
                                            alert("Неправильно введён номер!");
                                            return false;
                                        }
                                        if (spanElement.innerText !== inputElement.value) {
                                            spanElement.innerText = prettyNumber(inputElement.value);
                                        }
                                        spanElement.classList.remove('hidden_case');
                                        inputElement.classList.add('hidden_case');
                                    } else {
                                        if (inputElement.value === '') {
                                            alert("Поле не может быть пустым!");
                                            inputElement.value = temp_bulk;
                                            return false;
                                        }
                                        if (spanElement.innerText !== inputElement.value) {
                                            spanElement.innerText = inputElement.value;
                                        }
                                        if (spanElement.classList.contains('city')) {
                                            spanElement.innerText = 'г. Нижний Новгород, ' + spanElement.innerText;
                                        }
                                        spanElement.classList.remove('hidden_case');
                                        inputElement.classList.add('hidden_case');
                                    }
                                    if (document.getElementById("inputBulk" + input).classList.contains('hidden_case')) {
                                        changeData('rawBulk' + input, id, type);
                                    }
                                }

                                async function changePrivilege(answer, type, id) {
                                    let button = (type === 'driver') ? 'driver' : 'admin';
                                    let newAnswer = (answer === 'no') ? 'yes' : 'no';
                                    document.getElementById(newAnswer + button + id).classList.remove('hidden_last_button');
                                    document.getElementById(answer + type + id).classList.add('hidden_last_button');

                                    let request = {
                                        user_id: id,
                                        user_perm: (answer === 'yes') ? 0 : 1,
                                        user_type: 'is_' + type
                                    };
                                    await fetch('/pizza-service/post_services/change_privilege.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                            'Accept': 'application/json;charset=utf-8'
                                        },
                                        body: JSON.stringify(request)
                                    }).then(response => response.json()).then(response => {
                                        console.log(response);
                                        if (response[0] === "notLoggedIn") {
                                            myFunction();
                                        } else if (response[10] === "Success") {
                                            console.log(response);
                                        } else if (response[10] === "Error") {
                                            console.log("Cannot update mysql table");
                                        }
                                    });
                                }
                            </script>
                            <?php
                            include("includes/connect_sql.php");
                            $sql = "SELECT t.* FROM `pizza-hut`.users t";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $i = 0;
                                $random = 1000 + $i;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $numberino = substr_replace($row["number"], ' ', 1, 0);
                                    $numberino = substr_replace($numberino, ' ', 5, 0);
                                    $numberino = substr_replace($numberino, '-', 9, 0);
                                    $numberino = substr_replace($numberino, '-', 12, 0);

                                    ?>
                                    <tr class="table__wrapper__union--table--body">
                                        <td class="column1">
                                            <div id="rawBulk<?php echo $random + $i ?>" class="row_bulk_content"><?php echo $row["name"];?></div>
                                            <input id="inputBulk<?php echo $random + $i ?>" class=" inputterino name_cell hidden_case" value="<?php echo $row["name"];?>">
                                            <svg class="edit_button" onclick="editValue('<?php echo $random + $i ?>', <?php echo $row["user_id"];?>, 'name')" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="25" height="25" x="0" y="0" viewBox="0 0 492.49284 492" xml:space="preserve"><g><path xmlns="http://www.w3.org/2000/svg" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path></g></svg>
                                        </td>
                                        <?php $i += 1; ?>
                                        <td class="column2">
                                            <div id="rawBulk<?php echo $random + $i ?>" class="row_bulk_content">+<?php echo $numberino;?></div>
                                            <input id="inputBulk<?php echo $random + $i ?>" class="inputterino number_cell hidden_case" value="+<?php echo $numberino;?>">
                                            <svg class="edit_button" onclick="editValue('<?php echo $random + $i ?>', <?php echo $row["user_id"];?>, 'number')" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="25" height="25" x="0" y="0" viewBox="0 0 492.49284 492" xml:space="preserve"><g><path xmlns="http://www.w3.org/2000/svg" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path></g></svg>
                                        </td>
                                        <?php $i += 1; ?>
                                        <td class="column3">
                                            <div id="rawBulk<?php echo $random + $i ?>" class="row_bulk_content city"><?php echo $row["address"];?></div>
                                            <input id="inputBulk<?php echo $random + $i ?>" class=" inputterino address_cell hidden_case" value="<?php echo $row["address"];?>">
                                            <svg class="edit_button" onclick="editValue('<?php echo $random + $i ?>', <?php echo $row["user_id"];?>, 'address')" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="25" height="25" x="0" y="0" viewBox="0 0 492.49284 492" xml:space="preserve"><g><path xmlns="http://www.w3.org/2000/svg" d="m304.140625 82.472656-270.976563 270.996094c-1.363281 1.367188-2.347656 3.09375-2.816406 4.949219l-30.035156 120.554687c-.898438 3.628906.167969 7.488282 2.816406 10.136719 2.003906 2.003906 4.734375 3.113281 7.527344 3.113281.855469 0 1.730469-.105468 2.582031-.320312l120.554688-30.039063c1.878906-.46875 3.585937-1.449219 4.949219-2.8125l271-270.976562zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path><path xmlns="http://www.w3.org/2000/svg" d="m476.875 45.523438-30.164062-30.164063c-20.160157-20.160156-55.296876-20.140625-75.433594 0l-36.949219 36.949219 105.597656 105.597656 36.949219-36.949219c10.070312-10.066406 15.617188-23.464843 15.617188-37.714843s-5.546876-27.648438-15.617188-37.71875zm0 0" fill="#ff8e3c" data-original="#000000" style="" class=""></path></g></svg>
                                        </td>
                                        <td class="column4 table_bulk">
                                            <img id="nodriver<?php echo $row['user_id'];?>" onclick="changePrivilege('no','driver', <?php echo $row['user_id'];?>)" class="other_edit_button <?php echo ($row["is_driver"] == '1')?'hidden_last_button':'';?>" src="images/error.svg" height="25px">
                                            <svg id="yesdriver<?php echo $row['user_id'];?>" onclick="changePrivilege('yes','driver', <?php echo $row['user_id'];?>)" class="other_edit_button <?php echo ($row["is_driver"] == '1')?'':'hidden_last_button';?>" xmlns="http://www.w3.org/2000/svg" width="30" height="30" x="0" y="0" viewBox="0 0 417.81333 417" xml:space="preserve"><g transform="matrix(1,0,0,1,1.7053025658242404e-13,40.00000000000006)"><path xmlns="http://www.w3.org/2000/svg" d="m159.988281 318.582031c-3.988281 4.011719-9.429687 6.25-15.082031 6.25s-11.09375-2.238281-15.082031-6.25l-120.449219-120.46875c-12.5-12.5-12.5-32.769531 0-45.246093l15.082031-15.085938c12.503907-12.5 32.75-12.5 45.25 0l75.199219 75.203125 203.199219-203.203125c12.503906-12.5 32.769531-12.5 45.25 0l15.082031 15.085938c12.5 12.5 12.5 32.765624 0 45.246093zm0 0" fill="#7aa754" data-original="#000000" style="" class=""></path></g></svg>
                                        </td>
                                        <td class="column5 table_bulk">
                                            <img id="noadmin<?php echo $row['user_id'];?>" onclick="changePrivilege('no','admin', <?php echo $row['user_id'];?>)" class="other_edit_button <?php echo ($row["is_admin"] == '1')?'hidden_last_button':'';?>" src="images/error.svg" height="25px">
                                            <svg id="yesadmin<?php echo $row['user_id'];?>" onclick="changePrivilege('yes','admin', <?php echo $row['user_id'];?>)" class="other_edit_button <?php echo ($row["is_admin"] == '1')?'':'hidden_last_button';?>" xmlns="http://www.w3.org/2000/svg" width="30" height="30" x="0" y="0" viewBox="0 0 417.81333 417" xml:space="preserve"><g transform="matrix(1,0,0,1,1.7053025658242404e-13,40.00000000000006)"><path xmlns="http://www.w3.org/2000/svg" d="m159.988281 318.582031c-3.988281 4.011719-9.429687 6.25-15.082031 6.25s-11.09375-2.238281-15.082031-6.25l-120.449219-120.46875c-12.5-12.5-12.5-32.769531 0-45.246093l15.082031-15.085938c12.503907-12.5 32.75-12.5 45.25 0l75.199219 75.203125 203.199219-203.203125c12.503906-12.5 32.769531-12.5 45.25 0l15.082031 15.085938c12.5 12.5 12.5 32.765624 0 45.246093zm0 0" fill="#7aa754" data-original="#000000" style="" class=""></path></g></svg>
                                        </td>
                                    </tr>
                                <?php $i += 1; } }

                                include("includes/disconnect_sql.php"); ?>
                            </tbody>
                        </table>
                        <div class="ps__rail-x">
                            <div class="ps__thumb-x" tabindex="0"></div>
                        </div>
                        <div class="ps__rail-y">
                            <div class="ps__thumb-y" tabindex="0"></div>
                        </div>
                    </div>
                </div>
            </div>
            <h2 class="personal__data--title">Заказы</h2>
    <table class="tables">
        <tr>
            <td>Названия</td>
            <td>Цены</td>
            <td>Статус</td>
            <td>user_id</td>
            <td>driver_id</td>
        </tr>
        <?php
        include("includes/connect_sql.php");
        $sql = "SELECT t.* FROM `pizza-hut`.orders t";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["price"]; ?></td>
                <td><?php echo $row["status"]; ?></td>
                <td><?php echo $row["user_id"]; ?></td>
                <td><?php echo (($row["driver_id"] == '') ? "Водитель не назначен" : $row["driver_id"]) ?></td>
            </tr>
        <?php
        } }
        include("includes/disconnect_sql.php");
        ?>
    </table>
            <h2 class="personal__data--title">Статистика</h2>
    <?php } ?>
    <?php
    if (isset($_SESSION["is_driver"]) && $_SESSION["is_driver"] == 1) { ?>
        <h2 class="personal__data--title">Вы водитель!</h2>
        <table class="tables" style="margin-bottom: 40px">
            <tr>
                <td>Названия</td>
                <td>Цены</td>
                <td>Статус</td>
                <td>user_id</td>
                <td>Действие</td>
            </tr>
            <?php
            include("includes/connect_sql.php");
            $sql = "SELECT t.* FROM `pizza-hut`.orders t";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["name"]; ?></td>
                        <td><?php echo $row["price"]; ?></td>
                        <td><?php echo $row["status"]; ?></td>
                        <td><?php echo $row["user_id"]; ?></td>
                        <td><?php echo (($row["status"] == 'готовится') ? "Забрать" : "...") ?></td>
                    </tr>
                    <?php
                } }
            include("includes/disconnect_sql.php");
            ?>
        </table>
    <?php } ?>
    <?php
    if (isset($_SESSION["firstName"]) && $_SESSION["is_driver"] == 0 && $_SESSION["is_admin"] == 0) { ?>
    <h2 class="personal__data--title">Мои заказы</h2>
    <div class="my__orders">
        <table class="tables">
            <tr>
                <td>Названия</td>
                <td>Цены</td>
                <td>Статус</td>
            </tr>
        <?php
        include("includes/connect_sql.php");
        $sql = "SELECT t.* FROM `pizza-hut`.orders t WHERE user_id = ".$_SESSION["our_user_id"];
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["price"]; ?></td>
                    <td><?php echo $row["status"]; ?></td>
                </tr>
            <?php }
        }
        include("includes/disconnect_sql.php");
        } ?>
        </table>
        <div class="custom_exit_button" onclick="exitSession();">
            Выйти
        </div>
    </div>
</div>
<script>
    let number_cells = document.getElementsByClassName('number_cell');
    for (let i = 0; i < number_cells.length; i++) {
        setInputFilter(number_cells[i], function(value) {
            return /^\+\d*$/.test(value) && (value === "+" || parseInt(value) <= 79999999999); })
    }
</script>

<?php include("includes/footer.php");?>

</body>
</html>