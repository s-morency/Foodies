<?php
// Chargement des données des utilisateurs depuis le fichier JSON
$usersJson = file_get_contents('includes/data/user.json');
$users = json_decode($usersJson, true);

$errors = [
  'username' => '',
  'email' => '',
  'password' => '',
  'password-confirmation' => ''
];

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}
// Vérification si la requête est de type POST et si le bouton de soumission a été cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  // Génération d'un nouvel ID pour le nouvel utilisateur en incrémentant le plus grand ID existant
  $userId = max(array_keys($users));
  $userId++;
  // Récupération des données du formulaire

  $array = $_POST;
  array_walk_recursive($array, function (&$v) {
    $v = filter_var(trim($v), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  });
  $prepared = $array;

  $username = $prepared['username'] ?? '';
  $email = $prepared['email'] ?? '';

  $password = $prepared['password'] ?? '';
  $passwordConfirmation = $prepared['password-confirmation'];

  if (empty($prepared['username'])) {
    $errors['username'] = 'Le champ Nom d\'utilisateur est requis.';
  }

  if (empty($prepared['email'])) {
    $errors['email'] = 'Le champ Courriel est requis.';
  }
  if (isset($prepared['preferences'])) {
    $preferences = $prepared['preferences'];
  }
  if (empty($prepared['password'])) {
    $errors['password'] = 'Le champ Mot de passe est requis.';
  }
  if (empty($prepared['password-confirmation'])) {
    $errors['password'] = 'Le champ Confirmation de mot de passe est requis.';
  }
  if ($password !==  $passwordConfirmation) {
    $errors['password'] = 'Les deux mots de passe ne sont pas identiques!';
  }
  // Validation des données du formulaire
  // Si aucune erreur n'est présente, on ajoute le nouvel utilisateur et on met à jour le fichier JSON
  if (empty(array_filter($errors, fn ($element) => $element !== ''))) {
    $users = [
      ...$users, [
        "userId" => $userId,
        "username" => $username,
        "email" => $email,
        "preferences" => $preferences ?? '',
        "password" => password_hash($password, PASSWORD_DEFAULT)
      ],
    ];
    file_put_contents('includes/data/user.json', json_encode(($users)));
    // Affichage d'un message de succès
    $login_success = "<div> 
      <p class='text-success'>Vous êtes inscrit avec succès.</h3> 
      <p>Cliquez ici pour vous <a href='login.php'>connecter</a></p> 
    </div>";
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieShare - S'enregistrer</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <?php require_once('includes/header.php'); ?>
    <div class="box-container">
      <h1>S'inscrire</h1>
      <form action="register.php" method="post">
        <div class="form-group">
          <input type="text" name="username" placeholder="Nom d'utilisateur" required />
          <?php if (isset($errors['username'])) : ?>
            <p class='text-danger'><?= $errors['username'] ?? '' ?>
            <?php endif; ?>
            <input type="email" name="email" placeholder="Courriel" required />
            <?php if (isset($errors['email'])) : ?>
            <p class='text-danger'><?= $errors['email'] ?? '' ?>
            <?php endif; ?>
            <div class="preferences">
              <label for="Italien"><input type="checkbox" name="preferences[]" value="Italien" id="Italien"> Italien</label>
              <label for="Vegan"><input type="checkbox" name="preferences[]" value="Vegan" id="Vegan"> Vegan</label>
              <label for="Asiatique"><input type="checkbox" name="preferences[]" value="Asiatique" id="Asiatique"> Asiatique</label>
              <label for="Mexicain"><input type="checkbox" name="preferences[]" value="Mexicain" id="Mexicain"> Mexicain</label>
              <label for="Français"><input type="checkbox" name="preferences[]" value="Français" id="Français"> Français</label>
              <label for="Americain"><input type="checkbox" name="preferences[]" value="Americain" id="Americain"> Américain</label>
            </div>
            <input type="password" name="password" placeholder="Mot de passe" required />
            <input type="password" name="password-confirmation" placeholder="Mot de passe à nouveau" required />
            <?php if (isset($errors['password'])) : ?>
              <p class='text-danger'><?= $errors['password'] ?? '' ?>
              <p class='text-success'><?= $login_success ?? '' ?>
              <?php endif; ?>
              <button type="submit" name="submit">S'inscrire</button>
              <p class="box-register">Déjà inscrit?
                <a href="login.php">Connectez-vous ici</a>
              </p>
        </div>
      </form>
    </div>
  </div>
</body>

</html>