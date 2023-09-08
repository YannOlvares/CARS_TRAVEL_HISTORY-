<?php
session_start();

if(!$_SESSION['motDePasse']){
  header('location:index.php');
}
try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "" );
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}
$errors=[];
$numerotation = 1 ;
@$nom = $_POST['nom'];
@$prenom = $_POST['prenom'];

$query = $pdo -> prepare ("SELECT * FROM employes WHERE nomEmploye = ? AND prenomEmploye = ?");
$query -> execute ([$nom,$prenom]);
$check = $query -> fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  if((!empty($nom)) AND (!empty($prenom))){
      if ($check){
        $errors['Erreur1']="Nom et prénoms déjà présent dans la base de données! ";
      }else{
      $z = "INSERT INTO employes (nomEmploye,prenomEmploye) VALUES ('$nom','$prenom')";
      $pdo -> exec($z);
      }
  }
}


$sql = "SELECT emp.nomEmploye, emp.prenomEmploye, equ.numSeriequipement
        FROM employes AS emp
        LEFT JOIN equipements AS equ ON emp.idEmploye = equ.utilisateursEquipement";

$result = $pdo->query($sql);

$stmt = $pdo->query("SELECT * FROM employes");
$employes = $stmt->fetchAll();

/*$st = $pdo->query("SELECT employes.nomEmploye, employes.prenomEmploye, equipements.typeEquipement,equipements.numSerieEquipement,equipements.modeleEquipement
FROM employes
JOIN affecter ON employes.idEmploye = affecter.idEmploye
JOIN equipements ON affecter.idEquipement = equipements.idEquipement;");
$equipements = $st->fetchAll();*/

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="dist/css/bootstrap.min.css">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
    <link href="https://getbootstrap.com/docs/5.2/assets/css/bootstrap.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style>
      /* Positionnez la forme Popup */
      .login-popup {
        position: relative;
        text-align: center;
        width: 100%;
      }
      /* Masquez la forme Popup */
      .form-popup {
        display: none;
        position: fixed;
        left: 45%;
        top: 5%;
        transform: translate(-45%, 5%);
        border: 2px solid #666;
        z-index: 9;
      }
      /* Styles pour le conteneur de la forme */
      .form-container {
        max-width: 300px;
        padding: 20px;
        background-color: #fff;
      }
      /* Largeur complète pour les champs de saisie */
      .form-container input[type="text"],
      .form-container input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0 22px 0;
        border: none;
        background: #eee;
      }
      /* Quand les entrées sont concentrées, faites quelque chose */
      .form-container input[type="text"]:focus,
      .form-container input[type="password"]:focus {
        background-color: #ddd;
        outline: none;
      }
      /* Stylez le bouton de connexion */
      .form-container .btn {
        background-color: #8ebf42;
        color: #fff;
        padding: 12px 20px;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 10px;
        opacity: 0.8;
      }
      /* Stylez le bouton pour annuler */
      .form-container .cancel {
        background-color: #cc0000;
      }
      /* Planez les effets pour les boutons */
      .form-container .btn:hover,
      .open-button:hover {
        opacity: 1;
      }

      .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        position: absolute;
        z-index: 9999;
      }

      .popup-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;

      }
</style>
</head>
<body>

<br>
<div class="float-end">
  <button class="open-button btn btn-success" id="popup-button">Ajouter employé</button>
</div>

<div id="popup" class="popup">
  <div class="popup-content">
    <form method="post">
      <label for="nom">Nom</label>
      <br>
      <input type="text" id="nom" name="nom" class="w-100 form-control" required><br>
      <label for="prenom">Prenom</label>
      <br>
      <input type="prenom" id="prenom" name="prenom" class="w-100 form-control"><br>
      <?php if (isset($errors["Erreur1"])): ?>
        <span  style="background-color:rgb(254, 150, 160);color:rgb(217, 1, 21)">
          <?= $errors["Erreur1"] ?>
        </span>
      <?php endif ?>
      <div class="d-flex m-auto">
        <input type="submit" value="Envoyer" class="btn btn-success">
        <input type="submit" id="close-button" value="Fermer" class="btn btn-danger">
      </div>
    </form>
  </div>
</div>

<div>
  <div class="">
    <a href="accueil.php" class="btn btn-primary">Retour</a>
  </div>
</div>
<br>
    <h3 class="text-center">LISTE DU PERSONNELS </h3>
    <h5 class="text-center"></h5>
    <br>
    <table class="table table-dark">
                <thead>
                    <tr>
                        <td>N°</td>
                        <td>Nom</td>
                        <td>Prénom</td>
                        <td>Equipement(s) lié(s)</td>
                        <td>Action 1</td>
                    </tr>
                </thead>
                <tbody id="myTable">    
                    <?php foreach ($employes as $employe): ?>
                    <tr>
                        <td><?= $numerotation ?></td>
                        <td><?= $employe['nomEmploye'] ?></td>
                        <td><?= $employe['prenomEmploye'] ?></td>
                        <td><?// $equipement['Equipement']; ?></td>
                        <td><a href="delete2.php?idEmploye=<?= $employe[0] ?>" onclick="return confirm('Supprimer employés ?')">Supprimer</a></td>
                    </tr>
                    <?php
                     $numerotation++ ;
                     endforeach ?>
                </tbody>
    </table>
    
<br><br>
<script>
        const popupButton = document.getElementById("popup-button");
        const popup = document.getElementById("popup");
        const closeButton = document.getElementById("close-button");

        popupButton.addEventListener("click", function() {
          popup.style.display = "block";
        });

        closeButton.addEventListener("click", function() {
        popup.style.display = "none";
});
</script>
</body>
</html>