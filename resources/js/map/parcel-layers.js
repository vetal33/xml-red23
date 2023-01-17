function getAllParcels() {
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
                        "id": item.id,
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

                /** Додаємо групу до карти    */
                parcelFromBaseGroup.addTo(leafletMap);
                //leafletMap.fitBounds(parcelFromBaseLayer.getBounds());
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr["error"]("Помилка обробки файла!");
        },
    })
}

getAllParcels();
