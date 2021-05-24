import Quill from 'quill';

/**
 * Initialize function
 */
const init = () => {
  colorPicker();
  editorsWYSIWYG();
}

/*
* Function for editor WYSIWYG
 */
const editorsWYSIWYG = () => {
  let editors = document.querySelectorAll('.page-content-editor');

  let toolbarOptions = [
    [{'header': [1, 2, 3, 4, 5, 6, false]}],
    ['bold', 'italic', 'underline', 'strike'],
    ['blockquote', 'code-block'],
    [{'header': 1}, {'header': 2}, {'header': 3}, {'header': 4}, {'header': 5}],
    [{'list': 'ordered'}, {'list': 'bullet'}],
    [{'script': 'sub'}, {'script': 'super'}],
    [{'indent': '-1'}, {'indent': '+1'}],
    ['image'],
    [{'size': ['small', false, 'large', 'huge']}],
    [{'color': []}, {'background': []}],
    [{'align': []}],
    ['clean']
  ];

  editors.forEach((editor) => {
    let quill = new Quill(editor, {
      modules: {
        toolbar: toolbarOptions
      },
      placeholder: 'Page content here',
      theme: 'snow'
    });
  });

  // Event on form update
  $(".form-update-page").on("submit", function (event) {
    let pageId = $(this).attr('data-page-id');
    let contentEditor = $("#page-content-editor-" + pageId + " .ql-editor").html();
    let contentTextarea = $("#page-content-" + pageId).val(contentEditor);
  });
}

/**
 * Function for color picker
 */
const colorPicker = () => {
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

export default {init};
