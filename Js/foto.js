'use strict';

let photo = document.getElementById('imgPhoto');
let file = document.getElementById('flImage');
var usuario = document.getElementById('usuario_registro');
var email_registro = document.getElementById('email_registro');
var passwords_registro = document.getElementById('senha_registro');

photo.addEventListener('click', () => {
  file.click();
});

file.addEventListener('change', () => {
  if (file.files.length <= 0) {
    return;
  }

  let reader = new FileReader();
  reader.onload = () => {
    const img = new Image();
    img.onload = () => {
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = 600;
      canvas.height = 600;
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      const resizedImage = canvas.toDataURL('image/jpeg');
      photo.src = resizedImage;
    };
    img.src = reader.result;
  },

    reader.readAsDataURL(file.files[0]);
});