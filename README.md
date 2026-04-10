🚀 Sistema de Gestión de Lotes y Vendedores

Sistema web desarrollado para la administración centralizada de Lotes (Puntos de Venta) y Vendedores, implementando una arquitectura moderna con Laravel (Backend API REST) y Vue.js (Frontend).

⚙️ Instalación del Proyecto
📌 Requisitos previos
PHP >= 8.1
Composer
Node.js >= 18
NPM
MySQL
Git
🔧 Instalación Backend (Laravel)
1. Entrar a la carpeta backend
cd backend
2. Instalar dependencias
composer install
3. Configurar entorno
cp .env.example .env
4. Generar clave
php artisan key:generate
5. Configurar base de datos en .env
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
6. Configuración de API (Sanctum)
php artisan install:api
7. Migraciones y base de datos
php artisan migrate --seed
8. Levantar servidor
php artisan serve

👉 Backend disponible en:
http://127.0.0.1:8000

💻 Instalación Frontend (Vue)
1. Entrar a la carpeta frontend
cd frontend
2. Instalar dependencias
npm install
3. Ejecutar proyecto
npm run dev

👉 Frontend disponible en:
http://localhost:5173

🔗 Conexión Frontend - Backend

Verifica en:

frontend/src/services/api.js

Que la URL sea:

const API_URL = "http://127.0.0.1:8000/api";
📂 Estructura del Proyecto
Gestion-Lotes-Vendedores/
│
├── backend/   → API REST en Laravel
├── frontend/  → Aplicación en Vue
├── docs/      → Documentación
🧠 Descripción del Sistema

El sistema permite:

📦 Gestión de Lotes (CRUD)
👨‍💼 Gestión de Vendedores
🔐 Autenticación de usuarios
🌐 Consumo de API REST
⚡ Arquitectura desacoplada (Frontend + Backend)
🛠️ Tecnologías utilizadas
Laravel 12
Vue.js 3
MySQL
Axios
Vite
📌 Notas
Ejecutar primero el backend antes del frontend
Verificar que los puertos no estén en uso
Usar Postman para pruebas de API
👨‍💻 Autor

Manuel López
