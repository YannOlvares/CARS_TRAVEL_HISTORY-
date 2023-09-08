<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "");
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}

    $sql = "SELECT * FROM employes";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $employes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $errors = [];


if ($_SERVER['REQUEST_METHOD'] === "POST"){

    $typeEquipement = $_POST['typeEquipement'];
    $marque = $_POST['marqueEquipement'];
    $modele = $_POST['modeleEquipement'];
    $numSerie = $_POST['numSerieEquipement'];
    $emplacement = $_POST['emplacementEquipement'];
    $etat = 'Utilisable' ;
    @$utilisateurs = implode(",", $_POST['utilisateursEquipement']);
    @$logiciels = implode(",", $_POST['logicielsEquipement']);
    @$description = $_POST['descriptionEquipement'];
    $dateMiseEnService = $_POST['dateMiseEnService'];
    
    $stmt = $pdo->prepare("SELECT * FROM equipements WHERE numSerieEquipement=?");
    $stmt->execute([$numSerie]); 
    $check_numSerie = $stmt->fetch();

    
    /*if ($check_identifiant) {
        $errors['Erreur1']="Numéro de série déjà exixtant ! ";
    }
    else {*/
    $z = "INSERT INTO equipements (typeEquipement,marqueEquipement,modeleEquipement,numSerieEquipement,emplacementEquipement,utilisateursEquipement,logicielsEquipement,dateMiseEnService,descriptionEquipement,dateAjout,etatEquipement) VALUES ('$typeEquipement','$marque','$modele','$numSerie','$emplacement','$utilisateurs' ,'$logiciels','$dateMiseEnService','$description',now(),'$etat')";
    $pdo -> exec($z);

    header('location:accueil.php');
    //}
}

    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="dist/css/bootstrap.css" rel="stylesheet">
    <link href="dist/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.6/dist/sweetalert2.all.min.js"></script>
    <style>
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }

        .popup button {
            display: block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <a class="btn btn-primary justify-content-start" href="accueil.php">Retour</a>
    <form class="container mt-5" name="myForm" method="POST" id="myForm">
        <div class="d-flex">
            <div class="col-6">  
                <label for="" class="fw-bold">Type d'équipement</label>  
                <select id="type" name="typeEquipement" class="form-select border bg-light w-75" required>
                    <option value=""></option>
                    <option value="Ordinateur">Ordinateur</option>
                    <option value="Imprimante">Imprimante</option>
                    <option value="Onduleur">Onduleur</option> 
                    <option value="Téléphone">Téléphone</option>   
                    <option value="Autres">Autres équipements (à préciser dans la description)</option>          
                </select>
            </div>   
            <div class="col-6">
                <label for="" class="fw-bold">Fabricant de l'équipement</label>
                <input type="text" id="fabricant" class="form-control border bg-light w-75" name="marqueEquipement" autocomplete="off" value="" required>
            </div>
        </div>
        <br>
        <div class="d-flex">          
            <div class="col-6">
                <label for="" class="fw-bold">Modèle de l'équipement</label>
                <input type="text" id="modele" class="form-control border bg-light w-75" name="modeleEquipement" autocomplete="off" value="" required>
            </div>
            <div class="col-6">
                <label for="" class="fw-bold">Numéro de série de l'équipement</label>
                <input type="text" id="numSerie" class="form-control border bg-light w-75" name="numSerieEquipement" autocomplete="off" value="" required>
                <?php if (isset($errors["Erreur1"])): ?>
                    <span  style="background-color:rgb(254, 150, 160);color:rgb(217, 1, 21)">
                        <?= $errors["Erreur1"] ?>
                    </span>
                <?php endif ?>
            </div>
        </div>
        <br>
        <div class="d-flex">
            <div class="col-6">
                <label for="" class="fw-bold">Emplacement de l'équipement</label>
                <input type="text" id="emplacement" class="form-control border bg-light w-75" name="emplacementEquipement" autocomplete="off" value="" required>
            </div>
            <div class="col-6">
                <label for="" class="fw-bold">Date de mise en service</label>
                <div class="d-flex">
                <input type="date" id="date" class="form-control border bg-light w-75" name="dateMiseEnService" autocomplete="off" value="" required>
                </div>
            </div>
        </div>
        <br>
        <div class="d-flex">
            <div class="col-6">
                <label class="fw-bold">Logiciel(s) ou programme(s) installé(s) </label> <br>
                <input type="checkbox" class="form-check-input" name="logicielsEquipement[]" id="logiciels" value="Clé d\'activation Windows" multiple>
                <label for="flexCheckDefault" class="form-check-label">Clé d'activation Windows</label> <br>
                <input type="checkbox" class="form-check-input" name="logicielsEquipement[]" id="logiciels" value="Antivirus" multiple>
                <label for="flexCheckDefault" class="form-check-label">Antivirus</label> <br>
                <input type="checkbox" class="form-check-input" name="logicielsEquipement[]" id="logiciels" value="Licence office" multiple>
                <label for="flexCheckDefault" class="form-check-label">Licence office</label><br>
            </div>
            <div class="col-6">
                <label for="" class="fw-bold">Utilisateur(s) de l'équipement</label>
                <div class="form-check">
                        <?php foreach ($employes as $row){
                            echo '<input class="form-check-input" name="utilisateursEquipement[]" type="checkbox" id="flexCheckDefault" value="'.$row["nomEmploye"]." ".$row['prenomEmploye'].'">';
                            echo '<label class="form-check-label" for="flexCheckDefault">';
                            echo  $row["nomEmploye"]." ".$row['prenomEmploye'];
                            echo '</label><br>';
                        } ?>

                 
                    </select> 
                </div>
                
            </div>
        </div>
        <br>         
        <div>
            <label for="" class="fw-bold">Description équipement</label>
            <textarea name="descriptionEquipement" id="description" cols="5" rows="5" class="form-control border bg-light" autocomplete="off"></textarea>
        </div>
        <br>
        <div class="text-center">
            <input type="submit" value="Valider" id="submit" class="btn btn-success">   
        </div>
        <br>
     </tr>
</form>
<script>
     
</script>
</body>
</html>