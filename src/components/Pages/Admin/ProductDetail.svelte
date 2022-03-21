<script>
  import { onMount } from 'svelte';
  import DataTable from '../../Widgets/DataTable.svelte';
  import UploadFile from '../../Widgets/UploadFile.svelte';
  import InputText from '../../Widgets/InputText.svelte';
  import AlertMessage from '../../Widgets/AlertMessage.svelte';
  import InputCheckGroup from '../../Widgets/InputCheckGroup.svelte';
  import { alertMessage as alertMessageStore} from '../../Stores/alertMessage.js';
  import { getProductById, saveProductDetail, saveProductTypes } from '../../../services/product_service.js';
  export let id;
  export let disabled = false;
  export let disabledProductType = false;
  let productCheckGroup;
  let baseURL = BASE_URL;
  let staticURL = STATIC_URL;
  let imagesDataTable;
  let title = '';
  let alertMessage = null;
  let alertMessageProps = {};
  let imageURL = 'E';
  let imageUploadFile = '';
  let color = '343434'; let inputColor; let colorValid = false;
  let price = ''; let inputPrice; let priceValid = false;
  let name = ''; let inputName; let nameValid = false;
  let description = ''; let inputDescription; let descriptionValid = false;
  
  onMount(() => {    
    alertMessageStore.subscribe(value => {
      if(value != null){
        alertMessage = value.component;
        alertMessageProps = value.props;
      }
    });
    // ajax
    if(id === undefined){
      console.log('if')
      title = 'Crear Producto';
      id = 'E';
      disabledProductType = true;
    }else{
      console.log('else')
      title = 'Editar Producto';
      loadDetail(id);
      disabledProductType = false;
    }
    productCheckGroup.url = `${baseURL}admin/product/product-type?product_id=${id}`;
    productCheckGroup.list();
    // image table
    imagesDataTable.urlServices.list = `${baseURL}admin/product/image/list?product_id=${id}`;
    imagesDataTable.list();
    imagesDataTable.extraData.product_id = id;
  });

  const launchAlert = (event, message, type) => {
    alertMessage = null;
    alertMessage = AlertMessage;
    alertMessageProps = {
      message: message,
      type: type,
      timeOut: 5000
    }
  };

  const saveDetail = () => {
    // run validations
    inputColor.validate();
    inputName.validate();
    inputDescription.validate();
    inputPrice.validate();
    // check image url
    if(imageURL == 'E'){
      imageURL = 'assets/img/default-product.png'
    }
    // check if true
    if(colorValid && nameValid && descriptionValid && priceValid) {
      var params = {
        id: id,
        color: color,
        name: name,
        price: price,
        description: description,
        url: imageURL,
      };
      saveProductDetail(params).then((resp) => {
        var data = resp.data;
        if(data != ''){
          id = data;
          title = 'Editar Producto';
          launchAlert(null, 'Se ha creado un nuevo producto', 'success');
          disabledProductType = false;
          imagesDataTable.extraData.product_id = data;
        }else{
          launchAlert(null, 'Se ha editado un producto', 'success');
        }
      }).catch((resp) =>  {
        if(resp.status == 404){
          launchAlert(null, 'Recurso guardar detalle de producto no existe en el servidor', 'danger');
        }else if(resp.status == 501){ 
          launchAlert(null, resp.data, 'danger');
        }else { 
          launchAlert(null, 'Ocurrió un error en guardar los datos del producto', 'danger');
        }
      })
    }
  };

  const loadDetail = (id) => {
    getProductById(id).then((resp) => {
      var data = resp.data;
      color = data.color;
      name = data.name;
      description = data.description;
      price = data.price;
      imageURL = data.url;
    }).catch((resp) =>  {
      disabled = true;
      if(resp.status == 404){
        launchAlert(null, 'Producto a editar no existe', 'warning');
      }else{
        launchAlert(null, 'Ocurrió un error en obtener los datos del producto', 'danger');
      }
    })
  };

  const saveTypes = () => {
    if(id != 'E'){
      var params = {
        id: id,
        data: productCheckGroup.data,
      };
      saveProductTypes(params).then((resp) => {
        var data = resp.data;
        console.log(resp.data)
        if(data == ''){
          launchAlert(null, 'Se ha asociado el tipo(s) de evento', 'success');
        }
      }).catch((resp) =>  {
        if(resp.status == 404){
          launchAlert(null, 'Recurso asosiar los tipos no existe en el servidor', 'danger');
        }else if(resp.status == 501){ 
          launchAlert(null, resp.data, 'danger');
        }else { 
          launchAlert(null, 'Ocurrió un error en asosiar los tipos de eventos del evento', 'danger');
        }
      })
    }
  };
</script>

<svelte:head>
	<title>{title}</title>
</svelte:head>

<div class="container">
	<div class="row">
		<svelte:component this={alertMessage} {...alertMessageProps} />
		<div class="col-lg-12 page-header">
			<h2>{title}</h2>
		</div>
  </div>
  <div class="row">
    <div class="col-md-2">
      <InputText 
        label={'Color(hex)'}
        bind:value={color}
        placeholder={'Color default del producto'} 
        disabled={disabled}
        validations={[
          {type:'notEmpty', message: 'Debe de ingresar un color'},
        ]}
        bind:valid={colorValid} 
        bind:this={inputColor}
      />
    </div>
    <div class="col-md-2">
      <InputText 
        label={'Precio (S/.)'}
        bind:value={price}
        placeholder={'Precio del producto'} 
        disabled={disabled}
        validations={[
          {type:'notEmpty', message: 'Debe de ingresar un precio'},
          {type:'floatNumber', message: 'Formato inválido, ejemplo: 10.5'},
        ]}
        bind:valid={priceValid} 
        bind:this={inputPrice}
      />
    </div>
    <div class="col-md-5">
      <InputText 
        label={'Nombre'}
        bind:value={name}
        placeholder={'Nombre del producto'} 
        disabled={disabled}
        validations={[
          {type:'notEmpty', message: 'Debe de ingresar un nombre producto'},
          {type:'maxLength', length: 100, message: 'Nombre máximo 100 letras'},
        ]}
        bind:valid={nameValid} 
        bind:this={inputName}
      />
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <UploadFile bind:this={imageUploadFile} 
          label={'Imagen Principal'}
          fileName={'file'} 
          url={`${baseURL}upload`} 
          baseUrlFile={`${staticURL}`}  
          validationSize={
            {size: 2.6, message: 'Máximo 2 MB'}
          }
          validationExtension={
            {allowed: ['image/png', 'image/jpg', 'image/jpeg'], message: 'Sólo Imágenes'}
          }
          disabled={disabled}
          bind:urlFile={imageURL}
        />
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <InputText 
        label={'Descripción'}
        bind:value={description}
        placeholder={'Descripción del producto'} 
        disabled={disabled}
        validations={[
          {type:'notEmpty', message: 'Debe de ingresar una descripción del producto'},
          {type:'maxLength', length: 200, message: 'Nombre máximo 200 letras'},
        ]}
        bind:valid={descriptionValid} 
        bind:this={inputDescription}
      />
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 pull-right">
      <button class="btn btn-success btn-actions" disabled="{disabled}" on:click="{saveDetail}"><i class="fa fa-check" aria-hidden="true"></i>
        {title}</button>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-9">
      <InputCheckGroup bind:this={productCheckGroup} 
        url={`${baseURL}admin/product/product-type`}
        key = {{ id: 'id', name: 'name', exist: 'exist' }}
        inline = {true}
        label = {'Seleccionar el Tipo(s) de Producto'}
        disabled = {disabledProductType}
      />
    </div>
    <div class="col-md-3 pull-right">
      <button class="btn btn-primary btn-actions" style="margin-top: 35px;" disabled="{disabledProductType}" on:click="{saveTypes}"><i class="fa fa-list" aria-hidden="true"></i>
        Asosiar Tipo de Producto</button>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-12">
      <br>
      <h6>Galería de Imágenes</h6>
      <DataTable bind:this={imagesDataTable} 
				urlServices={{ 
					list: `${baseURL}admin/product/image/list`, 
					save: `${baseURL}admin/product/image/save` 
				}}
				buttonSave={true},
        buttonAddRow={true},
				rows={{
					id: {
						style: 'color: red; display:none;',
						type: 'id',
					},
					description:{
						type: 'input[text]',
					},
          color:{
						type: 'input[text]',
					},
          url:{
						type: 'upload',
            style: 'text-align: center',
            tableKeyURL: 'url',
            tableRecordKey: 'id',
					},
          actions:{
						type: 'actions',
						buttons: [
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
						style: 'display:none;',
					},
					{
						caption: 'Descripción',
					},
          {
						caption: 'Color',
					},
          {
						caption: 'Imágenes del Producto',
            style: 'text-align: center',
					},
          {
						caption: 'Operaciones',
						style:'text-align: center;',
					},
				]}
				messages={{
					notChanges: 'No ha ejecutado cambios en la tabla de imágenes del producto',
					list404: 'Rercuso no encontrado para listar los elmentos de la tabla de imágenes del producto',
					list500: 'Ocurrió un error en listar los elementos de la tabla de imágenes del producto',
					save404: 'Rercuso no encontrado para guardar los cambios de la tabla de imágenes del producto',
					save500: 'Ocurrió un error para guardar los cambios de la table de imágenes del producto',
					save200: 'Se han actualizado los registros de la tabla de imágenes del producto',
				}}
        disabled={disabledProductType}
			/>
    </div>
  </div>
</div>

<style>
  .btn-actions{
    float:right;
    margin-top:15px;
  }
</style>