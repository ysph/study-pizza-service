<?php
	switch ($_SERVER["SCRIPT_NAME"]) {
		case "/pizza-service/about.php":
			$CURRENT_PAGE = "About"; 
			$PAGE_TITLE = "Pizza-hut nn | О нас";
			break;
		case "/pizza-service/Order.php":
			$CURRENT_PAGE = "Заказ";
			$PAGE_TITLE = "Pizza-hut nn | Заказ";
			break;
        case "/pizza-service/profile.php":
            $CURRENT_PAGE = "Profile";
            $PAGE_TITLE = "Pizza-hut nn | Профиль";
            break;
        case "/pizza-service/cart.php":
            $CURRENT_PAGE = "Cart";
            $PAGE_TITLE = "Pizza-hut nn | Корзина";
            break;
		default:
			$CURRENT_PAGE = "Index";
			$PAGE_TITLE = "Pizza-hut nn | Меню";
	}
