<meta charset="utf-8">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

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
<a href="shuffle.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
<a href="shuffle.php?depenses"><button class="btn btn-danger"><i class="fa fa-money fa-5x"></i></button></a><br><br><br>
<form method="POST" action="shuffle.php?hebergeur"> 
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
<a href="shuffle.php"><button class="btn btn-warning"><i class="fa fa-home fa-5x"></i></button></a>
<a href="shuffle.php?hebergeur"><button class="btn btn-success"><i class="fa fa-code fa-5x"></i></button></a>
</div>

<div class="container box text-center">

<?php 
$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM DepensesGwen WHERE id=1"));	
echo "<p>Votre somme actuelle : $row[SommeDisponible] €</p>"; 
mysqli_close(); 
?>

</div> 

<form action="shuffle.php?depenses" method="POST" class="text-center submit">
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
		header("location: shuffle.php?depenses");	
	}

	elseif(isset($_POST['ajout']) && !empty($_POST['ajout'])){
		mysqli_query($link, "UPDATE DepensesGwen SET Date='$date', SommeDisponible='$ajout'");
		mysqli_query($link, "INSERT INTO ListeDepense(nom,ajout,date) VALUES ('$_POST[nom]','$_POST[ajout]','$date')");
		// echo "<div class='container text-center'>Mise à jour<br><i class='fa fa-refresh fa-spin fa-5x'></i></div>";
		mysqli_close(); 
		// header("Refresh: 1; url=shuffle.php?depenses");
		header("Location: shuffle.php?depenses");	
	}

	// Liste des dépenses

$result = mysqli_query($link, "SELECT * FROM ListeDepense");
echo "<div class='container'>";
	while($row = mysqli_fetch_assoc($result)) {
	
	echo "<div class='depenses col-md-2'>
	<a href='shuffle.php?remove=$row[id]'>
	<i class='fa fa-remove btn btn-danger' style='float: right'></i></a>
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
	header('Location: shuffle.php?depenses');
}



// Page par défault

else{

echo "<div class='text-center button'>
<a href='shuffle.php?depenses'><button class='btn btn-danger'><i class='fa fa-money fa-5x'></i></button></a>
<a href='shuffle.php?hebergeur'><button class='btn btn-success'><i class='fa fa-code fa-5x'></i></button></a>
</div>";

} 


?>

<style type="text/css">

 .box{
	font-size: 20px; 
	border: 1px #0088CC solid; 
	margin-top: 1%; 
	background-color: #0088CC; 
	border-radius: 10px; 
	color: white; 
	width: 30%; 
	padding: 1%
}

.suivi{
	width: 31%;
	margin: auto;
	border-radius: 5px;
	color: white;
	border: 1px #0088CC solid;
	padding: 2%;
}

.button{
	margin-top: 5%;
}

 .box:hover{
	background-color: white; 
	transition: 0.4s; 
	color: #0088CC;
	border: 1px #0088CC solid;
 } 

 .box p{
	word-break: break-all;
	margin: 0;
} 

.liste, .depenses{
	max-height: 150px;
	overflow-x: hidden;
	overflow-y: scroll;
	border : 1px #0088cc solid; 
	margin: auto;
	width: 25%;
	margin-top: 2%;
	padding: 1%; 
}

.id{
	color: #0088cc;
	text-shadow: 0 0 1px #0088CC;
}

.submit{
	padding: 1%;
	margin: 2%;
}

.submit input{
	padding: 0.5%;
	border: 1px #0088CC solid;
}

.submit textarea{
	border: 1px #0088CC solid;
	padding: 0.5%;
	margin: 1%;
	min-width: 30%;
	max-width: 30%;
	min-height: 10%;
	max-height: 10%;
}

</style>