// $(document).ready(function () {
    /**  Створюєм глобальний об'єкт Map   */
    window.leafletMap = L.map('map').setView([48.5, 31], 6);

/**  Створюєм глобальні групи шарів   */
/*window.mejaLayersGroup = L.layerGroup();
window.zonyLayersGroup = L.layerGroup();
window.localLayersGroup = L.layerGroup();
window.landsLayersGroup = L.layerGroup();
window.regionsLayersGroup = L.layerGroup();
window.intersectLocalLayersGroup = L.layerGroup();
window.parcelFromBaseGroup = L.layerGroup();
window.parcelGroup = L.layerGroup();
window.parcelLayer = L.geoJSON();*/
window.parcelFromBaseLayer = L.geoJSON();
window.parcelFromBaseGroup = L.layerGroup();

window.parcelIntersectFromBaseLayer = L.geoJSON();
window.parcelIntersectFromBaseGroup = L.layerGroup();
/*window.pointLayer = L.geoJSON();
window.markerLayer = L.marker();*/
// })

export function setBounds($boundsStr) {
    let arrayBounds = [];
    if ($boundsStr.trim() !== '') {
        $boundsStr = $boundsStr.replace('BOX(', '');
        $boundsStr = $boundsStr.replace(')', '');
        let arraySplited = $boundsStr.split(',');
        arrayBounds.push([arraySplited[0].split(' ')[1], arraySplited[0].split(' ')[0]], [arraySplited[1].split(' ')[1], arraySplited[1].split(' ')[0]]);
    }
    return arrayBounds;
}

export function setStyleIn (layerName, id, isZoom = true) {
    layerName.eachLayer(function (layer) {
        if (Number(layer.feature.properties.id) === Number(id)) {
            layer.setStyle(addFeatureFromJsonSelectedStyle);
            layer.bringToFront();
            if (isZoom === true) {
                leafletMap.fitBounds(layer.getBounds());
            }
        }
    });
}

$('body').on('click', '.btn-zoom', function (e) {
    e.preventDefault();
    //$('#calculate-parcel').removeClass('disabled');

    //let boundsStr = $(this).attr('data-bounds');
    let boundsStr = $(this).data('extent');
    let bound = setBounds(boundsStr);
    if (bound.length) {
        leafletMap.fitBounds(bound, {maxZoom: 18});
    }

    //let cadNum = getCadNumFromRow(this);

    // parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
    // let layer = getParcelLayerByCadNum(cadNum);
    // if (typeof layer !== 'undefined') layer.setStyle(addFeatureFromJsonSelectedStyle);

    //setParcelValueInTable(layer);
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
