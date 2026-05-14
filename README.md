# Sistema de Gestión de Cine

Proyecto de Tecnologías Web — Universidad de Granada (UGR).

**Autores:** Carlos Manuel Pérez Molina, Mario Muñoz Gutiérrez, Luis Pérez Velasco
**Curso:** 2025-2026
**Grupo / Subgrupo:** A3 / 1

## Tecnologías

- Laravel 12
- MySQL
- CSS propio (sin frameworks)
- JavaScript vanilla

## Requisitos previos

- PHP 8.2 o superior
- Composer
- MySQL 8 / MariaDB
- (Recomendado) XAMPP

## Instalación

1. Clonar el repositorio:
```bash
   git clone https://github.com/Carlitros20/sistema-cine.git
   cd sistema-cine
```

2. Instalar dependencias:
```bash
   composer install
```

3. Configurar el entorno:
```bash
   cp .env.example .env
   php artisan key:generate
```

4. Editar `.env` y poner los datos de mi MySQL:
DB_DATABASE=sistema_cine
DB_USERNAME=root
DB_PASSWORD=

5. Crear la base de datos `sistema_cine` en MySQL (desde phpMyAdmin o con un cliente).

6. Ejecutar migraciones y seeders (datos de prueba):
```bash
   php artisan migrate --seed
```

7. Arrancar el servidor:
```bash
   php artisan serve
```

8. Abrir `http://localhost:8000`.

## Usuarios de prueba

| Rol      | Email              | Contraseña |
|----------|--------------------|------------|
| Admin    | admin@cine.com     | admin123   |
| Cliente  | cliente@cine.com   | user1234   |
| Cleinte  | maestro@cine.com   | maestro123 |
