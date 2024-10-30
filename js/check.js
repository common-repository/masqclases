jQuery(document).ready(function ($) {
   //check all
   $('input[name="marca_todos"]').click(function () {
      //for all checkboxes where the name begins with "usuario", check them
      $('input[name^="usuario"]').attr('checked', true);
   });

   //uncheck all
   $('input[name="desmarca_todos"]').click(function () {
      //for all checkboxes where the name begins with "usuario", uncheck them
      $('input[name^="usuario"]').attr('checked', false);
   });
});

