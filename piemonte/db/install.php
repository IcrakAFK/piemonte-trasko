<?php
// ============================================================
//  Instalador de la BBDD — CD Piemonte Trasco
//  Ejecuta este archivo UNA VEZ desde el navegador para crear
//  las tablas y cargar los datos iniciales.
//  URL: http://tu-dominio.com/db/install.php
//  ⚠ ELIMINA este archivo después de instalar.
// ============================================================
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: text/html; charset=utf-8');
echo '<pre style="background:#0A0E13;color:#00FF9C;font-family:JetBrains Mono,monospace;padding:2rem;min-height:100vh;margin:0;">';
echo "╔════════════════════════════════════════════════╗\n";
echo "║  CD PIEMONTE TRASCO — INSTALADOR DE BBDD       ║\n";
echo "╚════════════════════════════════════════════════╝\n\n";

try {
    // 1) Conectar SIN seleccionar BBDD para poder crearla
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "[✓] Conectado a MySQL en " . DB_HOST . "\n";

    // 2) Leer schema.sql y ejecutarlo
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    if ($sql === false) throw new Exception('No se pudo leer schema.sql');

    $pdo->exec($sql);
    echo "[✓] Base de datos `" . DB_NAME . "` creada\n";
    echo "[✓] Tablas creadas: jugadores, partidos, clasificacion, patrocinadores, mensajes\n";
    echo "[✓] Datos iniciales insertados\n\n";

    // 3) Comprobar
    $pdo->exec('USE `' . DB_NAME . '`');
    $count = $pdo->query('SELECT COUNT(*) FROM jugadores')->fetchColumn();
    echo "[✓] Jugadores en plantilla: $count\n";
    $count = $pdo->query('SELECT COUNT(*) FROM partidos')->fetchColumn();
    echo "[✓] Partidos cargados: $count\n\n";

    echo "════════════════════════════════════════════════\n";
    echo "  ✅ INSTALACIÓN COMPLETADA\n";
    echo "════════════════════════════════════════════════\n\n";
    echo "Siguiente paso:\n";
    echo "  1. ⚠ ELIMINA este archivo (/db/install.php)\n";
    echo "  2. Cambia ADMIN_PASS en includes/config.php\n";
    echo "  3. Visita /admin/ para gestionar el equipo\n";
    echo "  4. Visita / para ver la web\n";
} catch (Throwable $e) {
    echo "\n[✗] ERROR: " . htmlspecialchars($e->getMessage()) . "\n";
    echo "\nRevisa includes/config.php (DB_HOST, DB_USER, DB_PASS).\n";
}
echo '</pre>';
