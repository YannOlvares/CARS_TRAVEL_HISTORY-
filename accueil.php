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

@$equipement_id = (int) $_SESSION['idEquipement'];
 
$req = $pdo -> prepare("SELECT * FROM equipements WHERE idEquipement = ?");
$req ->execute(array($equipement_id));
$voir_equipement =  $req-> fetch();

$numerotation = 1;
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

$stmt = $pdo->query("SELECT * FROM equipements");
$equipements = $stmt->fetchAll();

$req1 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement = 'Ordinateur'");
$nbre_ordi = $req1 -> fetch();

$req2 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement = 'Imprimante'");
$nbre_imp = $req2 -> fetch();

$req3 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement = 'Téléphone'");
$nbre_tel = $req3 -> fetch();

$req4 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement = 'Onduleur'");
$nbre_onduleur = $req4 -> fetch();

$req5 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement = 'autres'");
$nbre_autre = $req5 -> fetch();

 ////////// ------------- SYSTEME DE PAGINATION --------------- /////////////
 
 // Nombre d'équipements par page
$equipementsParPage = 10;

// Numéro de la page en cours, par défaut 1
$pageCourante = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Index de départ des équipements pour la page en cours
$indexDepart = ($pageCourante - 1) * $equipementsParPage;

// Nombre total d'équipements dans la liste
$totalEquipements = count($equipements);

// Nombre total de pages
$totalPages = ceil($totalEquipements / $equipementsParPage);

// Sous-liste d'équipements pour la page en cours
$equipements = array_slice($equipements, $indexDepart, $equipementsParPage);

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
      * {
        box-sizing: border-box;
      }
      body {
        font-family: Roboto, Helvetica, sans-serif;
      }
      /* Fixez le bouton sur le côté gauche de la page the button on the left side of the page */
      /*.open-btn {
        /*display: flex;
        justify-content: flex-end;
      }
      /* Stylez et fixez le bouton sur la page 
      .open-button {
        background-color: #1c87c9;
        color: white;
        padding: 5px 14px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        opacity: 0.8;
        position: fixed;
      }*/
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
      .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
      }

      .pagination a {
        color: #333;
        font-size: 16px;
        text-decoration: none;
        padding: 8px 16px;
        border: 1px solid #ccc;
        margin: 0 5px;
        border-radius: 4px;
      }

      .pagination a.active {
        background-color: #333;
        color: #fff;
        border-color: #333;
      }


      .visual {
        /* Styles for visual aid. */
        font-size: 4em;
        height: 1.2em;
        background: #CCCCCC;
        width: 100%;
        margin: 20px auto;
        position: relative;
        bottom : 0;
        height : 100px;
      }

      .maclass span {
        margin-right : 100px;
      }

      .marquee {
        overflow: hidden;
      }
      .marquee > * {
        white-space: nowrap;
        position: absolute;
        animation: marquee 12s linear 0s infinite;
      }

      @keyframes marquee {
        0% {
          left: 100%;
          transform: translateX(0%);
        }
        100% {
          left: 0%;
          transform: translateX(-100%);
        }
      }


</style>
</head>
<body>

<br>
<div class="d-flex">  
    <div class="col-10">
        <img src="assets/images/logo.png" alt="" width="300px">
    </div>
    <div class="">
        <a href="ajoutEquipement.php" class="btn btn-dark text-light">
          Ajouter équipement
        </a>
        <br><br>
        <a href="employes.php" class="open-button btn btn-success">
          Afficher employé
        </a>
        <br><br>
        <a href="deconnexion.php" class="btn btn-danger text-light">
          Déconnexion
        </a> 
    </div>
</div>
<br>
   <div style="margin:auto;" class="input-group d-flex col-md-6">
      <input class="form-control border-end-0 border border-dark" type="search" value="" id="example-search-input" placeholder="" onkeyup="searchTable()" > 
      <input class="btn btn-success " type="submit" value="Rechercher"> 
    </div>
        <br>
        <div style="justify-content:center" class="d-flex">
            <a class="col-1 mr-1 btn btn-success text-light" href="ordi.php"><img src ="assets/images/PC-DISPLAY.svg" alt="" width="20">Ordinateurs</a>
            <a class="col-1 mr-1 btn btn-success text-light" href="imp.php"><img src="assets/images/printer.svg" alt="" style width="20">Imprimantes</a>
            <a class="col-1 mr-1 btn btn-success text-light" href="ond.php"><img src="assets/images/speedometer2.svg" alt="" width="20">Onduleurs</a>
            <a class="col-1 mr-1 btn btn-success text-light" href="tel.php"><img src="assets/images/telephone.svg" alt="" width="20">Téléphones</a>
            <a class="col-1 mr-1 btn btn-success text-light" href="autres.php"><img src="assets/images/autres.svg" alt="" width="20"> <br> Autres</a>
        </div>
    <br>
    <h3 class="text-center">LISTE DES EQUIPEMENTS REPERTORIES</h3>
    
    <br>
    <table class="table table-dark">
                <thead>
                    <tr>
                        <td>N°</td>
                        <td>Type</td>
                        <td>Modèle</td>
                        <td>Etat </td>
                        <td>Plus d'informations</td>
                        <td>Action 1</td>
                        <td>Action 2</td>
                        <td>Action 3</td>
                    </tr>
                </thead>
                <tbody id="myTable">    
                    <?php foreach ($equipements as $equipement): ?>
                    <tr>
                        <td><?= $numerotation ?></td>
                        <td><?= $equipement['typeEquipement'] ?></td>
                        <td><?= $equipement['modeleEquipement'] ?></td>
                        <td><?= $equipement['etatEquipement'] ?></td>
                        <td><a href="voirPlus.php?idEquipement=<?= $equipement[0] ?>">Voir plus</a></td>
                        <td><a href="update.php?idEquipement=<?= $equipement[0] ?>" onclick="return confirm('Modifier les informations relatives à l\'équipement ?')">Modifier</a></td>
                        <td><a href="panne.php?idEquipement=<?= $equipement[0] ?>" onclick="return confirm('Remplir fiche de panne de l\'équipement ?')">Fiche de panne</a></td>
                        <td><a href="delete.php?idEquipement=<?= $equipement[0] ?>" onclick="return confirm('Supprimer cet équipement du parc ?')">Mettre hors service</a></td>
                    </tr>
                    <?php
                     $numerotation++ ;
                     endforeach ?>
                </tbody>
    </table>
   
    <div class="pagination">
        <?php if ($pageCourante > 1): ?>
            <a href="?page=<?= $pageCourante - 1 ?>">Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" <?= ($i === $pageCourante) ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($pageCourante < $totalPages): ?>
            <a href="?page=<?= $pageCourante + 1 ?>">Suivant</a>
        <?php endif; ?>
    </div>
    <br> <br>
    <div class="marquee visual">
      <div class="maclass">
        <span><img src ="assets/images/PC-DISPLAY.svg" alt="" width="60"> <?= $nbre_ordi['idEquipement']; ?>  ordinateur(s) </span>
        <span><img src="assets/images/printer.svg" alt="" style width="60"> <?= $nbre_imp['idEquipement']; ?> imprimante(s)</span>
        <span><img src="assets/images/speedometer2.svg" alt="" style width="60"> <?= $nbre_onduleur['idEquipement']; ?> onduleur(s)</span>
        <span><img src="assets/images/telephone.svg" alt="" style width="60"> <?= $nbre_tel['idEquipement']; ?> téléphone(s)</span>
        <span><img src="assets/images/autres.svg" alt="" style width="60"> <?= $nbre_autre['idEquipement']; ?> autres équipements</span>
      </div>
    </div>
<script>
        $(document).ready(function(){
        $("#example-search-input").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            });
            function search() {
            var searchInput = document.getElementById("searchInput").value;

            // Filter your data based on the input values
            var filteredData = data.filter(function(item) {
            return (item.name.includes(searchInput));
            });

    // Do something with the filtered data, such as displaying it on the page
          }
        });


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