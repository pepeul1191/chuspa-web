<script>
  import { onMount } from 'svelte';
  import random from '../Helpers/random.js';
  import Quill from 'quill/dist/quill.min.js';
  import 'quill/dist/quill.snow.css';
  let editor = '';
  let container;
  export let label = '';
  export let valid = true;
  export let disabled = false;
  export let validationMessage = 'Debe de ingresar un texto';
  let randId;
  let validationMessageClass = '';

  onMount(() => {
    randId = random(20);
  });

  export const load = (innerHTML) => {
    editor = new Quill(container, {
      theme: 'snow',
    });
    editor.root.innerHTML = innerHTML;
  };

  export const validate = () => {
    if(editor.getLength() < 2){
      validationMessage = validation.message;
      valid = false;
      validationMessageClass = 'text-danger';
    }else{
      valid = true;
    }
  };

  export const getHTML = () => {
    return editor.root.innerHTML;
  };
</script>

<label for="" class="form-label {validationMessageClass}">{label}</label>
<div id={randId} bind:this={container}></div>

<style>
</style>