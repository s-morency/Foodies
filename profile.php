<?php
// Démarrage de la session
session_start();
// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
  // Redirection vers la page de connexion si l'utilisateur n'est pas authentifié
  header('Location: login.php');
  exit;
}
// Chargement des données des utilisateurs depuis le fichier JSON
$usersJson = file_get_contents('includes/data/user.json');
$users = json_decode($usersJson, true);

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}
// Récupération de l'ID de l'utilisateur à afficher à partir des paramètres de requête GET
$_GET = filter_input_array(INPUT_GET, [
  'userId' => FILTER_SANITIZE_NUMBER_INT
]);
$userId = isset($_GET['userId']) ? $_GET['userId'] : '';
if ((isset($userId) && $userId !== '')) {
  // Récupération des détails de l'utilisateur correspondant à l'ID
  $user = $users[$userId];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieShare - Accueil</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <?php require_once('includes/header.php'); ?>
    <h1>Bienvenue sur FoodieShare</h1>
    <div class="box-container">
      <h2><?= $user['username'] ?></h2>
      <p><?= $user['email'] ?></p>
      <?php if ($user['preferences']) :
        foreach ($user['preferences'] as $preference) : ?>
          <p><?= $preference ?></p>
      <?php endforeach;
      endif; ?>
      <a class="center" href="account_manager.php?userId=<?= $user['userId'] ?>">
        <div class="meal-button">Modifier</div>
      </a>
      <br>
      <a class="center" href="logout.php">
        <div class="meal-button">Déconnexion</div>
      </a>
    </div>
    <?php require_once('includes/footer.php'); ?>
  </div>
</body>

</html>