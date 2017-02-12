<?php
	// connexion à la base de données
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=dbnameLocal', 'user', 'pass',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch(Exception $e) {
        exit('Impossible de se connecter à la base de données.');
    }	
?>