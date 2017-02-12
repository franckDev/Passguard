<?php
	// On démarre la session
	session_start();

	// Déclaration des constantes
	define('PREFIX_SALT', '16478931');
	define('SUFFIX_SALT', '489923141');

	if(!empty($_POST['loginCo']) && !empty($_POST['passCo'])){

		// On prépare les variables
		$login = htmlspecialchars(addslashes($_POST['loginCo']));
		$pass = htmlspecialchars(addslashes($_POST['passCo']));

		// Connexion à la bdd
		include("../include/bdd.php");

		// On vérifie si le login est valide et on récupère le salage  
		$requete = "SELECT count(*), salt FROM users WHERE login='$login'";
		// On exécute la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
		// On lit le résultat
		$response = $resultat->fetch();

		// Si le login existe on continue
		if($response[0] == 1){

			$salt = $response['salt']; // On récupère la clé de salage

			$pass =  hash('sha512', $salt.$pass); 

			// On vérifie le login et le mdp
			$requete = "SELECT count(*), id, pass FROM users WHERE login='$login' AND pass='$pass'";

			// On exécute la requête
			$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
			// On lit le résultat
			$response = $resultat->fetch();

			if($response[0] == 1){ 

				// On récupère l'id
				$id = $response['id'];
				// On vérifie si le compte a bien été validé
				$requete = "SELECT count(*) FROM users WHERE id='$id' AND confirmation='1'";
				// On exécute la requête
				$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));
				// On lit le résultat
				$response = $resultat->fetch();

				if($response[0] == 1){

					// On encode l'id en base 64
					$idX = base64_encode(PREFIX_SALT.$id.SUFFIX_SALT);
					// Puis on la stocke dans une variable de session
					$_SESSION["connect"] = $idX;

					$class="ok"; // Connexion réussie

				}else{ 

					$class="pasok"; // Compte non validé
				}

			}else{
				
				$class="error"; // Login ou pass invalide
			}

		}else{

			$class="error"; // Login ou pass invalide
		}

	}else{

		$class="errorempty"; // Login ou pass vide
	}

	echo $class;
?>