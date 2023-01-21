$(document).ready(function () {
    var iconOk = $("<i class='bx bx-check-circle font-size-16 align-middle me-2 text-success'></i>")

    $('#xml-validation').on('click', function () {
        $(this).addClass('disabled');
        $(this).closest('.card-body').addClass('placeholder');
        $(this).closest('form').submit();
    });

    function setSpinner(thisEl, isVisible = true) {
        if (isVisible === true) {
            $(thisEl).closest('a').find('i').removeClass('bx-add-to-queue');
            $(thisEl).closest('a').find('i').addClass('bx-loader bx-spin');
            $(thisEl).closest('a').addClass('disabled');
        } else {
            $(thisEl).closest('a').find('i').addClass('bx-add-to-queue');
            $(thisEl).closest('a').find('i').removeClass('bx-loader bx-spin');
            $(thisEl).closest('a').removeClass('disabled');
        }
    }


    $('body').on('click', '#xml-validation-geom', function (e) {
        e.preventDefault();
        /*        $(this).addClass('disabled');
                $(this).closest('.card-body').addClass('placeholder');*/
        // console.log($(this).attr('href'));
        //return false;

        var promise = $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Невідома помилка!");
            },
        })

        promise.then(function (data) {
            $('#zone-validate-area').removeClass('d-none');
            $('#region-validate-area').removeClass('d-none');


            var zone = checkGeom($('#zone-route').val(), data.parseJson.zones);
            var region = checkGeom($('#region-route').val(), data.parseJson.regions);

            zone.then(function (zoneData) {
                if (zoneData.result === false) {
                    $('.icon-geom').removeClass('text-primary');
                    $('.icon-geom').addClass('text-danger');
                    var spinnerZone = $('#zone-validate-area').find('.spinner');
                    console.log(spinnerZone);
                    spinnerZone.html('');
                    spinnerZone.append(iconOk);
                    $('#geom-errors').append(zoneData.tableHtml);

                    //initTableData($('#datatable-buttons'));
                }
            })
            region.then(function (regionData) {
                if (regionData.result === false) {
                    console.log(regionData);
                    $('.icon-geom').removeClass('text-primary');
                    $('.icon-geom').addClass('text-danger');
                    var spinnerRegion = $('#region-validate-area').find('.spinner');
                    console.log(spinnerRegion);
                    spinnerRegion.html('');
                    spinnerRegion.append("<i class='bx bx-check-circle font-size-16 align-middle me-2 text-success'></i>");
                    $('#geom-errors').append(regionData.tableHtml);

                    setTimeout(function () {
                        initTableData($('.datatable-buttons'));
                    }, 500);

                    //
                }
            })

        }, function (reason) {
            console.log(reason); // Ошибка!
        });
    });

    function initTableData(object)
    {
        var table = object.DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        });
        table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    }

    function checkGeom(url, data) {
        var strData = JSON.stringify(data);
        var promise = $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {'data': strData},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Невідома помилка!");
            },
        })

        return promise;
    }


    var updateDataTable = function (data) {
        var dataTable = $('.data-table');

        $(dataTable).html('');
        $(dataTable).append(data.tableHtml);
        $('#datatable').DataTable({
            order: [[4, 'desc']],
        });
    };

});
