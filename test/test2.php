<?php

	include("/include/parameter.php");

	$a = 0;

	while($a < 10){

		$crypte = "yoQUaqn+09914Ub9X4bUqupofaAdDxaJU3iaFP1vFKk=";

		$decrypte = mc_decrypt($crypte);

		echo "Message en clair : $decrypte <br/> Message crypté : $crypte <br /> Message décrypté : $decrypte <br />";

		$a++;

	}
?>