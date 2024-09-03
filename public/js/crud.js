/******/ (() => { // webpackBootstrap
/*!******************************!*\
  !*** ./resources/js/crud.js ***!
  \******************************/
/*
    Custom CRUD
*/

(function () {
  "use strict";

  /*------------------------------------------
          --------------------------------------------
          Pass Header Token
          --------------------------------------------
     --------------------------------------------*/
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
  });

  /*------------------------------------------
      --------------------------------------------
      Render DataTable
      --------------------------------------------
      --------------------------------------------*/
  var table = $(".data-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: URLindex,
    columns: columnas
  });

  /*------------------------------------------
      --------------------------------------------
      Create or Update Record Table Code
      --------------------------------------------
      --------------------------------------------*/
  $("#form").on("submit", function (e) {
    //use on if jQuery 1.7+
    e.preventDefault(); //prevent form from submitting

    if ($("#form").valid()) {
      //$(this).html('Enviando...');
      $.ajax({
        data: $("#form").serialize(),
        url: URLindex,
        type: "POST",
        dataType: "json",
        success: function success(data) {
          $("#form").trigger("reset");
          $("#ajaxModel").modal("hide");
          toastr.success("Registro guardado correctamente.", "Exito", {
            timeOut: 5000
          });
          table.draw();
        },
        error: function error(data) {
          console.log("Error:", data);
          //$('#saveBtn').html('Guardar');
        }
      });
    } else {
      console.log("Datos no validos");
    }
  });

  /*------------------------------------------
      --------------------------------------------
      Click to Button
      --------------------------------------------
  --------------------------------------------*/
  $("#createNewRecord").on("click", function () {
    $("#table_id").val("");
    //$('#form').trigger("reset");
    $("#form")[0].reset();
    $("#modelHeading").html("Crear nueva " + titulo);
    $("#ajaxModel").modal("show");
  });

  /*------------------------------------------
      --------------------------------------------
      Click to Edit Button
      --------------------------------------------
  --------------------------------------------*/
  $("body").on("click", ".editRecord", function () {
    var table_id = $(this).data("id");
    $.get(URLindex + "/" + table_id + "/edit", function (data) {
      $("#modelHeading").html("Editar " + titulo);
      $("#ajaxModel").modal("show");
      $("#table_id").val(data.id);
      $.each(data, function (index, itemData) {
        $('[name="' + index + '"]').val(itemData);
      });
    });
  });

  /*------------------------------------------
      --------------------------------------------
      Delete Record Table Code
      --------------------------------------------
      --------------------------------------------*/
  $("body").on("click", ".deleteRecord", function () {
    var table_id = $(this).data("id");
    var sino = confirm("Confirma borrar el registro?");
    if (sino) {
      $.ajax({
        type: "DELETE",
        url: URLindex + "/" + table_id,
        success: function success(data) {
          table.draw();
        },
        error: function error(data) {
          console.log("Error:", data);
        }
      });
    }
  });
})();
/******/ })()
;