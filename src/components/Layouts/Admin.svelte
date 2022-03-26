<script>
	import 'bootstrap/dist/css/bootstrap.min.css';
	import 'font-awesome/css/font-awesome.min.css';
	import '../../stylesheets/admin.css';
  import { Modal } from 'bootstrap';
	import { onMount } from 'svelte';
	import { Router, Route } from 'svelte-routing';
  import Index from '../Pages/Admin/Index.svelte';
  import ProductType from '../Pages/Admin/ProductType.svelte';
  import Product from '../Pages/Admin/Product.svelte';
  import ProductDetail from '../Pages/Admin/ProductDetail.svelte';
  import Service from '../Pages/Admin/Service.svelte';
	import Redirect404 from '../Pages/Error/Redirect404.svelte';
  import Footer from './../Widgets/Footer.svelte';
  import Navigation from './../Widgets/Navigation.svelte';
  import { modal } from '../Stores/modal.js';
  export let url = '';
  export let basepath = '/admin';
  let modalComponent;
  let modalDOMStore;

  onMount(() => {
    console.log('home');
    modal.subscribe(value => {
      modalComponent = value;
    });
    modalDOMStore = new Modal(document.getElementById('modal'));
    document.getElementById('modal').addEventListener('hidden.bs.modal', function (event) {
      alert('=P');
    })
  });

  const modalOpen = () => {
    //myModal.toggle()
    modalComponent = Foo;
    // modalDOMStore.show();
    showModal(null);
  };

  function showModal(event) {
    if(typeof(event) !== "undefined" && event !== null){
      console.log(event.detail.param1);
    }
		modalDOMStore.show();
	}

</script>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <svelte:component this={modalComponent} />
    </div>
  </div>
</div>

<button class="btn btn-info d-none" on:click="{modalOpen}">Modal FOO</button>

<Navigation />

<Router url="{url}" basepath="{basepath}">
  <div>
    <Route path="/" component="{Product}" />
    <Route path="/product-type" component="{ProductType}" />
    <Route path="/service" component="{Service}" />
    <Route path="/product" component="{Product}" />
    <Route path="/product/add" component="{ProductDetail}" />
    <Route path="/product/edit/:id" let:params><ProductDetail id={params.id}/></Route>
    <Route path="/*" component="{Redirect404}" />
  </div>
</Router>

<Footer />

<style>
</style>