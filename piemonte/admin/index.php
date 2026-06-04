<?php
// === Panel de Administración — CD Piemonte Trasco ===
session_start();
require_once __DIR__ . '/../includes/db.php';

// Login
if (isset($_GET['logout'])) { session_destroy(); header('Location: index.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['login'])) {
    if (($_POST['user']??'')===ADMIN_USER && ($_POST['pass']??'')===ADMIN_PASS) {
        $_SESSION['admin']=true; header('Location: index.php'); exit;
    } else { $err = 'Credenciales incorrectas'; }
}
if (empty($_SESSION['admin'])) { ?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Admin · Piemonte Trasco</title>
<link rel="stylesheet" href="../assets/css/style.css"></head>
<body style="display:grid;place-items:center;min-height:100vh;background:var(--void);">
<form method="post" class="form" style="max-width:380px;width:100%;">
  <h2 style="font-family:'Bebas Neue';color:var(--terminal);">// admin login</h2>
  <?php if(!empty($err)): ?><div class="alert err"><?= $err ?></div><?php endif; ?>
  <label>Usuario</label><input name="user" required>
  <label>Contraseña</label><input type="password" name="pass" required>
  <button name="login" class="btn btn-primary">Entrar →</button>
</form></body></html>
<?php exit; }

// Acciones
$pdo = db();
$msg = '';
$action = $_POST['action'] ?? '';
try {
  if ($action==='player_save') {
    if (!empty($_POST['id'])) {
      $pdo->prepare("UPDATE jugadores SET dorsal=?,nombre=?,posicion=?,lenguaje=?,skill=?,capitan=?,activo=? WHERE id=?")
        ->execute([$_POST['dorsal'],$_POST['nombre'],$_POST['posicion'],$_POST['lenguaje'],$_POST['skill'],!empty($_POST['capitan'])?1:0,!empty($_POST['activo'])?1:0,$_POST['id']]);
      $msg='Jugador actualizado';
    } else {
      $pdo->prepare("INSERT INTO jugadores (dorsal,nombre,posicion,lenguaje,skill,capitan,activo) VALUES (?,?,?,?,?,?,1)")
        ->execute([$_POST['dorsal'],$_POST['nombre'],$_POST['posicion'],$_POST['lenguaje'],$_POST['skill'],!empty($_POST['capitan'])?1:0]);
      $msg='Jugador añadido';
    }
  } elseif ($action==='player_del') {
    $pdo->prepare("DELETE FROM jugadores WHERE id=?")->execute([$_POST['id']]); $msg='Jugador eliminado';
  } elseif ($action==='match_save') {
    if (!empty($_POST['id'])) {
      $pdo->prepare("UPDATE partidos SET fecha=?,hora=?,local=?,visitante=?,goles_local=?,goles_visitante=?,condicion=?,jugado=? WHERE id=?")
        ->execute([$_POST['fecha'],$_POST['hora'],$_POST['local'],$_POST['visitante'],$_POST['gl']!==''?$_POST['gl']:null,$_POST['gv']!==''?$_POST['gv']:null,$_POST['condicion'],!empty($_POST['jugado'])?1:0,$_POST['id']]);
      $msg='Partido actualizado';
    } else {
      $pdo->prepare("INSERT INTO partidos (fecha,hora,local,visitante,goles_local,goles_visitante,condicion,jugado) VALUES (?,?,?,?,?,?,?,?)")
        ->execute([$_POST['fecha'],$_POST['hora'],$_POST['local'],$_POST['visitante'],$_POST['gl']!==''?$_POST['gl']:null,$_POST['gv']!==''?$_POST['gv']:null,$_POST['condicion'],!empty($_POST['jugado'])?1:0]);
      $msg='Partido añadido';
    }
  } elseif ($action==='match_del') {
    $pdo->prepare("DELETE FROM partidos WHERE id=?")->execute([$_POST['id']]); $msg='Partido eliminado';
  } elseif ($action==='msg_del') {
    $pdo->prepare("DELETE FROM mensajes WHERE id=?")->execute([$_POST['id']]); $msg='Mensaje eliminado';
  } elseif ($action==='msg_read') {
    $pdo->prepare("UPDATE mensajes SET leido=1 WHERE id=?")->execute([$_POST['id']]);
  }
} catch (Throwable $e) { $msg = 'Error: '.$e->getMessage(); }

$tab = $_GET['tab'] ?? 'jugadores';
$jugadores = $pdo->query("SELECT * FROM jugadores ORDER BY dorsal")->fetchAll();
$partidos  = $pdo->query("SELECT * FROM partidos ORDER BY fecha DESC")->fetchAll();
$mensajes  = $pdo->query("SELECT * FROM mensajes ORDER BY created_at DESC")->fetchAll();
$nuevos    = $pdo->query("SELECT COUNT(*) FROM mensajes WHERE leido=0")->fetchColumn();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Admin · Piemonte Trasco</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
  body{background:var(--void);color:var(--paper);padding:2rem;font-family:'Space Grotesk',sans-serif}
  .adm-nav{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;border-bottom:1px solid #1f2a36;padding-bottom:1rem}
  .adm-nav a{padding:.5rem 1rem;background:var(--carbon);color:var(--paper);text-decoration:none;border-radius:6px;font-family:'JetBrains Mono',monospace;font-size:.85rem}
  .adm-nav a.active{background:var(--terminal);color:var(--void)}
  .adm-table{width:100%;border-collapse:collapse;margin-top:1rem;font-size:.9rem}
  .adm-table th,.adm-table td{padding:.5rem;border-bottom:1px solid #1f2a36;text-align:left;vertical-align:top}
  .adm-table th{background:var(--carbon);color:var(--terminal);font-family:'JetBrains Mono',monospace}
  .adm-form{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.5rem;padding:1rem;background:var(--carbon);border-radius:8px;margin-bottom:1rem}
  .adm-form input,.adm-form select,.adm-form textarea{padding:.5rem;background:var(--void);color:var(--paper);border:1px solid #2a3a4a;border-radius:4px;font-family:inherit}
  .adm-form button{grid-column:1/-1}
  .badge{display:inline-block;padding:.15rem .5rem;border-radius:99px;font-size:.75rem;background:var(--terminal);color:var(--void);font-weight:700}
  .danger{background:#e63946;color:#fff;border:none;padding:.25rem .6rem;border-radius:4px;cursor:pointer;font-size:.8rem}
  h1{font-family:'Bebas Neue';color:var(--terminal);font-size:2.5rem;letter-spacing:.05em}
  .alert{padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;background:var(--terminal);color:var(--void);font-family:'JetBrains Mono',monospace}
</style></head>
<body>
<h1>// CD Piemonte Trasco — Admin</h1>
<?php if($msg): ?><div class="alert">✓ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<nav class="adm-nav">
  <a href="?tab=jugadores" class="<?= $tab==='jugadores'?'active':'' ?>">Jugadores (<?= count($jugadores) ?>)</a>
  <a href="?tab=partidos"  class="<?= $tab==='partidos'?'active':'' ?>">Partidos (<?= count($partidos) ?>)</a>
  <a href="?tab=mensajes"  class="<?= $tab==='mensajes'?'active':'' ?>">Mensajes (<?= count($mensajes) ?><?= $nuevos?" · <span class='badge'>$nuevos nuevos</span>":'' ?>)</a>
  <a href="../" target="_blank">Ver web ↗</a>
  <a href="?logout=1" style="margin-left:auto;background:#2a1a1a;">Cerrar sesión</a>
</nav>

<?php if($tab==='jugadores'): ?>
  <h2>Añadir / editar jugador</h2>
  <form method="post" class="adm-form">
    <input type="hidden" name="action" value="player_save">
    <input type="hidden" name="id" id="pid">
    <input name="dorsal" id="pdorsal" type="number" placeholder="Dorsal" required>
    <input name="nombre" id="pnombre" placeholder="Nombre" required>
    <input name="posicion" id="pposicion" placeholder="Posición" required>
    <input name="lenguaje" id="planguaje" placeholder="Lenguaje fav.">
    <input name="skill" id="pskill" placeholder="Skill / frase friki">
    <label><input type="checkbox" name="capitan" id="pcapitan" value="1"> Capitán</label>
    <label><input type="checkbox" name="activo" id="pactivo" value="1" checked> Activo</label>
    <button class="btn btn-primary">Guardar jugador</button>
  </form>
  <table class="adm-table">
    <tr><th>#</th><th>Nombre</th><th>Pos</th><th>Lang</th><th>Skill</th><th>C</th><th>Act</th><th></th></tr>
    <?php foreach($jugadores as $j): ?>
    <tr>
      <td><?= $j['dorsal'] ?></td><td><?= htmlspecialchars($j['nombre']) ?></td>
      <td><?= htmlspecialchars($j['posicion']) ?></td><td><?= htmlspecialchars($j['lenguaje']) ?></td>
      <td><?= htmlspecialchars($j['skill']) ?></td>
      <td><?= $j['capitan']?'★':'' ?></td><td><?= $j['activo']?'✓':'✗' ?></td>
      <td>
        <button type="button" onclick='loadPlayer(<?= json_encode($j) ?>)' class="danger" style="background:#3a4a5a">Editar</button>
        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
          <input type="hidden" name="action" value="player_del"><input type="hidden" name="id" value="<?= $j['id'] ?>">
          <button class="danger">×</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <script>
  function loadPlayer(j){
    pid.value=j.id; pdorsal.value=j.dorsal; pnombre.value=j.nombre;
    pposicion.value=j.posicion; planguaje.value=j.lenguaje||''; pskill.value=j.skill||'';
    pcapitan.checked=j.capitan==1; pactivo.checked=j.activo==1;
    window.scrollTo(0,0);
  }
  </script>

<?php elseif($tab==='partidos'): ?>
  <h2>Añadir / editar partido</h2>
  <form method="post" class="adm-form">
    <input type="hidden" name="action" value="match_save"><input type="hidden" name="id" id="mid">
    <input name="fecha" id="mfecha" type="date" required>
    <input name="hora"  id="mhora"  type="time">
    <input name="local" id="mlocal" placeholder="Local" required>
    <input name="visitante" id="mvisit" placeholder="Visitante" required>
    <input name="gl" id="mgl" type="number" placeholder="Goles L">
    <input name="gv" id="mgv" type="number" placeholder="Goles V">
    <select name="condicion" id="mcond"><option value="casa">Casa</option><option value="fuera">Fuera</option></select>
    <label><input type="checkbox" name="jugado" id="mjugado" value="1"> Jugado</label>
    <button class="btn btn-primary">Guardar partido</button>
  </form>
  <table class="adm-table">
    <tr><th>Fecha</th><th>Partido</th><th>Resultado</th><th></th></tr>
    <?php foreach($partidos as $p): ?>
    <tr>
      <td><?= $p['fecha'] ?> <?= substr($p['hora']??'',0,5) ?></td>
      <td><?= htmlspecialchars($p['local']) ?> vs <?= htmlspecialchars($p['visitante']) ?></td>
      <td><?= $p['jugado'] ? ($p['goles_local'].' - '.$p['goles_visitante']) : '<em>pendiente</em>' ?></td>
      <td>
        <button type="button" onclick='loadMatch(<?= json_encode($p) ?>)' class="danger" style="background:#3a4a5a">Editar</button>
        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar?')">
          <input type="hidden" name="action" value="match_del"><input type="hidden" name="id" value="<?= $p['id'] ?>">
          <button class="danger">×</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <script>
  function loadMatch(p){
    mid.value=p.id; mfecha.value=p.fecha; mhora.value=p.hora||'';
    mlocal.value=p.local; mvisit.value=p.visitante;
    mgl.value=p.goles_local??''; mgv.value=p.goles_visitante??'';
    mcond.value=p.condicion; mjugado.checked=p.jugado==1;
    window.scrollTo(0,0);
  }
  </script>

<?php else: ?>
  <h2>Mensajes recibidos</h2>
  <table class="adm-table">
    <tr><th>Fecha</th><th>Nombre</th><th>Email</th><th>Mensaje</th><th></th></tr>
    <?php foreach($mensajes as $m): ?>
    <tr style="<?= $m['leido']?'opacity:.55':'' ?>">
      <td><?= $m['created_at'] ?></td>
      <td><?= htmlspecialchars($m['nombre']) ?></td>
      <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
      <td style="max-width:400px"><?= nl2br(htmlspecialchars($m['mensaje'])) ?></td>
      <td>
        <?php if(!$m['leido']): ?>
        <form method="post" style="display:inline"><input type="hidden" name="action" value="msg_read"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="danger" style="background:#3a4a5a">✓ Leído</button></form>
        <?php endif; ?>
        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar?')"><input type="hidden" name="action" value="msg_del"><input type="hidden" name="id" value="<?= $m['id'] ?>"><button class="danger">×</button></form>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(!$mensajes): ?><tr><td colspan="5" style="text-align:center;padding:2rem;opacity:.6">Sin mensajes todavía.</td></tr><?php endif; ?>
  </table>
<?php endif; ?>
</body></html>
