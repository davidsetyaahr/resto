$(document).ready(function() {
    var setValueOption = false;

    $(".datepicker").prop("autocomplete", "off");
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
        if (tipe !== "" && tanggal !== "") {
            $.ajax({
                type: "get",
                url: url,
                data: { tanggal: tanggal, tipe: tipe },
                success: function(data) {
                    $("#kode").val(data);
                }
            });
        }
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

    $("#no_kartu").prop("disabled", true);
    $("#charge").prop("disabled", true);
    $("#no_kartu").keyup(function() {
        if ($(this).closest(".split-bill").length == 1) {
            var thisVal = $(this).val();
            var activeKey = $(".user-bill.active").attr("data-key");
            $(
                ".guest-pembayaran[data-key='" + activeKey + "'] .pembNoKartu"
            ).val(thisVal);
        }
    });

    $("#jenis_bayar").change(function() {
        var thisVal = $(this).val();
        getCharge(thisVal);
    });

    $("#jenis_order").change(function() {
        var thisVal = $(this).val();
        if (thisVal == "Room Order") {
            $("#nomor_kamar").prop("disabled", false);
            $("#nomor_kamar").attr("required", true);
        } else {
            $("#nomor_kamar").prop("disabled", true);
            $("#nomor_kamar").attr("required", false);
        }
    });
    $("#jenis_order").change(function() {
        var thisVal = $(this).val();
        if (thisVal == "Room Order") {
            $("#nomor_kamar").prop("disabled", false);
            $("#nomor_kamar").attr("required", true);
        } else {
            $("#nomor_kamar").prop("disabled", true);
        }
    });
    var diskonPersen = 0;
    $(".diskon_tambahan").keyup(function() {
        var diskon_tambahan = parseInt($(this).val());
        var tipe = $(this).data("tipe");
        var ppnTemp = parseInt($("#temp_ppn").val());
        // console.log(ppnTemp);
        var total = parseInt($("#total").val()) - ppnTemp;
        var diskon = 0;
        if (tipe == "persen") {
            diskon = (diskon_tambahan * total) / 100;
            diskonPersen = diskon;
        }
        var newPpn = parseInt(((total - diskon) * 10) / 100);
        var grand_total = total - diskon + newPpn;
        $("#new_ppn").val(newPpn);
        $("#grand_total").val(grand_total);
        $("#temp_grand_total").val(grand_total);
        $("#idrGrandTotal").html(formatRupiah(grand_total));

        if ($(this).closest(".split-bill").length == 1) {
            var activeKey = $(".user-bill.active").attr("data-key");
            var toInput;
            if (tipe == "persen") {
                toInput = ".pembDiskon";
            } else {
                toInput = ".pembDiskonTambahan";
            }
            $(".guest-pembayaran[data-key='" + activeKey + "'] " + toInput).val(
                diskon_tambahan
            );
            $(".guest-pembayaran[data-key='" + activeKey + "'] .pembTotal").val(
                grand_total
            );
        }
    });

    $(".potongan").keyup(function() {
        var potongan = parseInt($(this).val());
        var grand_total = parseInt($("#temp_grand_total").val());
        // var grand_total = parseInt($("#grand_total").val());
        console.log(grand_total);

        // var newPpn = parseInt((total - diskon) * 10 / 100);
        var grand_total = grand_total - potongan;
        // $("#new_ppn").val(newPpn);
        $("#grand_total").val(grand_total);
        $("#idrGrandTotal").html(formatRupiah(grand_total));

        if ($(this).closest(".split-bill").length == 1) {
            var activeKey = $(".user-bill.active").attr("data-key");
            var toInput;

            toInput = ".pembDiskonTambahan";

            $(".guest-pembayaran[data-key='" + activeKey + "'] " + toInput).val(
                diskon_tambahan
            );
            $(".guest-pembayaran[data-key='" + activeKey + "'] .pembTotal").val(
                grand_total
            );
        }
    });
    function getCharge(thisVal) {
        var grand_total =
            parseInt($("#total").val()) -
            parseInt(diskonPersen) -
            parseInt($(".potongan[data-tipe='rp']").val());
        var charge = 0;
        if (thisVal != "Tunai") {
            $("#no_kartu").prop("disabled", false);
            $("#no_kartu").attr("required", true);

            $("#charge").prop("disabled", false);
            $("#charge").attr("required", true);
        } else {
            $("#no_kartu").prop("disabled", true);
            $("#charge").prop("disabled", true);
            $("#charge").val(0);
        }

        if (thisVal == "Debit BCA") {
            // charge = (grand_total * 1) / 100;
            charge = 0;
        } else if (thisVal == "Debit BRI") {
            charge = (grand_total * 0.15) / 100;
        } else if (thisVal == "Kredit BCA") {
            // charge = (grand_total * 1.8) / 100;
            charge = 0;
        } else if (thisVal == "Kredit BRI") {
            charge = (grand_total * 1.5) / 100;
        } else if (thisVal == "Debit Bank Lain") {
            charge = (grand_total * 0.15) / 100;
        } else if (thisVal == "Kredit Bank Lain") {
            charge = (grand_total * 1.5) / 100;
        } else if (thisVal == "Shopee Food" || thisVal == "Gofood") {
            charge = (grand_total * 20) / 100;
        } else if (thisVal == "QRIS") {
            charge = (grand_total * 0.3) / 100;
        }
        $("#charge").val(Math.round(charge));
        $("#grand_total").val(grand_total + Math.round(charge));
        $("#idrGrandTotal").html(
            formatRupiah(grand_total + Math.round(charge))
        );
        if ($(".split-bill").length == 1) {
            var activeKey = $(".user-bill.active").attr("data-key");
            $(
                ".guest-pembayaran[data-key='" +
                    activeKey +
                    "'] .pembJenisBayar"
            ).val(thisVal);
            $(
                ".guest-pembayaran[data-key='" + activeKey + "'] .pembCharge"
            ).val(Math.round(charge));
            $(".guest-pembayaran[data-key='" + activeKey + "'] .pembTotal").val(
                grand_total + Math.round(charge)
            );
        }
    }

    $("#bayar").keyup(function() {
        var terbayar = parseInt($(this).val());
        var grand_total = parseInt($("#grand_total").val());
        var kembalian = terbayar - grand_total;

        $("#kembalian").val(kembalian);
        if ($(this).closest(".split-bill").length == 1) {
            var activeKey = $(".user-bill.active").attr("data-key");
            $(".guest-pembayaran[data-key='" + activeKey + "'] .pembBayar").val(
                terbayar
            );
            $(
                ".guest-pembayaran[data-key='" + activeKey + "'] .pembKembalian"
            ).val(kembalian);
        }
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
        var url = $(this).attr("data-url");

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
                $(".row-menu .paging-menu").remove();
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
                                    <td width='15%' class='tdDiskon'><input type='hidden' class='inputDiskon' name='diskon[]' data-diskon='${data}' value='${data}'> <span>${diskon}</span></td>
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
        var diskon = parseInt(
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" + no + "'] .inputDiskon"
            ).val()
        );
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
            var diskonPerQty = parseInt(
                $(
                    ".keranjang .tbodyLoop .tr[data-tr='" +
                        no +
                        "'] .inputDiskon"
                ).data("diskon")
            );

            var newDiskon = diskonPerQty * newQty;
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" + no + "'] .tdDiskon span"
            ).html(formatRupiah(newDiskon));
            $(
                ".keranjang .tbodyLoop .tr[data-tr='" +
                    no +
                    "'] .tdDiskon .inputDiskon"
            ).val(newDiskon);

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
        $(".box-penjualan").toggleClass("show");
        $(".body-click").toggleClass("show");
    });

    $(document).on("click", ".paging-menu a", function(event) {
        event.preventDefault();
        $(".loading").addClass("show");

        $("li").removeClass("active");
        $(this)
            .parent("li")
            .addClass("active");
        var page = $(this).attr("href");

        $.ajax({
            url: page,
            type: "get",
            datatype: "html",
            success: function(data) {
                $("#tag_container")
                    .empty()
                    .html(data);
                $(".tbodyLoop .inputKodeMenu").each(function() {
                    $(".menu[data-menu='" + $(this).val() + "']").attr(
                        "data-pick",
                        "true"
                    );
                });

                $(".menu").click(function() {
                    pickMenu($(this));
                });

                $(".loading").removeClass("show");
            }
        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            alert("No response from server");
        });
    });
    $("#add-new-user-bill").click(function(e) {
        e.preventDefault();
        var countNow = parseInt($(".list-user-bill").attr("data-count"));
        if (
            $(".guestHidden[data-key='" + countNow + "'] .guestMenu").length > 0
        ) {
            var newCount = countNow + 1;
            $(".list-user-bill").attr("data-count", newCount);
            var img = $(".user-bill[data-key='1'] img").attr("src");
            $(".user-bill").removeClass("active");
            $("#table-menu-bill tbody").attr("data-key", newCount);
            $(".new-user-bill")
                .append(`<div class="mt-2 user-bill active py-4" data-key='${newCount}'>
                                <a href='' class='removeUserBill'>X</a>
                                <img src="${img}" width="60%">
                                <h4  contenteditable='true' class='mt-3'>Guest ${newCount}</h4>
                              </div>`);
            $(".removeUserBill").click(function(e) {
                e.preventDefault();
                var guestKey = $(this)
                    .closest(".user-bill")
                    .attr("data-key");
                renewTableBill();
                $(".guestHidden[data-key='" + guestKey + "']").remove();
                tfootUserBill();
                var newCountKey =
                    parseInt($(".list-user-bill").attr("data-count")) - 1;
                $(".list-user-bill").attr("data-count", newCountKey);
                $("#table-menu-bill tbody").attr("data-key", newCountKey);
                $(this)
                    .closest(".user-bill")
                    .remove();
                $(
                    ".data-pembayaran .guest-pembayaran[data-key='" +
                        guestKey +
                        "']"
                ).remove();
            });

            checkOptionMenu();

            $(".user-bill[data-key='" + newCount + "']").click(function() {
                clickUserBill($(this));
            });

            renewTableBill();

            $(".data-pembayaran").append(`
                <div class='guest-pembayaran' data-key='${newCount}'>
                    <input type='hidden' class='pembDiskon' name='diskon[${newCount}]' value='0'>
                    <input type='hidden' class='pembDiskonTambahan' name='diskon_tambahan[${newCount}]' value='0'>
                    <input type='hidden' class='pembJenisBayar' name='jenis_bayar[${newCount}]' value='Tunai'>
                    <input type='hidden' class='pembBayar' name='bayar[${newCount}]' value='0'>
                    <input type='hidden' class='pembKembalian' name='kembalian[${newCount}]' value='0'>
                    <input type='hidden' class='pembNoKartu' name='no_kartu[${newCount}]' value=''>
                    <input type='hidden' class='pembCharge' name='charge[${newCount}]' value='0'>
                    <input type='hidden' class='pembTotal' name='total[${newCount}]' value='0'>
                </div>
            `);
        } else {
            alert("Pilih menu pada guest");
        }
    });
    function checkOptionMenu() {
        var activeKey = $("#table-menu-bill tbody").attr("data-key");
        $("#user-bill-menu .menuCheck").prop("checked", false);
        $("#user-bill-menu .menuCheck").prop("disabled", false);
        $("#user-bill-menu .custom-checkbox").removeClass("disabled");
        $("#user-bill-menu .menuCheck").each(function() {
            var kodeMenu = $(this).val();
            var qty = parseInt($(this).data("qty"));

            var sumQty = 0;
            $(".guestHidden .guestQty[data-menu='" + kodeMenu + "']").each(
                function() {
                    sumQty += parseInt($(this).val());
                }
            );

            $(".custom-control-label .badge[for='" + kodeMenu + "']").html(
                qty - sumQty
            );

            if (
                $(
                    ".guestHidden[data-key='" +
                        activeKey +
                        "'] .guestMenu[data-menu='" +
                        kodeMenu +
                        "']"
                ).length == 1
            ) {
                $(this).prop("checked", true);
            } else {
                if (qty == sumQty) {
                    $(this)
                        .closest(".custom-checkbox")
                        .addClass("disabled");
                    $(this).prop("disabled", true);
                }
            }
        });
    }
    function clickUserBill(thisParam) {
        $(".user-bill").removeClass("active");
        $(thisParam).addClass("active");
        var key = $(thisParam).attr("data-key");
        $("#table-menu-bill tbody").attr("data-key", key);
        checkOptionMenu();
        renewTableBill();
        if (
            $(".data-bill .guestHidden[data-key='" + key + "'] .guestMenu")
                .length != 0
        ) {
            var selector = ".data-bill .guestHidden[data-key='" + key + "']";
            $(selector + " .guestMenu").each(function(i) {
                var nama = $(selector + " .guestNama")[i].value;
                var qty = $(selector + " .guestQty")[i].value;
                var harga = $(selector + " .guestHarga")[i].value;
                var diskon = $(selector + " .guestDiskon")[i].value;
                var subtotal = $(selector + " .guestSubtotal")[i].value;
                var diskon_satuan = parseInt(diskon) / parseInt(qty);
                $("#table-menu-bill tbody").append(`
                <tr data-menu='${$(this).val()}'>
                    <td>${$(this).val()}</td>
                    <td>${nama}</td>
                    <td>
                        <div class="change-qty">
                            <button class='btnqty' data-tipe='min'>-</button>
                            <input type='text' name='qty[]' value='${qty}' class='form-control text-center inputQty' readonly>
                            <button class='btnqty' data-tipe='plus'>+</button>
                        </div>
                    </td>
                    <td class='harga' data-harga='${harga}'>${formatRupiah(harga)}</td>
                    <td class='diskon' data-diskon='${diskon_satuan}'>${formatRupiah(diskon)}</td>
                    <td class='subtotal'>${formatRupiah(subtotal)}</td>
                </tr>
                `);
            });
            $("tbody[data-key='" + key + "'] tr .change-qty .btnqty").click(
                function(e) {
                    e.preventDefault();
                    var sumQty = 0;
                    var menu = $(this)
                        .closest("tr")
                        .data("menu");
                    var count = parseInt(
                        $(
                            "tr[data-menu='" + menu + "'] .change-qty input"
                        ).val()
                    );
                    $(".guestHidden .guestQty[data-menu='" + menu + "']").each(
                        function() {
                            sumQty += parseInt($(this).val());
                        }
                    );
                    var maxQty =
                        count +
                        (parseInt(
                            $(
                                "#user-bill-menu .menuCheck[value='" +
                                    menu +
                                    "']"
                            ).data("qty")
                        ) -
                            sumQty);

                    billMenuQty($(this), maxQty);
                }
            );

            tfootUserBill();
            $(".diskon_tambahan[data-tipe='persen']").val(
                $(".guest-pembayaran[data-key='" + key + "'] .pembDiskon").val()
            );
            $(".diskon_tambahan[data-tipe='rp']").val(
                $(
                    ".guest-pembayaran[data-key='" +
                        key +
                        "'] .pembDiskonTambahan"
                ).val()
            );
            $("#jenis_bayar").val(
                $(
                    ".guest-pembayaran[data-key='" + key + "'] .pembJenisBayar"
                ).val()
            );
            $("#bayar").val(
                $(".guest-pembayaran[data-key='" + key + "'] .pembBayar").val()
            );
            $("#kembalian").val(
                $(
                    ".guest-pembayaran[data-key='" + key + "'] .pembKembalian"
                ).val()
            );
            $("#no_kartu").val(
                $(
                    ".guest-pembayaran[data-key='" + key + "'] .pembNoKartu"
                ).val()
            );
            $("#charge").val(
                $(
                    ".guest-pembayaran[data-key='" + key + "'] .pembCharge "
                ).val()
            );

            if (
                $(
                    ".guest-pembayaran[data-key='" + key + "'] .pembJenisBayar"
                ).val() != "Tunai"
            ) {
                $("#no_kartu").prop("disabled", false);
                $("#charge").prop("readonly", false);
            }
        }
    }
    function renewTableBill() {
        $("#table-menu-bill tbody tr").remove();
        $("#tfootDiskon").html("");
        $("#tfootSubtotal").html("");
        $("#tfootDiskonSubtotal").html("");
        $("#tfootPpn").html("");
        $("#tfootTotal").html("");
        $("#grand_total").val(0);
        $("#idrGrandTotal").html(0);

        $(".diskon_tambahan").val(0);
        $(".jenis_bayar").val("Tunai");
        $("#bayar, #kembalian, #no_kartu, #charge").val("");
        $("#kembalian").prop("readonly", true);
        $("#no_kartu, #charge").prop("disabled", true);
    }
    $(".user-bill").click(function() {
        clickUserBill($(this));
    });
    function tfootUserBill() {
        var activeKey = $("#table-menu-bill tbody").attr("data-key");
        if (
            $(".data-bill .guestHidden[data-key='" + activeKey + "']").length ==
            1
        ) {
            var selector =
                ".data-bill .guestHidden[data-key='" + activeKey + "']";
            var sumDiskon = 0;
            var sumSubtotal = 0;
            $(selector + " .guestDiskon").each(function() {
                sumDiskon += parseInt($(this).val());
            });
            $(selector + " .guestSubtotal").each(function() {
                sumSubtotal += parseInt($(this).val());
            });
            var sumDiskonSubtotal = sumSubtotal - sumDiskon;
            var ppn = (10 * sumDiskonSubtotal) / 100;
            var total = sumDiskonSubtotal + ppn;
            $("#table-menu-bill tfoot #tfootDiskon").html(
                formatRupiah(sumDiskon)
            );
            $("#table-menu-bill tfoot #tfootSubtotal").html(
                formatRupiah(sumSubtotal)
            );
            $("#table-menu-bill tfoot #tfootDiskonSubtotal").html(
                formatRupiah(sumDiskonSubtotal)
            );
            $("#table-menu-bill tfoot #tfootPpn").html(formatRupiah(ppn));
            $("#table-menu-bill tfoot #tfootTotal").html(formatRupiah(total));
            $("#total").val(total);

            var diskon_persen =
                (parseInt(
                    $(
                        ".guest-pembayaran[data-key='" +
                            activeKey +
                            "'] .pembDiskon"
                    ).val()
                ) *
                    parseInt($("#total").val())) /
                100;
            var pembDiskon =
                diskon_persen +
                parseInt(
                    $(
                        ".guest-pembayaran[data-key='" +
                            activeKey +
                            "'] .pembDiskonTambahan"
                    ).val()
                );
            var grand_total =
                parseInt($("#total").val()) -
                pembDiskon +
                parseInt(
                    $(
                        ".guest-pembayaran[data-key='" +
                            activeKey +
                            "'] .pembCharge"
                    ).val()
                );

            $(".guest-pembayaran[data-key='" + activeKey + "'] .pembTotal").val(
                grand_total
            );
            $("#grand_total").val(grand_total);
            $("#idrGrandTotal").html(formatRupiah(grand_total));
        }
    }
    $("#user-bill-menu .menuCheck").change(function() {
        var thisMenu = $(this).val();
        var kodePenjualan = $("#user-bill-menu").data("kodepenjualan");
        var url = $("#user-bill-menu").data("url");
        var guestKey = $(".user-bill.active").attr("data-key");
        if ($(this).is(":checked")) {
            $.ajax({
                type: "get",
                data: { kode_menu: thisMenu, kode_penjualan: kodePenjualan },
                url: url,
                beforeSend: function() {
                    $(".loading").addClass("show");
                },
                success: function(data) {
                    $(".loading").removeClass("show");
                    data = $.parseJSON(data);
                    var qtyVal = 0;
                    var maxQty = data.qty;
                    if (
                        $(
                            ".guestHidden .guestMenu[data-menu='" +
                                data.kode_menu +
                                "']"
                        ).length == 0
                    ) {
                        qtyVal = data.qty;
                    } else {
                        var sumQty = 0;
                        $(
                            ".guestHidden .guestQty[data-menu='" +
                                data.kode_menu +
                                "']"
                        ).each(function() {
                            sumQty += parseInt($(this).val());
                        });
                        maxQty = data.qty - sumQty;

                        if (
                            $(
                                ".guestHidden[data-key='" +
                                    guestKey +
                                    "'] .guestMenu[data-menu='" +
                                    data.kode_menu +
                                    "']"
                            ).length == 0
                        ) {
                            qtyVal = maxQty;
                        } else {
                            qtyVal = $(
                                ".guestHidden[data-key='" +
                                    guestKey +
                                    "'] .guestQty[data-menu='" +
                                    data.kode_menu +
                                    "']"
                            ).val();
                        }
                    }
                    var diskon = parseInt(data.diskon_satuan) * qtyVal;
                    var subtotal = parseInt(data.harga_jual) * qtyVal;
                    var append = `<input type='hidden' data-menu='${data.kode_menu}' class='guestMenu' name='guestMenu[${guestKey}][]' value='${data.kode_menu}'>
                    <input type='hidden' data-menu='${data.kode_menu}' class='guestQty' name='guestQty[${guestKey}][]' value='${qtyVal}'>
                    <input type='hidden' data-menu='${data.kode_menu}' class='guestNama' name='guestNama[${guestKey}][]' value='${data.nama}'>
                    <input type='hidden' data-menu='${data.kode_menu}' class='guestHarga' name='guestHarga[${guestKey}][]' value='${data.harga_jual}'>
                    <input type='hidden' data-menu='${data.kode_menu}' class='guestDiskon' name='guestDiskon[${guestKey}][]' value='${diskon}'>
                    <input type='hidden' data-menu='${data.kode_menu}' class='guestSubtotal' name='guestSubtotal[${guestKey}][]' value='${subtotal}'>
                    `;

                    if (
                        $(
                            ".data-bill .guestHidden[data-key='" +
                                guestKey +
                                "']"
                        ).length == 1
                    ) {
                        $(
                            ".data-bill .guestHidden[data-key='" +
                                guestKey +
                                "']"
                        ).append(append);
                    } else {
                        $(".data-bill").append(
                            `
                        <div class='guestHidden' data-key='${guestKey}'>
                            ${append}
                        </div>
                        `
                        );
                    }
                    $("#table-menu-bill tbody").append(`<tr data-menu='${
                        data.kode_menu
                    }'>
                    <td>${data.kode_menu}</td>
                    <td>${data.nama}</td>
                    <td>
                        <div class="change-qty">
                            <button class='btnqty' data-tipe='min'>-</button>
                            <input type='text' name='qty[]' value='${qtyVal}' class='form-control text-center inputQty' readonly>
                            <button class='btnqty' data-tipe='plus'>+</button>
                        </div>
                    </td>
                    <td class='harga' data-harga='${
                        data.harga_jual
                    }'>${formatRupiah(data.harga_jual)}</td>
                    <td class='diskon' data-diskon='${
                        data.diskon_satuan
                    }'>${formatRupiah(diskon)}</td>
                    <td class='subtotal'>${formatRupiah(subtotal)}</td>
                </tr>`);
                    checkOptionMenu();
                    tfootUserBill();

                    $(
                        "tr[data-menu='" +
                            data.kode_menu +
                            "'] .change-qty .btnqty"
                    ).click(function(e) {
                        e.preventDefault();
                        billMenuQty($(this), maxQty);
                    });
                }
            });
        } else {
            $(
                "#table-menu-bill tbody tr[data-menu='" + thisMenu + "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestMenu[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestQty[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestNama[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestHarga[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestDiskon[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            $(
                ".guestHidden[data-key='" +
                    guestKey +
                    "'] .guestSubtotal[data-menu='" +
                    thisMenu +
                    "']"
            ).remove();
            checkOptionMenu();
            tfootUserBill();
        }
    });

    function billMenuQty(thisParam, maxQty) {
        var guestKey = $(".user-bill.active").attr("data-key");
        var tipe = thisParam.data("tipe");
        var menu = thisParam.closest("tr").data("menu");
        var selector = "tr[data-menu='" + menu + "']";
        var count = parseInt($(selector + " .change-qty input").val());
        if (tipe == "min") {
            if (count != 1) {
                count--;
            }
        } else {
            if (count != maxQty) {
                count++;
            }
        }
        var newDiskon =
            parseInt($(selector + " .diskon").attr("data-diskon")) * count;
        var newSubtotal =
            parseInt($(selector + " .harga").attr("data-harga")) * count;
        $("tr[data-menu='" + menu + "'] .change-qty input").val(count);
        $(
            ".guestHidden[data-key='" +
                guestKey +
                "'] .guestQty[data-menu='" +
                menu +
                "']"
        ).val(count);
        $(
            ".guestHidden[data-key='" +
                guestKey +
                "'] .guestDiskon[data-menu='" +
                menu +
                "']"
        ).val(newDiskon);
        $(
            ".guestHidden[data-key='" +
                guestKey +
                "'] .guestSubtotal[data-menu='" +
                menu +
                "'"
        ).val(newSubtotal);

        checkOptionMenu();
        tfootUserBill();

        $("tr[data-menu='" + menu + "'] .diskon").html(formatRupiah(newDiskon));
        $("tr[data-menu='" + menu + "'] .subtotal").html(
            formatRupiah(newSubtotal)
        );
    }

    $("#split-bill-form").submit(function(e) {
        var id = $(this).attr("id");
        e.preventDefault();
        if (confirm("Apakah anda yakin?")) {
            var error = 0;
            var errorBayar = 0;
            $(".custom-control-label .badge").each(function() {
                if ($(this).html() != 0) {
                    error++;
                }
            });
            $(".guest-pembayaran .pembBayar").each(function() {
                if ($(this).val() == 0) {
                    errorBayar++;
                }
            });

            if (error != 0) {
                alert("Masih ada menu yang belum dipilih");
            } else if (errorBayar != 0) {
                alert("Masih ada user bill yang belum dibayar");
            } else {
                $("#" + id)
                    .unbind("submit")
                    .submit();
            }
        }
        $(".loading").removeClass("show");
    });
});
