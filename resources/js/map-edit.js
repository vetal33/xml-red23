//window.addBaseLayars = require('./map/services.js');
//import './map/services';
window.addBaseLayars = require('./map/base-layers.js');
window.addControls = require('./map/controls-edit.js');
window.addBaseLayars = require('./map/parcel-layers.js');
import { setBounds } from "./map/services.js";
//require('base-layers');
//import myModule from 'base-layers';
/*import { myFunction } from './base-layers.js';

myFunction();*/
$(document).ready(function () {
    let extent = $('#data-extent').data('extent');
    let bound = setBounds(extent);
    if (bound.length) {
        leafletMap.fitBounds(bound, {maxZoom: 18});
    }
    $('body').on('click', '.btn-zoom-intersect', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
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

                parcelIntersectFromBaseLayer.clearLayers();
                let coord = JSON.parse(data.json);
                let new_parcel = {
                    "type": "Feature",
                    "properties": {
                        "name": "parcelFromBaseIntersect",
                        "fromBase": true,
                    },
                    "geometry": {
                        "type": coord.type,
                        "coordinates": coord.coordinates,
                    },
                };

                parcelIntersectFromBaseLayer.addData(new_parcel);
                parcelIntersectFromBaseLayer.setStyle(intersectSelectedStyle);
                parcelIntersectFromBaseLayer.addTo(parcelIntersectFromBaseGroup);

                /** Додаємо групу до карти    */
                parcelIntersectFromBaseGroup.addTo(leafletMap);
                leafletMap.fitBounds(parcelIntersectFromBaseLayer.getBounds());
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("Помилка обробки файла!");
            },
        })
    });
});
