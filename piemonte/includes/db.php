<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:monospace;padding:2rem;background:#0A0E13;color:#00FF9C;">
                <h2>⚠ Error de conexión a la BBDD</h2>
                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                <p>Ejecuta primero <code>/db/install.php</code> o revisa <code>includes/config.php</code>.</p>
            </div>');
        }
    }
    return $pdo;
}
