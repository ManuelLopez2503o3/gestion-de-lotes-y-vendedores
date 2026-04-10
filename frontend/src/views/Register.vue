<template>
  <div class="hero" :style="{ backgroundImage: `url(${fondo})` }">
    <div class="overlay">
      
      <div class="content fade-in">
        <h1 class="title">Crear cuenta</h1>
        <p class="subtitle">Regístrate para gestionar tus lotes</p>

        <div class="formulario-container">
          <div class="input-group">
            <input v-model="name" type="text" placeholder="Nombre completo" />
            <input v-model="email" type="email" placeholder="Correo electrónico" />
            <input v-model="password" type="password" placeholder="Contraseña" />
            <input v-model="confirm" type="password" placeholder="Confirmar contraseña" @keyup.enter="register" />
          </div>

          <div class="actions">
            <button @click="register" class="btn-login">Registrarse</button>
            <button @click="irLogin" class="btn-cancel">Volver al inicio</button>
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

const name = ref('');
const email = ref('');
const password = ref('');
const confirm = ref('');
const router = useRouter();

const register = async () => {
  // Validación de campos vacíos
  if (!name.value || !email.value || !password.value || !confirm.value) {
    return Swal.fire({
        title: 'Campos vacíos',
        text: 'Por favor, llena todos los campos para continuar.',
        icon: 'warning',
        confirmButtonColor: '#f39c12'
    });
  }

  // Validación de contraseñas
  if (password.value !== confirm.value) {
    return Swal.fire({
        title: 'Error de Contraseña',
        text: 'Las contraseñas no coinciden. Verifícalas.',
        icon: 'error',
        confirmButtonColor: '#d33'
    });
  }

  try {
    const res = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: confirm.value
    });

    localStorage.setItem('token', res.data.token);
    
    await Swal.fire({
      title: '¡Cuenta creada!',
      text: 'Tu registro se completó con éxito.',
      icon: 'success',
      timer: 1500,
      showConfirmButton: false
    });

    router.push('/lotes');

  } catch (error) {
    console.log(error.response?.data);
    
    // Atrapamos errores de Laravel (ej. Correo ya registrado o contraseña muy corta)
    if (error.response && error.response.status === 422) {
      const errores = error.response.data.errors;
      let mensajeError = "";
      Object.values(errores).forEach(err => {
        mensajeError += `<li style="margin-bottom: 5px;">${err[0]}</li>`;
      });

      Swal.fire({
        title: 'Revisa tu información',
        html: `<ul style="text-align: left; color: #555;">${mensajeError}</ul>`,
        icon: 'warning',
        confirmButtonText: 'Corregir',
        confirmButtonColor: '#f39c12'
      });
    } else {
      Swal.fire('Error', 'Ocurrió un problema al intentar crear la cuenta.', 'error');
    }
  }
};

const irLogin = () => {
  router.push('/');
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

.btn-login { background: #2563eb; color: white; }
.btn-cancel { background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.4); }

.fade-in { animation: slideUp 0.6s ease-out; }
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>