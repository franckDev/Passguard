<?php
		// Si on a bien reçu l'id de la note à supprimer
		if(isset($_POST["IdNote"]) && $_POST["IdNote"] > 0){

			$id = htmlspecialchars(addslashes($_POST["IdNote"]));

			// Connexion à la bdd
			include("../include/bdd.php");

			// Requête de suppression
			$requete = "DELETE FROM notes WHERE Id='$id'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

			// On ferme la requête
			$resultat->closeCursor();

			echo "ok";
		}else{
			echo "Impossible de supprimer la note";
		} 
 ?>