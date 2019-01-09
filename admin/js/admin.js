jQuery(document).ready(function( $ ) {
  if ( $( "#wooes-regenerate-codes" ).length ) {
    $( "#wooes-regenerate-codes" ).click(function() {
      var r = confirm(php_vars.message);
      if (r == true) {
          window.location.href = "?page=wooes-settings&regenerate-codes=true";
      }
    });
  }

});
