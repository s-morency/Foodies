<?php
session_start();
$errors = [
  'username' => '',
  'password' => ''
];
$_SESSION['authenticated'] = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $_POST = filter_input_array(INPUT_POST, [
    'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'password' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
  ]);

  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Valider les champs du formulaire
  if (empty($username)) {
    $errors['username'] = 'Le champ Nom d\'utilisateur de votre commentaire est requis.';
  }

  if (empty($password)) {
    $errors['password'] = 'Le champ mot de passe est requis.';
  }

  $usersJson = file_get_contents('includes/data/user.json');
  $users = json_decode($usersJson, true);

  if ($users === null) {
    die('Erreur lors du chargement des données des utilisateurs.');
  }

  if ($_SESSION['authenticated']) {
    // Si l'utilisateur est déjà authentifié, affiche un message et des liens vers les autres pages
    echo "<p>Vous êtes déjà connecté en tant que " . $_SESSION['username'] . ".</p>";
    echo "<p><a href=\"logout.php\">Se déconnecter</a></p>";
    echo "<p><a href=\"index.php\">Retour à la page principale</a></p>";
  } else {
    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
      foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
          // Si les informations de connexion sont valides, met à jour la session et redirige vers la page principal
          $_SESSION['username'] = $username;
          $_SESSION['authenticated'] = true;
          $_SESSION['userId'] = $user['userId'];
          header('Location: index.php');
          exit;
        }
      }
    }
    if (!$_SESSION['authenticated']) {
      $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieShare - Connexion</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <?php require_once('includes/header.php'); ?>
    <div class="box-container">
      <h1>Connexion</h1>
      <?php if (isset($error)) : ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <form action="login.php" method="POST">
        <div class="form-group">
          <input type="text" id="username" name="username" required placeholder="Nom d'utilisateur">
          <?php if ($errors['username']) : ?>
            <p class='text-danger'><?= $errors['username'] ?? '' ?></p>
          <?php endif; ?>
          <input type="password" id="password" name="password" required placeholder="Mot de passe">
          <?php if ($errors['password']) : ?>
            <p class='text-danger'><?= $errors['password'] ?? '' ?></p>
          <?php endif; ?>
        </div>
        <button type="submit">Entrer</button>
      </form>
      <p>Vous n'êtes pas inscrit? <a href="register.php">S'enregistrer</a></p>
    </div>
  </div>
</body>

</html>