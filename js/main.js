//by: JOKO SANTOSA (0877 5829 3281) | || mailto:jspos.info@gmail.com or joko@global-automation.in

//on input form change value or on keyup
function valuechange(post, target, htmlid) {
  var value = $(target).val();
  $.post(window.location.pathname, {
    post: post,
    value: value
  }, function (a) {
    $(htmlid).html(a);
  });
}

// value change untuk combine 2 columns
function valuechange2(post, value1, value2, htmlid) {
  var value = $(value1).val() + "&" + $(value2).val();
  $.post(window.location.pathname, {
    post: post,
    value: value
  }, function (a) {
    $(htmlid).html(a);
  });
}


//on button edit employee choosed and clicked in user setting page
function button_update(x, target, post, htmlid) {
  $(target).val(x);
  valuechange(post, target, htmlid);
}

//on input form change value or on keyup
function button_delete(post, value) {
  var r = confirm("Are you sure to delete?")
  if (r == true) {
    $.post(window.location.pathname, {
      post: post,
      value: value
    }, function () {
      location.reload();
    });
  }
}

//on input form change value or on keyup
function desmodal(title, value, post) {
  $.post(window.location.pathname, {
    post: post,
    value: value
  }, function (x) {
    $('#destitle').html(title);
    $('#desbody').html(x);
    $('.modaltable').DataTable({
      "pageLength": 25,
      "lengthChange": false,
      dom: 'Bfrtip',
      buttons: [
        'copy',{
          extend: 'excel',
          messageTop: function () {
            if ($(".modaltable").attr("data-title") !== null) {
              return $(".modaltable").attr("data-title");
            }
            else {
              return '';
            }
          }
        },
        {
          extend: 'pdf',
          messageTop: function () {
            if ($(".modaltable").attr("data-title") !== null) {
              return $(".modaltable").attr("data-title");
            }
            else {
              return '';
            }
          }
        },
        {
          extend: 'print',
          messageTop: function () {
            if ($(".modaltable").attr("data-title") !== null) {
              return $(".modaltable").attr("data-title");
            }
            else {
              return '';
            }
          }
        }
      ],
    });
    //start custom the datatable button
    $(".dt-button").addClass("btn btn-sm");
    $(".buttons-copy").addClass("btn-light");
    $(".buttons-copy").html('<span class="icon text-dark-50"><i class="fa fa-copy fa-sm"></i></span><span class="text"> Copy</span>');
    $(".buttons-excel").addClass("btn-success");
    $(".buttons-excel").html('<span class="icon text-white-50"><i class="fa fa-file-excel fa-sm"></i></span><span class="text"> Excel</span>');
    $(".buttons-pdf").addClass("btn-danger");
    $(".buttons-pdf").html('<span class="icon text-white-50"><i class="fa fa-file-pdf fa-sm"></i></span><span class="text"> PDF</span>');
    $(".buttons-print").addClass("btn-info");
    $(".buttons-print").html('<span class="icon text-white-50"><i class="fa fa-print fa-sm"></i></span><span class="text"> Print</span>');
    //end of custom the datatable button
  });
}

//on submitted form
function submitform() {
  var progress = $('#progress');
  var bar = $('#progressbar');
  var text = $('#progresstext');
  var form_data = new FormData(); //Encode form elements for submission
  $(progress).removeClass('d-none');
  $(bar).attr("style", "width : 0%");
  $(bar).attr("aria-valuenow", "0");
  $(text).html("0%");
  $.ajax({
    url: window.location.pathname, // point to server-side PHP script 
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    xhr: function () {
      //upload Progress
      var xhr = $.ajaxSettings.xhr();
      if (xhr.upload) {
        xhr.upload.addEventListener('progress', function (event) {
          var percent = 0;
          var position = event.loaded || event.position;
          var total = event.total;
          if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
          }
          //update progressbar
          $(bar).attr("style", "width :" + percent + "%");
          $(bar).attr("aria-valuenow", percent);
          $(text).html(percent);
        }, true);
      }
      return xhr;
    }
  }).done(function () {
    $(text).html("Complate!");
    $("#alert-massage").append('<div class="alert alert-success alert-dismissible fixed-center"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Data already submitted. This page will reload automaticay in 3 seconds..</div>');
    setTimeout(function () {
      location.reload();
    }, 2000);
  });
}
//on search form
function searchform(id, target) {
  $.post(window.location.pathname,
    $(id).serialize(), function (x) {
      $(target).html(x);
      $(target + "table").DataTable({
        "pageLength": 25,
        "searching": false,
      });
    });
}


//on add form
function addform(count, target) {
  var s_count = parseInt($(count).val());
  var new_count = s_count + 1;
  $.post(window.location.pathname, {
    addform: '', s_count: new_count
  }, function (x) {
    $(count).remove();
    $(target).prepend(x);
  });
}

//make selectall checbox
function selectall(select, selectid) {
  if ($(select).is(':checked')) {
    $(selectid).attr('checked', true);
  } else {
    $(selectid).attr('checked', false);
  }
}

//validate for confirm password form
function confirmpass(password, confirm_password) {
  if ($(password).val() !== $(confirm_password).val()) {
    $("#alert-massage").append('<div class="alert alert-warning alert-dismissible fixed-center"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warnning!</strong> Corfirm password does not match.</div>');
  }
}

function autofill(fieldid, sugestion, caseid, target = null) {
  var fill = $(fieldid).val();
  if (fill.length > 3) {
    $.ajax({
      url: window.location.pathname,
      type: 'post',
      data: { auto_fill: fill, case: caseid },
      dataType: 'json',
      success: function (response) {
        var leng = response.length;
        if (leng > 0) {
          $(sugestion).show();
          $(sugestion).empty();
          for (var i = 0; i < leng; i++) {
            var label = response[i]['label'];
            var value = response[i]['value'];
            $(sugestion).append("<li class='dropdown-item' value='" + value + "'>" + label + "</li>");
          }
          // binding click event to li
          $(sugestion + " li").bind("click", function () {
            $(sugestion).hide();
            $(sugestion).empty();
            var value = $(this).attr("value");
            if (target == null) {
              $(fieldid).val(value);
            } else {
              $(target).val(value);
              $(target).trigger("click");
              $(target).focus();
            }
          });
        }
      }
    });
  } else {
    $(sugestion).hide();
  }

}

function makeid(id, target) {
  var str = $(id).val();
  var res = str.toLowerCase();
  var val = res.replace(/\s/g, "");
  $(target).val(val);
}

function makeqr(value, target, dir) {
  $.post("" + dir + "phpqrcode/qrprocess.php", { data: value }, function (a) {
    $(target).html(a);
  });
}

function saveimage(value, name) {
  html2canvas($(value)[0]).then(function (canvas) {
    var w = canvas.width * 2;
    var h = canvas.height * 2;
    // save as image
    Canvas2Image.saveAsImage(canvas, w, h, "png", name);
  });
}


//on insert or upload new image for user
function img_upload(input, target) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $(target).attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function handleImageUpload(event) {
  var imageFile = event.target.files[0];
  console.log('originalFile instanceof Blob', imageFile instanceof Blob); // true
  console.log(`originalFile size ${imageFile.size / 1024 / 1024} MB`);
  $("#alert-message").html('<div class="alert alert-warning fixed-center">Please wait, image still compressing.</div>');
  $('[type="submit"]').hide();
  var options = {
    maxSizeMB: 1,
    maxWidthOrHeight: 1920,
    useWebWorker: true
  }
  imageCompression(imageFile, options)
    .then(function (compressedFile) {
      console.log('compressedFile instanceof Blob', compressedFile instanceof Blob); // true
      console.log(`compressedFile size ${compressedFile.size / 1024 / 1024} MB`); // smaller than maxSizeMB
      $("#alert-message").html("");
      return uploadToServer(compressedFile); // write your own logic
    })
    .catch(function (error) {
      console.log(error.message);
    });
}

function uploadToServer(file) {
  var formData = new FormData();
  var filename = $('#imgtmp').val();
  formData.append('imagetemp', file);
  formData.append('filename', filename);
  $.ajax({
    url: window.location.pathname, // point to server-side PHP script 
    cache: false,
    contentType: false,
    processData: false,
    data: formData,
    type: 'post',
    xhr: function () {
      //upload Progress
      var xhr = $.ajaxSettings.xhr();
      if (xhr.upload) {
        $("#alert-message").html('<div class="alert alert-warning fixed-center">Saving the image...</div>');
      }
      return xhr;
    }
  }).done(function () {
    $("#alert-message").html("");
    $('.inputfile').val('');
    $('[type="submit"]').show();
  });
}

function numberformat(number) {
  number += '';
  var x = number.split('.');
  var x1 = x[0];
  var x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}


/**
 * On Keyup field memberid from Form Transaction
 */
$(".submit_form").on("click", function (e) {
  var progress = $('#progress');
  var bar = $('#progressbar');
  var text = $('#progresstext');
  $(progress).removeClass('d-none');
  $(bar).attr("style", "width : 0%");
  $(bar).attr("aria-valuenow", "0");
  $(text).html("0%");
  var data = $(this).attr('data-target');
  var data_reload = $(this).attr('data-reload');
  let form = document.getElementById(data);
  let form_data = new FormData(form); //Encode form elements for submission
  form_data.append($(this).attr('name'), '');
  $.ajax({
    url: window.location.pathname, // point to server-side PHP script 
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    xhr: function () {
      //upload Progress
      var xhr = $.ajaxSettings.xhr();
      if (xhr.upload) {
        xhr.upload.addEventListener('progress', function (event) {
          var percent = 0;
          var position = event.loaded || event.position;
          var total = event.total;
          if (event.lengthComputable) {
            percent = Math.ceil(position / total * 100);
          }
          //update progressbar
          $(bar).attr("style", "width :" + percent + "%");
          $(bar).attr("aria-valuenow", percent);
          $(text).html(percent);
        }, true);
      }
      return xhr;
    }
  }).done(function (response) {
    try {
      var obj = JSON.parse(response);
      obj.forEach(data => {
        if (data.status == "success") {
          $(text).html("Success");
          $("#alert-massage").html('<div class="alert alert-success alert-dismissible fixed-center"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Data already submitted.</div>');
          setTimeout(function () {
            $("#alert-massage").html("");
            $(progress).addClass('d-none');
            $('button[type="reset"]').trigger("click");
            if (data_reload == "true") {
              location.reload();
            }
          }, 3000);
        } else {
          $(text).html("Failed");
          $("#alert-massage").html('<div class="alert alert-warning alert-dismissible fixed-center"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> Data cant process.</div>');
          setTimeout(function () {
            $("#alert-massage").html("");
            $(progress).addClass('d-none');
            if (data_reload == "true") {
              location.reload();
            }
          }, 3000);
        }
      });
    } catch (ex) {
      $(text).html("No Response");
      setTimeout(function () {
        $(progress).addClass('d-none');
        if (data_reload == "true") {
          location.reload();
        }
      }, 3000);
    }
  });

});


(function ($) {
  "use strict"; // Start of use strict
  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // //logout on closed
  // window.onunload = function () {
  //   $.post(window.location.pathname, {
  //     logout: 'logout'
  //   });
  // }

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function () {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    };
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });


  $(document).on('show.bs.modal', '.modal', function () {

    const zIndex = 1040 + 10 * $('.modal:visible').length;

    $(this).css('z-index', zIndex);

    setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
  });

  // Scroll to top button appear
  $(document).on('scroll', function () {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function (e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

  $(document).ready(function () {
    if ($("#btn-login").length) {
      $("#btn-login").trigger("click");
    }
    $('.defaulttable1').DataTable({
      "pageLength": 25,
      "lengthChange": false,
      dom: 'Bfrtip',
      buttons: [
        
        {
          extend: 'excel',
          messageTop: function () {
            if ($(".defaulttable1").attr("data-title") !== null) {
              return $(".defaulttable1").attr("data-title");
            }
            else {
              return '';
            }
          }
        },
        {
          extend: 'pdf',
          messageTop: function () {
            if ($(".defaulttable1").attr("data-title") !== null) {
              return $(".defaulttable1").attr("data-title");
            }
            else {
              return '';
            }
          }
        }
      ],
    });
    $('.defaulttable2').DataTable({
      "pageLength": 25,
      "lengthChange": false,
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'excel',
          messageTop: function () {
            if ($(".defaulttable2").attr("data-title") !== null) {
              return $(".defaulttable2").attr("data-title");
            }
            else {
              return '';
            }
          }
        },
        {
          extend: 'pdf',
          messageTop: function () {
            if ($(".defaulttable2").attr("data-title") !== null) {
              return $(".defaulttable2").attr("data-title");
            }
            else {
              return '';
            }
          }
        }
      ],
    });
    $('.nosorttable').DataTable({
      "pageLength": 50,
      "lengthChange": false,
      "ordering": false,
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'excel',
          messageTop: function () {
            if ($(".nosorttable").attr("data-title") !== null) {
              return $(".nosorttable").attr("data-title");
            }
            else {
              return '';
            }
          }
        },
        {
          extend: 'pdf',
          messageTop: function () {
            if ($(".nosorttable").attr("data-title") !== null) {
              return $(".nosorttable").attr("data-title");
            }
            else {
              return '';
            }
          }
        }
      ],
    });

    //start custom the datatable button
    $(".dt-button").addClass("btn btn-sm");
    $(".buttons-copy").addClass("btn-light");
    $(".buttons-copy").html('<span class="icon text-dark-50"><i class="fa fa-copy fa-sm"></i></span><span class="text"> Copy</span>');
    $(".buttons-excel").addClass("btn-success");
    $(".buttons-excel").html('<span class="icon text-white-50"><i class="fa fa-file-excel fa-sm"></i></span><span class="text"> Excel</span>');
    $(".buttons-pdf").addClass("btn-danger");
    $(".buttons-pdf").html('<span class="icon text-white-50"><i class="fa fa-file-pdf fa-sm"></i></span><span class="text"> PDF</span>');
    $(".buttons-print").addClass("btn-info");
    $(".buttons-print").html('<span class="icon text-white-50"><i class="fa fa-print fa-sm"></i></span><span class="text"> Print</span>');
    //end of custom the datatable button

  });
  // Add the following code if you want the name of the file appear on select
  $(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

})(jQuery);
// End of use strict