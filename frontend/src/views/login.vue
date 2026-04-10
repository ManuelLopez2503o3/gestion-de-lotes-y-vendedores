 <template>
  <div class="hero" :style="{ backgroundImage: `url(${fondo})` }">
    <div class="overlay">
      
      <div class="content fade-in">
        <h1 class="title">Bienvenido</h1>
        <p class="subtitle">Gestión de Lotes y Vendedores</p>

        <button v-if="!mostrarLogin" @click="mostrarLogin = true" class="btn-main">
          Acceder al Sistema
        </button>

        <div v-if="mostrarLogin" class="formulario-container">
          <h2 class="form-title">Acceso Administrativo</h2>
          
          <div class="input-group">
            <input v-model="email" type="email" placeholder="Correo electrónico" />
            <input v-model="password" type="password" placeholder="Contraseña" @keyup.enter="login" />
          </div>

          <div class="actions">
            <button @click="login" class="btn-login">Entrar</button>
            <button @click="mostrarLogin = false" class="btn-cancel">Cancelar</button>
          </div>

          <div class="footer-form">
            <p>¿No tienes cuenta? <span @click="irRegister" style="cursor: pointer; text-decoration: underline;">Regístrate aquí</span></p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../services/api';
import Swal from 'sweetalert2'; // <-- Importamos SweetAlert2
import fondo from './assets/prueba.jpg'; 

const email = ref('');
const password = ref('');
const mostrarLogin = ref(false);
const router = useRouter();

const login = async () => {
  // Validación de campos vacíos con modal
  if (!email.value || !password.value) {
    return Swal.fire({
      title: 'Campos incompletos',
      text: 'Por favor, ingresa tu correo y contraseña.',
      icon: 'warning',
      confirmButtonColor: '#f39c12'
    });
  }

  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value
    });
    
    localStorage.setItem('token', res.data.token);
    
    // Modal de éxito que desaparece rápido
    await Swal.fire({
      title: '¡Bienvenido!',
      text: 'Iniciando sesión...',
      icon: 'success',
      timer: 1500, // Desaparece en 1.5 segundos
      showConfirmButton: false
    });

    router.push('/lotes');

  } catch (error) {
    console.error(error.response?.data);
    
    // Si la contraseña o el correo están mal (Error 401 de Laravel)
    if (error.response && error.response.status === 401) {
      Swal.fire({
        title: 'Acceso Denegado',
        text: 'El correo o la contraseña son incorrectos.',
        icon: 'error',
        confirmButtonColor: '#d33'
      });
    } 
    // Si Laravel detecta que el email no tiene formato válido (Error 422)
    else if (error.response && error.response.status === 422) {
      Swal.fire({
        title: 'Datos inválidos',
        text: 'Verifica el formato del correo.',
        icon: 'warning',
        confirmButtonColor: '#f39c12'
      });
    } else {
      Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
    }
  }
};

const irRegister = () => {
  router.push('/register');
};
</script>
<style scoped>
 
.hero {
  height: 100vh;
  width: 100%;
  background-size: cover;
  background-position: center;
  font-family: 'Inter', sans-serif;
}

 
.overlay {
  height: 100%;
  width: 100%;
  background: linear-gradient(135deg, rgba(30, 64, 175, 0.4) 0%, rgba(0, 0, 0, 0.7) 100%);
  display: flex;
  justify-content: center;
  align-items: center;
}

 
.content {
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 28px;
  padding: 45px;
  max-width: 420px;
  width: 90%;
  text-align: center;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  color: white;
}

.title { font-size: 3.2rem; font-weight: 800; margin: 0; }
.subtitle { font-size: 1.1rem; color: #d1d5db; margin-bottom: 25px; }

input {
  width: 100%;
  padding: 14px 18px;
  margin-bottom: 12px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.3);
  background: rgba(255, 255, 255, 0.95);
  color: #1e293b;
  box-sizing: border-box;
  outline: none;
}

.actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

button {
  padding: 14px;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  border: none;
  transition: 0.3s;
}

.btn-main, .btn-login { background: #2563eb; color: white; }
.btn-cancel { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.4); }

.footer-form { margin-top: 20px; }
.footer-form span { color: #60a5fa; cursor: pointer; text-decoration: underline; font-weight: bold; }

.fade-in { animation: slideUp 0.6s ease-out; }
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>