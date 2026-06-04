<?php
$page='contacto';
$title='Contacto — CD Piemonte Trasco';
require_once 'includes/db.php';
include 'includes/header.php';

$enviado = false; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre']  ?? '');
    $email   = trim($_POST['email']   ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre === '' || $email === '' || $mensaje === '') {
        $error = 'Faltan campos obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido.';
    } else {
        try {
            $stmt = db()->prepare(
              "INSERT INTO mensajes (nombre, email, mensaje, ip) VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$nombre, $email, $mensaje, $_SERVER['REMOTE_ADDR'] ?? null]);
            $enviado = true;
            $_POST = []; // limpiar formulario
        } catch (PDOException $e) {
            $error = 'No se pudo guardar el mensaje: ' . $e->getMessage();
        }
    }
}
?>

<section class="block">
  <div class="container">
    <div class="section-head reveal">
      <span class="section-tag"></span>
      <h2>Contáctanos</h2>
      <p>¿Quieres unirte, patrocinarnos o simplemente saludar? Escríbenos.</p>
    </div>

    <form class="form reveal" method="post" novalidate>
      <?php if ($enviado): ?>
        <div class="alert ok">✓ Mensaje guardado en la BBDD. Te respondemos lo antes posible.</div>
      <?php elseif ($error): ?>
        <div class="alert err">✗ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
      <br>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <br>
      <label for="mensaje">Mensaje</label>
      <textarea id="mensaje" name="mensaje" required><?= htmlspecialchars($_POST['mensaje'] ?? '') ?></textarea>
      <br>
      <button type="submit" class="btn btn-primary">Enviar mensaje →</button>
    </form>

    <div class="grid grid-3 reveal" style="margin-top:3rem;">
      <div class="card">
        <div class="card-icon">@</div>
        <h3>Instagram</h3>
        <p><a href="https://instagram.com/piemonte_trasko" target="_blank" rel="noopener">@piemonte_trasko</a></p>
      </div>
      <div class="card">
        <div class="card-icon">📍</div>
        <h3>Origen</h3>
        <p>ESEI — Campus de Ourense, UVigo.</p>
      </div>
      <div class="card">
        <div class="card-icon">★</div>
        <h3>Patrocinadores</h3>
        <p>
          <?php
            $pats = db()->query("SELECT nombre FROM patrocinadores WHERE activo=1 ORDER BY orden")->fetchAll();
            echo htmlspecialchars(implode(' · ', array_column($pats, 'nombre')));
          ?>
        </p>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
