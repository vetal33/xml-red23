/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/map/base-layers.js":
/*!*****************************************!*\
  !*** ./resources/js/map/base-layers.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {

window.addBaseLayars = __webpack_require__(/*! ./services.js */ "./resources/js/map/services.js"); //import './services';
//module.exports = function () {
//export const myFunction = () => {
//var leafletMap = L.map('map').setView([48.5, 31], 6);

/** Створюєм карту MapBox  */

var mapbox = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
  maxZoom: 19,
  id: 'mapbox/streets-v11',
  accessToken: 'pk.eyJ1IjoidmV0YWwzMyIsImEiOiJjazU2bm9nYmQwNWhtM29wZXM4aW80bzdqIn0.NjzzExdElo0C7JhER04PSQ'
});
mapbox.addTo(leafletMap);
var Esri_WorldStreetMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
  attribution: 'Tiles &copy; Esri'
});
Esri_WorldStreetMap.addTo(leafletMap);
var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
});
osm.addTo(leafletMap);
/*let bing_key = 'Ap2Aa1SZydkxBTmchpgIYaIXw-OgT9TxU-UY-bOhsDUJO2WTicJyytnoUjjsWOnr';
let bing = L.tileLayer.bing(bing_key);
bing.addTo(leafletMap);*/

/** Створюєм Публічнку кадастрову карту Укарїни   */

/*let kadastr = L.tileLayer.wms("https://map.land.gov.ua/geowebcache/service/wms", {
    layers: 'kadastr',
    format: 'image/png',
    transparent: true,
    version: '1.1.1',
    maxNativeZoom: 16,
    maxZoom: 18,
});

kadastr.addTo(leafletMap);*/

/**
 * Створюємо набор базових шарів для відображення(можна вибрать лише один)
 * @type {{mapBox: *, bing: *}}
 */

var baseLayersMap = {
  "esri": Esri_WorldStreetMap,
  "osm": osm
};
/**  Додаємо базові шари на карту   */

window.layersControl = L.control.layers(baseLayersMap).addTo(leafletMap); //}
//};

/***/ }),

/***/ "./resources/js/map/services.js":
/*!**************************************!*\
  !*** ./resources/js/map/services.js ***!
  \**************************************/
/***/ (function() {

// $(document).ready(function () {

/**  Створюєм глобальний об'єкт Map   */
window.leafletMap = L.map('map').setView([48.5, 31], 6); // var leafletMap = L.map('map').setView([48.5, 31], 6);

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

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*****************************!*\
  !*** ./resources/js/map.js ***!
  \*****************************/
//window.addBaseLayars = require('./map/services.js');
//import './map/services';
window.addBaseLayars = __webpack_require__(/*! ./map/base-layers.js */ "./resources/js/map/base-layers.js"); //require('base-layers');
//import myModule from 'base-layers';

/*import { myFunction } from './base-layers.js';

myFunction();*/

$(document).ready(function () {
  $('#file-form-jsonFile').on('change', function (event) {
    var fileJson = $("#file-form-jsonFile")[0].files[0];
    var formData = new FormData();
    formData.append("jsonFile", fileJson);
    var href = $(this).data('href');
    var thisEl = this;
    sendFile(formData, href, thisEl); //$("#file-form-jsonFile")[0].closest('.d-inline-block').reset();
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
  };

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
      beforeSend: function beforeSend() {
        setSpinner(thisEl);
      },
      success: function success(data) {
        //overlayControl[0].hidden = true;
        setSpinner(thisEl, false);

        if (data.result === false) {
          toastr["error"](data.error);
        }

        console.log('dd');
        console.log(data); //console.log(JSON.parse(data));
        // return false;
        //var dataJ = JSON.parse(data.json);
        //console.log(dataJ);
        //console.log(JSON.parse(dataJ));

        parcelFromBaseLayer.clearLayers(); //let feature = dataJ.map(function (item) {

        var coord = JSON.parse(data.json); //console.log(coord);

        var item_new = {
          "type": "Feature",
          "properties": {
            "area": data.area,
            "cadnum": data.cadNum,
            "name": "parcelFromBase",
            "fromBase": true,
            "purpose": data.purpose
          },
          "geometry": {
            "type": coord.type,
            "coordinates": coord.coordinates
          }
        }; //return item_new;
        //});
        //console.log(item_new);

        parcelFromBaseLayer.addData(item_new); //parcelFromBaseLayer.setStyle(parcelFromBaseStyle);

        parcelFromBaseLayer.addTo(parcelFromBaseGroup);
        /** Додаємо групу до карти    */

        parcelFromBaseGroup.addTo(leafletMap);
        /*                $('#marker-parcels').html('<i class="fas fa-check text-success"></i>');
                        $('#parcels').prop('disabled', false);*/

        leafletMap.fitBounds(parcelFromBaseLayer.getBounds()); //$('#calculate').remove();
        // let dataJson = JSON.parse(data);

        /*                if (dataJson.errors.length) {
                            errorsHandler(dataJson.errors);
        
                            return false;
                        }*/

        /*let bounds = addFeatureToMap(dataJson);
        setDataToParcelTable(dataJson, bounds);*/
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        toastr["error"]("Невідома помилка!");
        setSpinner(thisEl, false); //servicesThrowErrors(jqXHR);
      }
    });
  }

  function setSpinner(thisEl) {
    var isVisible = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

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
}();
/******/ })()
;