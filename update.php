<?php
session_start();

try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "");
} catch (Exception $e) {
   die('Erreur : '.$e->getMessage());
}
@$equipement_id = (int)trim ($_GET['idEquipement']);

$req = $pdo->prepare("SELECT * FROM equipements WHERE idEquipement = ?");
$req ->execute(array($equipement_id));
$voir_equipement = $req -> fetch();

@$emplacementEquipement = $_POST['emplacementEquipement'];
@$utilisateursEquipement = $_POST['utilisateursEquipement'];
@$descriptionEquipement = $_POST['descriptionEquipement'];
@$etatEquipement = $_POST['etatEquipement'];

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $z = "UPDATE equipements SET emplacementEquipement='$emplacementEquipement',utilisateursEquipement='$utilisateursEquipement',descriptionEquipement='$descriptionEquipement',etatEquipement='$etatEquipement'
     WHERE idEquipement= $equipement_id";
    $pdo -> exec($z);
    header('location:accueil.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <title>Document</title>
</head>
<body>
<a class="btn btn-primary justify-content-start" href="accueil.php">Retour</a>
<form action="" method="post">
<div class="card">
    <div class="card-header d-flex"><div> FICHE TECHNIQUE DE L'EQUIPEMENT </div> <pre> </pre> <div class="fw-bolder"> <?= $voir_equipement['numSerieEquipement'];?></div> </div>
    <div class="card-body">
        <div class="row">
            <label class="col-3 col-form-label">Type d'équipement</label>
            <div class="col">
                <label class="col-3 col-form-label fw-bold"><?= $voir_equipement['typeEquipement'];?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Marque de l'équipement</label>
            <div class="col">
                <label class="col-3 col-form-label fw-bold"><?= $voir_equipement['marqueEquipement'];?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Modèle de l'équipement</label>
            <div class="col">
                <label class="col-3 col-form-label fw-bold"><?= $voir_equipement['modeleEquipement'];?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Numéro de série de l'équipement</label>
            <div class="col">
                <label class="col-3 col-form-label fw-bold"><?= $voir_equipement['numSerieEquipement'];?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Localisation de l'équipement</label>
            <div class="col">
                <input class="form-control w-50" type="text" name="emplacementEquipement" value="<?= $voir_equipement['emplacementEquipement'];?>">
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Utilisateur(s) de l'équipement</label>
            <div class="col">
                <input class="form-control w-50" type="text" name="utilisateursEquipement" value="<?= $voir_equipement['utilisateursEquipement'];?>">
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Date de mise en service</label>
            <div class="col">
                <label class="col-3 col-form-label fw-bold"><?= $voir_equipement['dateMiseEnService'];?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Description de l'équipement</label>
            <div class="col">
                <textarea class="form-control w-50" name="descriptionEquipement" id="" cols="5" rows="4"  value="<?= $voir_equipement['descriptionEquipement'];?>">
                <?= $voir_equipement['descriptionEquipement'];?>
                </textarea>
            </div>
        </div>
        <br>
        <div class="row">
            <label class="col-3 col-form-label">Etat de l'équipement</label>
            <div class="col">              
                <input type="radio" value="Utilisable" name="etatEquipement">
                <label for="etatEquipement">Utilisable</label>
                <br>
                <input type="radio" value="En maintenance" name="etatEquipement">
                <label for="etatEquipement">En maintenance</label>
            </div>
        </div>
        <br>
        <div class="text-center">
            <input class="btn btn-success" type="submit" value="Valider">
        </div>
    </div>
</div>

</form>
</body>
</html>