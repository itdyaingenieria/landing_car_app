## Descripción del Proyecto - LandingCarApp

Este proyecto es una aplicación web desarrollada con Laravel 12 y Vue.js que implementa un sistema para gestionar un concurso promocional de Volkswagen. La aplicación cuenta con un landing page público donde los usuarios pueden registrarse para participar en el concurso, y un panel de administración protegido donde se pueden visualizar todos los participantes, exportar datos y seleccionar ganadores.

## Características

-   **Landing page público**:

    -   Formulario de registro para participantes
    -   Validación de datos en tiempo real
    -   Política de privacidad con aceptación de habeas data

-   **Panel de administración**:
    -   Estadísticas en tiempo real (total de participantes, registros del día)
    -   Listado de participantes con filtrado por búsqueda
    -   Exportación de datos a Excel
    -   Sistema de selección de ganadores aleatorios

## Requisitos del Sistema

-   PHP 8.1 o superior
-   Composer
-   Node.js y NPM
-   MySQL 5.7 o superior

## 🚀 Tecnologías Utilizadas

-   Laravel 12 - Framework backend para gestionar autenticación y lógica de negocio.
-   Vue.js 3 - Framework frontend para interacciones dinámicas con el usuario.
-   Inertia.js - Framework que conecta(enlace) Laravel y Vue.js, permitiendo crear SPA sin necesidad de desarrollar una API separada.
-   Breeze - Esqueleto de autenticación simple para Laravel y Vue.
-   MySQL - Base de datos relacional para almacenar empleados y registros de acceso.
-   Arquitectura Monolítica - Estructura centralizada para facilitar el despliegue.
-   Vite - Herramienta moderna de frontend para desarrollo rápido y recarga en vivo.

## 🛠 Pasos de Instalación

1. **Clonar el repositorio**:

    ```bash
    git clone https://github.com/tu-usuario/landing-car-app.git
    cd landing-car-app
    ```

2. **Instalar dependencias PHP**:

    ```bash
    composer install
    ```

3. **Instalar dependencias JavaScript**:

    ```bash
    npm install
    ```

4. **Configurar el entorno**:

    ```bash
    cp .env.example .env
    ```

    Edita el archivo .env con tus configuraciones de base de datos y aplicación.

5. **Generar clave de aplicación**:

    ```bash
    php artisan key:generate
    ```

6. **Ejecutar migraciones**:

    ```bash
    php artisan migrate
    ```

    Esto también creará un usuario administrador inicial con las credenciales:

    - Email: admin@gmail.com
    - Contraseña: pass2025

7. **Compilar assets**:
   Necesario por usar vite

    ```bash
    npm run dev
    ```

    Para producción:

    ```bash
    npm run build
    ```

8. **Iniciar el servidor**:
    ```bash
    php artisan serve
    ```
    La aplicación estará disponible en http://localhost:8000

## Estructura de la Aplicación

-   Exports - Clases para exportación de datos a Excel
-   Api - Controladores API para la gestión de participantes
-   Models - Modelos de la aplicación (Participant, Winner, etc.)
-   Pages - Componentes Vue para las diferentes páginas
    -   Landing.vue - Página principal de registro público
    -   DashboardNew.vue - Panel de administración
-   migrations - Migraciones de la base de datos

## Rutas Principales

-   `/` - Landing page pública para registros
-   `/login` - Acceso al panel de administración
-   `/dashboard` - Panel de administración (requiere autenticación)

## Uso del Panel de Administración

1. Accede con las credenciales de administrador
2. En el panel podrás ver estadísticas en tiempo real
3. La lista de participantes se puede filtrar mediante el buscador
4. Para exportar los datos a Excel, haz clic en el botón "Exportar Participantes"
5. Para seleccionar un ganador aleatorio, haz clic en "Seleccionar Ganador"
   (Requiere al menos 5 participantes registrados)

## Customización

-   Los colores principales se basan en (#001E50)
-   Para modificar el estilo, puedes editar los archivos en css y tailwind.config.js
-   Las imágenes de la promoción se almacenan en img

## Seguridad

-   La aplicación implementa protección CSRF en todos los formularios
-   Las contraseñas se almacenan con hash utilizando el algoritmo Bcrypt
-   El panel de administración está protegido con autenticación de Laravel

## Contribuciones

Si deseas contribuir a este proyecto, por favor crea un fork del repositorio, realiza tus cambios y envía un Pull Request.

## Licencia

Este proyecto es una prueba de concepto y no está disponible para uso comercial sin autorización explícita. Todos los derechos de marca y logos pertenecen a Volkswagen.

## Contacto

Para más información, contacta al desarrollador a través de [diegoyamaa@gmail.com].

## 🚀 Futuras mejoras

Para futuros desarrollos y aprendizaje, se consideran las siguientes mejoras:

-   **Internacionalización**: Añadir soporte multiidioma para ampliar el alcance de la aplicación a mercados internacionales.
-   **PWA (Progressive Web App)**: Implementar características de PWA para permitir la instalación en dispositivos móviles y funcionamiento offline.
-   **Tests automatizados**: Aumentar la cobertura con tests unitarios y de integración para garantizar la estabilidad del código.
-   **Análisis y métricas**: Integrar herramientas como Google Analytics o Matomo para tracking de usuarios y comportamiento.
-   **Accesibilidad**: Mejorar la accesibilidad siguiendo las pautas WCAG para usuarios con discapacidades.
-   **Notificaciones push**: Implementar notificaciones en tiempo real para avisar a los participantes sobre su estado en el concurso.
-   **Dockerización**: Crear configuración Docker para facilitar el despliegue en diferentes entornos.
-   **CI/CD**: Implementar pipeline de integración continua para automatizar pruebas y despliegue.
-   **Dashboard mejorado**: Añadir más gráficos y analíticas para una mejor visualización de datos.
-   **Autenticación OAuth**: Permitir inicio de sesión con cuentas de redes sociales o Google.

Estas mejoras forman parte del plan de desarrollo continuo y aprendizaje, buscando implementar las mejores prácticas de la industria y tecnologías emergentes.

---

**Nota**: Esta aplicación es una prueba de concepto(POC) y no está destinada para uso en producción sin revisiones adicionales de seguridad y optimización.
