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
    <section class="content-header">
      <h1>
        Wind Forecast
        <small>source: NOAA</small>
      </h1>
    </section>
    <section class="content">

        <div class="graph js-plotly-plot" style="height: 70vh;" id="wind-speed"></div>

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

<script>
    Plotly.d3.csv('https://raw.githubusercontent.com/plotly/datasets/master/wind_speed_laurel_nebraska.csv', function(rows){
        var trace = {
        type: 'scatter',                    // set the chart type
        mode: 'lines',                      // connect points with lines
        x: rows.map(function(row){          // set the x-data
            return row['Time'];
        }),
        y: rows.map(function(row){          // set the x-data
            return row['10 Min Sampled Avg'];
        }),
        line: {                             // set the width of the line.
            width: 1
        },
        error_y: {
            array: rows.map(function(row){    // set the height of the error bars
            return row['10 Min Std Dev'];
            }),
            thickness: 0.5,                   // set the thickness of the error bars
            width: 0
        }
        };

        var layout = {
        yaxis: {title: "Wind Speed"},       // set the y axis title
        xaxis: {
            showgrid: false,                  // remove the x-axis grid lines
        },
        margin: {                           // update the left, bottom, right, top margin
            l: 40, b: 20, r: 10, t: 20
        }
        };

        Plotly.plot(document.getElementById('wind-speed'), [trace], layout, {showLink: false});
    });
    </script>

</body>
</html>
