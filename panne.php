<?php
try{
  $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "" );
} catch (Exception $e) {
  die('Erreur : '.$e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM equipements");
$equipements = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === "POST"){
  $typePanne = $_POST['typePanne'];
  $detailPanne = $_POST['detailPanne'];
  $dateEntree = $_POST['dateEntree'];
  $dateSortie = $_POST['dateSortie'];
  $equipement_id = (int)trim ($_GET['idEquipement']);

  $z = "INSERT INTO pannes (typePanne,detailPanne,dateEntree,dateSortie,idEquipement) VALUES ('$typePanne','$detailPanne','$dateEntree','$dateSortie','$equipement_id')";
  $pdo -> exec($z);
  header('location:accueil.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="dist/css/bootstrap.css">
</head>
<body> 
    <br>
    <a class="btn btn-primary justify-content-start" href="accueil.php">Retour</a>
    <br>
      <form method="POST" class="container ml-5">
          <label for="" class="fw-bold">Type de panne</label>
          <input type="text" id="" class="form-control border bg-light w-50" name="typePanne" autocomplete="off" value="" required>
          <br>
          <label for="" class="fw-bold">Détails sur la panne</label>
          <textarea name="detailPanne" cols="4" rows="3" class="form-control border bg-light w-50"></textarea>
          <br>
          <label for="" class="fw-bold">Date de mise en maintenance</label>
          <input type="date" id="" class="form-control border bg-light w-50" name="dateEntree" autocomplete="off" value="" required>
          <br>
          <label for="" class="fw-bold">Date de sortie de maintenance</label>
          <input type="date" id="" class="form-control border bg-light w-50" name="dateSortie" autocomplete="off" value="" required>
          <br>
          <input type="submit" class="btn btn-success" value="Valider">
      </form>
</body>
</html>

