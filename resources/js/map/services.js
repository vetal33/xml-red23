// $(document).ready(function () {
    /**  Створюєм глобальний об'єкт Map   */
    window.leafletMap = L.map('map').setView([48.5, 31], 6);
   // var leafletMap = L.map('map').setView([48.5, 31], 6);

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
/*window.pointLayer = L.geoJSON();
window.markerLayer = L.marker();*/
// })
