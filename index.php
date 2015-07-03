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
<input type="text" name="submit" placeholder="Somme par défault"><button class="btn btn-info">Valider</button>
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
		mysqli_query($link, "INSERT INTO ShoppingList(nom,retrait,date) VALUES ('$_POST[nom]','$_POST[depenses]','$date')");
		mysqli_close(); 
		header("location: index.php?depenses");	
	}

	elseif(isset($_POST['ajout']) && !empty($_POST['ajout'])){
		mysqli_query($link, "UPDATE MoneyAvailable SET Date='$date', SommeDisponible='$ajout'");
		mysqli_query($link, "INSERT INTO ShoppingList(nom,ajout,date) VALUES ('$_POST[nom]','$_POST[ajout]','$date')");
		mysqli_close(); 
		header("Location: index.php?depenses");	
	}

			// Liste des dépenses

		echo "<button class='toggle btn-lg btn-info text-center' id='toggle'>Liste des dépenses</button><br>";

		$result = mysqli_query($link, "SELECT * FROM ShoppingList");
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
	mysqli_query($link, "DELETE FROM ShoppingList WHERE id='$_GET[remove]'");
	header('Location: index.php?depenses');
}

elseif (isset($_GET['course'])) { ?>
	
		<h1 class="text-center main">Votre liste</h1>	
		<form method="POST" action="" class="liste_course">
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="submit" class="btn btn-info" value="Valider">
		</form>
	
<?php } 


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