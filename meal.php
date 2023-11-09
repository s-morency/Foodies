<?php
// Chargement des données des repas depuis le fichier JSON
$mealsJson = file_get_contents('includes/data/meal.json');
$meals = json_decode($mealsJson, true);

if ($meals === null) {
  die('Erreur lors du chargement des données des repas.');
}
// Chargement des données des utilisateurs depuis le fichier JSON
$usersJson = file_get_contents('includes/data/user.json');
$users = json_decode($usersJson, true);

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}
// Récupération de l'ID du repas à afficher à partir des paramètres de requête GET
$_GET = filter_input_array(INPUT_GET, [
  'id' => FILTER_SANITIZE_NUMBER_INT
]);
$mealId = isset($_GET['id']) ? $_GET['id'] : '';
if ((isset($mealId) && $mealId !== '')) {
  // Récupération des détails du repas correspondant à l'ID
  $meal = $meals[$mealId];
}

// Calcul de la moyenne des étoiles de commentaires et arrondir vers le bas
$count = 0;
$i = 0;
foreach ($meal['comment'] as $comment) {
  $finalRating = 0;
  $count += $meal['comment'][$i]['rating'];
  $i++;
  $finalRating = floor($count / $i);
}

// Afficher ies étoiles et la quantité de commentaire, ou 0.
if (isset($finalRating)) {
  $rating = '<img class="stars-review" src="assets/images/' . $finalRating . '-star.svg"></img> (' . $i . ')';
} else {
  $rating = '<img class="stars-review" src="assets/images/0-star.svg"> (0)';
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
    <div class="box-container">
      <div class="meal-top">
        <img class="meal-img" src="<?= $meal['image'] ?>" alt="<?= $meal['name'] ?>"></img>

        <div class="meal-text">
          <h1><?= $meal['name'] ?></h1>
          <div class="meal-rating">
            <?= $rating ?>
            <a href="add_comment.php?mealId=<?= $meal['id'] ?>">
              <div class="meal-button">Évaluer ce repas</div>
            </a>
          </div>
          <p><?= $meal['description'] ?></p>
          <p><strong>Prix:</strong> <?= $meal['price'] ?>$ </p>
          <p><strong>Localisation:</strong> <?= $meal['location'] ?></p>
        </div>
      </div>
      <div class="meal-reviews">
        <?php if ($meal['comment']) :
          foreach ($meal['comment'] as $comment) : ?>
            <div class="single-review">
              <p><?= $users[$comment['userId']]['username'] ?></p>
              <p><img class="stars-review" src="assets/images/<?= $comment['rating'] ?>-star.svg"> </img><?= $comment['short'] ?></p>
              <p><?= $comment['long'] ?></p>
              <?php echo !empty($comment['image-comment']) ? '<p><img class ="image-comment" src="assets/images/' . $comment['image-comment'] . '"></img></p>' : ''; ?>
            </div>
          <?php endforeach;
        else : ?>
          <div class="single-review">
            <p>Pas de commentaire pour ce repas.</p>
          </div>
        <?php
        endif; ?>
      </div>
    </div>
    <?php require_once('includes/footer.php'); ?>
  </div>
</body>

</html>