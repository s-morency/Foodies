<?php

session_start();

$mealsJson = file_get_contents('includes/data/meal.json');
$meals = json_decode($mealsJson, true);

if ($meals === null) {
  die('Erreur lors du chargement des données des repas.');
}

$_GET = filter_input_array(INPUT_GET, [
  'search' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
]);
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($searchTerm)) {
  // Filtrer les repas en fonction du terme de recherche saisi
  $filteredMeals = array_filter($meals, function ($meal) use ($searchTerm) {
    return stripos($meal['name'], $searchTerm) !== false;
  });
  $meals = $filteredMeals;
}

$_GET = filter_input_array(INPUT_GET, [
  'price' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
]);
$priceTerm = isset($_GET['price']) ? $_GET['price'] : '';
if (!empty($priceTerm)) {
  $min = 0.0;
  $max = INF;
  if (stripos($priceTerm, 'minus10') !== false) {
    $max = 10.00;
  } elseif (stripos($priceTerm, '10-20') !== false) {
    $min = 10.01;
    $max = 20.00;
  } elseif (stripos($priceTerm, '20Plus') !== false) {
    $min = 20.01;
  }
  // Filtrer les repas en fonction de la fourchette de prix sélectionnée
  $filteredMeals = array_filter($meals, function ($meal) use ($min, $max) {
    return $min < $meal['price'] && $meal['price'] < $max;
  });
  $meals = $filteredMeals;
}

function displayMeal($meal)
{
  echo '<div class="meal">';
  echo '<a href="meal.php?id=' . $meal['id'] . '"><img class="meal-photo" src="' . $meal['image'] . '" alt="' . $meal['name'] . '"></img>';
  echo '<h2>' . $meal['name'] . '</h2></a>';
  echo '<div class="meal-container"><p class="meal-description">' . $meal['description'] . '</p><p class="meal-price"><strong> ' . $meal['price'] . '$ </strong></p></div>';

  $count = 0;
  $i = 0;
  foreach ($meal['comment'] as $comment) {
    $finalRating = 0;
    $count += $meal['comment'][$i]['rating'];
    $i++;
    $finalRating = floor($count / $i);
  }

  echo '<div class="meal-bottom">';
  if (isset($finalRating)) {
    echo '<img class="stars-review" src="assets/images/' . $finalRating . '-star.svg"></img> (' . $i . ')';
  } else {
    echo '<img class="stars-review" src="assets/images/0-star.svg"></img> (0)';
  }

  echo '';
  echo '<br><strong>Localisation:</strong> ' . $meal['location'];
  echo '</div></div>';
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
    <div class="meals">
      <?php foreach ($meals as $meal) : ?>
        <?php displayMeal($meal); ?>
      <?php endforeach; ?>
    </div>
    <?php require_once('includes/footer.php'); ?>
  </div>
</body>

</html>