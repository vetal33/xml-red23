/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/map/base-layers.js":
/*!*****************************************!*\
  !*** ./resources/js/map/base-layers.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __unused_webpack_exports, __webpack_require__) {

window.addServices = __webpack_require__(/*! ./services.js */ "./resources/js/map/services.js");
window.addStyle = __webpack_require__(/*! ./style.js */ "./resources/js/map/style.js");
window.addControls = __webpack_require__(/*! ./controls.js */ "./resources/js/map/controls.js"); //module.exports = function () {
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

/***/ "./resources/js/map/controls.js":
/*!**************************************!*\
  !*** ./resources/js/map/controls.js ***!
  \**************************************/
/***/ (function() {

/** Create coordinates panel in the map  */
var coordinates = L.Control.extend({
  options: {
    position: 'bottomleft'
  },
  onAdd: function onAdd() {
    var container = L.DomUtil.create('div', '');
    container.setAttribute("id", "coordinates-map");
    return container;
  }
});
/** Full extent button  */

var fullzoomButton = L.Control.extend({
  options: {
    position: 'topleft'
  },
  onAdd: function onAdd() {
    var container = L.DomUtil.create('a', 'btn btn-outline-secondary'); // let container = L.DomUtil.create('a', 'btn btn-outline-secondary btn-sm edit');

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
  lat = (Math.round(ev.latlng.lat * 1000000) / 1000000).toFixed(6);
  lng = (Math.round(ev.latlng.lng * 1000000) / 1000000).toFixed(6);
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
  var bounds = JSON.stringify(e.layer.getBounds());
  $('#geom-from-json').attr("data-bounds", bounds);
  $('#geom-from-json').val(e.layer.feature.properties.wkt);
  $('#save-parcel').removeClass('disabled');
  $('#calculate').remove();
});

/***/ }),

/***/ "./resources/js/map/parcel-layers.js":
/*!*******************************************!*\
  !*** ./resources/js/map/parcel-layers.js ***!
  \*******************************************/
/***/ (function() {

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
    success: function success(data) {
      if (data.result === false) {
        toastr["error"](data.error);
      }

      parcelFromBaseLayer.clearLayers();
      var feature = data.map(function (item) {
        var coord = JSON.parse(item.json);
        var item_new = {
          "type": "Feature",
          "properties": {
            //"area": item.area,
            "id": item.id,
            "cadnum": item.cadNum,
            "name": "parcelFromBase",
            "fromBase": true,
            "usage": item.usage
          },
          "geometry": {
            "type": coord.type,
            "coordinates": coord.coordinates
          }
        };
        return item_new;
      });

      if (feature.length > 0) {
        parcelFromBaseLayer.addData(feature);
        parcelFromBaseLayer.nameLayer = "parcels";
        parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
        parcelFromBaseLayer.addTo(parcelFromBaseGroup);
        /** Додаємо групу до карти    */

        parcelFromBaseGroup.addTo(leafletMap); //leafletMap.fitBounds(parcelFromBaseLayer.getBounds());
      }
    },
    error: function error(jqXHR, textStatus, errorThrown) {
      toastr["error"]("Помилка обробки файла!");
    }
  });
}

getAllParcels();

/***/ }),

/***/ "./resources/js/map/services.js":
/*!**************************************!*\
  !*** ./resources/js/map/services.js ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "setBounds": function() { return /* binding */ setBounds; },
/* harmony export */   "setStyleIn": function() { return /* binding */ setStyleIn; }
/* harmony export */ });
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

function setBounds($boundsStr) {
  var arrayBounds = [];

  if ($boundsStr.trim() !== '') {
    $boundsStr = $boundsStr.replace('BOX(', '');
    $boundsStr = $boundsStr.replace(')', '');
    var arraySplited = $boundsStr.split(',');
    arrayBounds.push([arraySplited[0].split(' ')[1], arraySplited[0].split(' ')[0]], [arraySplited[1].split(' ')[1], arraySplited[1].split(' ')[0]]);
  }

  return arrayBounds;
}
function setStyleIn(layerName, id) {
  var isZoom = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
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
  e.preventDefault(); //$('#calculate-parcel').removeClass('disabled');
  //let boundsStr = $(this).attr('data-bounds');

  var boundsStr = $(this).data('extent');
  var bound = setBounds(boundsStr);

  if (bound.length) {
    leafletMap.fitBounds(bound, {
      maxZoom: 18
    });
  } //let cadNum = getCadNumFromRow(this);
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
};

/***/ }),

/***/ "./resources/js/map/style.js":
/*!***********************************!*\
  !*** ./resources/js/map/style.js ***!
  \***********************************/
/***/ (function() {

/**
 * Стиль для межі населеного пункту
  */
window.boundaryStyle = {
  "color": '#ff735b',
  "weight": 7,
  "opacity": 1,
  "fillOpacity": 0.05,
  "fillColor": '#5bff10'
};
/**
 * Стиль для економіко-планувальної зони під час виділення
 */

window.selectZoneStyle = {
  weight: 3,
  color: '#666',
  dashArray: '',
  fillOpacity: 0.7
};
/**
 * Стиль для ділянки грунту під час виділення
 */

window.selectlandsStyle = {
  "color": '#ffffff',
  "weight": 1,
  "opacity": 1
};
/**
 * Стиль для локального фактору під час виділення
 */

window.selectLocalStyle = {
  "color": '#ffffff',
  "weight": 2,
  "opacity": 1
};
/**
 * Стиль для імпортованої ділянки з json
 */

window.addFeatureFromJsonStyle = {
  "color": '#290a30',
  "weight": 1,
  "opacity": 1,
  "fillOpacity": 0.4,
  "fillColor": '#b3ffc9'
};
/**
 * Стиль для ділянки з бази
  */

window.parcelFromBaseStyle = {
  "color": '#290a30',
  "weight": 1,
  "opacity": 1,
  "fillOpacity": 0.4,
  "fillColor": '#1ed9ff'
};
/**
 * Стиль для виділеної ділянки
 */

window.addFeatureFromJsonSelectedStyle = {
  "color": '#9a14a5',
  "weight": 1,
  "opacity": 1,
  "fillOpacity": 0.5,
  "fillColor": '#fff327'
};
window.intersectSelectedStyle = {
  "color": '#9a14a5',
  "weight": 1,
  "opacity": 1,
  "fillOpacity": 0.5,
  "fillColor": '#fa28e4'
};
/**
 * PopUp message style
 */

window.swalStyle = {
  title: "Ви впевнені?",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#34c38f",
  cancelButtonColor: "#f46a6a",
  confirmButtonText: "Так, видалити ділянку!",
  cancelButtonText: "Відмінити"
};
/**
 * Стиль для ділянки перетину з локальним фактором
 */

window.intersectLocalsStyle = {
  "color": '#301005',
  "weight": 1,
  "opacity": 0,
  "fillOpacity": 0,
  "fillColor": '#8dff14'
};
/**
 * Стиль для ділянки перетину з локальним фактором під час наведення
 */

window.intersectLocalsSelectedStyle = {
  "color": '#290a30',
  "weight": 1,
  "opacity": 1,
  "fillOpacity": 0.9,
  "fillColor": '#ff8e09'
};
/**
 * Стиль для точок
 */

window.pointsSelectedStyle = {
  radius: 2,
  fillColor: "#f1ef35",
  color: "#2e2e2e",
  weight: 1,
  opacity: 1,
  fillOpacity: 0.8
};

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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!*****************************!*\
  !*** ./resources/js/map.js ***!
  \*****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _map_services_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./map/services.js */ "./resources/js/map/services.js");
//window.addBaseLayars = require('./map/services.js');
//import './map/services';
window.addBaseLayars = __webpack_require__(/*! ./map/base-layers.js */ "./resources/js/map/base-layers.js");
window.addParcelLayars = __webpack_require__(/*! ./map/parcel-layers.js */ "./resources/js/map/parcel-layers.js");
 //require('base-layers');
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
        setSpinner(thisEl, false);

        if (data.result === false) {
          toastr["error"](data.error);
        } //parcelFromBaseLayer.clearLayers();


        var coord = JSON.parse(data.parcel.json);
        var new_parcel = {
          "type": "Feature",
          "properties": {
            "id": data.parcel.id,
            "area": data.parcel.area,
            "cadnum": data.parcel.cadNum,
            "name": "parcelFromBase",
            "fromBase": true,
            "purpose": data.parcel.purpose
          },
          "geometry": {
            "type": coord.type,
            "coordinates": coord.coordinates
          }
        };
        parcelFromBaseLayer.addData(new_parcel);
        parcelFromBaseLayer.setStyle(parcelFromBaseStyle);
        parcelFromBaseLayer.addTo(parcelFromBaseGroup);
        /** Додаємо групу до карти    */

        parcelFromBaseGroup.addTo(leafletMap);
        (0,_map_services_js__WEBPACK_IMPORTED_MODULE_0__.setStyleIn)(parcelFromBaseLayer, data.parcel.id);
        updateDataTable(data);
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        toastr["error"]("Помилка обробки файла!");
        setSpinner(thisEl, false); //servicesThrowErrors(jqXHR);
      }
    });
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
          success: function success(data) {
            if (data.result === false) {
              toastr["error"](data.error);
            } else {
              toastr["success"]('Ділянка успішно видалена');
              updateDataTable(data);
            }
          },
          error: function error(jqXHR, textStatus, errorThrown) {
            toastr["error"]("Невідома помилка!");
          }
        }); //Swal.fire("Deleted!", "Your file has been deleted.", "success");
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
      success: function success(data) {
        if (data.result === false) {
          toastr["error"](data.error);
        } else {
          toastr["success"]('Ділянка успішно збережена');
          updateDataTable(data);
        }
      },
      error: function error(jqXHR, textStatus, errorThrown) {
        toastr["error"]("Невідома помилка!");
      }
    });
  });

  var updateDataTable = function updateDataTable(data) {
    var dataTable = $('.data-table');
    $(dataTable).html('');
    $(dataTable).append(data.tableHtml);
    $('#datatable').DataTable({
      order: [[4, 'desc']]
    });
  };

  leafletMap.fitBounds(parcelFromBaseLayer.getBounds());
});
}();
/******/ })()
;