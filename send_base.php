<?php

	funcion createNewSessionId() {

		$id = 1;

		// manip BDD

		echo $id;
	}

	function getLocationData() {
		$user = $_POST['id']; // on est raccord sur les variables ?
		$jour = $_POST['jour'];
		$long = $_POST['long'];
		$lat = $_POST['lat'];

		// insérer dans la BDD
		$req = $bdd->prepare('INSERT INTO tracks(user, lat, long, jour) VALUES(:user, :lat, :long, :jour)');
		$req->execute(array(
		    'user' => $user,
        'lat' => $lat,
        'long' => $long,
        'jour' => $jour
		    ));

		echo 1;
	}

?>
