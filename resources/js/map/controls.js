/** Create coordinates panel in the map  */
let coordinates = L.Control.extend({
    options: {
        position: 'bottomleft',
    },
    onAdd: function () {
        let container = L.DomUtil.create('div', '');
        container.setAttribute("id", "coordinates-map");

        return container;
    }
});
/** Full extent button  */
let fullzoomButton = L.Control.extend({
    options: {
        position: 'topleft',
    },
    onAdd: function () {
        let container = L.DomUtil.create('a', 'btn btn-outline-secondary');
       // let container = L.DomUtil.create('a', 'btn btn-outline-secondary btn-sm edit');
        container.innerHTML += '<i class="mdi mdi-arrow-expand-all font-size-20"></i>';
        container.type = "button";
        container.title = "Загальний вигляд";
        container.setAttribute("data-toggle", "tooltip");
        container.onclick = function () {
            leafletMap.eachLayer(function (layer) {
                if (layer.nameLayer && layer.nameLayer === "parcels") {
                    leafletMap.fitBounds(layer.getBounds());
                }
            });
        };
        return container;
    }
});


leafletMap.addControl(new coordinates());
leafletMap.addControl(new fullzoomButton());

/** Set coordinates into the map  */
leafletMap.addEventListener('mousemove', function (ev) {
    lat = ((Math.round(ev.latlng.lat * 1000000)) / 1000000).toFixed(6);
    lng = ((Math.round(ev.latlng.lng * 1000000)) / 1000000).toFixed(6);
    $('#coordinates-map').html('<i class="bx bx-navigation text-gray"></i>' + ' ' + lat + '  ' + lng);
});

/** Remove panel when cursor leave map  */
leafletMap.addEventListener('mouseout', function () {
    $('#coordinates-map').html('');
});
/*export function setStyleIn (id) {
    parcelFromBaseLayer.eachLayer(function (layer) {
        console.log(layer)
        console.log(layer.feature)
        console.log(layer.feature.properties)
        if (Number(layer.feature.properties.id) === Number(id)) {
            layer.setStyle(addFeatureFromJsonSelectedStyle);
            layer.bringToFront();
        }
    });
}*/

parcelFromBaseLayer.on('click', function (e) {
    parcelFromBaseLayer.setStyle(addFeatureFromJsonStyle);
    e.layer.setStyle(addFeatureFromJsonSelectedStyle);
    $('#feature-card-area').html(e.layer.feature.properties.area);
    $('#feature-card-cud-num').html(e.layer.feature.properties.cadNum);
    $('#feature-purpose').html(e.layer.feature.properties.purpose);

    let bounds = JSON.stringify(e.layer.getBounds());
    $('#geom-from-json').attr("data-bounds", bounds);
    $('#geom-from-json').val(e.layer.feature.properties.wkt);
    $('#save-parcel').removeClass('disabled');
    $('#calculate').remove();
});

