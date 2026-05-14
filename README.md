# Sistema de Gestión de Cine

Proyecto de Tecnologías Web — Universidad de Granada (UGR).

**Autores:** Carlos Manuel Pérez Molina, Mario Muñoz Gutiérrez, Luis Pérez Velasco
**Curso:** 2025-2026
**Grupo / Subgrupo:** A3 / 1

---

## Tecnologías

- Laravel 12 (PHP 8.2)
- MySQL 8
- CSS propio (sin frameworks)
- JavaScript vanilla

---

## 1. Software que hay que instalar previamente

Antes de descargar el proyecto, el sistema debe tener instalado lo siguiente.

### a) XAMPP (Apache + MySQL + PHP)

Paquete que instala de una sola vez el servidor web, la base de datos y PHP.

1. Descargar de [apachefriends.org](https://www.apachefriends.org/es/download.html) la versión para **Windows con PHP 8.2 o superior**.
2. Ejecutar el instalador con permisos de administrador. Aceptar todas las opciones por defecto. Dejar la ruta de instalación en `C:\xampp`.
3. Tras instalar, abrir el **Panel de Control de XAMPP** y pulsar **Start** en las filas de **Apache** y **MySQL**. Ambos indicadores deben quedar en verde.

### b) Composer

Gestor de dependencias de PHP. Sin él, Laravel no arranca.

1. Descargar **Composer-Setup.exe** de [getcomposer.org/download](https://getcomposer.org/download/).
2. Ejecutar el instalador. Cuando pregunte por la ruta a PHP, indicar `C:\xampp\php\php.exe` (suele autodetectarse). Aceptar el resto por defecto.
3. Comprobar abriendo una nueva ventana de PowerShell:
   ```bash
   composer --version
   ```
   Debe devolver `Composer version 2.x.x`.

### c) Git

Para clonar el repositorio.

1. Descargar de [git-scm.com/download/win](https://git-scm.com/download/win) (la descarga se inicia automáticamente).
2. Ejecutar el instalador y aceptar todas las opciones por defecto.
3. Comprobar:
   ```bash
   git --version
   ```

---

## 2. Descargar el proyecto

Abrir PowerShell o CMD y colocarse en la carpeta donde se quiera guardar el proyecto:

```bash
cd C:\Users\TU_USUARIO\Documents
git clone https://github.com/Carlitros20/sistema-cine.git
cd sistema-cine
```

---

## 3. Instalar las dependencias de Laravel

Dentro de la carpeta del proyecto, ejecutar:

```bash
composer install
```

Esto descarga unas 100 librerías en la carpeta `vendor/`. Tarda 1-2 minutos.

Si aparece algún error sobre extensiones de PHP faltantes (`ext-mbstring`, `ext-fileinfo`, etc.), abrir `C:\xampp\php\php.ini` y descomentar las líneas correspondientes (quitar el `;` del principio).

---

## 4. Configurar el archivo de entorno

```bash
copy .env.example .env
php artisan key:generate
```

Abrir el archivo `.env` con cualquier editor y verificar que estos valores son correctos para una instalación estándar de XAMPP:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_cine
DB_USERNAME=root
DB_PASSWORD=
```

En XAMPP la contraseña de MySQL por defecto está **vacía**.

---

## 5. Crear la base de datos

Con MySQL en marcha desde el panel de XAMPP, abrir en el navegador `http://localhost/phpmyadmin`.

1. Pulsar **"Nueva"** en la columna izquierda.
2. Nombre: `sistema_cine`.
3. Cotejamiento: `utf8mb4_unicode_ci`.
4. Pulsar **"Crear"**.

---

## 6. Crear las tablas y cargar los datos de prueba

```bash
php artisan migrate --seed
```

Esto crea las 8 tablas y las puebla con datos realistas: 3 usuarios, 3 salas, 20 películas con sus pósters, sesiones para los próximos 7 días, valoraciones y entradas de ejemplo.

---

## 7. Arrancar el servidor

```bash
php artisan serve
```

Abrir el navegador en `http://localhost:8000`. Para detener el servidor, pulsar `Ctrl + C` en la terminal.

---

## Usuarios de prueba

| Rol      | Email              | Contraseña |
|----------|--------------------|------------|
| Admin    | admin@cine.com     | admin123   |
| Cliente  | cliente@cine.com   | user1234   |
| Cliente  | maestro@cine.com   | maestro123 |

---

## Solución de problemas frecuentes

### `SQLSTATE[HY000] [2002] No se puede establecer una conexión`
MySQL no está en marcha. Abrir el panel de XAMPP y pulsar Start en la fila de MySQL.

### `Class 'PDO' not found`
Falta habilitar la extensión PDO en PHP. Editar `C:\xampp\php\php.ini` y descomentar `extension=pdo_mysql` y `extension=mysqli`.

### La página muestra solo texto sin estilos
Verificar que la URL es `http://localhost:8000` (no `localhost` a secas) y que existe el archivo `public/css/cine.css`.
