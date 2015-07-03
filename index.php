<meta charset="utf-8">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<?php 
$host = "localhost"; 
$username = "root"; 
$password = "motdepasselocalhostgwen"; 
$db_name = "MDPHebergeur"; 
$tbl_name = "1fichier"; 
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

<div class="container box text-center">
	<p></p>
 <?php echo "<strong>Mot de passe : </strong><br>$password<br><br><strong>Généré le :</strong><br>$date"; ?> 
</div>


<?php 

	if (isset($_POST['submit'])) {

	mysqli_query($link , "INSERT INTO 1fichier(Password,Date) VALUES ('$password','$date')");
	mysqli_close(); 
	}
} 


// Page des dépenses

elseif(isset($_GET['depenses'])){ ?>

<div class="text-center button">
<a href="index.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
<a href="index.php?hebergeur"><button class="btn btn-success"><i class="fa fa-code fa-5x"></i></button></a>
</div>

<div class="container box text-center">

<?php 
$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM DepensesGwen WHERE id=1"));	
echo "<p>Votre somme actuelle : $row[SommeDisponible] €</p>"; 
mysqli_close(); 
?>

</div> 
<form action="TCPDF.php" method="POST"><button class="btn btn-info">Générer un PDF</button></form>
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
		mysqli_query($link, "UPDATE DepensesGwen SET Date='$date', SommeDisponible='$depenses'");
		mysqli_query($link, "INSERT INTO ListeDepense(nom,retrait,date) VALUES ('$_POST[nom]','$_POST[depenses]','$date')");
		// echo "<div class='container text-center'>Mise à jour<br><i class='fa fa-refresh fa-spin fa-5x'></i></div>";
		mysqli_close(); 
		header("location: index.php?depenses");	
	}

	elseif(isset($_POST['ajout']) && !empty($_POST['ajout'])){
		mysqli_query($link, "UPDATE DepensesGwen SET Date='$date', SommeDisponible='$ajout'");
		mysqli_query($link, "INSERT INTO ListeDepense(nom,ajout,date) VALUES ('$_POST[nom]','$_POST[ajout]','$date')");
		// echo "<div class='container text-center'>Mise à jour<br><i class='fa fa-refresh fa-spin fa-5x'></i></div>";
		mysqli_close(); 
		// header("Refresh: 1; url=index.php?depenses");
		header("Location: index.php?depenses");	
	}

			// Liste des dépenses

		$result = mysqli_query($link, "SELECT * FROM ListeDepense");
		echo "<div class='container'>";
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
	mysqli_query($link, "DELETE FROM ListeDepense WHERE id='$_GET[remove]'");
	header('Location: index.php?depenses');
}

elseif (isset($_GET['course'])) { ?>
	<div class="container">
		<h1 class="text-center main">Votre liste</h1>	
		<form method="POST" action="" class="liste_course">
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="text" placeholder="Nom de l'article"><br>
		<input type="submit" class="btn btn-info" value="Valider">
		</form>
	</div>
<?php } 


// Page par défault

else{

echo "<h1 class='text-center main'>PixOFHeaven's Checker</h1>
<div class='text-center button'>
<a href='index.php?depenses'><button class='btn btn-danger'><i class='fa fa-eur fa-5x'></i><br>Mes dépenses</button></a>
<a href='index.php?hebergeur'><button class='btn btn-success'><i class='fa fa-code fa-5x'></i><br>Password generator</button></a><br>
<a href='index.php?course'><button class='btn btn-info course'><i class='fa fa-barcode fa-5x'></i><br>Créer une liste de course</button></a>
</div>";

} 


?>