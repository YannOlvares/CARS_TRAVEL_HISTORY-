<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "");
} catch (Exception $e) {
   die('Erreur : '.$e->getMessage());
}

/*
$idEquipement = $pdo->lastInsertId();
echo $idEquipement ;
    // Enregistrement des employés associés dans la table "affecter"
foreach ($employes as $idEmploye) {
    $stmt = "INSERT INTO test (nom, email) VALUES ('$idEquipement', '$idEmploye')";
    $pdo -> exec($stmt);
} 
//header('location:accueil.php');
*/