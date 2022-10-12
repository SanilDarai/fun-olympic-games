/* ===============================================
	SLIDER 
=============================================== */

const options = {
  slidesToShow: 1,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  adaptiveHeight: true,
  autoplay: true
};

// my slick slider as constant object
const mySlider = jQuery('.slider').on('init', function(slick) {

  // constant var
  const slider = this;
  
  // slight delay so init completes render
  setTimeout(function() {

    // dot buttons
    let dots = jQuery('.slick-dots > li > button', slider);

    // each dot button function
    jQuery.each(dots, function(i,e) {

      // slide id
      let slide_id = jQuery(this).attr('aria-controls');
      
      // custom dot image
      let dot_img = jQuery('#'+slide_id).data('dot-img');
      
      jQuery(this).html('<img src="' + dot_img + '" alt="" />');

    });

  }, 100);

}).slick(options);

/* ===============================================
  OPEN CLOSE Menu
============================================= */

function entertainment_media_open_menu() {
  jQuery('button.menu-toggle').addClass('close-panal');
  setTimeout(function(){
    jQuery('nav#main-menu').show();
  }, 100);

  return false;
}

jQuery( "button.menu-toggle").on("click", entertainment_media_open_menu);

function entertainment_media_close_menu() {
  jQuery('button.close-menu').removeClass('close-panal');
  jQuery('nav#main-menu').hide();
}

jQuery( "button.close-menu").on("click", entertainment_media_close_menu);

/* ===============================================
  TRAP TAB FOCUS ON MODAL MENU
============================================= */

jQuery('button.close-menu').on('keydown', function (e) {
  if (jQuery("this:focus") && (e.which === 9)) {
    e.preventDefault();
    jQuery(this).blur();
    jQuery('button.menu-toggle').focus();
  }
});

/* ===============================================
  TABS
=============================================== */

jQuery(document).ready(function () {
  jQuery( ".tablinks" ).first().addClass( "active" );
  jQuery( ".tabcontent" ).first().addClass( "active" );
});

function entertainment_media_projects_tab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  jQuery('#'+ cityName).show()
  evt.currentTarget.className += " active";
}

/* ===============================================
  Scroll Top //
============================================= */

jQuery(window).scroll(function () {
  if (jQuery(this).scrollTop() > 100) {
      jQuery('.scroll-up').fadeIn();
  } else {
      jQuery('.scroll-up').fadeOut();
  }
}); 

jQuery('a[href="#tobottom"]').click(function () {
  jQuery('html, body').animate({scrollTop: 0}, 'slow');
  return false;
});