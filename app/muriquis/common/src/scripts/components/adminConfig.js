const init = () => {
  console.log('adminInit');
  // Color picker for primary color
  $('#colorpicker-primary').on('input', function () {
    $('#project-color-primary').val(this.value);
  });
  $('#project-color-primary').on('input', function () {
    $('#colorpicker-primary').val(this.value);
  });
  // Color picker for secondary color
  $('#colorpicker-secondary').on('input', function () {
    $('#project-color-secondary').val(this.value);
  });
  $('#project-color-secondary').on('input', function () {
    $('#colorpicker-secondary').val(this.value);
  });
  // Color picker for header text color
  $('#colorpicker-header-text').on('input', function () {
    $('#project-color-header-text').val(this.value);
  });
  $('#project-color-header-text').on('input', function () {
    $('#colorpicker-header-text').val(this.value);
  });
  // Color picker for header background color
  $('#colorpicker-header-background').on('input', function () {
    $('#project-color-header-background').val(this.value);
  });
  $('#project-color-header-background').on('input', function () {
    $('#colorpicker-header-background').val(this.value);
  });
  // Color picker for body text color
  $('#colorpicker-body-text').on('input', function () {
    $('#project-color-body-text').val(this.value);
  });
  $('#project-color-body-text').on('input', function () {
    $('#colorpicker-body-text').val(this.value);
  });
  // Color picker for body background color
  $('#colorpicker-body-background').on('input', function () {
    $('#project-color-body-background').val(this.value);
  });
  $('#project-color-body-background').on('input', function () {
    $('#colorpicker-body-background').val(this.value);
  });
}

const loadPageContent = (data) => {
  $.ajax
  ({
    url: 'common/admin/load_page_content.php',
    data: data,
    type: 'post',
    success: function (result) {
      // $('#b_' + rid + '_add').show();
      console.log('success');
    }
  });
}

export default {init, loadPageContent};
