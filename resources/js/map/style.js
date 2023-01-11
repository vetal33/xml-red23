/**
 * Стиль для межі населеного пункту
  */
window.boundaryStyle = {
    "color": '#ff735b',
    "weight": 7,
    "opacity": 1,
    "fillOpacity": 0.05,
    "fillColor": '#5bff10',
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
    "opacity": 1,
};

/**
 * Стиль для локального фактору під час виділення
 */

window.selectLocalStyle = {
    "color": '#ffffff',
    "weight": 2,
    "opacity": 1,
};

/**
 * Стиль для імпортованої ділянки з json
 */
window.addFeatureFromJsonStyle = {
    "color": '#290a30',
    "weight": 1,
    "opacity": 1,
    "fillOpacity": 0.4,
    "fillColor": '#b3ffc9',
};

/**
 * Стиль для ділянки з бази
  */
window.parcelFromBaseStyle = {
    "color": '#290a30',
    "weight": 1,
    "opacity": 1,
    "fillOpacity": 0.4,
    "fillColor": '#1ed9ff',
};

/**
 * Стиль для виділеної ділянки
 */
window.addFeatureFromJsonSelectedStyle = {
    "color": '#9a14a5',
    "weight": 1,
    "opacity": 1,
    "fillOpacity": 0.5,
    "fillColor": '#fff327',
};

/**
 * Стиль для ділянки перетину з локальним фактором
 */
window.intersectLocalsStyle = {
    "color": '#301005',
    "weight": 1,
    "opacity": 0,
    "fillOpacity": 0,
    "fillColor": '#8dff14',
};

/**
 * Стиль для ділянки перетину з локальним фактором під час наведення
 */
window.intersectLocalsSelectedStyle = {
    "color": '#290a30',
    "weight": 1,
    "opacity": 1,
    "fillOpacity": 0.9,
    "fillColor": '#ff8e09',
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
