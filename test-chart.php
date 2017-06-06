<?php
	session_start();
	var_dump($_SESSION);
	include('classes/notices_controllers.php');
	$titleid = 5538;
	$title = 'THE DALLAS BUYERS CLUB';
	$isp = 'Primus Telecommunications Canada';
	$region = 'Ontario';
    
    $notices = new Notices;		

	$chart1 = $notices->load_chart_isp($isp,$titleid,$region);
    $chart2 = $notices->load_chart_isp2($isp,$titleid,$region);
    $charts1 = array();
    $charts2 = array();

	var_dump($chart1);
	var_dump($chart2);
	
    if($chart1){
		foreach($chart1 AS $k=>$i){
			$charts1[$i[0]] = array('date'=>$i[0],'value1'=>$i[1],'value2'=>0);
		}
    }
	if($chart2){
		foreach($chart2 AS $k=>$i){
			$charts2[$i[0]] = array('date'=>$i[0],'value1'=>0,'value2'=>$i[1]);
		}
    }

	if(count($charts1) >= count($charts2)){
		foreach($charts1 AS $k=>$i){
			if(array_key_exists($k,$charts2)){
				$charts1[$k]['value2'] = $charts2[$k]['value2'];
				unset($charts2[$k]);
			}
		}
	}
	else{
		foreach($charts2 AS $k=>$i){
			if(array_key_exists($k,$charts2)){
				$charts2[$k]['value2'] = $charts1[$k]['value2'];
				unset($charts1[$k]);
			}
		}
	}
	
	$charts = array_merge($charts1,$charts2);
	ksort($charts);							
?>
<html>
	<title><?php echo $_POST['isp'].'-CHART'; ?></title>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="js/jquery.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="https://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css">
		<script src="https://code.jquery.com/jquery-latest.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script src="js/pdf.js"></script>
		<script src="https://www.google.com/jsapi?autoload={%27modules%27:[{%27name%27:%27visualization%27,%27version%27:%271.1%27,%27packages%27:[%27corechart%27]}]}"></script>
		-->
		<!--
		<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css">
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/amcharts.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/serial.js"></script>
		<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/light.js"></script>
		<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
		-->
		<link rel="stylesheed" href="/amcharts/plugins/export"/>
		<script type="text/javascript" src="amcharts/amcharts.js"></script>
		<script type="text/javascript" src="amcharts/serial.js"></script>
		<script type="text/javascript" src="amcharts/themes/light.js"></script>
		<script type="text/javascript" src="amcharts/plugins/export/export.min.js"></script>

		<script>
			var chart,charts;
			$(document).ready(function(){

				var filename = $("#filename").val();
				draw_chart(filename);

			});

			function draw_chart(filename){

				chart = AmCharts.makeChart("chart3", {
			    "type": "serial",
			    "theme": "light",
				"legend": {"useGraphSettings": true},
			    "autoMargins": false,
			    "marginTop": 60,
			    "marginBottom": 60,
			    "marginLeft": 30,
			    "marginRight": 30,
			    "dataDateFormat": "YYYY-MM-DD",
			    "chartScrollbar": {
			        "graph": "g1",
			        "oppositeAxis":false,
			        "offset":30,
			        "scrollbarHeight": 80,
			        "backgroundAlpha": 0,
			        "selectedBackgroundAlpha": 0.1,
			        "selectedBackgroundColor": "#888888",
			        "graphFillAlpha": 0,
			        "graphLineAlpha": 0.5,
			        "selectedGraphFillAlpha": 0,
			        "selectedGraphLineAlpha": 1,
			        "autoGridCount":true,
			        "color":"#AAAAAA"
			    },
			    "chartCursor": {
			        "pan": true,
			        "valueLineEnabled": true,
			        "valueLineBalloonEnabled": true,
			        "cursorAlpha":1,
			        "cursorColor":"#258cbb",
			        "limitToGraph":"g1",
			        "valueLineAlpha":0.2
			    },
			    "valueScrollbar":{
			      "oppositeAxis":false,
			      "offset":50,
			      "scrollbarHeight":10
			    },
			    "categoryField": "date",
				  pathToImages: "//cdn.amcharts.com/lib/3/images/",
				  dataDateFormat: "YYYY-MM-DD",
				  categoryField: "date",

				  categoryAxis: {
				    parseDates: true,
				    minPeriod: "DD",
				    gridAlpha: 0.1,
				    minorGridAlpha: 0.1,
				    axisAlpha: 0,
				    minorGridEnabled: true,
				    inside: true,
				    title: "Date",
				  },

				  valueAxes: [{
						id: "v1",
						tickLength: 0,
						axisAlpha: 0,
						showFirstLabel: false,
						showLastLabel: false,

						guides: [{
						  value: 10,
						  toValue: 20,
						  fillColor: "#337ab7",
						  inside: true,
						  fillAlpha: 0.2,
						  lineAlpha: 0
						}]

					  },{
						id: "v2",
						tickLength: 0,
						axisAlpha: 0,
						showFirstLabel: false,
						showLastLabel: false,

						guides: [{
						  value: 10,
						  toValue: 20,
						  fillColor: "#a42e2e",
						  inside: true,
						  fillAlpha: 0.2,
						  lineAlpha: 0
						}]

					  }],

				  "valueAxes": [ {
				    "position": "left",
				    "title": "Infringers"
				  }, {
				    "position": "bottom",
				    "title": "Date"
				  },
				  {
				    "position": "top",
				    "title": "Title"
				  }
				],
				 graphs: [{
					    valueAxis: "v1",
						lineColor: "#337ab7",
						valueField: "value1",
						dashLength: 0,
						bullet: "round",
						title: "First Notice",
						balloonText: "[[category]]<br><b><span style='font-size:14px;'>Infringers: [[value]]</span></b>"
					  },{
					    valueAxis: "v2",
						lineColor: "#a42e2e",
						valueField: "value2",
						dashLength: 0,
						bullet: "round",
						title: "Second Notice",
						balloonText: "[[category]]<br><b><span style='font-size:14px;'>Infringers: [[value]]</span></b>"
					  }],
			    "export": {
			        "enabled": true,
					"menu": []
				},
			    "dataProvider": [
						
							<?php

									foreach($charts as $gl)
									{
										echo '
										{
											"date": "'.$gl['date'].'",
											"value1": '.$gl['value1'].',
											"value2": '.$gl['value2'].'
										},';
									}	
								?>
						
			    ]
			});
			
				chart.addListener("rendered", zoomChart);
							
				zoomChart();

			}

			function zoomChart() {
			    chart.zoomToIndexes(chart.dataProvider.length, chart.dataProvider.length);
			}
			/**
			 * Function that triggers export of all charts to PDF
			 */

			function exportCharts(filename) {
			  // iterate through all of the charts and prepare their images for export
			  var images = [];
			  var pending = AmCharts.charts.length;
			  for ( var i = 0; i < AmCharts.charts.length; i++ ) {
			    var chart = AmCharts.charts[ i ];
			    chart.export.capture( {}, function() {
			      this.toJPG( {}, function( data ) {
			        images.push( {
			          "image": data,
			          "fit": [ 523.28, 769.89 ]
			        } );
			        pending--;
			        if ( pending === 0 ) {
			           // all done - construct PDF
					   // Initiliaze a PDF layout
						var layout = {
						  "content": []
						}

						// Let's add a custom title
						layout.content.push({
						  "text": "Lorem ipsum dolor sit amet, consectetur adipiscing",
						  "fontSize": 15
						});
	
			          chart.export.toPDF( {
			            content: images
			          }, function( data ) {
						this.download( data, "application/pdf", filename+".pdf" );
			          } );
			        }
			      } );
			    } );
			  }
			}

			function exportReport() {
			  // Define IDs of the charts we want to include in the report
			  var ids = ["chart3"];

			  // Collect actual chart objects out of the AmCharts.charts array
			  charts = {},
			  charts_remaining = ids.length;
			  for (var i = 0; i < ids.length; i++) {
				for (var x = 0; x < AmCharts.charts.length; x++) {
				  if (AmCharts.charts[x].div.id == ids[i])
					charts[ids[i]] = AmCharts.charts[x];
				}
			  }
			  // Trigger export of each chart
			  for (var x in charts) {
				if (charts.hasOwnProperty(x)) {
				  var chart = charts[x];
				  chart["export"].capture({}, function() {
					this.toJPG({}, function(data) {

					  // Save chart data into chart object itself
					  this.setup.chart.exportedImage = data;

					  // Reduce the remaining counter
					  charts_remaining--;

					  // Check if we got all of the charts
					  if (charts_remaining == 0) {
						// Yup, we got all of them
						// Let's proceed to putting PDF together
						generatePDF();
					  }

					});
				  });
				}
			  }
			}
			
			function generatePDF(){
				// Initiliaze a PDF layout
				var layout = {
				  "content": []
				}

				layout.content.push({
				  "table": {
					"headerRows": 1,
					"widths": ["33%", "47%", "20%"],
					"body": [
						["GEO ISP:", "Title:", "GEO IP Region:"],
						[
							"<?php echo $isp; ?>",
							"<?php echo $title; ?>",
							"<?php echo $region; ?>",
						]
					]
				  },
				  "layout": 'noBorders'
				});
				
				layout.content.push({
					"text": " ",
					"columnGap": 50
				});
				
				layout.content.push({
				  "image": charts["chart3"].exportedImage,
				  "fit": [523, 300]
				});
				chart["export"].toPDF(layout, function(data) {
				  this.download(data, "application/pdf", "<?php echo $isp.'-'.$titleid; ?>.pdf");
				});
			}
			
			function print_save(){
				var printContents = document.getElementById('chart-bg').innerHTML;
			    var originalContents = document.body.innerHTML;
			    document.body.innerHTML = printContents;
			    window.print();
			    document.body.innerHTML = originalContents;
			}
		</script>
      	
      	<style>
      		table tr td{color:#1a237e;font-size: 13px;}   		
      		div.ui-datepicker{font-size:12px;}
		  	.ui-datepicker {
			    background: #337ab7;
			    border: 1px solid #555;
			    color: #EEE;
			}
			.chartdiv {
			    width       : 1100px;
			    margin:0px auto 15px;
			    height      : 600px;
			    font-size   : 11px;
			    /*background: #d9edf7;*/
				background: #ffffff;
			}
			#JSFiddle{
				position:relative;
			    width       : 1100px;
			    margin:0px auto 15px;
				border-radius:0 2px 2px 2px;
				box-shadow: 0px -4px 9px rgba(0,0,0,0.45);
				-webkit-box-shadow: 0px -4px 9px rgba(0,0,0,0.45);
				-moz-box-shadow: 0px -4px 9px rgba(0,0,0,0.45);
			}
			a#exportToPDFBTN {
				position: absolute;
				right: 10px;
				top: 10px;
				border: 1px solid #ccc;
				padding: 2px 10px;
				border-radius: 2px;
				box-shadow: 0px 1px 2px #ccc;
				-moz-box-shadow: 0px 1px 2px #ccc;
				-webkit-box-shadow: 0px 1px 2px #ccc;
				cursor: pointer;
				z-index: 99;
				text-decoration: none;
			}
			#chart-title-wrapper{
				box-shadow: inset 0px -5px 5px rgba(0, 0, 0, 0.15);
				-webkit-box-shadow: inset 0px -5px 5px rgba(0, 0, 0, 0.15);
				-moz-box-shadow: inset 0px -5px 5px rgba(0, 0, 0, 0.15);
			}
			/*.amcharts-export-menu{display:none;}*/
			a[title="JavaScript charts"]{ display:none !important;}
			#tableinfo tr td{font-size: 20px;font-style: bold;padding:10px;}
			.chart-bg{width:100%;margin: 0px auto;/*background-image:url('img/notice-bg.jpg');border:1px solid black;*/}
			.body-bg{background:#2B2929;}
			.infoText{width:100%;padding-top:15px;padding-bottom:15px;background:#BAD3F7;
				 box-shadow: 0px 8px 5px gray;
			    -moz-box-shadow: 0 8px 8px -2px gray;
			    -webkit-box-shadow: 0 8px 8px -2px gray;
			}
			#tblheaderTxt{width: 100%;}
			#tblheaderTxt tr td{padding:0px 10px 0px 10px;width:33%;}
			.innerText{background:#67B7DC;padding:10px 0px 10px 0px;font-size: 18px;color:black;border-radius: 10px;}
			.innerText2{ padding:10px 0px 10px 0px;font-size: 18px;color:black;border-radius: 10px;}
			#save{position:absolute;right:20px;top:20px;position:fixed;}
      	</style>
      </head>	
	<body style="background:#fff;">
		<a href="javascript:print_save()" class="btn btn-primary" id="save"><i class="glyphicon glyphicon-floppy-disk"></i> Save/Print</a>
		<div class="chart-bg" id="chart-bg">
			<input type="hidden" id="filename" value="<?php echo $isp.'-'.$titleid; ?>">

			<div id="tblhtext" class="infoText">
				<table id="tblheaderTxt" style="width: 1100px !important;margin: 0 auto;">
					<tr>
						<td>
							<div class="innerText2">
								GEO ISP : <span id="tblGEOISP" ><?php echo $isp; ?></span>
							</div>	
						</td>
						<td>
							<div class="innerText2">
								Title ID : <span  id="tblTitleId"><?php echo $titleid; ?></span>
							</div>	
						</td>
						<td>
							<div class="innerText2">
								GEO IP Region : <span  id="tblGEOIPRegion"><?php echo $region; ?></span>
							</div>	
						</td>
					</tr>
				</table>
			</div>
			<br><br>
			<div style="position:relative;width:1100px;margin:0 auto;">
				<div style="display:none;position:absolute;border:4px solid #67B7DC;top:20px;width:100%;z-index:0;"></div>
				<center style="display:none;">
					<div class="innerText" style="position:absolute;left:350px;width:300px;font-size:18px;z-index:999;">
						<center><i class="glyphicon glyphicon-stats"></i> CHART</center>
					</div>
				</center>
				<div id="chart-title-wrapper" class="innerText" style="display: inline-block;padding: 10px 30px;background: #bad3f7;border-radius: 2px 2px 0 0;border:1px solid rgba(0,0,0,0.1);">
					<center><i class="glyphicon glyphicon-stats"></i> CHART</center>
				</div>
			</div>
			<div id="JSFiddle">
				<a id="exportToPDFBTN" type="button" value="Export to PDF" onclick="exportReport();">Export PDF</a>
				<div id="chart3" class="chartdiv">
				</div>
			</div>	
		</div>	
	</body>	
</html> 