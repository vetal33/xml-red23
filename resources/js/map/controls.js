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

leafletMap.addControl(new coordinates());

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
