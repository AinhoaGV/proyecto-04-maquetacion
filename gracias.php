<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gracias</title>
  </head>
  <body>
    <nav>

    </nav>
    <?php
    $nombre = isset($_GET["nom"]) ? htmlspecialchars($_GET["nom"], ENT_QUOTES, 'UTF-8') : 'amigo';
    ?>
    <header>
      <h1>Gracias, <?= $nombre; ?></h1>
    </header>
   
    <script type="module" src="/src/main.js"></script>
  </body>
</html>
