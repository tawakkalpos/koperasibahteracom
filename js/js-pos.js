
let listtransaction = [];
var grandtotal = 0;
var i = 0;


/**
 * update list transaction
 */
function listtbtransaction() {
    var value = "";
    grandtotal = 0;
    for (var x = 0; x < listtransaction.length; x++) {
        var status = listtransaction[x]['status'];
        if (status == "success") {
            value += "<tr style='text-align:center'>";
            value += "<td>" + listtransaction[x]['code'] + "</td>";
            value += "<td>" + listtransaction[x]['name'] + "</td>";
            value += "<td>" + numberformat(listtransaction[x]['price']) + "</td>";
            value += "<td>" + listtransaction[x]['qty'] + "</td>";
            value += "<td>" + numberformat(listtransaction[x]['total']) + "</td>";
            value += "<td><i onclick='cancel_item(" + x + ")' class='fas fa-trash fa-xs text-danger' style='cursor:pointer'></i></td>";
            value += "</tr>";
            grandtotal = grandtotal + parseInt(listtransaction[x]['total']);
        }
    }
    $("#list-transaction").html(value);
    $("#total_price").html(numberformat(grandtotal));
    var totalpayment = $("#tr_payment").val().length > 0 ? parseInt($("#tr_payment").val()) : 0;
    var moneychanges = totalpayment - grandtotal;
    $("#total_payment").html(numberformat(totalpayment));
    $("#money_changes").html(numberformat(moneychanges));
    
}



function checkliststatus(code) {
    if (listcanceled.length > 0) {
        for (var y = 0; y < listcanceled.length; y++) {
            if (listcanceled[y] == code) {

            }

        }
    } else {
        return true;
    }
}

function cancel_item(id) {
    listtransaction[id] = { status: "canceled" };
    listtbtransaction();

}

// JavaScript Document
function datatabel() {
    $('#in_tabel').DataTable({
        dom: 'Bfrtip',
        button: ['excel', 'pdf'],
        select: true,
        customize: function (doc) {
            doc.styles['table'] = {
                width: '100%'
            }
        }
    });
    $('#in_tabel_wrapper .dt-buttons .buttons-copy').remove();
    $('#in_tabel_wrapper .dt-buttons .buttons-csv').remove();
    $('#in_tabel_wrapper .dt-buttons .buttons-print').remove();
    $('#in_tabel_wrapper').addClass("row mt-2");
    $('.dt-buttons').addClass("col col-md-2");
    $('.dt-buttons .btn').addClass("btn-sm");
    $('#in_tabel_filter').addClass("col col-md-10");
    $('#in_tabel_info').addClass("hidden");
    $('#ba_tabel').DataTable({
        retrieve: true,
        "pageLength": 3,
        "order": [
            [4, "desc"]
        ]
    });
    $('#ba_tabel_length').remove();
    $('#ba_tabel_info').addClass("hidden");
}


function resetformtransaction() {
    $("#tr_code").val("");
    $("#tr_name").val("");
    $("#tr_qty").val("1");
    $("#tr_memberid").val("");
    $("#tr_membername").val("");
    $("#tr_payment_reference").val("");
    $("#tr_payment").val("");
    $('input[name="tr_member"][value="Non Member"]').attr('checked', true);
    $('input[name="tr_payment_method"][value="Cash"]').attr('checked', true);

    listtransaction = [];
    grandtotal = 0;
    i = 0
    listtbtransaction();
}
function tr_print(id) {
    $.post(window.location.pathname, {
        post_modal: 'print_transaction', value: id
    }, function (result) {
        $("#printarea").html(result);
    });
}

$(document).ready(function () {
    if ($("#myMonthlyChart").length) {
        var target = document.getElementById("myMonthlyChart");
        let datachart = [];
        let datalabel = [];
        $.post(window.location.pathname, {
            post_modal: 'getMonthlyTransaction',
        }, function (result) {
            var obj = JSON.parse(result);
            var i = 0;
            obj.forEach(data => {
                datachart[i] = data.value;
                datalabel[i] = data.label;
                i++;
            });
            getLineChart(datalabel, datachart, "Total Sales", target);
        });
    }
    if ($("#myDailyChart").length) {
        var target2 = document.getElementById("myDailyChart");
        let datachart2 = [];
        let datalabel2 = [];
        $.post(window.location.pathname, {
            post_modal: 'getDailyTransaction',
        }, function (result) {
            var obj = JSON.parse(result);
            var i = 0;
            obj.forEach(data => {
                datachart2[i] = data.value;
                datalabel2[i] = data.label;
                i++;
            });
            getLineChart(datalabel2, datachart2, "Total Sales", target2);
        });
    }
  
});

/**
 * On Keyup field Code from Form Transaction
 */
$("#tr_code").on("keyup", function (e) {
    if (e.key === 'Enter' || e.key === 'Tab') {
    if ($("#tr_qty").val() == "") {
        alert("Qty Empty!")
    } else {
        var checkid = true;
        for (var x = 0; x < listtransaction.length; x++) {
            var status = listtransaction[x]['status'];
            if (status == "success") {
                var code = listtransaction[x]['code'];
                if (code == $.trim($("#tr_code").val())) {
                    var name = listtransaction[x]['name'];
                    var price = listtransaction[x]['price'];
                    var qty = parseInt(listtransaction[x]['qty']) + parseInt($("#tr_qty").val());
                    var total = parseInt(qty) * listtransaction[x]['price'];
                    $("#tr_name").val(name);
                    $("#tr_price").val(price);

                    const addlist = { status: "success", code: code, name: name, qty: qty, price: price, total: total };
                    listtransaction[x] = addlist;
                    $("#tr_code").focus();
                    if ($("#tr_code").val().length > 0) {
                        document.getElementById('tr_code').setSelectionRange(0, $("#tr_code").val().length);
                    }
                    listtbtransaction();
                    checkid = false;
                }
            }
        }

        if (checkid) {
            $.ajax({
                url: window.location.pathname,
                type: 'post',
                data: { post_modal: 'getdata_product_bycode', value: $("#tr_code").val() },
                dataType: 'json',
                success: function (response) {

                    var leng = response.length;
                    if (leng > 0) {
                        var status = response[0]['status'];
                        if (status == "success") {
                            var code = response[0]['code'];
                            var name = response[0]['name'];
                            var stock = response[0]['stock'];
                            var price = response[0]['price'];
                            var qty = $("#tr_qty").val();
                            var total = parseInt(qty) * parseInt(price);
                            if(stock < parseInt(qty)){
                                alert("Product Stock:" + stock );
                            }
                            $("#tr_name").val(name);
                            // $("#tr_price").val(price);

                            const addlist = { status: "success", code: code, name: name, qty: qty, price: price, total: total };
                            listtransaction[i] = addlist;
                            $("#tr_code").focus();
                            if ($("#tr_code").val().length > 0) {
                                document.getElementById('tr_code').setSelectionRange(0, $("#tr_code").val().length);
                            }
                            listtbtransaction();
                            i++;
                        }

                    }
                }
            });
        }
        
    }
}
});

$("#tr_code").on("click", function (e) {
    this.setSelectionRange(0, this.value.length);
});
$("#tr_name").on("click", function (e) {
    this.setSelectionRange(0, this.value.length);
});
$("#tr_qty").on("change", function (e) {
    $("#tr_code").focus();
    if ($("#tr_code").val().length > 0) {
        document.getElementById('tr_code').setSelectionRange(0, $("#tr_code").val().length);
    }
});


/**
 * On Keyup field memberid from Form Transaction
 */
$("#tr_memberid").on("keyup", function (e) {
    $.ajax({
        url: window.location.pathname,
        type: 'post',
        data: { post_modal: 'getdata_member_byid', value: $("#tr_memberid").val() },
        dataType: 'json',
        success: function (response) {
            var leng = response.length;
            if (leng > 0) {
                var status = response[0]['status'];
                if (status == "success") {
                    var id = response[0]['id'];
                    var name = response[0]['name'];
                    $("#tr_membername").val(name);
                }

            }
        }
    });

});

/**
 * On Keyup field memberid from Form Transaction
 */
$("#tr_submit").on("click", function (e) {
    let form = document.getElementById('form_transaction');
    let form_data = new FormData(form); //Encode form elements for submission
    for (var x = 0; x < listtransaction.length; x++) {
        var status = listtransaction[x]['status'];
        if (status == "success") {
            var code = listtransaction[x]['code'];
            var name = listtransaction[x]['name'];
            var price = listtransaction[x]['price'];
            var qty = parseInt(listtransaction[x]['qty']);
            var total = parseInt(qty) * listtransaction[x]['price'];
            form_data.append("code[]", code);
            form_data.append("name[]", name);
            form_data.append("price[]", price);
            form_data.append("qty[]", qty);
            form_data.append("total[]", total);
        }
    }

    var totalpayment = $("#tr_payment").val();
    var moneychanges = totalpayment - grandtotal;

    form_data.append("grandtotal", grandtotal);
    form_data.append("moneychanges", moneychanges);
    form_data.append("tr_submit", "");
    var memberstatus = true;
    if($("#tr_member2").is(':checked') && ($("#tr_memberid").val() == "" || $("#tr_membername").val() == "")){
        memberstatus = false;
    }
    
    
    if(!memberstatus){
        alert("Member Id need to fill!")        
    }else if (moneychanges < 0) {
        alert("Payment less than Total!")
    } else {

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
                    //do something
                }
                return xhr;
            }
        }).done(function (response) {
            var obj = JSON.parse(response);
            obj.forEach(data => {
                if (data.status == "success") {
                    resetformtransaction();
                    $("#alert-massage").html('<div class="alert alert-success alert-dismissible fixed-center"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Success!</strong> Transaction Recorded with ID #' + data.transactionid + '.</div>');
                    $("#tr_print").removeClass("d-none");
                    $("#tr_print").attr("data-transaction", data.transactionid);
                    $("#tr_print").trigger("click");
                }
            });
        });
    }

});

/**
 * On Keyup field name from Form Transaction
 */
$("#tr_name").on("keyup", function (e) {
    autofill("#tr_name", "#name_result", "getProductName", "#tr_code");
});


/**
 * On Keyup field member name from Form Transaction
 */
$("#tr_membername").on("keyup", function (e) {
    autofill("#tr_membername", "#membername_result", "getMemberName", "#tr_memberid");
});


/**
 * On Keyup field name from Form Transaction
 */
$("#tr_payment").on("keyup", function (e) {
    listtbtransaction();
    if (e.key === 'Enter') {
        $("#tr_submit").trigger('click');
    }
});

/**
 * On Keyup field name from Form Transaction
 */
$(".tr_print").on("click", function (e) {
    var id = $(this).attr("data-transaction");
    $.post(window.location.pathname, {
        post_modal: 'print_transaction', value: id
    }, function (result) {
        $("#printarea").html(result);
    });
});
/**
 * On Keyup field name from Form Transaction
 */
$("#tr_payment_method2").on("click", function (e) {
    if($(this).is(':checked')) {
        $("#tr_payment").val(grandtotal);
        $("#tr_payment").trigger("keyup");
    }else{
        $("#tr_payment").val("");
        $("#tr_payment").trigger("keyup");
    }
});

/**
 * On Keyup field memberid from Form Register Member
 */
$("#mb_id").on("keyup", function (e) {
    $.ajax({
        url: window.location.pathname,
        type: 'post',
        data: { post_modal: 'getdata_member_byid', value: $(this).val() },
        dataType: 'json',
        success: function (response) {
            var leng = response.length;
            if (leng > 0) {
                var status = response[0]['status'];
                if (status == "success") {
                    var id = response[0]['id'];
                    var name = response[0]['name'];
                    var gender = response[0]['gender'];
                    var status_member = response[0]['status_member'];
                    $("#mb_name").val(name);
                    $('input[name="mb_gender"][value="' + gender + '"]').attr('checked', true);
                    $('input[name="mb_status"][value="' + status_member + '"]').attr('checked', true);
                } else {
                    $("#mb_name").val("");
                    $('input[name="mb_gender"][value="L"]').attr('checked', true);
                    $('input[name="mb_status"][value="Aktif"]').attr('checked', true);
                }

            }
        }
    });

});

$(".edit_member").on("click", function (e) {
    var code = $(this).attr("data-member");
    $("#mb_id").val(code);
    $("#mb_id").trigger("keyup");

});

$(".delete_member").on("click", function (e) {
    var code = $(this).attr("data-member");
    button_delete("delete_member",code)
});
/**
 * On Keyup field memberid from Form Register Member
 */
$("#pr_barcode").on("keyup", function (e) {
    $.ajax({
        url: window.location.pathname,
        type: 'post',
        data: { post_modal: 'getdata_product_bycode', value: $(this).val() },
        dataType: 'json',
        success: function (response) {
            var leng = response.length;
            if (leng > 0) {
                var status = response[0]['status'];
                if (status == "success") {
                    $("#pr_name").val(response[0]['name']);
                    $("#pr_category").val(response[0]['category']);
                    $("#pr_purchase").val(response[0]['purchase']);
                    $("#pr_sales").val(response[0]['price']);
                    $("#imgtmp").val(response[0]['code'] + ".jpg");
                    $("#product_img").attr('src', response[0]['imgurl']);
                    $("#pr_qty").focus();
                }

            }
        }
    });

});

$("#pr_barcode").on("click", function (e) {
    this.setSelectionRange(0, this.value.length);
});

 $("#pr_category").on("keyup", function (e) {
    autofill("#pr_category", "#category_result", "getCategory");
});

/**
 * On change field name from Form Register Product
 */
$("#pr_photo").on("change", function (e) {
    img_upload(this, '#product_img');
});

/**
 * On Input field name from Form Register Product
 */
$("#pr_photo").on("input", function (e) {
    handleImageUpload(e);
});

$(".edit_product").on("click", function (e) {
    var code = $(this).attr("data-product");
    $("#pr_barcode").val(code);
    $("#pr_barcode").trigger("keyup");
});

$(".delete_product").on("click", function (e) {
    var code = $(this).attr("data-product");
    button_delete("delete_product",code)
});

$(".rp_transaction").on("click", function (e) {
    var value = $(this).attr("data-date");
    desmodal("Transaction on " + value, value, "dailytransaction")
});

$(".rp_member").on("click", function (e) {
    var date = $(this).attr("data-date");
    var dateend = $(this).attr("data-dateend");
    var id = $(this).attr("data-member");
    desmodal("Member Payment from " + date + " to " + dateend, date + ";" + dateend + ";" + id, "dailytransactionmember")
});

$(".nav-report").on("click", function (e) {
    var value = $(this).attr("data-session");
    $("#session").val(value);
});

$(".confirm_password").on("keyup", function (e) {
    if($(this).val() != $(".password").val()){
        $("#check_confirmpassword").html("Password Don't Match!");
        $(".btn_submit").hide();
    }else{
        $("#check_confirmpassword").html("");
        $(".btn_submit").show();
    }
});

$(".showpassword").on("click", function (e) {
    var target = $(this).attr("data-target");
   if($(target).attr("type") == "password"){
        $(target).attr("type","text");
    }else{
       $(target).attr("type","password");
   }
});

function closenote(){
    if($("#tr_code").length){
        $("#tr_code").focus();
    }
}





