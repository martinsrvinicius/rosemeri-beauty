<template>
  <h1 class="clients">Clientes</h1>
  <div class="add-container">
    <va-button class="settings" preset="plain" @click="showAdd = !showAdd">
      <va-icon name="add" size="large"></va-icon>Novo
    </va-button>
  </div>

  <va-modal v-model="showAdd" hide-default-actions :mobile-fullscreen="false">
    <div class="modal-container-form">
      <h1>Novo Cliente</h1>
      <va-input class="mb-3 mt-5 input" label="Nome"></va-input>
      <va-input class="mb-3 input" label="Email"></va-input>
      <va-input class="mb-3 input" label="Telefone"></va-input>
      <va-date-input class="mb-3 input" label="Data de Nascimento"></va-date-input>
      <va-input class="mb-3 input" label="Rua"></va-input>
      <va-input class="mb-3 input" label="Código Postal"></va-input>
      <va-select class="mb-3 input" label="País"></va-select>
    </div>
    <div class="btn-form-option">
      <va-button preset="plain" class="btn-form-cancel" @click="showAdd = false"
        ><span style="font-size: 20px">Cancelar</span></va-button
      >
      <va-button class="btn-form-guardar" @click="updateEvent" :disabled="invalidDate"
        ><span style="font-size: 20px">Guardar</span></va-button
      >
    </div>
  </va-modal>

  <va-card class="main-table">
    <va-data-table :items="items" :columns="columns" sticky-header :wrapper-size="200" :bench="10" height="500px">
      <template #cell()="{ row, isExpanded }">
        <va-button preset="plain" :icon="isExpanded ? 'va-arrow-up' : 'va-arrow-down'" @click="row.toggleRowDetails()">
          {{ isExpanded ? 'Menos' : 'Mais' }}
        </va-button>
      </template>
      <template #expandableRow="{ rowData }">
        <ClientForm :client="rowData" />
      </template>
    </va-data-table>
  </va-card>
</template>

<script setup lang="ts">
  import { ref } from 'vue'
  import ClientForm from '../../components/form/ClientForm.vue'
  const showAdd = ref()
  const columns = [{ key: 'Id' }, { key: 'Nome' }, { key: 'Telefone' }, { key: '' }]

  const items = ref([
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
    {
      Id: '1',
      Nome: 'Vinicius Martins',
      Telefone: '962468873',
    },
  ])
</script>

<style scoped>
  .main-table {
    padding-top: 5px;
  }

  .va-data-table__table-tr--expanded td {
    background: var(--va-background-border);
  }

  .va-data-table__table-expanded-content td {
    background-color: var(--va-background-element);
  }

  .clients {
    text-align: center;
    font-size: 20px;
  }

  .add-container {
    display: flex;
    justify-content: end;
    position: relative;
    bottom: 10px;
    right: 10px;
  }
</style>
