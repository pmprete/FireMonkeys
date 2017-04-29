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

<script src="./resources/cesium/Cesium.js"></script>  
<script>      
  var fire24Provider = new Cesium.WebMapTileServiceImageryProvider({
    url : 'https://firms.modaps.eosdis.nasa.gov/wms/c6/?',
    layer : 'fires24'
  });
  
  var viewer = new Cesium.Viewer('cesiumContainer',{
    imageryProvider: fire24Provider,
    baseLayerPicker : false
  });
  /*
  var entity = viewer.entities.add({
      position : Cesium.Cartesian3.fromDegrees(-123.0744619, 44.0503706),
      model : {
          uri : '/resources/cesium/Models/ISS_Interior.glb'
      }
  });
  viewer.trackedEntity = entity;
  */

  //MODIS Hotspots
  var fire24Provider = new Cesium.WebMapServiceImageryProvider({
    url : 'https://firms.modaps.eosdis.nasa.gov/wms/c6/?',
    layers : 'fires24'
  });
  viewer.imageryLayers.addImageryProvider(fire24Provider, 1);
  var fire48Provider = new Cesium.WebMapServiceImageryProvider({
    url : 'https://firms.modaps.eosdis.nasa.gov/wms/c6/?',
    layers : 'fires48'
  });
  viewer.imageryLayers.addImageryProvider(fire48Provider, 2);
</script>

</body>
</html>
