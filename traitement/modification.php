<?php
	session_start();

	// On inclut la classe d'encryptage
	include("../include/parameter.php");

	// On instancie les objets endeCrypte
	$crypteDesc = new endeCrypte(); 
	$crypteLog = new endeCrypte(); 
	$cryptePass = new endeCrypte(); 

	if(isset($_POST["IdNote"]) && $_POST["IdNote"] > 0){ // Si on a bien reçu l'id de la note à modifier

		if(isset($_POST["descMod"]) && !empty($_POST["descMod"]) && isset($_POST["login"]) && !empty($_POST["login"]) && isset($_POST["pwd"]) && !empty($_POST["pwd"])){ // Si on a bien les infos à modifier

			$id = htmlspecialchars(addslashes($_POST["IdNote"]));

			$desc = htmlspecialchars(addslashes($_POST["descMod"]));
			$desc_crypte = $crypteDesc->mc_encrypt($desc); // Cryptage description

			$login = htmlspecialchars(addslashes($_POST["login"]));
			$login_crypte = $crypteLog->mc_encrypt($login); // Cryptage login

			$pwd = htmlspecialchars(addslashes($_POST["pwd"]));
			$pwd_crypte = $cryptePass->mc_encrypt($pwd); // Cryptage mot de passe

			// Connexion à la bdd
			include("../include/bdd.php");

			// Requête de modification
			$requete = "UPDATE notes SET description='$desc_crypte', login='$login_crypte', pass='$pwd_crypte' WHERE Id='$id'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

			// On ferme la requête
			$resultat->closeCursor();

			echo "ok";
		}else{
			echo "Un ou plusieurs champs sont vides !";
		}

	}else{
		echo "Impossible de modifier la note";
	} 
 ?>