<?php
$page='calendario';
$title='Calendario y Clasificación — CD Piemonte Trasco';
require_once 'includes/db.php';
include 'includes/header.php';

$meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

// Traemos los partidos y concatenamos los goles de cada goleador en ese partido
$partidos = db()->query(
  "SELECT p.id, p.fecha, p.hora, p.local, p.visitante, p.goles_local, p.goles_visitante, p.condicion, p.jugado,
          (SELECT GROUP_CONCAT(CONCAT(gp.goleador, ' (', gp.goles, ')') SEPARATOR ', ') 
           FROM goles_partidos gp 
           WHERE gp.partido_id = p.id) AS goleadores
   FROM partidos p 
   ORDER BY p.fecha DESC, p.hora DESC"
)->fetchAll();

// NUEVA CONSULTA: Traemos los jugadores con sus goles totales calculados en tiempo real de más a menos goles
$goleadores_ranking = db()->query(
  "SELECT j.nombre, j.dorsal, j.posicion, IFNULL(SUM(gp.goles), 0) AS goles_totales
   FROM jugadores j
   LEFT JOIN goles_partidos gp ON j.nombre = gp.goleador
   WHERE j.activo = 1
   GROUP BY j.id
   ORDER BY goles_totales DESC, j.nombre ASC"
)->fetchAll();

// VARIABLES PARA CALCULAR LOS DATOS DE LA TEMPORADA
$pj = 0; $pg = 0; $pe = 0; $pp = 0; $gf = 0; $gc = 0;
$racha_temp = []; 

foreach ($partidos as $p) {
    if ($p['jugado'] == 1 && $p['goles_local'] !== null && $p['goles_visitante'] !== null) {
        $fecha_partido = strtotime($p['fecha']);
        $inicio_temporada = strtotime('2025-01-01');
        $fin_temporada = strtotime('2026-12-31');

        if ($fecha_partido >= $inicio_temporada && $fecha_partido <= $fin_temporada) {
            $pj++; 

            if ($p['condicion'] === 'casa') {
                $gf += $p['goles_local'];
                $gc += $p['goles_visitante'];
                
                if ($p['goles_local'] > $p['goles_visitante']) {
                    $pg++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'G';
                } elseif ($p['goles_local'] < $p['goles_visitante']) {
                    $pp++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'P';
                } else {
                    $pe++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'E';
                }
            } else {
                $gf += $p['goles_visitante'];
                $gc += $p['goles_local'];
                
                if ($p['goles_visitante'] > $p['goles_local']) {
                    $pg++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'G';
                } elseif ($p['goles_visitante'] < $p['goles_local']) {
                    $pp++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'P';
                } else {
                    $pe++;
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'E';
                }
            }
        }
    }
}

$ultimos_5 = array_slice(array_values($racha_temp), 0, 5);
$promedio_gf = $pj > 0 ? round($gf / $pj, 1) : 0;
$promedio_gc = $pj > 0 ? round($gc / $pj, 1) : 0;
$diff_goles = $gf - $gc;
?>

<section class="block">
  <div class="container">
    <div class="hero-logo-wrap reveal" style="margin-bottom: 1.5rem; display: flex; justify-content: center;">
      <img src="assets/img/logo.jfif" 
           alt="Escudo CD Piemonte Trasco" 
           style="width: 140px; height: 140px; object-fit: contain; border-radius: 24px; filter: drop-shadow(0 0 20px rgba(0, 255, 156, 0.3));">
    </div>
    <div class="section-head reveal">
      <span class="section-tag"></span>
      <h2>Calendario</h2>
      <p>Próximos partidos y resultados. Haz clic en un partido jugado para ver los goleadores. Las convocatorias se publican en <a href="https://instagram.com/piemonte_trasko" target="_blank" rel="noopener">@piemonte_trasko</a>.</p>
    </div>

    <?php foreach ($partidos as $p):
      $ts  = strtotime($p['fecha']);
      $day = date('d', $ts);
      $mo  = $meses[(int)date('n', $ts) - 1];
      $hora = $p['hora'] ? substr($p['hora'],0,5) : '';
      $loc  = $p['condicion'] === 'casa' ? 'Casa' : 'Fuera';
      $marc = $p['jugado'] ? ($p['goles_local'].' - '.$p['goles_visitante']) : 'Por jugar';
      
      $es_clickable = $p['jugado'] && !empty($p['goleadores']);
    ?>
      <div class="match-container">
        <div class="match <?= $p['jugado']?'':'pend' ?> <?= $es_clickable ? 'clickable' : '' ?> reveal">
          <div class="match-date">
            <div class="day"><?= $day ?></div>
            <div class="mo"><?= $mo ?></div>
          </div>
          <div style="flex-grow: 1; min-width: 0; padding-right: 0.5rem;">
            <div class="match-teams" style="word-wrap: break-word; overflow-wrap: break-word; max-width: 100%;"><?= htmlspecialchars($p['local']) ?> <span style="color:var(--muted)">vs</span> <?= htmlspecialchars($p['visitante']) ?></div>
            <div class="match-meta"><?= $hora ?> · <?= $loc ?> <?= $es_clickable ? '· <span style="color:var(--terminal); font-size:0.75rem;">⚡ VER GOLES</span>' : '' ?></div>
          </div>
          <div class="match-score"><?= htmlspecialchars($marc) ?></div>
        </div>

        <?php if ($es_clickable): ?>
          <div class="match-details-dropdown">
            <div class="goleadores-title">⚡ Goles Piemonte</div>
            <ul class="goleadores-list" style="list-style: none; padding: 0;">
              <?php
                $goles_partido = explode(', ', $p['goleadores']);
                foreach ($goles_partido as $g):
                  $g = trim($g);
                  if ($g !== ''):
                    $pos_parentesis = strpos($g, '(');
                    if ($pos_parentesis !== false) {
                      $nombre_jugador = trim(substr($g, 0, $pos_parentesis));
                      $cantidad_goles = (int)str_replace(['(', ')'], '', substr($g, $pos_parentesis));
                    } else {
                      $nombre_jugador = $g;
                      $cantidad_goles = 1;
                    }
              ?>
                <li style="margin-bottom: 0.45rem; font-family: var(--font-sans); display: flex; align-items: center; gap: 0.5rem;">
                  <span style="color: var(--terminal); font-family: var(--font-mono); font-weight: bold;">⚽ <?= $cantidad_goles ?> <?= $cantidad_goles === 1 ? 'gol' : 'goles' ?></span> 
                  <span style="color: var(--bone);">— <?= htmlspecialchars($nombre_jugador) ?></span>
                </li>
              <?php endif; endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="block" style="background:var(--carbon);">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag"></span>
      <h2>Datos de la Temporada</h2>
    </div>

    <div class="reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
      
      <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; margin-bottom: 0.5rem;">Últimos 5 partidos</div>
        <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center; height: 32px;">
          <?php if(empty($ultimos_5)): ?>
            <span style="color:var(--muted); font-size:0.9rem;">-</span>
          <?php else: ?>
            <?php foreach($ultimos_5 as $resultado): 
              $color = '#ff3860'; 
              if ($resultado === 'G') $color = '#00ff9c';
              if ($resultado === 'E') $color = '#ffdd57'; 
            ?>
              <span style="
                display: inline-block; 
                width: 24px; 
                height: 24px; 
                line-height: 24px; 
                border-radius: 50%; 
                font-size: 0.75rem; 
                font-weight: bold; 
                color: #000;
                background: <?= $color ?>;
                box-shadow: 0 0 10px <?= $color . '33' ?>;
              "><?= $resultado ?></span>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; margin-bottom: 0.5rem;">Diferencia Goles</div>
        <div style="font-size: 1.8rem; font-weight: bold; color: <?= $diff_goles >= 0 ? '#00ff9c' : '#ff3860' ?>;">
          <?= $diff_goles > 0 ? '+'.$diff_goles : $diff_goles ?>
        </div>
      </div>

      <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; margin-bottom: 0.5rem;">Goles Favor / Part.</div>
        <div style="font-size: 1.8rem; font-weight: bold; color: #fff;"><?= $promedio_gf ?></div>
      </div>

      <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; margin-bottom: 0.5rem;">Goles Contra / Part.</div>
        <div style="font-size: 1.8rem; font-weight: bold; color: #fff;"><?= $promedio_gc ?></div>
      </div>

    </div>

    <div class="reveal" style="overflow-x:auto; margin-bottom: 4rem;">
      <div style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--terminal); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">
        > Clasificación General
      </div>
      <table class="tabla">
        <thead>
          <tr>
            <th>Equipo</th>
            <th class="num">PJ</th>
            <th class="num">PG</th>
            <th class="num">PE</th>
            <th class="num">PP</th>
            <th class="num">GF</th>
            <th class="num">GC</th>
          </tr>
        </thead>
        <tbody>
          <tr class="us">
            <td>CD Piemonte Trasco</td>
            <td class="num"><?= $pj ?></td>
            <td class="num"><?= $pg ?></td>
            <td class="num"><?= $pe ?></td>
            <td class="num"><?= $pp ?></td>
            <td class="num"><?= $gf ?></td>
            <td class="num"><?= $gc ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="reveal" style="overflow-x:auto;">
      <div style="font-family: var(--font-mono); font-size: 0.8rem; color: var(--terminal); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;">
        > Tabla de Goleadores Piemonte
      </div>
      <table class="tabla">
        <thead>
          <tr>
            <th style="width: 70px;">Dorsal</th>
            <th>Jugador</th>
            <th>Posición</th>
            <th class="num" style="color: var(--terminal); width: 100px;">Goles</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($goleadores_ranking)): ?>
            <tr>
              <td colspan="4" style="color: var(--muted); text-align: center;">No hay jugadores registrados en la plantilla.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($goleadores_ranking as $index => $jugador): 
              $es_pichichi = ($index === 0 && $jugador['goles_totales'] > 0);
            ?>
              <tr class="<?= $es_pichichi ? 'pichichi' : '' ?>">
                <td style="font-family: var(--font-mono); color: <?= $es_pichichi ? '#000' : 'var(--muted)' ?>;">
                  #<?= htmlspecialchars($jugador['dorsal']) ?>
                </td>
                <td style="font-weight: <?= $es_pichichi ? 'bold' : 'normal' ?>;">
                  <?= htmlspecialchars($jugador['nombre']) ?> <?= $es_pichichi ? '👑' : '' ?>
                </td>
                <td style="font-size: 0.9rem; color: <?= $es_pichichi ? '#000' : 'var(--muted)' ?>;">
                  <?= htmlspecialchars($jugador['posicion']) ?>
                </td>
                <td class="num" style="font-family: var(--font-mono); font-weight: bold; font-size: 1.1rem; color: <?= $es_pichichi ? '#000' : 'var(--terminal)' ?>;">
                  <?= $jugador['goles_totales'] ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>