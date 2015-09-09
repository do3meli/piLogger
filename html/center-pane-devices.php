<?php
  global $root;
  require_once($_SERVER['DOCUMENT_ROOT']."/stub.inc");
  require_once($root . "myfunctions.inc");
?>
    <!-- center-pane.php -->

    <!-- Main Content Section -->
    <!-- This has been source ordered to come first in the markup (and on small devices) but to be to the right of the nav on larger screens -->
    <div class="large-10 large-push-2 small-11 small-push-1 columns">
    <!-- XXX -->
<!--    <?php  printDbPlotConfig(); ?> -->
    <!-- XXX -->

   <script type="text/javascript">
    //# Example from: http://omnipotent.net/jquery.sparkline/#s-docs
    $(function() {
        /** This code runs when everything has been loaded on the page */
        /* Inline sparklines take their values from the contents of the tag */
        $('.inlinesparkline').sparkline(); 

        /* Sparklines can also take their values from the first argument 
        passed to the sparkline() function */
        var myvalues = [10,8,5,7,4,4,1];
        $('.dynamicsparkline').sparkline(myvalues);

        /* The second argument gives options such as chart type */
        $('.dynamicbar').sparkline(myvalues, {type: 'bar', barColor: 'green'} );

        /* Use 'html' instead of an array of values to pass options 
        to a sparkline with data in the tag */
        $('.inlinebar').sparkline('html', {type: 'bar', barColor: 'red'} );
    });
    </script>
</head>
<body>

<ul>
<?php

  foreach (getDevices() as $curDevice){
    print "<li><span class=\"inlinesparkline\">" . printSparklineByDeviceId($curDevice['id']) . "</span> - {$curDevice['alias']} - {$curDevice['id']}\n";
  }
?>
</ul>

    </div>
