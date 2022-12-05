import $ from "jquery";
export const init = (valuePixel) => {
  var filter = 0;
  var order = 0;
  var orderusers = 0;

  $('#filter_select').on('change',function(){
    filter = $('#filter_select').val();
    filteringDiscussions();
  });

  $('#order_select').on('change',function(){
    order = $('#order_select').val();
    filteringDiscussions();
  });
  $('#orderusers_select').on('change',function(){
    orderusers = $('#orderusers_select').val();
    filteringDiscussions();
  });
  function filteringDiscussions(){
    window.location.replace("/local/repositoryciae/index.php?filter="+filter+"&order="+order+"&orderusers="+orderusers);
  }

  const pixelatedImage = document.querySelector("#pixelatedImage");
  // storying a copy of the original image
  const originalImage = pixelatedImage.cloneNode(true);
  const pixelationElement = document.querySelector("#pixelationRange");

  pixelationElement.oninput = (e) => {
    pixelateImage(originalImage, parseInt(e.target.value));
  };
  pixelateImage(originalImage, parseInt(valuePixel));
  window.console.log(valuePixel);

  function pixelateImage(originalImage, pixelationFactor) {
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    const originalWidth = originalImage.width;
    const originalHeight = originalImage.height;
    const canvasWidth = originalWidth;
    const canvasHeight = originalHeight;
    canvas.width = canvasWidth;
    canvas.height = canvasHeight;
    context.drawImage(originalImage, 0, 0, originalWidth, originalHeight);
    const originalImageData = context.getImageData(
      0,
      0,
      originalWidth,
      originalHeight
    ).data;
    if (pixelationFactor !== 0) {
      for (let y = 0; y < originalHeight; y += pixelationFactor) {
        for (let x = 0; x < originalWidth; x += pixelationFactor) {
          // extracting the position of the sample pixel
          const pixelIndexPosition = (x + y * originalWidth) * 4;
          // drawing a square replacing the current pixels
          context.fillStyle = `rgba(
            ${originalImageData[pixelIndexPosition]},
            ${originalImageData[pixelIndexPosition + 1]},
            ${originalImageData[pixelIndexPosition + 2]},
            ${originalImageData[pixelIndexPosition + 3]}
          )`;
          context.fillRect(x, y, pixelationFactor, pixelationFactor);
        }
      }
    }
    pixelatedImage.src = canvas.toDataURL();
  }
};