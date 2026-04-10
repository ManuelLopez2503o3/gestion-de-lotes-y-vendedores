<template>
  <div class="dashboard-wrapper">
    <main class="content-container">
      
      <header class="main-header fade-in">
        <div class="title-section">
          <h1 class="main-title">Panel de Lotes</h1>
          <p class="subtitle">Gestión inteligente de fuerza de ventas</p>
        </div>
        <div class="header-actions">
          <button @click="abrirModalCrear" class="btn-primary">
            <span class="plus-icon">+</span> Nuevo Lote
          </button>
         </div>
      </header>

      <div v-if="lotes && lotes.length > 0" class="cards-grid">
        <div v-for="l in lotes" :key="l.id" class="lote-card fade-in">
          <div class="card-top">
            <span class="lote-id">#{{ l.identificador }}</span>
            <button @click="abrirModalEditar(l)" class="btn-edit-pencil">✏️</button>
          </div>

          <div class="card-body">
            <h3 class="lote-name">{{ l.nombre }}</h3>
            <p class="lote-location">📍 {{ l.direccion }}</p>

            <div class="vendedores-count">
              <span class="user-icon">👤</span>
              <span class="number">{{ l.vendedores_count || 0 }}</span>
              <span class="text">Vendedores</span>
            </div>

            <div class="import-panel">
              <p class="panel-label">Importar personal <span style="font-size: 0.85em; color: #666; font-weight: normal;">(Máx 10)</span></p>
              <div class="import-group">
                <input type="number" v-model.number="cantidades[l.id]" min="0" max="10" class="qty-input">
                <button @click="importarPersonalizado(l.id)" class="btn-import-confirm">
                  📥 Importar
                </button>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <button @click="abrirDetalleVendedores(l)" class="btn-view-list">
              Ver lista de usuarios
            </button>
            <button @click="eliminarLote(l.id)" class="btn-delete-link">
              Eliminar lote
            </button>
          </div>
        </div>
      </div>

      <div v-if="loteSeleccionado" class="modal-overlay" @click.self="cerrarLista">
        <div class="modal-glass list-modal fade-in">
          <div class="modal-header">
            <div>
              <h2 class="modal-title">{{ loteSeleccionado.nombre }}</h2>
              <p class="modal-subtitle">Vendedores registrados</p>
            </div>
            <button @click="cerrarLista" class="btn-close-x">×</button>
          </div>
          <div class="modal-body scrollable-content">
            <ul v-if="vendedores.length > 0" class="vendedores-list">
              <li v-for="v in vendedores" :key="v.id" class="vendedor-item">
                <div class="v-avatar">👤</div>
                <div class="v-info">
                  <p class="v-username">@{{ v.username }}</p> 
                  <p class="v-full-name">{{ v.nombre }}</p>
                </div>
                <div style="display: flex; gap: 8px;">
                  <button @click="abrirModalEditarVendedor(v)" class="btn-edit-pencil">✏️</button>
                  <button @click="quitarVendedor(v.id)" class="btn-remove-item">🗑️</button>
                </div>
              </li>
            </ul>
            <p v-else class="empty-msg">No hay vendedores en este lote.</p>
          </div>
        </div>
      </div>

      <div v-if="mostrarFormLote" class="modal-overlay" @click.self="cerrarModalLote">
        <div class="modal-glass fade-in">
          <h2 class="modal-title">{{ editandoLote ? 'Editar Lote' : 'Nuevo Lote' }}</h2>
          <div class="form">
            <label class="input-label">Nombre de sucursal</label>
            <input v-model="formLote.nombre" class="m-input" placeholder="Nombre" />
            <label class="input-label">Dirección</label>
            <input v-model="formLote.direccion" class="m-input" placeholder="Dirección" />
            <label class="input-label">Identificador</label>
            <input v-model="formLote.identificador" class="m-input" placeholder="ID Lote" />
            <div class="modal-btns">
              <button @click="guardarLote" class="btn-save">Confirmar</button>
              <button @click="cerrarModalLote" class="btn-cancel">Cancelar</button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="mostrarFormVendedor" class="modal-overlay" @click.self="cerrarModalVendedor">
        <div class="modal-glass fade-in">
          <h2 class="modal-title">Editar Vendedor</h2>
          <div class="form">
            
            <label class="input-label">Nombre del Vendedor</label>
            <input v-model="formVendedor.nombre" class="m-input" placeholder="Nombre completo" />
            
            <label class="input-label">Asignar a Sucursal / Lote</label>
            <select v-model="formVendedor.lote_id" class="m-input">
              <option :value="null">-- Desvincular de todos --</option>
              <option v-for="lote in lotes" :key="lote.id" :value="lote.id">
                {{ lote.nombre }}
              </option>
            </select>

            <div class="modal-btns">
              <button @click="guardarVendedor" class="btn-save">Guardar Cambios</button>
              <button @click="cerrarModalVendedor" class="btn-cancel">Cancelar</button>
            </div>

          </div>
        </div>
      </div>

      <footer class="main-footer">
        <button @click="logout" class="btn-logout">
          <span class="power-icon">⏻</span> Cerrar sesión
        </button>
      </footer>

    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import Swal from 'sweetalert2';  

const lotes = ref([]);
const cantidades = ref({});
const loteSeleccionado = ref(null);
const mostrarFormLote = ref(false);
const editandoLote = ref(false);
const idLoteAEditar = ref(null);
const formLote = ref({ nombre: '', direccion: '', identificador: '' });

const vendedores = ref([]);
const mostrarFormVendedor = ref(false);
const formVendedor = ref({ id: null, nombre: '', lote_id: null });

const obtenerLotes = async () => {
  try {
    const res = await api.get('/lotes');
    lotes.value = Array.isArray(res.data) ? res.data : [];
    lotes.value.forEach(l => {
      if (cantidades.value[l.id] === undefined) cantidades.value[l.id] = 0;
    });
  } catch (e) { 
      console.error(e); 
  }
};

const abrirModalCrear = () => {
  editandoLote.value = false;
  formLote.value = { nombre: '', direccion: '', identificador: '' };
  mostrarFormLote.value = true;
};

const abrirModalEditar = (lote) => {
  editandoLote.value = true;
  idLoteAEditar.value = lote.id;
  formLote.value = { 
    nombre: lote.nombre, 
    direccion: lote.direccion, 
    identificador: lote.identificador 
  };
  mostrarFormLote.value = true;
};

const cerrarModalLote = () => { mostrarFormLote.value = false; };

const guardarLote = async () => {
  try {
    if (editandoLote.value) {
      await api.put(`/lotes/${idLoteAEditar.value}`, formLote.value);
    } else {
      await api.post('/lotes', formLote.value);
    }
    cerrarModalLote();
    obtenerLotes();
    
    Swal.fire({
        title: '¡Excelente!',
        text: editandoLote.value ? 'El lote se actualizó correctamente.' : 'El lote se creó correctamente.',
        icon: 'success',
        confirmButtonColor: '#3085d6'
    });
  } catch (error) { 
    if (error.response && error.response.status === 422) {
        const errores = error.response.data.errors;
        let mensajeError = "";
        Object.values(errores).forEach(err => {
            mensajeError += `<li style="margin-bottom: 5px;">${err[0]}</li>`;
        });
        Swal.fire({
            title: 'Revisa los datos',
            html: `<ul style="text-align: left; color: #555;">${mensajeError}</ul>`,
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
    } else {
        Swal.fire('Error', error.response?.data?.message || 'Ocurrió un problema en el servidor.', 'error');
    }
  }
};

const eliminarLote = async (id) => {
  const result = await Swal.fire({
    title: '¿Estás seguro?',
    text: "Se borrará el lote y no podrás recuperarlo.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar lote',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/lotes/${id}`);
      obtenerLotes();
      Swal.fire('¡Eliminado!', 'El lote fue borrado exitosamente.', 'success');
    } catch (e) { 
      Swal.fire('Error', e.response?.data?.message || 'No se pudo eliminar el lote.', 'error'); 
    }
  }
};

const importarPersonalizado = async (id) => {
  try {
    const qty = Number(cantidades.value[id]) || 0; 
    
    if (qty === 0) {
      return Swal.fire('Atención', 'Debes seleccionar al menos 1 vendedor para importar.', 'info');
    }

    const res = await api.post(`/importar-vendedores/${id}`, { cantidad: qty });
    Swal.fire('¡Completado!', res.data?.message || 'Personal importado con éxito.', 'success');
    obtenerLotes(); 
  } catch (e) { 
    Swal.fire('Error', e.response?.data?.message || e.response?.data?.error || 'Error al importar personal.', 'error'); 
  }
};

const abrirDetalleVendedores = async (lote) => {
  loteSeleccionado.value = lote;
  try {
    const res = await api.get(`/lotes/${lote.id}/vendedores`);
    vendedores.value = res.data;
  } catch (e) { console.error(e); }
};

const cerrarLista = () => { loteSeleccionado.value = null; vendedores.value = []; };

const quitarVendedor = async (id) => {
  const result = await Swal.fire({
    title: '¿Quitar vendedor?',
    text: "Esta acción lo desvinculará del lote, pero seguirá en la base de datos.", 
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, quitar',
    cancelButtonText: 'Cancelar'
  });

  if (result.isConfirmed) {
    try {
      await api.put(`/vendedores/${id}/quitar`); 
      const res = await api.get(`/lotes/${loteSeleccionado.value.id}/vendedores`);
      vendedores.value = res.data;
      obtenerLotes();
      Swal.fire('¡Quitado!', 'El vendedor ha sido retirado de este lote.', 'success');
    } catch (e) { 
      Swal.fire('Error', e.response?.data?.message || "Hubo un problema al quitar el vendedor.", 'error'); 
    }
  }
};

const abrirModalEditarVendedor = (vendedor) => {
  formVendedor.value = { 
    id: vendedor.id, 
    nombre: vendedor.nombre, 
    lote_id: vendedor.lote_id 
  };
  mostrarFormVendedor.value = true;
};

const cerrarModalVendedor = () => { 
  mostrarFormVendedor.value = false; 
};

const guardarVendedor = async () => {
  try {
    await api.put(`/vendedores/${formVendedor.value.id}/reasignar`, formVendedor.value);
    
    cerrarModalVendedor();
    Swal.fire('¡Listo!', 'El vendedor ha sido actualizado y reasignado.', 'success');
    
    obtenerLotes();
    
    if (loteSeleccionado.value) {
      const res = await api.get(`/lotes/${loteSeleccionado.value.id}/vendedores`);
      vendedores.value = res.data;
    }
  } catch (error) {
    if (error.response && error.response.status === 422) {
        const errores = error.response.data.errors;
        let mensajeError = "";
        Object.values(errores).forEach(err => {
            mensajeError += `<li style="margin-bottom: 5px;">${err[0]}</li>`;
        });
        Swal.fire({
            title: 'Revisa los datos',
            html: `<ul style="text-align: left; color: #555;">${mensajeError}</ul>`,
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
    } else {
        Swal.fire('Error', error.response?.data?.message || 'No se pudieron guardar los cambios.', 'error');
    }
  }
};

const logout = () => { 
    localStorage.removeItem('token'); 
    window.location.href = '/'; 
};

onMounted(() => obtenerLotes());
</script>

<style scoped>
</style>