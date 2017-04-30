/*
<script src="./resources/js/chechium.js"></script>  
<script src="./resources/js/chechium-config.js"></script>  
<script>
  Chechium.newViewer();
  Chechium.loadConfig();
</script>
*/


var Chechium = Chechium || {};
Cesium.BingMapsApi.defaultKey = "AqMkSLS6dI40Hj0aKnFokBw1zfgwsW7aIohyODaebG7LCEK0P2eMEqfX6t4O11dq";

Chechium.newViewer = function () { 
    //load imageryProviderViewModels
    var models = [];
    var providers = [];
    ChechiumConfig.wms.forEach(function (service, index) {
        var provider = Chechium.getWMSProvider(service);
        var model = new Cesium.ProviderViewModel({
            name: service.name,
            tooltip: service.description,
            iconUrl: service.icon,
            creationFunction: function() {
                return provider;
            }
        });
        providers.push(provider);
        models.push(model); 
    });
    
    //create viewer    
    Chechium.viewer = new Cesium.Viewer('cesiumContainer', {
        // no permite superponer capas...
        // cambiar por http://cesiumjs.org/Cesium/Apps/Sandcastle/index.html?src=Imagery%20Layers%20Manipulation.html&label=Showcases
        imageryProviderViewModels: models
    });
    /*
    var bing = new Cesium.BingMapsImageryProvider({
        url: 'https://dev.virtualearth.net',
        key: "AqMkSLS6dI40Hj0aKnFokBw1zfgwsW7aIohyODaebG7LCEK0P2eMEqfX6t4O11dq",
        mapStyle: Cesium.BingMapsStyle.AERIAL
    });
    Chechium.viewer.imageryLayers.addImageryProvider(bing);
    */
    //create handler for future clicks    
    Chechium.handler = new Cesium.ScreenSpaceEventHandler(Chechium.viewer.scene.canvas);
};

/**
 * Proxy to avoid CORS
 */
Chechium.proxy = {
    getURL: function (url) {
        return '/firemonkeys/proxy.php?url=' + encodeURIComponent(url);
    }
};

Chechium.layerClick = function () {    
    Chechium.handler.setInputAction(function(click) {
        var pickedObject = viewer.scene.pick(click.position);
        if (Cesium.defined(pickedObject)) {
            var entityId = pickedObject.id._id;
            var oldColor = buildingMap.get(entityId).polygon.material.color;
            buildingMap.get(entityId).polygon.material.color = pickColor;
            selectedEntity.set(entityId, oldColor);

            var currentLayer = viewer.scene.imageryLayers.get(1);
            if (typeof currentLayer !== 'undefined') {
                var info = currentLayer.imageryProvider.pickFeatures(click.position.x, click.position.y);
                console.log(info);
                Cesium.when(info, function (result) { 
                    console.log(result);
                })
            }
        }
    }, Cesium.ScreenSpaceEventType.LEFT_CLICK);    
};

Chechium.getWMSProvider = function (options) {
    var parameters = {
        format: 'image/png',
        transparent: true
    };

    if (options.parameters) {
        for (var attrname in options.parameters) {
            parameters[attrname] = options.parameters[attrname];
        }
    }
    //see: https://cesiumjs.org/Cesium/Build/Documentation/WebMapServiceImageryProvider.html    
    return new Cesium.WebMapServiceImageryProvider({
        url : options.url,
        layers : options.layer,
        parameters: parameters,
        proxy: Chechium.proxy
    });
};

Chechium.newEntity = function (options) {
    options.height = options.height || 0.0;
    return Chechium.viewer.entities.add({
        position: Cesium.Cartesian3.fromDegrees(options.lng, options.lat, options.height),
        model: {
            uri: options.model
        }
    });
};