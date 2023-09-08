<?php
//-----------Fichier suppresion équipements-------------// 
try {
    $bdd = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "");
} catch (Exception $e) {
   die('Erreur : '.$e->getMessage());
}

$id = $_GET['idEquipement'];
$req = $bdd->prepare('DELETE FROM equipements WHERE idEquipement='.$id);
$req->execute(compact('idEquipement'));
header('location:accueil.php');

