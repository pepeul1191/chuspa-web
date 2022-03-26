<script>
  import { onMount } from 'svelte';
  import random from '../Helpers/random.js';
  import Quill from 'quill/dist/quill.min.js';
  import 'quill/dist/quill.snow.css';
  let editor = '';
  let container;
  export let validations = [];
  export let label = '';
  export let valid = true;
  export let disabled = false;
  export let validationMessage = '';
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
    validations.forEach(validation => {
      if(validation.type == 'notEmpty'){
        // console.log('notEmpty')
        if(editor.getLength() < 2){
          var message = 'Este campo debe de estar lleno';
          if (typeof validation.message !== 'undefined'){
            message = validation.message;
          }
          console.log(container);
          container.classList.add('border-color-error');
          container.previousSibling.classList.add('border-color-error-no-bottom');
          validationMessage = message;
          valid = false;
          validationMessageClass = 'text-danger';
        }else{
          console.log(container);
          container.classList.remove('border-color-error');
          container.previousSibling.classList.remove('border-color-error-no-bottom');
          validationMessage = '';
          validationMessageClass = '';
          valid = true;
        }
      }
    });
  };

  export const getHTML = () => {
    return editor.root.innerHTML;
  };
</script>

<label for="" class="form-label {validationMessageClass}">{label}</label>
<div id={randId} bind:this={container}></div>
<small class="{validationMessageClass}">
  {validationMessage}
</small>

<style>
  .border-color-error{
    border: 1px solid red !important;
  }
</style>