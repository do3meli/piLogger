<?php
  require_once($_SERVER['DOCUMENT_ROOT']."/stub.inc");
  require_once($root . "myfunctions.inc");

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>piLogger</title>

    <!--
    <link rel="shortcut icon" href="http://pilogger.smalometern.com/favicon.ico" type="image/vnd.microsoft.icon"/>
    <link rel="icon" href="http://pilogger.smalometern.com/favicon.ico" type="image/x-ico"/>
    -->

    <link rel="shortcut icon" href="/favicon.ico" >
    <link rel="icon" href="/favicon.ico" >

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/20140227.0006.foundation.css">
    <link href="http://fonts.googleapis.com/css?family=Corben:bold" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Nobile" rel="stylesheet" type="text/css">

    <script src="http://fgnass.github.io/spin.js/spin.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script src="http://code.highcharts.com/highcharts-more.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>

    <link rel="stylesheet" href="css/main.css">
  
    <script src="myJs/myfunctions.js"></script>

  <!-- http://www.highcharts.com/docs/getting-started/your-first-chart -->
<script type="text/javascript">
   console.log("XXX Here it starts");

  $(function(){
/*
    var target = document.getElementById('highcharts');
    var spinner = new Spinner(getSpinnerOpts()).spin(target);

    // print the graph

    var url="api/sensorData";
    //var url="cache/sensorData.json";
    console.log("XXX graph data url: " + url);

    $.ajax({
      url: url,
      type: 'GET',
      dataType: "json",
      success: function(data) {
        draw_chart(data);
          console.log("XXX Stopping spinner");
          spinner.stop();
      },
      error: function(data) {
          spinner.stop();
          var target2 = document.getElementById('highcharts');
          target2.innerHTML = 'Error: could not load data for graph';
      }
    //end of ajax
    });

*/

   var nothing = printGraph('piLogger12h', '12h');
   var nothing = printGraph('piLogger24h', '24h');
   var nothing = printGraph('piLogger168h', '168h');

   console.log("XXX End of graph");

  //========================================
  // Devices
  //========================================
    console.log("XXX Start of device listing");

    var target = document.getElementById('deviceContainer');
    var dp = document.getElementById('device-pane');
    var spinner2 = new Spinner(getSpinnerOptsSmall()).spin(target);

    // print the devices into the devicesContainer
   var url="api/sensor/all/info";
   //var url="cache/sensorAllInfo.json";
   console.log("XXX url: " + url);

    $.ajax({
      url: url,
      type: 'GET',
      dataType: "json",
      success: function(data) {
        updateDevicesPane(data);
        var nothing = printGauge('deviceGauges1', data[0] ); 
        var nothing = printGauge('deviceGauges2', data[1] ); 
        var nothing = printGauge('deviceGauges3', data[2] ); 
        var nothing = printGauge('deviceGauges4', data[3] ); 
//        dp.innerHTML = 'Device: ' + data[0].sensorName;
        spinner2.stop();
      },
      error: function(data) {
        spinner2.stop();
        var target2 = document.getElementById('deviceContainer');
        target2.innerHTML = 'Error: could not load data for devices';
      }
    //end of ajax
    });


// 2014-02-19
//    $('#device-pane').hide();
//    $('#deviceContainer').hover(function() {
//        $('#device-pane').toggle();
//    });


//  var curData = { "sensorName" : "device1" , "temperature" : 80 };
//  var nothing = printGauge('deviceGauges', curData ); 

  console.log("XXX End of jquery block");
});

   console.log("XXX Here it ends");
</script>
  </head>
  <body>

<!-- http://thule.mine.nu/html/ -->

<!-- Header and Nav -->
  <div class="row">
    <div class="large-2 columns">
      <h1><img src="/images/rpi-logo.png" /></h1>
    </div>
    <div class="large-10 columns">
      <ul class="inline-list right">
        <li><a href="index.html">Home</a></li>
        <li><a href="https://github.com/maglub/piLogger">About</a></li>
      </ul>
    </div>
  </div>
 

<!-- End Header and Nav -->
  
  
  <div class="row">    
    <!-- Main Content Section -->
    <!-- This has been source ordered to come first in the markup (and on small devices) but to be to the right of the nav on larger screens -->
    <div class="large-10 push-2 columns">      
       <div id="piLogger12h" style="min-width: 600px; min-height: 400px ; margin: 0 auto"></div>
       <div id="piLogger24h" style="min-width: 400px; min-height: 400px ; margin: 0 auto"></div>
       <div id="piLogger168h" style="min-width: 400px; min-height: 400px ; margin: 0 auto"></div>
       <div id="deviceContainer" style="min-width: 400px; margin: 0 auto"></div>
       <div id="deviceGauges1" style="width: 200px; height: 200px;  margin: 0 auto; float:left"></div>
       <div id="deviceGauges2" style="width: 200px; height: 200px;  margin: 0 auto; float:left"></div>
       <div id="deviceGauges3" style="width: 200px; height: 200px;  margin: 0 auto; float:left"></div>
       <div id="deviceGauges4" style="width: 200px; height: 200px;  margin: 0 auto; float:left"></div>
    </div>

    
    <!-- Nav Sidebar -->
    <!-- This is source ordered to be pulled to the left on larger screens -->
    <div class="large-2 pull-10 columns">
      <ul class="side-nav">
         <li class="nav-header">Temperature</li>
         <li><a href="index.html?3h">Last 3h</a></li>
         <li><a href="/graphs">Graph files</a></li>
         <li><a href="/xml">XML files</a></li>
         <li><a href="/api/sensors">Devices</a></li>

         <div id="device-pane">some text</div>
      </ul>
    </div>
  </div>
  <!-- Footer -->
  <footer class="row">
    <div class="large-12 columns">
      <hr />
      <div class="row">
        <div class="large-6 columns">
          <p>&copy; KMG Group GmbH</p>
        </div>
        <div class="large-6 columns">
          
        </div>
      </div>
    </div> 
  </footer>

<script>
</script>

<?php
  $myDevices=getDevices();

  print "<h2>Devices</h2>\n";
  print "<ul>\n";
  foreach ($myDevices as $device) {
    print "<li> {$device['id']} - {$device['alias']} <br>\n";
  }
  print "</ul>\n";

  print "<h2>DB Files</h2>\n";
  print "<ul>\n";
  $myDbFiles = getDeviceStores();
  foreach ($myDbFiles as $dbFile) {
    preg_match('/^(...[^\.]*)/', $dbFile, $cur_id);
    print "<li> {$dbFile} - Device: {$cur_id[0]} Alias: " . getDeviceAliasById($cur_id[0]) . "<br>\n";
  }
  print "</ul>\n";


?>

  </body>
</html>