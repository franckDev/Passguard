<?php
	/**
	  * Définition de la classe encryptionChaine
	  *	Elle permet d'encrypter des chaines
	  *	
	  * Définition de la classe decryptionChaine
	  * Elle permet de decrypter des chaines
	  *
	  * 
	  */

	class endeCrypte
	{
		private $mc_key;
		private $passcrypt;
		private $encode;
		private $decoded;

		function endeCrypte()
		{
			$this->mc_key = "Ar45.Fs:,ùRDFS\$àèy4/AF%Gsf!45";
		}

		function mc_encrypt($message)
		{
			$this->passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->mc_key, trim($message), MCRYPT_MODE_ECB);
	    	$this->encode = base64_encode($this->passcrypt);

	    	return $this->encode;
		}

		function mc_decrypt($message_crypte)
		{
			$this->decoded = base64_decode($message_crypte);
	    	$this->decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->mc_key, $this->decoded, MCRYPT_MODE_ECB));

	    	return $this->decrypted;
		}
	}
?>