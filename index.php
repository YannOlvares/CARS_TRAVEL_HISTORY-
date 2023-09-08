<?php
session_start();
try{
  $pdo = new PDO("mysql:host=localhost;dbname=oazis;charset=UTF8", "root", "" );
} catch (Exception $e) {
  die('Erreur : '.$e->getMessage());
}

@$identifiant = htmlspecialchars($_POST['identifiant']);
@$motDePasse = $_POST['motDePasse'];

if ($_SERVER["REQUEST_METHOD"] == "POST"){ 
    if(!empty($identifiant) AND !empty($motDePasse)){     
        $errors = [];
        $recupAdmin = $pdo -> prepare("SELECT * FROM administrateur WHERE identifiant = ? AND motDePasse = ?");
        $recupAdmin -> execute(array($identifiant,$motDePasse));
      
        if($recupAdmin -> rowCount() > 0){
        $_SESSION['identifiant'] = $identifiant;
        $_SESSION['motDePasse'] = $motDePasse;
        $_SESSION['id'] = $recupAdmin->fetch()['id'];
        header('Location: accueil.php');
        }
        else{
            $errors['Erreur1']="Identifiant ou mot de passe incorrect !";
        }
    }
    else{
        $errors['Erreur2']="Veuillez remplir tout les champs !";
    }       
    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="css/bootstrap.css" rel="stylesheet">
  <style>
   body{
      /*background-color: red;*/
      background-image: url(assets/images/back.jpg);
      background-repeat: no-repeat;
      background-position-x: center;
      background-size:100%;
      -webkit-backdrop-filter: blur(5px);
      backdrop-filter: blur(5px);
    }
   
  </style>
</head>
<body>
    <div class="container w-50" style="margin-top: 10%;">
          <!--w-50 a un effet de text-center--> 
          <div class="jumbotron shadow-lg">
            <!--jumbotron permet de créer un cadre--->
              <h1 class="text-center">CONNEXION</h1>
              <hr class="w-100 text-center bg dark">
              <form method="POST">
                  <div class="form-group">
                      <label for="id">IDENTIFIANT</label>
                      <input type="text" id="id" class="form-control" name="identifiant"  autocomplete="off"> 
                  </div>
                  <div class="form-group">
                      <label for="pwd">MOT DE PASSE</label>
                      <input type="password" id="pwd" class="form-control" name="motDePasse" autocomplete="off">
                  </div>
                  <br>
                  <?php if (isset($errors["Erreur1"])): ?>
                    <span  style="background-color:rgb(254, 150, 160);color:rgb(217, 1, 21)">
                        <?= $errors["Erreur1"] ?>
                    </span>
                <?php endif ?>
                <?php if (isset($errors["Erreur2"])): ?>
                    <span  style="background-color:rgb(254, 150, 160);color:rgb(217, 1, 21)">
                        <?= $errors["Erreur2"] ?>
                    </span>
                <?php endif ?>
                  <div class="text-center">
                      <input type="submit" value="Envoyer" class="btn btn-success">
                  </div>        
              </form>
          </div>
    </div>
</body>
</html>