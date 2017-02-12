<?php 
	
	session_start();

	// Déclaration des constantes
	define('PREFIX_SALT', '16478931');
	define('SUFFIX_SALT', '489923141');

	// On inclut la classe d'encryptage
	include("../include/parameter.php");

	// On instancie des objets endeCrypte
	$crypte = new endeCrypte();  

	if(!empty($_POST['desc']) && !empty($_POST['login']) && !empty($_POST['pwd'])){ // Si tous les champs sont bien saisis

		$description = htmlspecialchars(addslashes($_POST['desc']));
		$description_crypte = $crypte->mc_encrypt($description); // Cryptage description

		$login = htmlspecialchars(addslashes($_POST['login']));
		$login_crypte = $crypte->mc_encrypt($login); // Cryptage login

		$pass = htmlspecialchars(addslashes($_POST['pwd']));
		$pass_crypte = $crypte->mc_encrypt($pass); // Cryptage mot de passe

		$num = htmlspecialchars(addslashes($_POST['numCache']));

		// On récupère l'id encodé
		$idX = $_SESSION["connect"];

		// On decode l'id en base 64
		$user_id = base64_decode($idX);
		$user_id = str_replace(PREFIX_SALT, "", $user_id);
		$user_id = str_replace(SUFFIX_SALT, "", $user_id);
		
		// Connexion à la bdd
		include("../include/bdd.php");

		// Requête d'enregistrement d'une nouvelle note
		$requete = "INSERT INTO notes (id , description , login , pass , numN , posL, posT, user_id)
				    VALUES ('' , '$description_crypte' , '$login_crypte' , '$pass_crypte' , '$num' , '42', '57', '$user_id')";

		// On exécute la requête
		$resultat = $bdd->query($requete) or die(print_r($bdd->errorInfo()));

		// On ferme la requête
		$resultat->closeCursor();

		$class = "ok";

	}elseif (!empty($_POST['desc']) && !empty($_POST['login'])) { // Si le password est vide

		$class = "emptyPass";

	}elseif (!empty($_POST['login']) && !empty($_POST['pwd'])) { // Si la description est vide

		$class = "emptyDes";

	}elseif(!empty($_POST['desc']) && !empty($_POST['pwd'])) { // Si le login est vide 

		$class = "emptyLog";

	}else{ // Si tous les champs sont vides

		$class = "empty";
	}

	echo $class;
?>