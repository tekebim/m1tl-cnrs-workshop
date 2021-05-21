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

$(document).ready(function () {
  init();
});
// document.addEventListener('DOMContentLoaded', init, false);
