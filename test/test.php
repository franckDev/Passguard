<meta charset="utf-8" />
<?php
	// Hashage
	// $mdp = "monmotdepassequitue";
	// $salt = sha1(uniqid(rand()));
	// $salt = "075c112840232bb327b25db8e47e4bc9a21e65fd";
	// $mdp = "franck"; 
	// $hash = hash('sha512', $salt . $mdp);
	// // $hash = crypt($salt.$mdp);
	// echo $hash;

	// die();
	// while(true){
	// 	//Encryption / Decryption
	// 	//On calcule la taille de la clé pour l'algo triple des
	// 	$cle_taille = mcrypt_module_get_algo_key_size(MCRYPT_3DES);

	// 	//On calcule la taille du vecteur d'initialisation pour l'algo triple des et pour le mode NOFB
	// 	$iv_taille = mcrypt_get_iv_size(MCRYPT_3DES, MCRYPT_MODE_NOFB);

	// 	// //On fabrique le vecteur d'initialisation, la constante MCRYPT_RAND permet d'initialiser un vecteur aléatoire
	// 	$iv = mcrypt_create_iv($iv_taille, MCRYPT_RAND);
	// 	// $iv = "�J��׃#�";

	// 	$cle ="Ar45Fs:,ùRDFSG54134zADFGsf!45reehhezz";
	// 	//On retaille la clé pour qu'elle ne soit pas trop longue
	// 	$cle = substr($cle, 0, $cle_taille);
	// 	$message ="coucou";
	// 	// //Le message à crypter
	// 	// $message = "coucou";
	// 	// //On le crypte
	// 	$message_crypte = mcrypt_encrypt(MCRYPT_3DES, $cle, $message, MCRYPT_MODE_NOFB, $iv);
	// 	// $message_crypte = "��H��";
	// 	//On le décrypte
	// 	$message_decrypte = mcrypt_decrypt(MCRYPT_3DES, $cle, $message_crypte, MCRYPT_MODE_NOFB, $iv);

	// 	// echo $message_decrypte;

	// 	echo "Message en clair : $message <br/> Message crypté : $message_crypte <br /> Message décrypté : $message_decrypte";
	// }


	include("/include/parameter.php");

	$a = 0;

	while($a < 10){

		$message = "Maison";

		$crypte = new endeCrypte();
		$message_crypte = $crypte->mc_encrypt($message);

		$decrypte = new endeCrypte();
		$message_decrypte = $decrypte->mc_decrypt($message_crypte);

		echo "Message en clair : $message <br/> Message crypté : $message_crypte <br /> Message décrypté : $message_decrypte <br />";

		$a++;

	}


?>