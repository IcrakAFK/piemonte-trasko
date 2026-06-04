<?php
$page='plantilla';
$title='Plantilla — CD Piemonte Trasco';
$desc='Conoce a los jugadores del CD Piemonte Trasco: dorsal, posición y stack favorito.';
require_once 'includes/db.php';
include 'includes/header.php';

// 1. Traemos los jugadores activos desde la Base de Datos (SIN la columna imagen)
$resultado = db()->query(
    "SELECT dorsal AS num, nombre AS name, posicion AS pos, pais, capitan 
     FROM jugadores 
     WHERE activo = 1
     ORDER BY FIELD(posicion, 'Portero', 'Lateral Der.', 'Central', 'Lateral Izq.', 'Mediocentro Def.', 'Mediocentro', 'Mediapunta', 'Extremo Der.', 'Extremo Izq.', 'Delantero', 'Entrenador'), dorsal"
)->fetchAll(PDO::FETCH_ASSOC);

// 2. Agrupamos los jugadores por su posición con PHP para mantener los bloques visuales
$plantilla = [];
foreach ($resultado as $j) {
    // Definimos la categoría general para agrupar las posiciones del campo
    $categoria = 'Jugadores';
    if ($j['pos'] === 'Portero') {
        $categoria = 'Porteros';
    } elseif (in_array($j['pos'], ['Central', 'Lateral Der.', 'Lateral Izq.'])) {
        $categoria = 'Defensas';
    } elseif (in_array($j['pos'], ['Mediocentro', 'Mediapunta', 'Mediocentro Def.'])) {
        $categoria = 'Mediocentros';
    } elseif (in_array($j['pos'], ['Extremo Der.', 'Extremo Izq.', 'Delantero'])) {
        $categoria = 'Delanteros';
    } elseif ($j['pos'] === 'Entrenador') {
        $categoria = 'Entrenador';
    }
    
    $plantilla[$categoria][] = $j;
}
?>

<section class="block">
  <div class="container">
    
    <div class="hero-logo-wrap reveal" style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
      <img src="assets/img/logo.jfif" 
           alt="Escudo CD Piemonte Trasco" 
           style="width: 140px; height: 140px; object-fit: contain; border-radius: 24px; filter: drop-shadow(0 0 20px rgba(0, 255, 156, 0.3));">
    </div>

    <div class="section-head reveal" style="text-align: center;">
      <span class="section-tag"></span>
      <h2>La plantilla</h2>
      <h3>OS NOSOS</h3>
      <p>Nuestros guerreros organizados por líneas. Cada ficha incluye dorsal, demarcación oficial y el país asignado.</p>
    </div>

    <?php foreach ($plantilla as $categoria => $jugadores): ?>
      <div class="section-head reveal" style="margin-top: 3rem; margin-bottom: 1.5rem; text-align: left;">
        <h3 style="font-family: var(--font-display); font-size: 2rem; color: var(--terminal); border-bottom: 1px dashed var(--line); padding-bottom: 0.5rem;">
          // <?= $categoria ?>
        </h3>
      </div>

      <div class="grid grid-4" style="margin-bottom: 4rem;">
        <?php foreach ($jugadores as $j): ?>
          <article class="player reveal">
            <div class="player-num"><?= str_pad($j['num'], 2, '0', STR_PAD_LEFT) ?></div>
            <div class="player-body">
              <h3 class="player-name">
                <?= htmlspecialchars($j['name']) ?>
                <?= $j['capitan'] ? ' <span style="color:var(--gold)">(C)</span>' : '' ?>
              </h3>
              <span class="player-pos"><?= htmlspecialchars($j['pos']) ?></span>
              <div class="player-meta">
                <div><span class="pais"><?= htmlspecialchars($j['pais'] ?? '—') ?></span></div>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>

  </div>
</section>

<?php include 'includes/footer.php'; ?>