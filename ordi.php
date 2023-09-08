<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "" );
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}

$numerotation = 1;

$stmt = $pdo->query("SELECT * FROM equipements WHERE typeEquipement= 'Ordinateur' ");
$ordi = $stmt->fetchAll();

$req1 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement='Ordinateur' AND logicielsEquipement LIKE '%Antivirus%'");
$nbre_antivirus = $req1 -> fetch();

$req2 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement='Ordinateur' AND logicielsEquipement LIKE '%Licence office%'");
$nbre_office = $req2 -> fetch();

$req3 = $pdo->query("SELECT COUNT(*) AS idEquipement FROM equipements WHERE typeEquipement='Ordinateur' AND logicielsEquipement LIKE '%Clé d\'activation windows%'");
$nbre_windows = $req3 -> fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="dist/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
</head>
<body>
    <a class="btn btn-primary justify-content-start" href="accueil.php">Retour</a>
    <br><br>
    <div class="d-flex col-12 text-center justify-content-center">
        <div class="bg-danger text-light col-3">
            <h3><?= $nbre_windows['idEquipement']; ?></h3> <h5>ordinateur(s) avec <br> la clé d'activation windows</h5>
        </div>
        <div class="bg-primary text-light col-3">
            <h3><?= $nbre_antivirus['idEquipement']; ?></h3> <h5>ordinateur(s) avec <br> un antivirus installé</h5>
        </div>
        <div class="bg-secondary text-light col-3">
            <h3><?= $nbre_office['idEquipement']; ?></h3> <h5>ordinateur(s) avec <br> la licence office installée</h5> 
        </div>
    </div>
    <br>
    <div class="input-group d-flex w-50 m-auto">
        <input class="form-control border-end-0 w-25" type="search" value="" id="example-search-input" placeholder="" onkeyup="searchTable()"> 
        <input class="btn btn-success" type="submit" value="Rechercher"> 
    </div>
    <br>
    <h1 class="text-center">LISTE DES ORDINATEURS</h1>
    <br>
    <table class="table table-dark">
                <thead>
                    <tr>
                        <td>N°</td>
                        <td>Type</td>
                        <td>Modèle</td>
                        <td>Localisation</td>
                        <td>Plus d'informations</td>
                        <td>Action 1</td>
                        <td>Action 2</td>
                    </tr>
                </thead>
                <tbody id="myTable">    
                    <?php foreach ($ordi as $ordi): ?>
                    <tr>
                        <td><?= $numerotation ?></td>
                        <td><?= $ordi['typeEquipement'] ?></td>
                        <td><?= $ordi['modeleEquipement'] ?></td>
                        <td><?= $ordi['emplacementEquipement'] ?></td>
                        <td><a href="voirPlus.php?idEquipement=<?= $ordi[0] ?>">Voir plus</a></td>
                        <td><a href="update.php?idEquipement=<?= $ordi[0] ?>" onclick="return confirm('Modifier les informations relatives à l\'équipement ?')">Modifier</a></td>
                        <td><a href="delete.php?idEquipement=<?= $ordi[0] ?>" onclick="return confirm('Supprimer cet équipement du parc ?')">Mettre hors service</a></td>
                    </tr>
                    <?php
                     $numerotation++ ;
                     endforeach ?>
                </tbody>
    </table>
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
    </script>
</body>
</html>