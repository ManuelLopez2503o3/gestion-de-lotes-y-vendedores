# Gestion-de-Lotes-y-Vendedores
🚀 Gestión de Fuerza de Ventas (Lotes y Vendedores)

Sistema  desarrollado para la administración centralizada de Lotes (Puntos de Venta) y Vendedores, integrando consumo de API externa, arquitectura escalable y reglas de negocio.

📂 Estructura del Proyecto

El sistema está organizado bajo una arquitectura modular para facilitar mantenimiento y escalabilidad:

/backend   → API RESTful (Laravel 12)

/frontend  → SPA moderna (Vue.js )

/docs      → Documentación técnica (diagramas, pruebas, seguridad)

🛠️ Tecnologías y Arquitectura
🔧 Backend (Laravel 12)
Service Layer Pattern
Controladores limpios.
Manejo de API externa desacoplado.
Laravel Sanctum
Autenticación seguro.
Policies & Seguridad
Protección contra IDOR.
Restricción de acceso a recursos por propietario.
Respuestas HTTP correctas (403 Forbidden).
Integridad Referencial
Restricciones en base de datos:
❌ No se puede eliminar un lote con vendedores asignados.
Capa de Cache
Optimización de consultas externas.
Reducción de latencia y consumo de API.
Colas (Queues en modo sync)
Procesamiento eficiente sin necesidad de infraestructura adicional.
🎨 Frontend (Vue.js)
Consumo de API con Axios.
Separación clara de vistas y lógica.
UX Mejorada
Importación dinámica de vendedores.
Validaciones visuales.
Interacciones fluidas.
⚙️ Instalación y Configuración
1. Clonar el Repositorio
git clone https://github.com/ManuelLopez2503o3/gestion-de-lotes-y-vendedores.git
cd gestion-de-lotes-y-vendedores
2. Configuración del Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate

🔐 Nota de Seguridad:
El comando key:generate crea la APP_KEY, necesaria para cifrado de sesiones.

🗄️ Base de Datos

Configura tu .env y ejecuta:

php artisan migrate --seed

✔ Esto incluye:

Estructura de tablas
Datos iniciales
Usuario administrador

Credenciales de prueba:

Email: admin@test.com
Password: password
3. Configuración del Frontend
cd ../frontend
npm install
npm run dev

Accede en:

http://localhost:5173
🔗 Integración con API Externa

Fuente de datos:

https://jsonplaceholder.typicode.com/users
Procesamiento:
A través de VendedorService
Validación antes de guardar
Persistencia en base de datos local
📥 Importación de Vendedores
🔹 Comando Manual
php artisan import:vendedores
🔹 Importación Dinámica (Frontend)
El usuario puede:
Seleccionar cantidad de vendedores
Asignarlos automáticamente a un lote

✔ Mejora respecto a importaciones estáticas tradicionales.

🛡️ Seguridad y Validaciones
✔ Protección contra accesos no autorizados
✔ Validación de pertenencia de recursos
✔ Restricción de eliminación de lotes activos
✔ Regla de negocio:
❗ Todo vendedor debe estar asignado a un lote
⚡ Suite de Pruebas

Ejecuta:

php artisan test

Incluye:

Pruebas de rutas
Validación de seguridad
Integración básica
💡 Valor Agregado (Ingeniería)
🧩 Arquitectura Escalable
Separación clara de responsabilidades
Fácil extensión (microservicios futuro)
🚀 Rendimiento
Uso de cache
Optimización de consultas externas
🔒 Seguridad
Policies
Validaciones backend + DB
🎯 Experiencia de Usuario
UI moderna
Flujo intuitivo
Feedback visual claro
📑 Documentación Técnica

Ubicada en /docs:

📊 Diagrama de flujo
🧪 Reporte de testing
🔐 Protocolo de autorización
🏗️ Arquitectura del sistema
👨‍💻 Desarrollado por

Manuel Alejandro
Desarrollador Backend. 
