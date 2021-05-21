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

  $("#edit_form").validate();

  function add_to_basket(rid) {
    $.ajax
    ({
      url: 'add_to_basket.php',
      data: "rid=" + rid + "&act=add",
      type: 'post',
      success: function (result) {
        $('#b_' + rid + '_add').hide();
        $('#b_' + rid + '_rem').show();
      }
    });
  }

  function rem_from_basket(rid) {
    $.ajax
    ({
      url: 'add_to_basket.php',
      data: "rid=" + rid + "&act=rem",
      type: 'post',
      success: function (result) {
        $('#b_' + rid + '_rem').hide();
        $('#b_' + rid + '_add').show();
      }
    });
  }

  function clear_elt(element_id) {
    $('#' + element_id).val('');
  }

  $(function () {
    $(".dp").datepicker({
      dateFormat: "dd/mm/yy",
    });
  });
}

export default {init};
