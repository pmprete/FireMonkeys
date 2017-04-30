var Chechium = Chechium || {};
Cesium.BingMapsApi.defaultKey = "AqMkSLS6dI40Hj0aKnFokBw1zfgwsW7aIohyODaebG7LCEK0P2eMEqfX6t4O11dq";

Chechium.drawingModeEnabled = false;
Chechium.drawing = false;
Chechium.polyline;
Chechium.polylinePositions = [];

function createLayerForMenu(service){
    return '<li><a href="#" onclick=switchLayer('+service.id+')><i class="menu-icon fa fa-map-o bg-red"></i><div class="menu-info"><h4 class="control-sidebar-subheading">'+service.name+'</h4><p>'+service.description+'</p></div></a></li>';
}

function switchLayer(id) { 
    Chechium.viewer.imageryLayers.get(id).show = !Chechium.viewer.imageryLayers.get(id).show;
}
Chechium.newViewer = function () { 
    //load imageryProviderViewModels
    var models = [];
    var providers = [];

    ChechiumConfig.wms.forEach(function (service, index) {
        var provider = Chechium.getWMSProvider(service);
        providers.push(provider);
        service.id = index;
        var menuItem = createLayerForMenu(service);
        $("#layers-sidebar-menu").append(menuItem);
    });
    
    //create viewer    
    Chechium.viewer = new Cesium.Viewer('cesiumContainer');

    providers.forEach(function (provider, index) {
        Chechium.viewer.imageryLayers.addImageryProvider(provider, index);
    });
    

    //for drawing
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