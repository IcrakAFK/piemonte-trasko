<?php
$page='calendario';
$title='Calendario y Clasificación — CD Piemonte Trasco';
require_once 'includes/db.php';
include 'includes/header.php';

$meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

// Traemos todos los partidos ordenados del más reciente al más antiguo
$partidos = db()->query(
  "SELECT fecha, hora, local, visitante, goles_local, goles_visitante, condicion, jugado
   FROM partidos ORDER BY fecha DESC, hora DESC"
)->fetchAll();

// VARIABLES PARA CALCULAR LOS DATOS DE LA TEMPORADA 25/26
$pj = 0; $pg = 0; $pe = 0; $pp = 0; $gf = 0; $gc = 0;
$racha_temp = []; // Guardará los resultados cronológicamente

foreach ($partidos as $p) {
    // Solo contamos los partidos que ya se han JUGADO
    if ($p['jugado'] == 1) {
        $fecha_partido = strtotime($p['fecha']);
        $inicio_temporada = strtotime('2025-08-01');
        $fin_temporada = strtotime('2026-07-31');

        // Filtramos para que solo entren los partidos de la temporada 25/26
        if ($fecha_partido >= $inicio_temporada && $fecha_partido <= $fin_temporada) {
            $pj++; // Suma un partido jugado

            if ($p['condicion'] === 'casa') {
                // Si jugamos en casa
                $gf += $p['goles_local'];
                $gc += $p['goles_visitante'];
                
                if ($p['goles_local'] > $p['goles_visitante']) {
                    $pg++; // Victoria en casa
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'G';
                } elseif ($p['goles_local'] < $p['goles_visitante']) {
                    $pp++; // Derrota en casa
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'P';
                } else {
                    $pe++; // Empate en casa
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'E';
                }
            } else {
                // Si jugamos fuera
                $gf += $p['goles_visitante'];
                $gc += $p['goles_local'];
                
                if ($p['goles_visitante'] > $p['goles_local']) {
                    $pg++; // Victoria fuera
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'G';
                } elseif ($p['goles_visitante'] < $p['goles_local']) {
                    $pp++; // Derrota fuera
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'P';
                } else {
                    $pe++; // Empate fuera
                    $racha_temp[$p['fecha'] . '_' . $p['hora']] = 'E';
                }
            }
        }
    }
}

// Cortamos exactamente los últimos 5 partidos jugados
$ultimos_5 = array_slice(array_values($racha_temp), 0, 5);

// Cálculos avanzados para las tarjetas informativas
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
      <p>Próximos partidos y resultados. Las convocatorias se publican en <a href="https://instagram.com/piemonte_trasko" target="_blank" rel="noopener">@piemonte_trasko</a>.</p>
    </div>

    <?php foreach ($partidos as $p):
      $ts  = strtotime($p['fecha']);
      $day = date('d', $ts);
      $mo  = $meses[(int)date('n', $ts) - 1];
      $hora = $p['hora'] ? substr($p['hora'],0,5) : '';
      $loc  = $p['condicion'] === 'casa' ? 'Casa' : 'Fuera';
      $marc = $p['jugado'] ? ($p['goles_local'].' - '.$p['goles_visitante']) : 'Por jugar';
    ?>
      <div class="match <?= $p['jugado']?'':'pend' ?> reveal">
        <div class="match-date">
          <div class="day"><?= $day ?></div>
          <div class="mo"><?= $mo ?></div>
        </div>
        <div>
          <div class="match-teams"><?= htmlspecialchars($p['local']) ?> <span style="color:var(--muted)">vs</span> <?= htmlspecialchars($p['visitante']) ?></div>
          <div class="match-meta"><?= $hora ?> · <?= $loc ?></div>
        </div>
        <div class="match-score"><?= htmlspecialchars($marc) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="block" style="background:var(--carbon);">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag"></span>
      <h2>Datos de la Temporada (25/26)</h2>
    </div>

    <div class="reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
      
      <div style="background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05); text-align: center;">
        <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; margin-bottom: 0.5rem;">Últimos 5 partidos</div>
        <div style="display: flex; gap: 0.5rem; justify-content: center; align-items: center; height: 32px;">
          <?php if(empty($ultimos_5)): ?>
            <span style="color:var(--muted); font-size:0.9rem;">-</span>
          <?php else: ?>
            <?php foreach($ultimos_5 as $resultado): 
              // Definimos el color según el resultado: Verde (G), Gris/Amarillo (E), Rojo (P)
              $color = '#ff3860'; // Por defecto derrota
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
                color: #000; /* Texto oscuro para que resalte bien sobre los fondos brillantes */
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

    <div class="reveal" style="overflow-x:auto;">
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
  </div>
</section>

<?php include 'includes/footer.php'; ?>