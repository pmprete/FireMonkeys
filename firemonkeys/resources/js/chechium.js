var Chechium = Chechium || {};
Cesium.BingMapsApi.defaultKey = "AqMkSLS6dI40Hj0aKnFokBw1zfgwsW7aIohyODaebG7LCEK0P2eMEqfX6t4O11dq";

Chechium.drawingModeEnabled = false;
Chechium.drawing = false;
Chechium.polyline;
Chechium.polylinePositions = [];

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
    Chechium.viewer = new Cesium.Viewer('cesiumContainer', /*{
        // no permite superponer capas...
        // cambiar por http://cesiumjs.org/Cesium/Apps/Sandcastle/index.html?src=Imagery%20Layers%20Manipulation.html&label=Showcases
        imageryProviderViewModels: models
    }*/);
    
    var bing = new Cesium.BingMapsImageryProvider({
        url: 'https://dev.virtualearth.net',
        key: "AqMkSLS6dI40Hj0aKnFokBw1zfgwsW7aIohyODaebG7LCEK0P2eMEqfX6t4O11dq",
        mapStyle: Cesium.BingMapsStyle.AERIAL
    });
    Chechium.viewer.imageryLayers.addImageryProvider(bing);
    
    Chechium.polyline = Chechium.viewer.entities.add({
        polyline: {
            positions: new Cesium.CallbackProperty(function () {
                return Chechium.polylinePositions;
            }, false)
        }
    });

    //left click handler
    Chechium.handler = new Cesium.ScreenSpaceEventHandler(Chechium.viewer.scene.canvas);
    var ellipsoid = Chechium.viewer.scene.globe.ellipsoid;
    var camera = Chechium.viewer.camera;
    Chechium.handler.setInputAction(function (click) {
        var cartesian = camera.pickEllipsoid(click.position, ellipsoid);
        
        if (cartesian) {
            //draw hanlder
            if (Chechium.drawingModeEnabled) {
                Chechium.drawLeftClickHanlder(click);
            }

            var cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            if (Chechium.leftClickHandler) { 
                Chechium.leftClickHandler({ 
                    longitude: Cesium.Math.toDegrees(cartographic.longitude),
                    latitude: Cesium.Math.toDegrees(cartographic.latitude)
                });
            }
        }        
    }, Cesium.ScreenSpaceEventType.LEFT_CLICK);    
    
    //right click handler
    Chechium.handler.setInputAction(function (click) {
        var cartesian = camera.pickEllipsoid(click.position, ellipsoid);        
        if (cartesian) {
            var cartographic = Cesium.Cartographic.fromCartesian(cartesian);
            if (Chechium.rightClickHandler) { 
                Chechium.rightClickHandler({ 
                    longitude:  Cesium.Math.toDegrees(cartographic.longitude),
                    latitude: Cesium.Math.toDegrees(cartographic.latitude)
                });
            }
        }
    }, Cesium.ScreenSpaceEventType.right_CLICK);    

      Chechium.handler.setInputAction(Chechium.drawMoveHandler, Cesium.ScreenSpaceEventType.MOUSE_MOVE
  );

};

Chechium.drawMoveHandler = function (movement) {
    if (!Chechium.drawingModeEnabled) return;
    
    var surfacePosition = Chechium.viewer.camera.pickEllipsoid(movement.endPosition);
    if (Chechium.drawing && Cesium.defined(surfacePosition)) {
        Chechium.polylinePositions.push(surfacePosition);
    }
};
Chechium.drawLeftClickHanlder = function (click) {
    if (Chechium.drawing) {
        Chechium.viewer.entities.add({
            polygon: {
                hierarchy: {
                    positions: Chechium.polylinePositions
                },
                outline: true
            }
        });
        Chechium.viewer.entities.remove(Chechium.polyline);
        Chechium.polylinePositions = [];
    } /*else {
        Chechium.polyline = Chechium.viewer.entities.add({
            polyline: {
                positions: new Cesium.CallbackProperty(function () {
                    return Chechium.polylinePositions;
                }, false)
            }
        });
    }*/
    Chechium.drawing = !Chechium.drawing;
    if (!Chechium.drawingModeEnabled) { 
        Chechium.drawingModeEnabled = !Chechium.drawingModeEnabled;
    }
};

/**
 * Proxy to avoid CORS
 */
Chechium.proxy = {
    getURL: function (url) {
        return '/firemonkeys/proxy.php?url=' + encodeURIComponent(url);
    }
};

Chechium.leftClickHandler = function (position) {    
    console.log("Left Clicked on", position);
};

Chechium.rightClickHandler = function (position) {
    console.log("Right Clicked on", position);
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