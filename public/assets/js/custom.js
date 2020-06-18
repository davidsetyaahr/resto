$(document).ready(function() {
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"
    });
    $(".select2").select2();
    $("form").submit(function() {
        $(".loading").addClass("show");
    });
    $(".nav-link[data-toggle='collapse']").click(function(){
        if($(this).hasClass('collapsed')){
            $(".nav-link[data-toggle='collapse']").addClass('collapsed')
            $(".nav-link[data-toggle='collapse']").attr('aria-expanded',false)

            $(this).removeClass('collapsed')
            $(this).attr('aria-expanded',true)
        
            var target = $(this).data('target')
            $('.nav-collapse.collapse').removeClass('show')
            $('.nav-collapse.collapse'+target).addClass('collapsing')
        }
/*         $(".nav-collapse").removeClass('show')
        var target = $(this).data('target')
        $(".nav-collapse"+target).toggleClass('show')
 */    })
    function formatRupiah(angka) {
        var number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        return rupiah;
    }

    function addDetail(thisParam) {
        var biggestNo = 0; //setting awal No/Id terbesar
        $(".row-detail").each(function() {
            var currentNo = parseInt($(this).attr("data-no"));
            if (currentNo > biggestNo) {
                biggestNo = currentNo;
            }
        }); //mencari No teresar

        var next = parseInt(biggestNo) + 1; // No selanjutnya ketika ditambah field baru
        var thisNo = thisParam.data("no"); // No pada a href
        var url = $("#urlAddDetail").data("url");
        $.ajax({
            type: "get",
            url: url,
            data: { biggestNo: biggestNo },
            beforeSend: function() {
                $(".loading").addClass("show");
            },
            success: function(response) {
                $(".loading").removeClass("show");
                $(".row-detail[data-no='" + thisNo + "']").after(response);
                $(".select2").select2();

                $(".addDetail[data-no='" + next + "']").click(function(e) {
                    e.preventDefault();
                    addDetail($(this));
                });

                $(".deleteDetail").click(function(e) {
                    e.preventDefault();
                    deleteDetail($(this));
                });
                $(".getSubtotal").keyup(function() {
                    getSubtotal($(this));
                });

                $(".barang").change(function() {
                    barang($(this));
                });

                $(".totalQty").keyup(function() {
                    getTotalQty($(this));
                });

                $(".menu").change(function() {
                    getDetailMenu($(this));
                });

                $(".menu2").change(function() {
                    pjGetDetailMenu($(this));
                    pjGetDiskon($(this));
                });

                $(".qtyPj").change(function() {
                    getSubtotalPj($(this));
                });
            }
        });
    }
    $(".addDetail").click(function(e) {
        e.preventDefault();
        addDetail($(this));
    });
    function deleteDetail(thisParam) {
        var delNo = thisParam.data("no");
        var parent = ".row-detail[data-no='" + delNo + "']";
        var idDetail = $(parent + " .idDetail").val();
        if (thisParam.hasClass("addDeleteId") && idDetail != 0) {
            $(".idDelete").append(
                "<input type='hidden' name='id_delete[]' value='" +
                    idDetail +
                    "'>"
            );
        }
        $(parent).remove();
        getTotal();
        getTotalQty(thisParam);
    }
    $(".deleteDetail").click(function(e) {
        e.preventDefault();
        deleteDetail($(this));
    });

    function getSubtotal(thisParam) {
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";

        var thisval = parseInt(thisParam.val());
        var other = parseInt($(parent + " " + thisParam.data("other")).val());
        other = isNaN(other) ? 0 : other;
        var subtotal = thisval * other;
        $(parent + " " + ".subtotal").val(subtotal);
        getTotal();
    }
    function getTotal() {
        var total = 0;
        $(".subtotal").each(function() {
            var subtotalVal = parseInt($(this).val());
            subtotalVal = isNaN(subtotalVal) ? 0 : subtotalVal;
            total = total + subtotalVal;
        });
        $("#total").html(formatRupiah(total));
    }
    $(".getSubtotal").keyup(function() {
        getSubtotal($(this));
    });

    $(".getKode").change(function() {
        var tanggal = $(this).val();
        var url = $(this).data("url");

        $.ajax({
            type: "get",
            url: url,
            data: { tanggal: tanggal },
            success: function(data) {
                $("#kode").val(data);
            }
        });
    });
    
    $(".getKodeKas").change(function() {
        var tanggal = $('#tanggal').val();
        var tipe = $('#tipe').val();
        var url = $(this).data("url");

        $.ajax({
            type: "get",
            url: url,
            data: { tanggal: tanggal, tipe : tipe },
            success: function(data) {
                $("#kode").val(data);
            }
        });
    });
    
    function barang(thisParam) {
        var kode = thisParam.val();
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";

        $.ajax({
            type: "get",
            url: "get-detail-barang",
            data: { kode: kode },
            success: function(data) {
                $(parent + " #sisa_stock").val($.parseJSON(data)["stock"]);
                $(parent + " #satuan").val($.parseJSON(data)["satuan"]);
            }
        });
    }
    $(".barang").change(function() {
        barang($(this));
    });

    $("#debit_only").hide();
    $("#jenis_bayar").change(function() {
        var thisVal = $(this).val();
        if (thisVal == "Debit") {
            $("#debit_only").show();
            $('#no_kartu').attr('required', true);
        } else {
            $("#debit_only").hide();
        }
    });

    $('#diskon_tambahan').keyup(function () { 
        var diskon_tambahan = parseInt($(this).val());
        var total = parseInt($('#total').val());
        var grand_total = total - diskon_tambahan;
        $('#grand_total').val(grand_total);
    });
    
    $('#bayar').keyup(function () { 
        var terbayar = parseInt($(this).val());
        var grand_total = parseInt($('#grand_total').val());
        var kembalian = terbayar - grand_total;
        $('#kembalian').val(kembalian);
    });

    function pjGetDetailMenu(thisParam) {
        var kode = thisParam.val();
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";

        $.ajax({
            type: "get",
            url: "get-detail-menu",
            data: { kode: kode },
            success: function(data) {
                $(parent + " #harga").val($.parseJSON(data)["harga_jual"]);
            }
        });
    }
    function pjGetDiskon(thisParam) {
        var kode = thisParam.val();
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";

        $.ajax({
            type: "get",
            url: "get-diskon",
            data: { kode: kode },
            success: function(data) {
                $(parent + " #diskon").val(data);
                $(parent + " #diskon_satuan").val(data);
            }
        });
    }
    $(".menu2").change(function() {
        pjGetDetailMenu($(this));
        pjGetDiskon($(this));
    });

    function getSubtotalPj(thisParam) {
        var qty = parseInt(thisParam.val());
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";
        var harga = parseInt($(parent + " #harga").val()) * qty;
        var diskon = parseInt($(parent + " #diskon_satuan").val()) * qty;

        // $(parent + " #harga").val(harga);
        $(parent + " #diskon").val(diskon);
        $(parent + " #subtotal").val(harga - diskon);
        getTotalPj();
    }

    function getTotalPj() {
        var total = 0;
        var totalPpn = 0;
        $(".getTotalPj").each(function() {
            var subtotalVal = parseInt($(this).val());
            subtotalVal = isNaN(subtotalVal) ? 0 : subtotalVal;
            total = total + subtotalVal;
        });
        totalPpn = total * 10 / 100;
        $("#total_harga").html(formatRupiah(total));
        $("#total_ppn").html(formatRupiah(totalPpn));
        $("#grand_total").html(formatRupiah(total + totalPpn));
    }

    $(".qtyPj").change(function() {
        getSubtotalPj($(this));
    });

    function getDetailMenu(thisParam) {
        var kode = thisParam.val();
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";
        var tipediskon = $('#jenis_diskon').val();
        var diskon = parseInt($('#diskon').val());

        $.ajax({
            type: "get",
            url: "get-detail-menu",
            data: { kode: kode },
            success: function(data) {
                var nominaldiskon = 0;
                var harga = parseInt($.parseJSON(data)["harga_jual"]);
                $(parent + " #harga_jual").val($.parseJSON(data)["harga_jual"]);
                if (tipediskon == 'Persen') {
                    nominaldiskon = harga * diskon / 100;
                    $(parent + " #harga_setelah_diskon").val($.parseJSON(data)["harga_jual"] - nominaldiskon);
                }
                else if(tipediskon == 'Rupiah'){
                    $(parent + " #harga_setelah_diskon").val($.parseJSON(data)["harga_jual"] - diskon)
                }
            }
        });
    }
    $(".menu").change(function() {
        getDetailMenu($(this));
    });

    // var totalQty = 0;
    function getTotalQty(thisParam) {
        // var qty = parseInt(thisParam.val());
        // totalQty += qty;
        // $("#totalQty").html(formatRupiah(totalQty));
        var total = 0;
        $(".totalQty").each(function() {
            var subtotalVal = parseInt($(this).val());
            total = total + subtotalVal;
        });
        $("#totalQty").html(formatRupiah(total));
    }
    $(".totalQty").keyup(function() {
        getTotalQty($(this));
    });
});
