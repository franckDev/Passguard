<?php
	session_start();

	// Déclaration des constantes
	define('PREFIX_SALT', '16478931');
	define('SUFFIX_SALT', '489923141');

	// Déclaration des variables
	$nb = 0;
	$posL = 0;
	$posT = 0;

	// On récupère l'id de l'utilisateur
	$id = base64_decode($_SESSION['connect']);
	$id = str_replace(PREFIX_SALT, "", $id);
	$id = str_replace(SUFFIX_SALT, "", $id);

	// On stocke le tableau de position
	$tableJson = $_POST['tableJson'];

	//on récupère les données pour enregistrement
    if(isset($_POST['tableJson']) && !empty($_POST['tableJson'])) {
        
        // Connexion à la bdd
		include("../include/bdd.php");

        foreach ($tableJson as $key => $value) {

        	$nb = $key; // Numéro de la fiche note

        	foreach ($value as $key1 => $value1) {

        		if ($key1 == "posL") {

        			$posL = $value1; // Position Left

        		}elseif ($key1 == "posT"){

        			$posT = $value1; // Position Top
        		}

        		// On met à jour la fiche en cours
				$requete = "UPDATE notes SET posL='$posL',posT='$posT' WHERE user_id='$id' and numN='$nb'";

				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

				// On ferme la requête
				$resultat->closeCursor();

        	}
        }
    }
?>