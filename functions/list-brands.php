<?php

$host = "Localhost";
$username = "root";
$passwd = "password";
$port = "3306";
$db = "dreamwatch";



$bdd = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port.'', $username, $passwd);


$term = $_GET['term'];

$requete = $bdd->prepare('SELECT name, id FROM brands WHERE name LIKE :term');

$requete->execute(array('term' => ''.$term.'%'));

$array = array(); // on créé le tableau


while($donnee = $requete->fetch()) // on effectue une boucle pour obtenir les données

{

    array_push($array, $donnee['name']); // et on ajoute celles-ci à notre tableau

}


echo json_encode($array); // il n'y a plus qu'à convertir en JSON


?>