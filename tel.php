<?php
try{
    $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "" );
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}
$numerotation = 1;

$stmt = $pdo->query("SELECT * FROM equipements WHERE typeEquipement= 'Telephone' ");
$ond = $stmt->fetchAll();

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
    <br>
    <div class="input-group d-flex w-50 m-auto">
        <input class="form-control border-end-0 w-25" type="search" value="" id="example-search-input" placeholder="" onkeyup="searchTable()"> 
        <input class="btn btn-success" type="submit" value="Rechercher"> 
    </div>
    <br>
    <h1 class="text-center">LISTE DES TELEPHONES</h1>
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
                    <?php foreach ($ond as $ond): ?>
                    <tr>
                        <td><?= $numerotation ?></td>
                        <td><?= $ond['typeEquipement'] ?></td>
                        <td><?= $ond['modeleEquipement'] ?></td>
                        <td><?= $ond['emplacementEquipement'] ?></td>
                        <td><a href="voirPlus.php?idEquipement=<?= $ond[0] ?>">Voir plus</a></td>
                        <td><a href="update.php?idEquipement=<?= $ond[0] ?>" onclick="return confirm('Modifier les informations relatives à l\'équipement ?')">Modifier</a></td>
                        <td><a href="delete.php?idEquipement=<?= $ond[0] ?>" onclick="return confirm('Supprimer cet équipement du parc ?')">Mettre hors service</a></td>
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