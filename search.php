<?php
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
      <h1>Recherche</h1>
      <!-- Formulaire de recherche et de filtrage -->
      <form action="index.php" method="GET">
        <div class="form-group">
          <input type="text" id="search" name="search" value="<?= $searchTerm ?? ''; ?>" placeholder="Rechercher par nom de plat">
          <button type="submit">Rechercher</button>
        </div>
        <div class="form-group">
          <select id="price" name="price">
            <option value="">Tous les prix</option>
            <option value="minus10">Moins de 10 $</option>
            <option value="10-20">10 $ - 20 $</option>
            <option value="20Plus">Plus de 20 $</option>
          </select>
          <button type="submit">Filtrer</button>
        </div>
      </form>
    </div>
    <?php require_once('includes/footer.php'); ?>
  </div>
</body>

</html>