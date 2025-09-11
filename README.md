# 📚 PAI - Plataforma Académica Integral

Sistema de gestión educativa desarrollado en Laravel para instituciones educativas, que permite gestionar estudiantes, profesores, materias, calificaciones, actividades académicas y recursos bibliotecarios.

## 🚀 Características Principales

### 👥 Gestión de Usuarios
- **Estudiantes**: Registro, inscripciones, seguimiento académico
- **Profesores**: Asignaciones de materias, gestión de actividades y calificaciones
- **Padres de Familia**: Seguimiento del progreso académico de sus hijos
- **Administradores**: Gestión completa del sistema

### 📖 Gestión Académica
- **Planes Académicos**: Configuración de currículo por grados
- **Materias**: Gestión de asignaturas con contenido curricular
- **Períodos Académicos**: Organización temporal del año escolar
- **Actividades**: Tareas, exámenes y proyectos con sistema de calificaciones
- **Calificaciones**: Seguimiento detallado del rendimiento estudiantil

### 🏫 Gestión Institucional
- **Grupos**: Organización de estudiantes por salones
- **Inscripciones**: Proceso de matrícula estudiantil
- **Asignaciones**: Vinculación de profesores con materias y grupos

### 💬 Comunicación
- **Foro Académico**: Espacio de discusión entre la comunidad educativa
- **Notificaciones**: Sistema de alertas y comunicados

### 📚 Biblioteca Virtual
- **Recursos**: Gestión de libros y materiales educativos
- **Préstamos**: Sistema de solicitud y seguimiento de préstamos
- **Inventario**: Control de disponibilidad de recursos

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Livewire + Flux UI
- **Base de Datos**: PostgreSQL
- **CSS Framework**: Tailwind CSS
- **Testing**: Pest PHP
- **Build Tool**: Vite

## 📋 Requisitos del Sistema

- PHP >= 8.2
- Composer
- Node.js >= 18
- PostgreSQL >= 13
- Extensiones PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🔧 Instalación

### 1. Clonar el Repositorio
```bash
git clone <url-del-repositorio>
cd pai
```

### 2. Instalar Dependencias
```bash
# Dependencias de PHP
composer install

# Dependencias de Node.js
npm install
```

### 3. Configuración del Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos
Editar el archivo `.env` con las credenciales de PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pai
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 5. Ejecutar Migraciones y Seeders
```bash
# Ejecutar migraciones
php artisan migrate

# Cargar datos de prueba
php artisan db:seed
```

### 6. Compilar Assets
```bash
# Para desarrollo
npm run dev

# Para producción
npm run build
```

### 7. Iniciar Servidor
```bash
php artisan serve
```

La aplicación estará disponible en `http://localhost:8000`

## 👤 Usuarios de Prueba

Después de ejecutar los seeders, podrás acceder con los siguientes usuarios:

### Administrador
- **Email**: admin@escuela.edu
- **Contraseña**: password

### Profesor
- **Email**: maria.gonzalez@escuela.edu
- **Contraseña**: password

### Estudiante
- **Email**: juan.perez@estudiante.edu
- **Contraseña**: password

### Padre de Familia
- **Email**: roberto.perez@padre.edu
- **Contraseña**: password

## 🎯 Acceso Rápido (Solo Desarrollo)

Para facilitar las pruebas, puedes usar las rutas de acceso rápido:
- `/quick-login/admin` - Ingresar como administrador
- `/quick-login/teacher` - Ingresar como profesor
- `/quick-login/student` - Ingresar como estudiante
- `/quick-login/parent` - Ingresar como padre

> ⚠️ **Importante**: Estas rutas deben ser removidas en producción.

## 📁 Estructura del Proyecto

```
pai/
├── app/
│   ├── Http/Controllers/    # Controladores
│   ├── Models/             # Modelos Eloquent
│   ├── Livewire/           # Componentes Livewire
│   └── Policies/           # Políticas de autorización
├── database/
│   ├── migrations/         # Migraciones de base de datos
│   ├── seeders/           # Seeders de datos
│   └── factories/         # Factories para testing
├── resources/
│   ├── views/             # Plantillas Blade
│   ├── js/                # JavaScript
│   └── css/               # Estilos CSS
└── routes/
    ├── web.php            # Rutas web
    └── auth.php           # Rutas de autenticación
```

## 🔐 Roles y Permisos

### Administrador
- Gestión completa de usuarios
- Configuración de planes académicos
- Gestión de períodos y materias
- Acceso a reportes y estadísticas

### Profesor
- Gestión de actividades y calificaciones
- Acceso a grupos asignados
- Comunicación con estudiantes y padres

### Estudiante
- Consulta de calificaciones
- Acceso a actividades asignadas
- Participación en foros académicos
- Solicitud de préstamos bibliotecarios

### Padre de Familia
- Seguimiento académico de hijos
- Comunicación con profesores
- Consulta de actividades y calificaciones

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=nombre_del_test
```

## 📊 Funcionalidades por Módulo

### Dashboard
- Estadísticas generales del sistema
- Actividades recientes
- Publicaciones del foro
- Grupos con baja inscripción

### Gestión de Usuarios
- CRUD completo de usuarios
- Asignación de roles
- Gestión de relaciones padre-hijo

### Gestión Académica
- Configuración de planes curriculares
- Gestión de materias y períodos
- Sistema de calificaciones por períodos

### Actividades
- Creación y gestión de actividades
- Sistema de calificaciones
- Seguimiento de entregas

### Foro Académico
- Categorías de discusión
- Sistema de moderación
- Publicaciones y comentarios

### Biblioteca
- Gestión de recursos
- Sistema de préstamos
- Control de inventario

## 🚀 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generar componentes Livewire
php artisan make:livewire ComponentName

# Ejecutar migraciones específicas
php artisan migrate --path=database/migrations/archivo_especifico.php

# Rollback de migraciones
php artisan migrate:rollback

# Refrescar base de datos con seeders
php artisan migrate:fresh --seed
```

## 🛡️ Seguridad

- Autenticación con Laravel Breeze
- Protección CSRF en formularios
- Validación de datos en servidor
- Políticas de autorización por rol
- Encriptación de contraseñas

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agrega nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🐛 Reporte de Bugs

Si encuentras algún bug o tienes una sugerencia, por favor abre un issue en el repositorio.

## 📞 Soporte

Para soporte técnico o consultas sobre el proyecto, contacta al equipo de desarrollo.

---

⭐ Si este proyecto te ha sido útil, no olvides darle una estrella en GitHub.
