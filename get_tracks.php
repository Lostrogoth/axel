<?php
  function get_max_userid($bdd) {
    $reponse = $bdd->query('SELECT MAX(user) FROM tracks');
    $donnees = $reponse->fetch();
    $reponse->closeCursor();
    return($donnees[0]);
  }


  function get_tracks() {     // {[{"lat":x.x"lon":x.x}{"lat":x.x"lon":x.x}][{"lat":x.x"lon":x.x}{"lat":x.x"lon":x.x}]}

    $bdd = getpdo();
    $max_userid = get_max_userid($bdd);
    $userid = 1;
    $lon = 0;
    $lat = 0;
    $tracks = '{'
    while ($userid <= $max_userid)
    {
      $tracks .= '['
      $reponse = $bdd->query('SELECT lat , lon FROM tracks WHERE user = \'' . $userid . '\'');
      while ($donnees = $reponse->fetch())
        $tracks .= '{"lat":' . $donnees['lat'] . '"lng:' . $donnees['lon'] . '"}';
      $reponse->closeCursor();
      $tracks .= ']'
      $userid++;
    }
    $tracks .= '}'
  }
 ?>
