<?php $path = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["SERVER_NAME"]."/"; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Confirmation</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../assets/css/styles2.css">
	</head>
	<body id="confirmation">
		<!-- Validation de l'inscription -->
		<?php 
			if(isset($_GET["id"]) && !empty($_GET["id"])){

				// On décrypte l'id en base 64
				$id = base64_decode($_GET["id"]);

				// Connexion à la bdd
				include("../include/bdd.php");

				// Requête de mise à jour de la confirmation utilisateur
				$requete = "UPDATE users SET confirmation='1' WHERE id='$id'";

				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

				// On ferme la requête
				$resultat->closeCursor();

				// Puis on redirige sur le login
				header('Location: '.$path.'index.php?conf=ok');
				
			}else{
		?>
		<div id="center">
			<span><img src="../assets/img/confirmation.png" alt="icone" width="48" height="48"></span>
			<p>Votre inscription a bien été prise en compte vous allez recevoir un email de confirmation.</p>
		</div>
		<?php		
			}
		?>
	</body>
</html>