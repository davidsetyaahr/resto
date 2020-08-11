$(document).ready(function() {
    $(".datepicker").datepicker({
        format: "yyyy-mm-dd"
    });
    $(".select2").select2();
    $(".fullpage-version").click(function(e) {
        e.preventDefault();
        $("body").toggleClass("fullpage");
        $(".fullpage-version span").toggleClass("fa-chevron-right");
    });
    $("form").submit(function() {
        $(".loading").addClass("show");
    });
    $(".nav-link[data-toggle='collapse']").click(function() {
        if ($(this).hasClass("collapsed")) {
            $(".nav-link[data-toggle='collapse']").addClass("collapsed");
            $(".nav-link[data-toggle='collapse']").attr("aria-expanded", false);

            $(this).removeClass("collapsed");
            $(this).attr("aria-expanded", true);

            var target = $(this).data("target");
            $(".nav-collapse.collapse").removeClass("show");
            $(".nav-collapse.collapse" + target).addClass("collapsing");
        }
        /*         $(".nav-collapse").removeClass('show')
        var target = $(this).data('target')
        $(".nav-collapse"+target).toggleClass('show')
 */
    });
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
        var tanggal = $("#tanggal").val();
        var tipe = $("#tipe").val();
        var url = $(this).data("url");

        $.ajax({
            type: "get",
            url: url,
            data: { tanggal: tanggal, tipe: tipe },
            success: function(data) {
                $("#kode").val(data);
            }
        });
    });

    function barang(thisParam) {
        var kode = thisParam.val();
        var no = thisParam.closest(".row-detail").data("no");
        var parent = ".row-detail[data-no='" + no + "']";
        var url = thisParam.data("url");
        $.ajax({
            type: "get",
            url: url,
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

    $("#no_kartu").prop('disabled',true);
    $("#charge").prop('disabled',true);

    temp_grand_total = parseInt($('#grand_total').val());
    function getCharge(thisVal) {
        var grand_total = temp_grand_total;
        var charge = 0;
        if (thisVal != 'Tunai') {
            $("#no_kartu").prop('disabled',false);
            $("#no_kartu").attr("required", true);

            $("#charge").prop('disabled',false);
            $("#charge").attr("required", true);
        }
        else {
            $("#no_kartu").prop('disabled',true);
            $("#charge").prop('disabled',true);
            $('#charge').val(0);
        }

        if (thisVal == "Debit BCA") {
            charge = grand_total * 1 / 100; 
        }
        else if(thisVal == 'Debit BRI'){
            charge = grand_total * 0.15 / 100; 
        }
        else if(thisVal == 'Kredit BCA'){
            charge = grand_total * 1.80 / 100; 
        }
        else if(thisVal == 'Kredit BRI'){
            charge = grand_total * 1.50 / 100; 
        }
        $('#charge').val(Math.round(charge));
        $('#grand_total').val(grand_total + Math.round(charge));
        $('#idrGrandTotal').html(formatRupiah(grand_total + Math.round(charge)));
    }

    $("#jenis_bayar").change(function() {
        var thisVal = $(this).val();
        getCharge(thisVal);
    });
    
    $("#jenis_order").change(function(){
        var thisVal = $(this).val()
        if (thisVal == 'Room Order') {
            $("#nomor_kamar").prop('disabled',false);
            $("#nomor_kamar").attr("required", true);
        }
        else{
            $("#nomor_kamar").prop('disabled',true);
            $("#nomor_kamar").attr("required", false);
        }
    })
    $("#jenis_order").change(function() {
        var thisVal = $(this).val();
        if (thisVal == "Room Order") {
            $("#nomor_kamar").prop('disabled',false);
            $("#nomor_kamar").attr("required", true);
        } else {
            $("#nomor_kamar").prop('disabled',true);
        }
    });

    $(".diskon_tambahan").keyup(function() {
        var diskon_tambahan = parseInt($(this).val());
        var tipe = $(this).data('tipe')
        var total = parseInt($("#total").val());
        var diskon = 0;
        if(tipe=='persen'){
            var otherDisc = parseInt($(".diskon_tambahan[data-tipe='rp']").val())
            diskon = (diskon_tambahan * total / 100) + otherDisc;
        }
        else{
            var getOtherDisc = parseInt($(".diskon_tambahan[data-tipe='persen']").val())
            var otherDisc = 0;
            if(getOtherDisc>0){
                otherDisc = getOtherDisc * total / 100;
            }
            diskon = diskon_tambahan + otherDisc
        }
        var grand_total = total - diskon;
        $("#grand_total").val(grand_total);
        $("#idrGrandTotal").html(formatRupiah(grand_total));
    });

    $("#bayar").keyup(function() {
        var terbayar = parseInt($(this).val());
        var grand_total = parseInt($("#grand_total").val());
        var kembalian = terbayar - grand_total;
        $("#kembalian").val(kembalian);
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
        totalPpn = (total * 10) / 100;
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
        var tipediskon = $("#jenis_diskon").val();
        var diskon = parseInt($("#diskon").val());

        $.ajax({
            type: "get",
            url: "get-detail-menu",
            data: { kode: kode },
            success: function(data) {
                var nominaldiskon = 0;
                var harga = parseInt($.parseJSON(data)["harga_jual"]);
                $(parent + " #harga_jual").val($.parseJSON(data)["harga_jual"]);
                if (tipediskon == "Persen") {
                    nominaldiskon = (harga * diskon) / 100;
                    $(parent + " #harga_setelah_diskon").val(
                        $.parseJSON(data)["harga_jual"] - nominaldiskon
                    );
                } else if (tipediskon == "Rupiah") {
                    $(parent + " #harga_setelah_diskon").val(
                        $.parseJSON(data)["harga_jual"] - diskon
                    );
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
    $("#formFilterMenu").submit(function(e) {
        e.preventDefault();
        var idKategori = $("#idKategori").val();
        var key = $("#key").val();
        var url = $(this).attr('data-url')

        $.ajax({
            type: "get",
            data: { idKategori: idKategori, key: key },
            url: url,
            beforeSend: function() {
                $(".loading").addClass("show");
            },
            success: function(data) {
                $(".loading").removeClass("show");
                $(".row-menu .col-md-3").remove();
                $(".row-menu").append(data);
                $(".keranjang .tbodyLoop .tr").each(function() {
                    var no = $(this).attr("data-tr");
                    var kodeMenu = $(
                        ".keranjang .tbodyLoop .tr[data-tr='" +
                            no +
                            "'] .inputKodeMenu"
                    ).val();
                    $(".menu[data-menu='" + kodeMenu + "']").attr(
                        "data-pick",
                        "true"
                    );
                });
                $(".menu").click(function() {
                    pickMenu($(this));
                });
            }
        });
    });
    function pickMenu(thisParam) {
        var kode = thisParam.data("menu");
        var picked = thisParam.attr("data-pick");
        var url = $(".row-menu").attr("data-urlDiskon");
        if (picked == "false") {
            $.ajax({
                type: "get",
                url: url,
                data: { kode: kode },
                beforeSend: function() {
                    $(".loading").addClass("show");
                },
                success: function(data) {
                    $(".loading").removeClass("show");
                    var selector = ".menu[data-menu='" + kode + "']";
                    $(selector).attr("data-pick", "true");
                    var namaMenu = $(selector + " .info h4").html();
                    var hargaIDR = $(selector + " .info h5").data("harga");
                    var diskon = formatRupiah(data);
                    var subtotal = parseInt(hargaIDR);
                    var no = $(".keranjang .tbodyLoop").attr("data-no");
                    var newNo = parseInt(no) + 1;
                    $(".keranjang .tbodyLoop").attr("data-no", newNo);
                    $(".keranjang .tbodyLoop").append(`
                    <tr data-tr='${newNo}' class='tr'>
                        <td colspan='6' class='p-0'>
                            <table width='100%'>
                                <tr>
                                    <td width='10%' class='no'>${newNo}</td>
                                    <td width='25%'>
                                    <input type='hidden' name='kode_menu[]' class='inputKodeMenu' value='${kode}'> 
                                    <input type='hidden' name='nama_menu[]' class='inputNamaMenu' value='${namaMenu}'> 
                                    ${namaMenu}</td>
                                    <td width='25%' class='px-0'>
                                        <input type="hidden" name="harga[]" value="${hargaIDR}" class="inputHarga">
                                        <div class="change-qty">
                                            <button class='btnqty' data-tipe='min'>-</button>
                                            <input type='text' name='qty[]' value='1' class='form-control text-center inputQty' readonly>
                                            <button class='btnqty' data-tipe='plus'>+</button>
                                        </div>
                                    </td>
                                    <td width='15%' class='tdDiskon'><input type='hidden' class='inputDiskon' name='diskon[]' value='${data}'> ${diskon}</td>
                                    <td width='15%' class='tdSubtotal'><input type='hidden' name='subtotal[]' value='${subtotal}' class='inputSubtotal'> <span> ${formatRupiah(
                        subtotal
                    )}</span></td>
                                    <td width='10%'><a href='' title="Hapus" class='deleteCart'><span class='fa fa-trash fa-lg'></span></a></td>
                                </tr>
                                <tr>
                                    <td colspan='6' class='p-0'>
                                        <input type='text' class='form-control form-line' name='keterangan[]' placeholder="Keterangan">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    `);
                    getTfoot();
                    $(
                        ".keranjang .tbodyLoop .tr[data-tr='" +
                            newNo +
                            "'] .deleteCart"
                    ).click(function(e) {
                        e.preventDefault();
                        deleteCart($(this));
                    });
                    $(
                        ".keranjang .tbodyLoop .tr[data-tr='" +
                            newNo +
                            "'] .change-qty .btnqty"
                    ).click(function(e) {
                        e.preventDefault();
                        changeQty($(this));
                    });
                }
            });
        }
    }
    $(".keranjang .tbodyLoop .tr .change-qty .btnqty").click(function(e) {
        e.preventDefault();
        changeQty($(this));
    });

    function changeQty(thisParam) {
        var no = thisParam.closest(".tr").attr("data-tr");
        var tipe = thisParam.data("tipe");
        var input = ".keranjang .tbodyLoop .tr[data-tr='" + no + "'] .inputQty";
        var qty = parseInt($(input).val());
        var diskon = parseInt($(".keranjang .tbodyLoop .tr[data-tr='" + no + "'] .inputDiskon").val());
        var harga = parseInt(
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" + no + "'] .inputHarga"
            ).val()
        );
        var update = true;
        if (tipe == "min") {
            if (qty > 1) {
                newQty = qty - 1;
                $(input).val(newQty);
            } else {
                update = false;
            }
        } else {
            newQty = qty + 1;
            $(input).val(newQty);
        }
        if (update) {
            var subtotal = harga * newQty;
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" +
                    no +
                    "'] .tdSubtotal .inputSubtotal"
            ).val(subtotal);
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" +
                    no +
                    "'] .tdSubtotal span"
            ).html(formatRupiah(subtotal));
            getTfoot();
        }
    }
    $(".keranjang .tbodyLoop .tr .deleteCart").click(function(e) {
        e.preventDefault();
        deleteCart($(this));
    });
    function deleteCart(thisParam) {
        if (confirm("Apakah anda yakin?")) {
            var no = thisParam.closest(".tr").attr("data-tr");
            var selector = ".keranjang .tbodyLoop .tr[data-tr='" + no + "']";

            var kodeMenu = $(selector + " .inputKodeMenu").val();
            $(".menu[data-menu='" + kodeMenu + "']").attr("data-pick", "false");

            if ($(".box-penjualan form").hasClass("formEdit")) {
                if ($(selector + " .idDetail").length == 1) {
                    var idDetail = $(selector + " .idDetail").val();
                    $(".formEdit").prepend(
                        `<input type='hidden' name='id_delete[]' value='${idDetail}'>`
                    );
                }
            }

            $(selector).remove();
            var numbOfNo = parseInt($(".keranjang .tbodyLoop").attr("data-no"));
            var newNumbOfNo = numbOfNo - 1;
            $(".keranjang .tbodyLoop").attr("data-no", newNumbOfNo);
            var updateNo = 0;
            $(".keranjang .tbodyLoop .tr").each(function(key, val) {
                updateNo++;
                $(this).attr("data-tr", updateNo);
                $(
                    ".keranjang .tbodyLoop tr[data-tr='" + updateNo + "'] .no"
                ).html(updateNo);
            });
            getTfoot();
        }
    }
    $(".menu").click(function() {
        pickMenu($(this));
    });
    function getTfoot() {
        var no = parseInt($(".keranjang .tbodyLoop").attr("data-no"));
        var qty = 0;
        var diskon = 0;
        var subtotal = 0;
        var selector = ".keranjang .tbodyLoop";
        for (let i = 1; i <= no; i++) {
            qty =
                qty +
                parseInt(
                    $(selector + "  .tr[data-tr='" + i + "'] .inputQty").val()
                );
            diskon =
                diskon +
                parseInt(
                    $(
                        selector +
                            "  .tr[data-tr='" +
                            i +
                            "'] .tdDiskon .inputDiskon"
                    ).val()
                );
            subtotal =
                subtotal +
                parseInt(
                    $(
                        selector +
                            "  .tr[data-tr='" +
                            i +
                            "'] .tdSubtotal .inputSubtotal"
                    ).val()
                );
        }
        var total = subtotal - diskon;

        $("#tfootQty").html(qty);
        $("#tfootDiskon").html(formatRupiah(diskon));
        $("#tfootSubtotal").html(formatRupiah(subtotal));
        $("#total").html(formatRupiah(total));
    }
    $(".toggle-cart,.body-click").click(function() {
        $(".box-penjualan").toggleClass('show')
        $(".body-click").toggleClass('show')
    })
    
    $(document).on('click', '.paging-menu a',function(event)
    {
        event.preventDefault();
        $(".loading").addClass("show");

        $('li').removeClass('active');
        $(this).parent('li').addClass('active');

        var myurl = $(this).attr('href');
        var page=$(this).attr('href').split('page=')[1];
        
        const kodeMenu = [];
        $(".tbodyLoop .inputKodeMenu").each(function(){
            kodeMenu.push($(this).val())
        })
        console.log(kodeMenu)


        $.ajax(
            {
                url: '?page=' + page,
                type: "get",
                datatype: "html",
                success : function(data){
                    $("#tag_container").empty().html(data);
                    $(".tbodyLoop .inputKodeMenu").each(function(){
                        $(".menu[data-menu='"+$(this).val()+"']").attr('data-pick','true')
                    })
            
                    $(".menu").click(function() {
                        pickMenu($(this));
                    });
                                
                    $(".loading").removeClass("show");
    
                }
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                  alert('No response from server');
            });
        });
  
});
