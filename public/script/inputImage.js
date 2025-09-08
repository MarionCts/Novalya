// DYNAMIC INPUTS FOR THE UPLOAD OF MULTIPLE PHOTOS IN THE PAGES "ADD" AND "EDIT" A PROPERTY

document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('images-collection');
  const addBtn = document.querySelector('.js-add-image');
  if (!container) return;

  // Initializes the counter of photos if there none for now
  if (!container.dataset.index) {
    container.dataset.index = container.querySelectorAll('.image-item').length;
  }

  if (addBtn) {
    addBtn.addEventListener('click', function () {
      const index = container.dataset.index;
    // "/__name__/" is the default name Symfony uses when creating this type of element ; with replace(), we are asking it to replace this name by the value of "index"
      const html = container.dataset.prototype.replace(/__name__/g, index);

      const wrapper = document.createElement('div');
      wrapper.className = 'image-item image-file-input';
      wrapper.innerHTML = html +
        '<button type="button" class="button__delete--icon js-remove-image"><svg width="100%" height="100%" viewbox="0 0 31 37" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path d="M30.042,4.807l-14.309,2.816l-3.04,0.599l14.538,-0c0.33,-0 0.644,0.134 0.87,0.372c0.225,0.237 0.34,0.556 0.318,0.881l-1.786,26.429c-0.042,0.617 -0.561,1.096 -1.189,1.096l-19.057,0c-0.627,0 -1.147,-0.479 -1.188,-1.096l-1.748,-25.863l-2.026,0.399c-0.079,0.015 -0.157,0.023 -0.235,0.023c-0.557,0 -1.054,-0.386 -1.167,-0.945c-0.129,-0.636 0.29,-1.255 0.935,-1.382l8.243,-1.623l0.687,-4.149c0.079,-0.483 0.455,-0.867 0.942,-0.963l7.008,-1.379c0.488,-0.094 0.983,0.117 1.245,0.532l2.249,3.571l8.243,-1.622c0.643,-0.128 1.273,0.286 1.402,0.922c0.129,0.636 -0.29,1.255 -0.935,1.382Zm-5.712,29.844l1.628,-24.08l-20.084,0l1.627,24.08l16.829,-0Zm-8.414,-3.524c-0.658,0 -1.191,-0.526 -1.191,-1.175l-0,-13.54c-0,-0.648 0.533,-1.175 1.191,-1.175c0.657,0 1.191,0.527 1.191,1.175l-0,13.54c-0,0.649 -0.534,1.175 -1.191,1.175Zm-5.063,-0.019c-0.614,-0 -1.134,-0.464 -1.186,-1.078l-1.139,-13.501c-0.055,-0.646 0.432,-1.214 1.088,-1.268c0.655,-0.052 1.231,0.427 1.285,1.073l1.14,13.501c0.055,0.647 -0.433,1.214 -1.088,1.268c-0.034,0.003 -0.067,0.005 -0.1,0.005Zm9.803,-0.019c-0.655,-0.067 -1.131,-0.643 -1.064,-1.288l1.393,-13.476c0.066,-0.645 0.641,-1.116 1.306,-1.049c0.653,0.066 1.13,0.642 1.063,1.287l-1.393,13.476c-0.062,0.605 -0.579,1.055 -1.183,1.055c-0.04,0 -0.081,-0.001 -0.122,-0.005Zm-8.55,-27.543l-0.41,2.477l7.142,-1.406l-1.342,-2.131l-5.39,1.06Z"/></svg></button>';

      container.appendChild(wrapper);
      container.dataset.index = String(parseInt(index, 10) + 1);

    // Removes the label "file" for the style
      const label = wrapper.querySelector('label');
      if (label) {
        label.remove();
      }

    });
  }

  // Removes a photo from the selection if selected
  container.addEventListener('click', function (e) {

    // Checks the closest button which contains the class "js-remove-image"
    if (e.target.closest('.js-remove-image')) {

        // Prevents the "submit" event on the button from happening 
      e.preventDefault();

    //   if there is a button with the class "js-remove-image" that is clicked, we remove the photo item associated (the closest one) 
      const item = e.target.closest('.image-item');
      if (item) item.remove();
    }
  });

  // Listens to the click on the checkbox element to prevent multiple selection from happening (only one should be checked for the property "isFeatured")
  container.addEventListener('change', function (e) {
    if (e.target.matches('input[type="checkbox"][name*="[isFeatured]"]') && e.target.checked) {
      container.querySelectorAll('input[type="checkbox"][name*="[isFeatured]"]').forEach(cb => {
        if (cb !== e.target) cb.checked = false;
      });
    }
  });
});
