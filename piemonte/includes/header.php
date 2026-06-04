<?php
$page = $page ?? 'inicio';
$title = $title ?? 'CD Piemonte Trasco';
$desc = $desc ?? 'Equipo de fútbol aficionado de la ESEI - Campus de Ourense (UVigo). Un trasco no meu peito.';
?>
<!DOCTYPE html>
<html lang="gl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($desc) ?>">
<meta property="og:title" content="<?= htmlspecialchars($title) ?>">
<meta property="og:description" content="<?= htmlspecialchars($desc) ?>">
<meta property="og:type" content="website">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Space+Grotesk:wght@400;500;700&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css?v=1.0.1">

</head>
<body data-page="<?= htmlspecialchars($page) ?>">
<header class="site-header">
  <div class="container nav-wrap">
    <a href="index.php" class="brand">
      <span class="brand-shield">CD</span>
      <span class="brand-name">Piemonte <em>Trasco</em></span>
    </a>
    <button class="nav-toggle" aria-label="Menú" onclick="document.body.classList.toggle('nav-open')">
      <span></span><span></span><span></span>
    </button>
    <nav class="main-nav">
      <a href="index.php" class="<?= $page==='inicio'?'active':'' ?>">Inicio</a>
      <a href="club.php" class="<?= $page==='club'?'active':'' ?>">El Club</a>
      <a href="plantilla.php" class="<?= $page==='plantilla'?'active':'' ?>">Plantilla</a>
      <a href="calendario.php" class="<?= $page==='calendario'?'active':'' ?>">Calendario</a>
      <a href="contacto.php" class="<?= $page==='contacto'?'active':'' ?>">Contacto</a>
    </nav>
  </div>
</header>
<main>