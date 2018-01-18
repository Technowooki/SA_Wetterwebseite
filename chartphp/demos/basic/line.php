<?php
/**
 * Charts 4 PHP
 *
 * @author Shani <support@chartphp.com> - http://www.chartphp.com
 * @version 2.0
 * @license: see license.txt included in package
 */
 
include("../../lib/inc/chartphp_dist.php");
include("../../example_data.php");

$p = new chartphp();

// data array is populated from example data file
$p->data = $line_chart_data;
$p->chart_type = "line";

// Common Options
$p->title = "Line Chart";
$p->xlabel = "Months";
$p->ylabel = "Sales";
$p->series_label = array("2014","2015","2016","2017");
//$p->shape="vhv";
//$p->options["axes"]["yaxis"]["tickOptions"]["prefix"] = '$';
$p->color ="soft"; // Choices are "metro" "soft"
$out = $p->render('c1');
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="../../lib/js/jquery.min.js"></script>


	</head>
	<body>
		<div>
			<?php echo $out; ?>
		</div>
	</body>
</html>