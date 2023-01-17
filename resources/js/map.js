//window.addBaseLayars = require('./map/services.js');
//import './map/services';
window.addBaseLayars = require('./map/base-layers.js');
window.addParcelLayars = require('./map/parcel-layers.js');
import { setStyleIn } from "./map/services.js";
//require('base-layers');
//import myModule from 'base-layers';
/*import { myFunction } from './base-layers.js';

myFunction();*/
$(document).ready(function () {
    $('#file-form-jsonFile').on('change', function (event) {
        let fileJson = $("#file-form-jsonFile")[0].files[0];
        let formData = new FormData();
        formData.append("jsonFile", fileJson);

        var href = $(this).data('href');
        var thisEl = this;
        sendFile(formData, href, thisEl);
        //$("#file-form-jsonFile")[0].closest('.d-inline-block').reset();
    });

    function sendFile(data, href, thisEl) {
        $.ajax({
            url: href,
            method: 'POST',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                setSpinner(thisEl);
            },
            success: function (data) {
                setSpinner(thisEl, false);
                if (data.result === false) {
                    toastr["error"](data.error);
                }
                //parcelFromBaseLayer.clearLayers();
                let coord = JSON.parse(data.parcel.json);
                let new_parcel = {
                    "type": "Feature",
                    "properties": {
                        "id": data.parcel.id,
                        "area": data.parcel.area,
                        "cadnum": data.parcel.cadNum,
                        "name": "parcelFromBase",
                        "fromBase": true,
                        "purpose": data.parcel.purpose,
                    },
                    "geometry": {
                        "type": coord.type,
                        "coordinates": coord.coordinates,
                    },
                };

                parcelFromBaseLayer.addData(new_parcel);
                parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
                parcelFromBaseLayer.addTo(parcelFromBaseGroup);

                /** Додаємо групу до карти    */
                parcelFromBaseGroup.addTo(leafletMap);
                setStyleIn(parcelFromBaseLayer, data.parcel.id);

                updateDataTable(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Помилка обробки файла!");
                setSpinner(thisEl, false);
                //servicesThrowErrors(jqXHR);
            },
        })
    }

/*    function getAllParcels() {
        var href = $('#get-all-parcels').data('href');
        $.ajax({
            url: href,
            method: 'GET',
            dataType: 'json',
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.result === false) {
                    toastr["error"](data.error);
                }
                parcelFromBaseLayer.clearLayers();

                let feature = data.map(function (item) {
                    let coord = JSON.parse(item.json);
                    let item_new = {
                        "type": "Feature",
                        "properties": {
                            //"area": item.area,
                            "cadnum": item.cadNum,
                            "name": "parcelFromBase",
                            "fromBase": true,
                            "usage": item.usage,
                        },
                        "geometry": {
                            "type": coord.type,
                            "coordinates": coord.coordinates,
                        },
                    };

                    return item_new;
                });

                if (feature.length > 0) {
                    parcelFromBaseLayer.addData(feature);
                    parcelFromBaseLayer.nameLayer = "parcels";
                    parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
                    parcelFromBaseLayer.addTo(parcelFromBaseGroup);

                    /!** Додаємо групу до карти    *!/
                    parcelFromBaseGroup.addTo(leafletMap);
                    leafletMap.fitBounds(parcelFromBaseLayer.getBounds());
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Помилка обробки файла!");
            },
        })
    }

    getAllParcels();*/

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
    $('body').on('click', '.btn-remove', function (e) {
        e.preventDefault();
        var that = this;
        Swal.fire(swalStyle).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: $(that).attr('href'),
                    method: 'DELETE',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.result === false) {
                            toastr["error"](data.error);
                        } else {
                            toastr["success"]('Ділянка успішно видалена');
                            updateDataTable(data);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        toastr["error"]("Невідома помилка!");
                    },
                })
                //Swal.fire("Deleted!", "Your file has been deleted.", "success");
            }
        });
    });

    $('body').on('click', '.btn-save', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            method: 'PATCH',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.result === false) {
                    toastr["error"](data.error);
                } else {
                    toastr["success"]('Ділянка успішно збережена');
                    updateDataTable(data);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Невідома помилка!");
            },
        })

    });


    var updateDataTable = function (data) {
        var dataTable = $('.data-table');

        $(dataTable).html('');
        $(dataTable).append(data.tableHtml);
        $('#datatable').DataTable({
            order: [[4, 'desc']],
        });
    };

    leafletMap.fitBounds(parcelFromBaseLayer.getBounds());
});
