//window.addBaseLayars = require('./map/services.js');
//import './map/services';
window.addBaseLayars = require('./map/base-layers.js');
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

    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 7000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

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
                //overlayControl[0].hidden = true;


                setSpinner(thisEl, false);
                if (data.result === false) {
                    toastr["error"](data.error);
                }


                console.log('dd');
                console.log(data);
                //console.log(JSON.parse(data));
               // return false;


                //var dataJ = JSON.parse(data.json);
                //console.log(dataJ);
                //console.log(JSON.parse(dataJ));
                parcelFromBaseLayer.clearLayers();

                //let feature = dataJ.map(function (item) {
                    let coord = JSON.parse(data.json);
                    //console.log(coord);
                    let item_new = {
                        "type": "Feature",
                        "properties": {
                            "area": data.area,
                            "cadnum": data.cadNum,
                            "name": "parcelFromBase",
                            "fromBase": true,
                            "purpose": data.purpose,
                        },
                        "geometry": {
                            "type": coord.type,
                            "coordinates": coord.coordinates,
                        },
                    };

                    //return item_new;
                //});
                //console.log(item_new);
                parcelFromBaseLayer.addData(item_new);
                //parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
                parcelFromBaseLayer.addTo(parcelFromBaseGroup);

                /** Додаємо групу до карти    */
                parcelFromBaseGroup.addTo(leafletMap);

/*                $('#marker-parcels').html('<i class="fas fa-check text-success"></i>');
                $('#parcels').prop('disabled', false);*/

                leafletMap.fitBounds(parcelFromBaseLayer.getBounds());


                //$('#calculate').remove();

               // let dataJson = JSON.parse(data);
/*                if (dataJson.errors.length) {
                    errorsHandler(dataJson.errors);

                    return false;
                }*/

                /*let bounds = addFeatureToMap(dataJson);
                setDataToParcelTable(dataJson, bounds);*/
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Помилка обробки файла!");
                setSpinner(thisEl, false);
                //servicesThrowErrors(jqXHR);
            },
        })
    }

    function setSpinner(thisEl, isVisible = true)
    {
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
});
