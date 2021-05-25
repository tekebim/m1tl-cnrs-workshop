import $ from 'jquery';
import 'alpinejs';
import inView from 'in-view';
import customForm from './components/form';
import adminConfig from './components/adminConfig';
import AOS from 'aos';

/**
 * Init function
 */
const init = () => {

  commons();

  if (document.getElementById('page-about') !== null) {
    isAbout();
  }

  if (document.getElementById('page-login') !== null) {
    isLogin();
  }

  if (document.getElementById('page-index') !== null) {
    isHome();
  }

  if (document.getElementById('page-basket')) {
    isBasket();
  }

  if (document.getElementById('page-admin-configuration')) {
    isAdminConfig();
  }
}

/**
 * Global commons functions
 */
const commons = () => {
  AOS.init({
    offset: 200,
    duration: 600,
    easing: 'ease-in-sine',
    delay: 100,
    disable: 'mobile'
  });
}

/**
 * Functions for home page
 */
const isHome = () => {
  customForm.init();
}

/**
 * Functions for basket page
 */
const isBasket = () => {
  let rows = document.querySelectorAll('.table-results tbody > tr');
  let selectionIds = [];
  rows.forEach(row => {
    selectionIds.push(row.dataset.id);
  })

  customForm.init();
  customForm.updateDynamicSelectionLinks(selectionIds);

  // Event click on button copy link to clipboard
  let btnCopyToClipboard = document.getElementById('btn-copy-clipboard');
  let shareLink = document.getElementById('share-link');
  btnCopyToClipboard.addEventListener('click', (e) => {
    e.preventDefault();
    // If element share link exist
    if (shareLink !== null) {
      // Copy href attribute to user clipboard
      copyToClipboard(shareLink.href);
    }
  });

  /*
  Function to create fake input, to copy value on clipboard
  @value : String
   */
  function copyToClipboard(value) {
    // Create a temporary input to get value
    let tempInput = document.createElement("input");
    // Assign value to temp input
    tempInput.value = value;
    document.body.appendChild(tempInput);
    // Select the value
    tempInput.select();
    // Copy the value to clipboard
    document.execCommand("copy");
    // Then remove temp input from DOM
    document.body.removeChild(tempInput);
  }
}

/**
 * Functions for about page
 */
const isAbout = () => {
}

/**
 * Functions for login page
 */
const isLogin = () => {
}

const isAdminConfig = () => {
  adminConfig.init();
}

$(document).ready(function () {
  init();
});
