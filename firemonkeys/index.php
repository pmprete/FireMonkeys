<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>

<?php include ('head.php');?>

<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include ('header.php');?>
  
  <?php include ('sidemenu.php');?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">

        <div id="cesiumContainer"></div>

    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include ('footer.php');?>
  
    <?php include ('sidebar.php');?>

 </div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="./resources/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="./resources/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="./resources/js/app.min.js"></script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
<script src="./resources/plugins/slimScroll/jquery.slimscroll.js"></script>

<script src="./resources/js/moment.js"></script>
<script src="./resources/cesium/Cesium.js"></script>  
<script src="./resources/js/chechium.js"></script>
<script src="./resources/js/chechium-config.js"></script>

<script>   
    Chechium.newViewer();
    Chechium.drawing = true;
    /*
    var entity = Chechium.newEntity({
        lat: -34.6083,
        lng: -58.3712,
        model: './resources/cesium/Models/IL-76.glb'
    });
    Chechium.viewer.trackedEntity = entity;
    */

//rotation requires Quaternions
//https://groups.google.com/forum/#!msg/cesium-dev/q01DY3kPxtg/vXT7DIZ7mZUJ

 var airtankerCZML = [{
    "id" : "document",
    "name" : "CZML Model",
    "version" : "1.0",
    "clock": {
        "interval": "2012-08-04T16:00:00Z/2012-08-04T22:00:00Z",
        "currentTime": "2012-08-04T16:00:00Z",
        "multiplier": 100
    }
}, {
    "id" : "airtanker model",
    "name" : "Ilyushin Il-76 (NATO reporting name: Candid)",
    "position" : {
        "interpolationAlgorithm": "LAGRANGE",
        "interpolationDegree": 1,
        "epoch": "2012-08-04T16:00:00Z",
        "cartesian": "...cartesian..."
    },
    "model": {
        "gltf" :  "./resources/cesium/Models/IL-76.glb",
        "scale" : 2.0,
        "minimumPixelSize": 128
    }
}];

 var chopperCZML = [{
    "id" : "document",
    "name" : "CZML Model",
    "version" : "1.0",
    "clock": {
        "interval": "2012-08-04T16:00:00Z/2012-08-04T20:00:00Z",
        "currentTime": "2012-08-04T16:00:00Z",
        "multiplier": 100
    }
}, {
    "id" : "chopper model",
    "name" : "BELL 212",
    "position" : {
        "cartographicDegrees" : [-58.3712, -34.6083, 10000]
    },
    "model": {
        "gltf" :  "./resources/cesium/Models/Bell212.glb",
        "scale" : 2.0,
        "minimumPixelSize": 128
    }
}];

  var wallCZML = [{
    "id" : "document",
    "name" : "CZML Model",
    "version" : "1.0",
    "clock": {
        "interval": "2012-08-04T16:00:00Z/2012-08-04T20:00:00Z",
        "currentTime": "2012-08-04T16:00:00Z",
        "multiplier": 100
    }
},
{
    "id" : "wall",
    "wall" : {
        "positions" : {
            "interval" : "2012-08-04T16:00:00Z/2012-08-04T18:00:00Z",
            "cartographicDegrees" : [
                -58.3712, -34.6083, 100000,
                -58.3712, -35.6083, 100000,
                -59.3712, -35.6083, 100000,
                -59.3712, -36.6083, 100000,
                -60.3712, -37.6083, 100000
            ]
        },
        "material" : {
            "solidColor" : {
                "color" : {
                    "rgba" : [255, 0, 0, 150]
                }
            }
        }
    }
}];

    var czml = [
    {
        "id" : "document",
        "name" : "CZML Polygon - Intervals and Availability",
        "version" : "1.0",
        "clock": {
            "interval": "2012-08-04T16:00:00Z/2012-08-04T17:00:00Z",
            "currentTime": "2012-08-04T16:00:00Z",
            "multiplier": 900
        }
    },
    {
        "id" : "dynamicPolygon",
        "name": "Dynamic Polygon with Intervals",
        "availability":"2012-08-04T16:00:00Z/2012-08-04T17:00:00Z",
        "polygon": {
            "positions": [{
                "interval" : "2012-08-04T16:00:00Z/2012-08-04T16:20:00Z",
                "cartographicDegrees" : [
                    -70.76980590820312, -32.3822809650579,  1000,
                    -70.762939453125, -32.393877575286446, 1000,
                    -70.72311401367188, -32.406632126733044, 1000,
                    -70.68740844726562, -32.39851580247401, 1000,
                    -70.67779541015624, -32.3846004062099, 1000,
                    -70.68740844726562, -32.36140331527542, 1000,
                    -70.72448730468749, -32.34168110749221, 1000,
                    -70.73822021484375, -32.34168110749221, 1000,
                    -70.751953125, -32.34632201382947, 1000,
                    -70.76980590820312, -32.36024330444844, 1000,
                    -70.77255249023438, -32.373002604986546, 1000
            ]
            }, {
                "interval" : "2012-08-04T16:20:00Z/2012-08-04T16:40:00Z",
                "cartographicDegrees": [
                    -70.76980590820312, -32.3822809650579,  0,
                    -70.762939453125, -32.393877575286446, 0,
                    -70.72311401367188, -32.406632126733044, 0,
                    -70.68740844726562, -32.39851580247401, 0,
                    -70.67779541015624, -32.3846004062099, 0,
                    -70.68740844726562, -32.36140331527542, 0,
                    -70.72448730468749, -32.35168110749221, 0,
                    -70.73822021484375, -32.35168110749221, 0,
                    -70.751953125, -32.34632221382947, 0,
                    -70.76980590820312, -32.37024330444844, 0,
                    -70.77255249023438, -32.383002604986546, 0
                ]
            }, {
                "interval" : "2012-08-04T16:40:00Z/2012-08-04T17:00:00Z",
                "cartographicDegrees" : [
                    -70.76980590820312, -32.3822809650579,  0,
                    -70.762939453125, -32.393877575286446, 0,
                    -70.72311401367188, -32.406632126733044, 0,
                    -70.68740844726562, -32.39851580247401, 0,
                    -70.67779541015624, -32.3846004062099, 0,
                    -70.68740844726562, -32.39140331527542, 0,
                    -70.72448730468749, -32.38168110749221, 0,
                    -70.73822021484375, -32.38168110749221, 0,
                    -70.751953125, -32.36632201382947, 0,
                    -70.76980590820312, -32.37024330444844, 0,
                    -70.77255249023438, -32.393002604986546, 0
                ]
            }],
            "material": {
                "solidColor": {
                    "color": [{
                        "interval" : "2012-08-04T16:00:00Z/2012-08-04T16:30:00Z",
                        "rgba" : [255, 0, 0, 150]
                    }, {
                        "interval" : "2012-08-04T16:30:00Z/2012-08-04T17:00:00Z",
                        "rgba" : [255, 0, 0, 150]
                    }]
                }
            }
        }
    }];


var dataSourcePromise = Chechium.viewer.dataSources.add(Cesium.CzmlDataSource.load(czml));
dataSourcePromise.then(function(dataSource){
    Chechium.viewer.trackedEntity = dataSource.entities.getById("dynamicPolygon");
}).otherwise(function(error){
    window.alert(error);
});

function enableDrawing(){
    Chechium.drawingModeEnabled = true;
    Chechium.drawing = false;
};

</script>
</body>
</html>
