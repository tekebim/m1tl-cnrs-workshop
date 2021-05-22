import $ from 'jquery';
import 'alpinejs';
import inView from 'in-view';
import gsap from 'gsap';
import customForm from './components/form';

/**
 * Init function
 */
const init = () => {
  console.log('function init loaded');

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
  $('#cssmenu').prepend('<div id="menu-button">Menu</div>');
  $('#cssmenu #menu-button').on('click', function () {
    var menu = $(this).next('ul');
    if (menu.hasClass('open')) {
      menu.removeClass('open');
    } else {
      menu.addClass('open');
    }
  });

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
  console.log('basket page');
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
  console.log('isAdminConfig');
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
}

$(document).ready(function () {
  init();
});
// document.addEventListener('DOMContentLoaded', init, false);
