import ionRangeSlider from 'ion-rangeslider';
import $ from 'jquery';

const init = () => {
  console.log('init from form');

  let sliders = document.querySelectorAll('.js-range-slider');
  // For each sliders on page
  sliders.forEach(slider => {
    let sliderID = slider.id,
      metaSliderID = slider.dataset.metaId,
      inputHiddenMin = document.getElementById(`${metaSliderID}_0`),
      inputHiddenMax = document.getElementById(`${metaSliderID}_1`);

    // Init the range plugin
    $(slider).ionRangeSlider({
      // Detect when values changed
      onFinish: (data) => {
        // Assign the minimum value to the hidden input
        inputHiddenMin.value = data.from;
        // Assign the maximun value to the hidden input
        inputHiddenMax.value = data.to;
      },
    });
  });

  // $("#{{ meta.id }}_0").val(ui.values[0]);
  // $("#{{ meta.id }}_1").val(ui.values[1]);
}

export default {init};
