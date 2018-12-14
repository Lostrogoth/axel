<?php
	function getpdo() { // acces à la bdd
		try
		{
		  return($bdd = new PDO('mysql:host=localhost;dbname=axel;charset=utf8', 'admin', 'admin'));
		}
		//If failed, display error
		catch(Exception $e)
		{
		        die('Erreur : '.$e->getMessage());
		}
		return (NULL);
	}

	function createNewSessionId() {
		$bdd = getpdo();
		$id = 1;

		if (!$bdd)
			echo -1;

		else {
			// Get User ID
			$reponse = $bdd->query('SELECT MAX(user) FROM tracks');
			while ($donnees = $reponse->fetch())
		    	$id = $donnees[0] + 1;
			$reponse->closeCursor();

			echo $id;
		}
	}

	function getLocationData() {
		$bdd = getpdo();
		$user = $_POST['user']; // on est raccord sur les variables ?
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
