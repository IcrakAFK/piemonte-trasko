# CD Piemonte Trasco — Web Oficial (PHP + MySQL)

Sitio web del equipo de fútbol aficionado de la **ESEI (Campus de Ourense, UVigo)** con base de datos MySQL y panel de administración.

## 📦 Estructura

```
piemonte/
├── index.php           Home (hero + patrocinadores)
├── club.php            Historia del club
├── plantilla.php       Plantilla (lee de BBDD)
├── calendario.php      Calendario + Clasificación (lee de BBDD)
├── contacto.php        Formulario (guarda en BBDD)
├── includes/
│   ├── config.php      ⚙ Credenciales BBDD + admin
│   ├── db.php          Conexión PDO
│   ├── header.php
│   └── footer.php
├── db/
│   ├── schema.sql      Esquema completo + datos iniciales
│   └── install.php     ⚡ Instalador web (1 clic)
├── admin/
│   └── index.php       🔐 Panel de administración
└── assets/             css/ js/ img/
```

## 🚀 Instalación rápida (3 pasos)

### 1. Sube los archivos
Sube toda la carpeta `piemonte/` a tu hosting (cPanel, Plesk, FTP…). El hosting debe tener **PHP 7.4+** y **MySQL 5.7+** (o MariaDB).

### 2. Crea la BBDD y configura credenciales
- En tu panel del hosting, crea una base de datos vacía y un usuario MySQL.
- Edita `includes/config.php` con los datos reales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tu_basedatos');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'pon_una_clave_fuerte'); // ⚠ CÁMBIALA
```

### 3. Ejecuta el instalador
Visita en el navegador:
```
https://tu-dominio.com/db/install.php
```
Esto crea automáticamente las tablas (`jugadores`, `partidos`, `clasificacion`, `patrocinadores`, `mensajes`) y carga los datos iniciales.

> ⚠️ **IMPORTANTE:** elimina el archivo `db/install.php` después de la instalación.

## 🔐 Panel de administración

Accede a `https://tu-dominio.com/admin/` con las credenciales que pusiste en `config.php`. Desde ahí puedes:

- ➕ Añadir / editar / eliminar **jugadores** (dorsal, posición, lenguaje, skill, capitán).
- 🗓 Añadir / editar / eliminar **partidos** y resultados.
- 📨 Leer y gestionar **mensajes** del formulario de contacto.

## 🗄 Esquema de la BBDD

| Tabla | Para qué sirve |
|---|---|
| `jugadores` | Plantilla con dorsal, posición, lenguaje favorito, skill friki, capitán |
| `partidos` | Calendario y resultados (fecha, hora, local/visitante, goles) |
| `clasificacion` | Tabla de la liga (PJ, PG, PE, PP, GF, GC, puntos) |
| `patrocinadores` | Queen, Júpiter, Cristina KDK… ordenables |
| `mensajes` | Bandeja del formulario de contacto |

Puedes ver/editar el SQL completo en `db/schema.sql`.

## 🧪 Probar en local

```bash
# Inicia MySQL local (XAMPP, MAMP, Docker…)
# Importa el schema:
mysql -u root -p < db/schema.sql

# Lanza PHP:
php -S localhost:8000
```

Abre http://localhost:8000

## 🎨 Identidad visual

- **Paleta:** `#0B3D2E` (verde césped) · `#00FF9C` (terminal) · `#00E5FF` (cyan) · `#0A0E13` (void) · `#FFC857` (capitán)
- **Tipografías:** Bebas Neue · Space Grotesk · JetBrains Mono
- **Lema:** *"Un trasco no meu peito · A trasco in my chest"*

---
Hecho con ⚽ + 💻 en la ESEI, Campus de Ourense.
