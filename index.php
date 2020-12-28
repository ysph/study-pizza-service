<?php include("includes/a_config.php");?>
<?php include("includes/connect_sql.php");?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<?php include("includes/head-tag-contents.php");?>
</head>
<body>

<?php include("includes/design-top.php");?>
<?php include("includes/navigation.php");?>

<div class="container" id="main-content" style="max-width: 1241px;">
    <?php include("includes/pizzas.php"); ?>
</div>

<?php include("includes/footer.php");?>
</body>
</html>
<?php include("includes/disconnect_sql.php");?>