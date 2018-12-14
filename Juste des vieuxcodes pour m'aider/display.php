<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Miyazaki</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="css/mine.css"  media="screen,projection" />
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems, options);
    });
    </script>
  </head>
  <body background="images/miyazaki.jpg">
    <nav>
      <div class="nav-wrapper">
        <a class="brand-logo center">Miyazaki</a>
      </div>
    </nav>
    <div class="container">
      <h1>FILMS</h1>
      <ul class="collapsible">
      <?php
      // Connexion to DB
      try
      {
        $bdd = new PDO('mysql:host=localhost;dbname=miyazaki;charset=utf8', 'octave', 'evatco');
      }
      catch(Exception $e)
      {
        die('Erreur : '.$e->getMessage());
      }
      //FILMS
      $req = $bdd->query('SELECT nom, note, image, annee FROM films');
      while ($donnees = $req->fetch())
      {
        $req2 = $bdd->query('SELECT genre.genre genre
        FROM genre
        INNER JOIN jonction ON genre.id = jonction.id_genre
        INNER JOIN films ON jonction.id_film = films.id
        WHERE films.nom = \'' . $donnees['nom'] . '\''); //SQL request for genres of the film
        echo '<li';
        if (isset($_GET['film']) && ($_GET['film'] == $donnees['nom'])) // If GET open accordion
        {
          echo ' class="active"';
        } //Display all the 5hit
          echo '><div class="collapsible-header"><strong>' . $donnees['nom'] . '</strong> (' . $donnees['annee'] . ')</div>
          <div class="collapsible-body row" style="margin: 0"><div class="col l8 m6 s12"><span>' . $donnees['note'] . '</span><p>genres : ';
          $comma = 0;
        while ($donnees2 = $req2->fetch())
        {
            if ($comma != 0)
            {
              echo ', ';
            }
            $comma = 1;
            echo $donnees2['genre'];
        }
        echo '</p><a class="waves-effect waves-light btn-small" href="http://localhost/miyazaki/display.php?film=' . $donnees['nom'] . '"><i class="material-icons left">expand_more</i>Voir les héros</a></div>
      <div class="col l4 m6 s12"><p><img src="' . $donnees['image'] . '" width="210"/></p></div></div></li>';
      }
      //HEROS
      echo '</ul><h1>HEROS';
      if (isset($_GET['film']))
        echo ' du film ' . $_GET['film'] . '</h1>';
      else
        echo "</h1>";
      if (isset($_GET['film']))
        echo '<a class="waves-effect waves-light btn" href="http://localhost/miyazaki/display.php"><i class="material-icons left">arrow_back</i>Voir tous les héros</a>';
      echo '<ul class="collection">';
      $query_prepare = 'SELECT heros.nom nom, heros.description description, heros.role role FROM heros'; // SQL request when no film is selected (GET)
      $query_prepare_tmp = $query_prepare;
      if (isset($_GET['film'])) // SQL request concatenation when film is selected (GET)
      {
        $query_prepare = $query_prepare_tmp . ' INNER JOIN films ON films.id = heros.id_film WHERE films.nom = \'' . $_GET['film'] . '\'';
      }
      $req = $bdd->query($query_prepare);
      while ($donnees = $req->fetch())
      { // Display all the 5hit
        echo '<li class="collection-item"><strong>' . $donnees['nom'] . '</strong> :<br/><i>' . $donnees['description'] . '</i><br/>role : '. $donnees['role'] . '</br>';
        if (!isset($_GET['film'])) // When no film is selected, put the film name after hero
        {
          $req2 = $bdd->query('SELECT films.nom nom_film
          FROM films
          INNER JOIN heros
          ON heros.id_film = films.id WHERE heros.nom = \'' . $donnees['nom'] . '\'');
          while ($donnees2 = $req2->fetch())
          {
              echo 'Aparait dans : ' . $donnees2['nom_film'] . '</br></br>';
          }
        }
        echo '</li>';
      }
      echo '</ul>';
      $req->closeCursor();
      $req2->closeCursor();
      ?>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
    $('.collapsible').collapsible();
    });
    </script>
  </body>
</html>
