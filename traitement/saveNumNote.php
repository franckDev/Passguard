<?php
	session_start();

	// Déclaration des constantes
	define('PREFIX_SALT', '16478931');
	define('SUFFIX_SALT', '489923141');

	// On récupère l'id de l'utilisateur
	$id = base64_decode($_SESSION['connect']);
	$id = str_replace(PREFIX_SALT, "", $id);
	$id = str_replace(SUFFIX_SALT, "", $id);

	// Initialisation
	$numNote = 0;

	//on récupère les données pour enregistrement
    if(isset($id) && !empty($id)) {
        
        // Connexion à la bdd
		include("../include/bdd.php");

		// On recherche le nombre d'enregistrement présent
		$requete = "SELECT Id as IdNote FROM notes WHERE user_id='$id' ORDER BY Id ASC";

		// On exécute la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

		try {
				//on lance la transaction
    			$bdd->beginTransaction();

				// On boucle sur les résultats
				while($donnees = $resultat->fetch(PDO::FETCH_ASSOC)){

					$idNote = $donnees["IdNote"]; // Id en cours

					// Pour chacune des notes on met à jour le numéro
					$requeteUPD = "UPDATE notes SET numN='$numNote' WHERE Id='$idNote'";

					// On exécute la requête
					$resultatUPD = $bdd->query($requeteUPD) or die(print_r($bdd->errorInfo()));

					// On ferme la requête
					$resultatUPD->closeCursor();

					// Incrémentation du numéro de note
					$numNote++;
				}

				//si tout s'est bien passé on valide la transaction
    			$bdd->commit();
			
		} catch (Exception $e) { // En cas d'erreur
			    //on annule la transation
    			$bdd->rollback();

    			//on affiche un message d'erreur ainsi que les erreurs
    			echo 'Tout ne s\'est pas bien passé, voir les erreurs ci-dessous<br />';
    			echo 'Erreur : '.$e->getMessage().'<br />';
    			echo 'N° : '.$e->getCode();

    			//on arrête l'exécution s'il y a du code après
    			exit();
		}

		// On ferme la requête
		$resultat->closeCursor();
        
    }else{
    	echo "Erreur de mise à jour de la base de données";
    }
?>