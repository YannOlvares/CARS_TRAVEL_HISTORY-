<?php
try {
    $bdd = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "");
} catch (Exception $e) {
   die('Erreur : '.$e->getMessage());
}

$id = $_GET['idEmploye'];
$req = $bdd->prepare('DELETE FROM employes WHERE idEmploye='.$id);
$req->execute(compact('idEmploye'));
header('location:employes.php');


