<?php 
session_start();

try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8","root","");
}
catch(PDOException $e){
    echo $e->getMessage();
}

@$equipement_id = (int)trim ($_GET['idEquipement']);
@$panne_id = (int)trim ($_GET['idPanne']);

$req = $pdo->prepare("SELECT * FROM equipements WHERE idEquipement = ?");
$req ->execute(array($equipement_id));
$voir_equipement = $req -> fetch();

$stmt = $pdo-> query ("SELECT * FROM pannes WHERE idEquipement = '$equipement_id'");
$pannes = $stmt-> fetchAll();

$numerotation = 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <style>
        a {
  text-decoration: none;
}

.dropdown {
  position: relative;
  display: inline-block;
  text-align: left;
  width: 100%;
}

.dropdown-text {
  cursor: pointer;
  position: absolute;
  text-indent: 10px;
  line-height: 32px;
  background-color: #eee;
  border: 1px solid #ccc;
  border-radius: 3px;
  box-shadow: 0 1px 0 rgba(255,255,255, .9) inset, 0 1px 3px rgba(0,0,0, .1);
  width: 100%;
}

.dropdown-text:after {
  position: absolute;
  right: 6px;
  top: 15px;
  content: '';
  width: 0px;
  height: 0px;
  border-style: solid;
  border-width: 5px 4px 0 4px;
  border-color: #555 transparent transparent transparent;
}

.dropdown-text,
.dropdown-content a {
  color: #333;
  text-shadow: 0 1px #fff;
}

.dropdown-toggle {
  font-size: 0;
  z-index: 1;
  cursor: pointer;
  position: absolute;
  top: 0;
  border: none;
  padding: 0;
  margin: 0 0 0 1px;
  background: transparent;
  text-indent: -10px;
  height: 50px;
  width: 100%;
}

.dropdown-toggle:focus {
  outline: 0;
}

.dropdown-content {
  -webkit-transition: all .25s ease;
  -moz-transition: all .25s ease;
  -ms-transition: all .25s ease;
  -o-transition: all .25s ease;
  transition: all .25s ease;
  list-style-type: none;
  position: absolute;
  top: 32px;
  padding: 0;
  margin: 0;
  opacity: 0;
  visibility:hidden;
  border-radius: 3px;
  text-indent: 10px;
  line-height: 32px;
  background-color: #eee;
  border: 1px solid #ccc;
  width: 100%;
}

.dropdown-content a {
  display: block;
}

.dropdown-content a:hover {
  background: #e8e8e8;
}


.dropdown-toggle:hover ~ .dropdown-text,
.dropdown-toggle:focus ~ .dropdown-text {
  background-color: #e8e8e8;
}

.dropdown-toggle:focus ~ .dropdown-text {
  box-shadow: 0 1px 3px rgba(0,0,0, .2) inset, 0 1px 0 rgba(255,255,255, 0.8);
  z-index: 2;
}

.dropdown-toggle:focus ~ .dropdown-text:after {
  border-width: 0 4px 5px 4px;
  border-color: transparent transparent #555 transparent;
}

.dropdown-content:hover,
.dropdown-toggle:focus ~ .dropdown-content {
  opacity: 1;
  visibility:visible;
  top: 42px;
}
    </style>
</head>
<body>
<a class="btn btn-primary justify-content-start" href="accueil.php">Retour</a>
<div class="card">
    <div class="card-header d-flex">
        <div class="d-flex">
            <div class="text-start">
                FICHE TECHNIQUE DE L'EQUIPEMENT </div> <pre> </pre> <div class="fw-bolder"> <?= $voir_equipement['numSerieEquipement'];?>
            </div>
            <div class="ps-5 fst-italic">
                Equipement ajouté le <?= $voir_equipement['dateAjout'];?>
            </div>        
        </div> 
    </div>
        
    <div class="card-body">
        <div class="row">
            <label class="col-3 col-form-label text-muted">Type d'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['typeEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Fabricant de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['marqueEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Modèle de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['modeleEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Numéro de série de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['numSerieEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Localisation de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['emplacementEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Utilisateur(s) de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['utilisateursEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Date de mise en service</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['dateMiseEnService'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Logiciel(s) ou programme(s) installé(s)</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['logicielsEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Description de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['descriptionEquipement'];?></label>
            </div>
        </div>
        <div class="row">
            <label class="col-3 col-form-label text-muted">Etat de l'équipement</label>
            <div class="col">
                <label class="form-label"><?= $voir_equipement['etatEquipement'];?></label>
            </div>
        </div>
    </div>
</div>

<div class="dropdown">
  <input class="dropdown-toggle" type="text">
  <div class="dropdown-text"> <img src="assets/images/key.svg" alt="" width="20"> Historiques des pannes </div>
  <ul class="dropdown-content">
  <table class="table table-light">
                <thead>
                    <tr>
                        <td>N°</td>
                        <td>Type de panne</td>
                        <td>Détail sur la panne</td>
                        <td>Date entrée maintenance</td>
                        <td>Date sortie maintenance</td>      
                    </tr>
                </thead>
                <tbody id="myTable">    
                    <?php  foreach ($pannes as $panne): ?>
                    <tr>
                        <td><?= $numerotation ?></td>
                        <td><?= $panne['typePanne'] ?></td>
                        <td><?= $panne['detailPanne'] ?></td>
                        <td><?= $panne['dateEntree'] ?></td>
                        <td><?= $panne['dateSortie'] ?></td>                
                    </tr>
                    <?php
                     $numerotation++ ;
                     endforeach ?>
                </tbody>
    </table>
  </ul>
</div>

</body>
</html>