jQuery(document).ready(function($) {

  $('#primary-menu').hcOffcanvasNav({
    disableAt: 800,
    customToggle: $('.toggle'),
    navTitle: 'Back',
    position: 'right',
    levelTitles: true,
    levelTitleAsBack: true
  });

});