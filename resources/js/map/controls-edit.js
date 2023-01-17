import { setBounds } from "./services.js";
/** Full extent button  */
let parcelZoomButton = L.Control.extend({
    options: {
        position: 'topleft',
    },
    onAdd: function () {
        let container = L.DomUtil.create('a', 'btn btn-outline-secondary');
       // let container = L.DomUtil.create('a', 'btn btn-outline-secondary btn-sm edit');
        container.innerHTML += '<i class="mdi mdi-map-search font-size-20"></i>';
        container.type = "button";
        container.title = "Ділянка";
        container.setAttribute("data-toggle", "tooltip");
        container.onclick = function () {
            let extent = $('#data-extent').data('extent');
            let bound = setBounds(extent);
            if (bound.length) {
                leafletMap.fitBounds(bound, {maxZoom: 18});
            }
        };
        return container;
    }
});

leafletMap.addControl(new parcelZoomButton());
