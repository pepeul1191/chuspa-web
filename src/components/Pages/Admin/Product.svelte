<script>
  import { onMount } from 'svelte';
	import { alertMessage as alertMessageStore} from '../../Stores/alertMessage.js';
  import DataTable from '../../Widgets/DataTable.svelte';
  import InputText from '../../Widgets/InputText.svelte';
  const base_url = BASE_URL;
  let alertMessage = null;
  let alertMessageProps = {};
  let productDataTable;
  let inputName = '';

  onMount(() => {
    // console.log('index');
    alertMessageStore.subscribe(value => {
      if(value != null){
        alertMessage = value.component;
        alertMessageProps = value.props;
      }
    });
    productDataTable.list();
  });

  const search = () => {
    // run validations
    productDataTable.queryParams = {
      name: inputName,
    };
    productDataTable.list();
  }
  
  const clean = () => {
    inputName = '';
    productDataTable.queryParams = {
      name: inputName,
    };
    productDataTable.list();
  };
</script>

<svelte:head>
	<title>Gestión de Productos</title>
</svelte:head>

<div class="container">
	<div class="row">
		<svelte:component this={alertMessage} {...alertMessageProps} />
		<div class="col-lg-12 page-header">
			<h2>Gestión de Productos</h2>
		</div>
  </div>
  <div class="row">
    <div class="col-md-5">
      <InputText 
        label={'Nombre'}
        bind:value={inputName}
        placeholder={'Ingrese nombre de producto'} 
      />
    </div> 
    <div class="col-md-3" style="padding-top:27px;">
      <button class="btn btn-warning" on:click="{clean}"><i class="fa fa-eraser" aria-hidden="true"></i>
        Limpiar</button>
      <button class="btn btn-success" on:click="{search}"><i class="fa fa-search" aria-hidden="true"></i>
        Buscar Productos</button>
    </div>
  </div>
  <div class="row">
		<div class="col-md-12">
      <br>
			<DataTable bind:this={productDataTable} 
				urlServices={{ 
					list: `${base_url}admin/product/list`, 
					save: `${base_url}admin/product/delete` 
				}}
				buttonSave={true}
				buttonAddRecord={'/admin/product/add'}
				rows={{
					id: {
						style: 'color: red; display:none;',
						type: 'id',
					},
					color:{
						type: 'td',
					},
          name:{
						type: 'td',
					},
					actions:{
						type: 'actions',
						buttons: [
              {
								type: 'link', 
								icon: 'fa fa-pencil', 
								style:'font-size:12px; margin-right:10px;',
								url: '/admin/product/edit/',
                key: 'id',
							},
							{
								type: 'delete',
							},
						],
						style: 'text-align:center;'
					},
				}}
				headers={[
					{
						caption: 'codigo',
						style: 'display:none',
					},
          {
						caption: 'Color',
					},
					{
						caption: 'Nombre',
					},
					{
						caption: 'Operaciones',
						style:'text-align: center;',
					},]}
        messages={{
          notChanges: 'No ha ejecutado cambios en la tabla de productos',
          list404: 'Rercuso no encontrado para listar los elmentos de la tabla de productos',
          list500: 'Ocurrió un error en listar los elementos de la tabla de productos',
          save404: 'Rercuso no encontrado para guardar los cambios de la tabla de productos',
          save500: 'Ocurrió un error para guardar los cambios de la table de productos',
          save200: 'Se han actualizado los registros de la tabla de productos',
        }}
        pagination={{
          step: 10,
        }}
			/>
		</div>
  </div>
</div>

<style>

</style>