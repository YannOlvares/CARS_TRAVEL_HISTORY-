<?php
// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$baseDeDonnees = "oazis";

$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}

// Requête SQL pour récupérer la liste des employés et les équipements liés
$requete = "SELECT e.nomEmploye, e.prénomEmploye, eq.typeEquipement, eq.numSeriequipement
            FROM employes e
            LEFT JOIN equipements eq ON e.idEmploye = eq.utilisateursEquipement";

$resultat = $connexion->query($requete);

// Vérification du résultat de la requête

    // Affichage du tableau
    echo "<table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Équipement lié</th>
            </tr>";
    
    // Parcours des lignes de résultat
    while ($ligne = $resultat->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $ligne['nomEmploye'] . "</td>";
        echo "<td>" . $ligne['prénomEmploye'] . "</td>";
        echo "<td>" . $ligne['typeEquipement'] . " (n° " . $ligne['numSeriequipement'] . ")</td>";
        echo "</tr>";
    }
    
    echo "</table>";
 

// Fermeture de la connexion à la base de données
$connexion->close();
?>
