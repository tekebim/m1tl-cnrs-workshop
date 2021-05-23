import $ from 'jquery';
import 'alpinejs';
import inView from 'in-view';
import gsap from 'gsap';
import customForm from './components/form';
import adminConfig from './components/adminConfig';

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
  inView('.to-animate')
    .on('enter', el => {
      gsap.to(el,
        {
          opacity: 1,
          y: 0,
          duration: 0.25,
          ease: "linear",
        },
      );
    });
}

/**
 * Functions for home page
 */
const isHome = () => {
  console.log('index page');
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
  console.log('about page');
}

/**
 * Functions for login page
 */
const isLogin = () => {
  console.log('login page');
}

const isAdminConfig = () => {
  adminConfig.init();
  // Bind click for all edit page button
  let btnEditAll = document.querySelectorAll('.btn-edit-content-page');
  btnEditAll.forEach(btn => {
    btn.addEventListener('click', (e) => {
      adminConfig.loadPageContent(btn.dataset.id);
    })
  })
}

$(document).ready(function () {
  init();
});
