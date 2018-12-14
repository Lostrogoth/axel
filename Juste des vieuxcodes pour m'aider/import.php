<?php
    //Get the API
$ch = curl_init('http://145.239.32.254:8081/Miyasaki/films');
// Disable SSL verification
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
// Will dump a beauty json :3
$films = json_decode($result, true);

$ch = curl_init('http://145.239.32.254:8081/Miyasaki/heros');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result=curl_exec($ch);
curl_close($ch);
$heros = json_decode($result, true);

try
{
  $bdd = new PDO('mysql:host=localhost;dbname=miyazaki;charset=utf8', 'octave', 'evatco');
}
//If failed, display error
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

// SYNTAX : ($bdd, TABLE, COL1, COL1_VALUE, COL2, COL2_VALUE ...)
function send_to_DB()
{
  // Connexion to Database
  try
  {
    $bdd = new PDO('mysql:host=localhost;dbname=miyazaki;charset=utf8', 'octave', 'evatco');
  }
  //If failed, display error
  catch(Exception $e)
  {
          die('Erreur : '.$e->getMessage());
  }
  //SQL script maker
  $args = func_get_args();
  $sql_script2 = '';
  $sql_script3 = '';
  $sql_script = '$req = $args[0]->prepare(\'INSERT INTO ' . $args[1] . '(';
  for ($i = 2; $i < func_num_args(); $i = $i + 2)
  {
    if ($i != 2)
    {
      $sql_script .= ', ';
      $sql_script2 .= ', ';
    }
    $sql_script .= $args[$i];// first part of request prepare()
    $sql_script2 .= ':' . $args[$i];// second part of request prepare()
    $sql_script3 .= '$args[' . (string)$i . '] => $args[' . (string)($i + 1) . '],'; // third part of request execute()
  }
  $sql_script .= ') VALUES(' . $sql_script2 . ')\');$req->execute(array(' . $sql_script3 . '));';
  eval($sql_script);
}
// Fill all tables
foreach ($films as $movie)
{
  send_to_DB($bdd, 'films', 'nom', $movie['nom'], 'note', $movie['note'], 'image', $movie['image'], 'annee', $movie['annee']);
}
foreach ($heros as $dude)
{
  send_to_DB($bdd, 'heros', 'nom' , $dude['nom'], 'id_film' , $dude['film'], 'description', $dude['description'], 'role', $dude['role']);
}
foreach ($films as $movie)
{
  foreach ($movie['Genre'] as $genre)
  {
    send_to_DB($bdd, 'genre', 'genre', $genre);
    $reponse = $bdd->query('SELECT id FROM genre WHERE genre ="'.$genre.'"');
    while ($donnees = $reponse->fetch())
    {
        send_to_DB($bdd, 'jonction', 'id_film', $movie['id'], 'id_genre', $donnees['id']);
    }
    $reponse->closeCursor();
    send_to_DB($bdd, 'jonction', 'id_film', $movie['id'], 'id_genre', $donnees['id']);
  }
}

//Useful tools
echo "
<a href=\"http://localhost/phpmyadmin/db_structure.php?server=1&db=miyazaki\">PHP_Myadmin/Miyazaki</a></br>
<a href=\"http://145.239.32.254:8081/Miyasaki/films\">API</a>
";
?>
