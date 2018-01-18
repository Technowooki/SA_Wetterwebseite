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


$p->data=$bar_chart_data;
$p->chart_type = "bar";

// Common Options
$p->title = "Bar Chart";
$p->xlabel = "Months";
$p->ylabel = "Purchase";
$p->showxticks = true;
$p->showyticks = true;
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


