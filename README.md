# Laravel SaaS - Sistema de Gestión Administrativa

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-FFA500?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)

Este es un proyecto base para una aplicación SaaS desarrollada con **Laravel 12** y **Filament v4**. La plataforma está diseñada para ofrecer una gestión administrativa eficiente, segura y escalable, con un enfoque en la experiencia del usuario y la robustez del código.

## 🚀 Características Principales

- **Gestión de Pedidos (Orders)**: Sistema completo para crear y gestionar pedidos, incluyendo una interfaz interactiva de selección de productos con cantidades y cálculos automáticos.
- **Catálogo de Productos**: Administración detallada de productos, precios y disponibilidad.
- **Panel Administrativo Premium**: Interfaz moderna y responsiva construida sobre Filament v4.
- **Control de Acceso (RBAC)**: Implementación de **Filament Shield** para gestionar roles y permisos de manera granular.
- **Internacionalización (i18n)**: Soporte completo para múltiples idiomas (Español e Inglés) con selector dinámico.
- **Arquitectura SaaS**: Estructura preparada para el crecimiento hacia un modelo de Software as a Service.
- **Pruebas de Calidad**: Suite de tests automatizados con **Pest PHP** para garantizar la integridad del sistema.

## 🛠️ Stack Tecnológico

- **Framework**: Laravel 12
- **Admin Panel**: Filament v4
- **Base de Datos**: PostgreSQL / MySQL / SQLite (Soportados)
- **Seguridad**: Filament Shield
- **Testing**: Pest PHP
- **Estilos**: Tailwind CSS & Blade Icons

## ⚙️ Instalación

Sigue estos pasos para configurar el proyecto localmente:

1. **Clonar el repositorio:**
   ```bash
   git clone <url-del-repositorio>
   cd laravel-saas
   ```

2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```

3. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de datos** en el archivo `.env` y ejecutar las migraciones:
   ```bash
   php artisan migrate --seed
   ```

5. **Instalar dependencias de Frontend:**
   ```bash
   npm install
   npm run dev
   ```

6. **Crear super administrador (opcional para Shield):**
   ```bash
   php artisan shield:install
   ```

## 🧪 Pruebas

Para ejecutar la suite de pruebas y asegurar que todo funciona correctamente:

```bash
php artisan test
```

o usando Pest directamente:

```bash
vendor/bin/pest
```

## 🌐 Soporte de Idiomas

El proyecto utiliza `bezhansalleh/filament-language-switch` para permitir a los usuarios cambiar entre:
- 🇪🇸 Español
- 🇺🇸 English

Las traducciones se encuentran en el directorio `lang/`.

---
Desarrollado con ❤️ para soluciones SaaS modernas.
