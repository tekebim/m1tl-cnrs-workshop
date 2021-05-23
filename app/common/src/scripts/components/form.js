import ionRangeSlider from 'ion-rangeslider';
import $ from 'jquery';
import tablesort from 'jquery-tablesort';
import daterangepicker from 'daterangepicker';
import moment from 'moment';

const validate = require('jquery-validation');

const init = () => {
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

  $('.table-results').tablesort();

  // $("#{{ meta.id }}_0").val(ui.values[0]);
  // $("#{{ meta.id }}_1").val(ui.values[1]);

  // $("#edit_form").validate();

  let addToBasketBtnAll = document.querySelectorAll('.action--add');
  let removeToBasketBtnAll = document.querySelectorAll('.action--remove');
  /**
   * Checkbox
   * @type {NodeListOf<Element>}
   */
  let checkboxAll = document.querySelectorAll('.action_icon');
  // Bind for all checkboxs
  checkboxAll.forEach(checkbox => {
    // On click event
    checkbox.addEventListener('click', (e) => {
      // Disable defautl event
      e.preventDefault();
      // Target the element's parent
      let currentDataID = checkbox.dataset.id;
      let parentTableRow = checkbox.closest('tr');
      // If the current element is already selected
      if (parentTableRow.classList.contains('record--selected')) {
        // Then remove the selected status
        rem_from_basket(currentDataID);
        parentTableRow.classList.remove('record--selected');
      } else {
        // Add the selected status
        add_to_basket(currentDataID);
        parentTableRow.classList.add('record--selected');
      }
    })
  })

  $("#edit_form").validate();

  /*
  Function to update the selection number
   */
  function updateSelectCount(value) {
    let countElement = document.getElementById('basket-count');
    let currentValue = parseInt(countElement.innerText);
    let newValue = currentValue;

    if (value === 'increase') {
      newValue++
    } else {
      newValue--
    }
    countElement.innerHTML = newValue;
  }

  /*
  Function to add item to basket / selection
   */
  function add_to_basket(rid) {
    $.ajax
    ({
      url: 'add_to_basket.php',
      data: "rid=" + rid + "&act=add",
      type: 'post',
      success: function (result) {
        $('#b_' + rid + '_add').hide();
        $('#b_' + rid + '_rem').show();
        updateSelectCount('increase');
      }
    });
  }

  /*
  Function to remove item to basket / selection
   */
  function rem_from_basket(rid) {
    $.ajax
    ({
      url: 'add_to_basket.php',
      data: "rid=" + rid + "&act=rem",
      type: 'post',
      success: function (result) {
        $('#b_' + rid + '_rem').hide();
        $('#b_' + rid + '_add').show();
        updateSelectCount('decrease');
      }
    });
  }

  /*
  Function to clear element
   */
  function clear_elt(element_id) {
    $('#' + element_id).val('');
  }

  /*
  Function to initialize datepicker
   */
  function initDatepicker() {
    let start = moment().subtract(29, 'days');
    let end = moment();

    function cb(start, end) {
      $('.input-daterange span').html(start.format('dd/mm/yy') + ' - ' + end.format('dd/mm/yy'));
    }

    $(".input-daterange").daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      }
    }, cb);

    cb(start, end);

    console.log(start);
    console.log(end);
  }

  initDatepicker();
}

export default {init};
