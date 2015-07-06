<html>
<head>
	<title>Pix's Checker</title>
<meta charset="utf-8">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/slider.js"></script>
</head>
<body>
<div class="container">

<?php 
$host = "localhost"; 
$username = "root"; 
$password = "motdepasselocalhostgwen"; 
$db_name = "Checker"; 
$link = mysqli_connect("$host", "$username", "$password", "$db_name")or die("Erreur de connexion"); 
$password = str_shuffle("salutcommentcava1234567890"); 
$date = date("Y-m-d H:i:s"); 
?>


<!-- Page des mots de passe -->

<?php if (isset($_GET['hebergeur'])) { ?>

<div class="text-center button">
<a href="index.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
<a href="index.php?depenses"><button class="btn btn-danger"><i class="fa fa-eur fa-5x"></i></button></a><br><br><br>
<form method="POST" action="index.php?hebergeur"> 
<button class="btn-lg btn-warning" name="submit" required>Générer un nouveau</button>
</form>
</div>

<div class="box text-center">
	<p></p>
 <?php echo "<strong>Mot de passe : </strong><br>$password<br><br><strong>Généré le :</strong><br>$date"; ?> 
</div>


<?php 

	if (isset($_POST['submit'])) {

	mysqli_query($link , "INSERT INTO Password(Password,Date) VALUES ('$password','$date')");
	mysqli_close(); 
	}
} 


// Page des dépenses

elseif(isset($_GET['depenses'])){ ?>

<div class="text-center button">
<a href="index.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
<a href="index.php?hebergeur"><button class="btn btn-success"><i class="fa fa-code fa-5x"></i></button></a>
</div>

<div class="box">

<?php 
$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM MoneyAvailable WHERE id=1"));	
echo "<p class='text-center'>Votre somme actuelle : $row[SommeDisponible] €</p>"; 
mysqli_close(); 
?>

</div>
<form class="init text-center" method="POST" action="">
<input type="text" name="submit" placeholder="Somme par défault"><br>
<button class="btn btn-info">Valider</button>
</form>

<?php 

// Initialise une nouvelle somme

if (isset($_POST) && isset($_POST['submit'])) {
	if (!empty($_POST['submit'])) {
		
		mysqli_query($link, "UPDATE MoneyAvailable SET SommeDisponible='$_POST[submit]'");
		header('Location: index.php?depenses');

	}
}



 ?>

<form action="index.php?depenses" method="POST" class="text-center submit">
<input type="number" name="depenses" placeholder="Depenses">
<input type="number" name="ajout" placeholder="Ajout"><br>
<textarea cols="20" rows="2" name="nom" placeholder="Petite description de la dépense" required></textarea><br>
<button type="submit" class="btn btn-warning">Actualiser</button>
</form>

<?php 

	$date = date("Y-m-d H:i:s");

	$ajout = $row['SommeDisponible'] + $_POST['ajout'];
	$depenses = $row['SommeDisponible'] - $_POST['depenses'];

	if (isset($_POST['depenses']) && !empty($_POST['depenses'])) {
		mysqli_query($link, "UPDATE MoneyAvailable SET Date='$date', SommeDisponible='$depenses'");
		mysqli_query($link, "INSERT INTO OperationList(nom,retrait,date) VALUES ('$_POST[nom]','$_POST[depenses]','$date')");
		mysqli_close(); 
		header("location: index.php?depenses");	
	}

	elseif(isset($_POST['ajout']) && !empty($_POST['ajout'])){
		mysqli_query($link, "UPDATE MoneyAvailable SET Date='$date', SommeDisponible='$ajout'");
		mysqli_query($link, "INSERT INTO OperationList(nom,ajout,date) VALUES ('$_POST[nom]','$_POST[ajout]','$date')");
		mysqli_close(); 
		header("Location: index.php?depenses");	
	}

			// Liste des dépenses

		echo "<button class='toggle btn btn-info text-center' id='toggle'>Cacher / Afficher</button><br>";

		$result = mysqli_query($link, "SELECT * FROM OperationList");
		echo "<div class='liste_depenses'>";
			while($row = mysqli_fetch_assoc($result)) {
			
			echo "<div class='depenses col-md-2'>
			<a href='index.php?remove=$row[id]'>
			<i class='fa fa-remove btn btn-remove' style='float: right'></i></a>
			<p><span class='id'>Identifiant</span> : $row[nom]</p>
			<p><span class='id'>Retrait</span> : $row[retrait] €</p>
			<p><span class='id'>Ajout</span> : $row[ajout] €</p>
			<p><span class='id'>Date</span> : $row[date]</p>
			</div>";
			} 
		echo "</div>";

}

elseif (isset($_GET['remove'])) {
	mysqli_query($link, "DELETE FROM OperationList WHERE id='$_GET[remove]'");
	header('Location: index.php?depenses');
}

elseif (isset($_GET['course'])) { 

	$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM ShoppingList"));

	?>

		<div class="text-center button">
			<a href="index.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
			<a href="index.php?hebergeur"><button class="btn btn-success"><i class="fa fa-code fa-5x"></i></button></a>
			<a href="index.php?hebergeur"><button class="btn btn-danger"><i class="fa fa-eur fa-5x"></i></button></a>
		</div>
	
		<h1 class="text-center main">Votre liste</h1>	
		<?php echo "<center><a href='index.php?flush'><button class='btn btn-red text-center'>Vider la liste</button></a></center>"; ?>
			<form action="index.php?course" method="POST" class="text-center article_list">
				<input type="text" name="article" placeholder="Nom de l'article" maxlength="55" required>
				<button type="submit" class="btn btn-warning">Ajouter</button><br>
			</form>

	
<?php 

if (isset($_POST) && isset($_POST['article'])) {
	if (!empty($_POST['article'])) {
		mysqli_query($link, "INSERT INTO ShoppingList(Article_Name) VALUES ('$_POST[article]')");
	}
}


$result = mysqli_query($link, "SELECT * FROM ShoppingList");

	while ($row = mysqli_fetch_assoc($result)) {
		echo "<li class='shopping_list col-md-2'><a href='index.php?remove_article=$row[id]'>
			<i class='fa fa-remove btn btn-remove' style='float: right'></i></a>Nom de l'article : <br>- $row[Article_name]</li>";
	}
}

elseif(isset($_GET['remove_article'])) {
		mysqli_query($link, "DELETE FROM ShoppingList WHERE id='$_GET[remove_article]'");
		header('Location: index.php?course');
	}
elseif(isset($_GET['flush'])) {
		mysqli_query($link, "DELETE FROM ShoppingList WHERE 1");
		header('Location: index.php?course');
	}	



// Page par défault

else{

echo "<h1 class='text-center main'>Pix's Checker</h1>
<div class='text-center button'>
<a href='index.php?depenses'><button class='btn btn-danger'><i class='fa fa-eur fa-5x'></i><br>Mes dépenses</button></a>
<a href='index.php?hebergeur'><button class='btn btn-success'><i class='fa fa-code fa-5x'></i><br>Password generator</button></a><br>
<a href='index.php?course'><button class='btn btn-info course'><i class='fa fa-barcode fa-5x'></i><br>Créer une liste de course</button></a>
</div>";

} 


?>

</div>
</body>
</html>