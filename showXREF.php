<?php 
 session_start();

    
    if($_SESSION['notices']['loginStat'] == '')
    {
        header('Location: ./') ;  
    }
    if(isset($_POST['logout']))
    {
        $_SESSION['notices']['loginStat'] = '';
        header('Location: ./');
       
    }   
    session_write_close();
	$ip = isset($_POST['ip'])? $_POST['ip'] : '';
	if($ip){
		//'https://securedservice.net/xp/?i=76.115.169.128&k=23361737&t=0ywpqjvid7tv6rm24xj24pvzov6spu1q&m=x'
		$url = 'https://securedservice.net/xp/?i='.$ip.'&k=23361737&t=0ywpqjvid7tv6rm24xj24pvzov6spu1q&m=x';
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
		
		$content = file_get_contents($url);
		$content = json_decode($content);
		if($content && is_array($content)){
		?>
		<a href="" id="btncaptureIPAllBack" style="position:absolute;left:0px;top:-20px;"><i class="glyphicon glyphicon-backward"></i> Back to home details</a>
		<table id="dg" title="IP <?php echo $_POST['ip']; ?> XREF" class="easyui-datagrid" style="height:600px;" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
			<thead>
				<tr>
					<th field="oip" width="50">Torrent Name</th>
					<th field="port" width="50">Torrent Hash</th>
					<th field="timestamp" width="50">Type</th>
					<th field="client" width="50">First Seen</th>
					<th field="filename" width="50">Last Seen</th>
					<th field="rightsholder" width="50">Occurances</th>
					<th field="sha1" width="50">IP</th>
				</tr>
			</thead>
			<tbody>
		<?php
			foreach($content AS $k=>$i){
				echo '<tr>';
				echo '<td>'.$i->TorrentName.'</td>';
				echo '<td>'.$i->TorrentHash.'</td>';
				echo '<td>'.$i->Type.'</td>';
				echo '<td>'.$i->FirstSeen.'</td>';
				echo '<td>'.$i->LastSeen.'</td>';
				echo '<td>'.$i->Occurances.'</td>';
				echo '<td>'.$i->IP.'</td>';
				echo '</tr>';
			}
		?>
			</tbody>
		</table>
		<div id="toolbar">
			<a href="javascript:void(0)" id="exportIP" class="easyui-linkbutton" plain="true"><img src="img/export.png"> EXPORT</a>
		</div>
		
		<input type="hidden" id="IP" value="<?php echo $ip; ?>">	 
		<link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
		<link rel="stylesheet" type="text/css" href="css-easyui/color.css">
		<link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
		<script type="text/javascript" src="js-easyui/easyui.js"></script>
		<script type="text/javascript">
			function exportTableToCSV($table, filename) {
				var $rows = $table.find('tr:has(td),tr:has(th)'),
			
				// Temporary delimiter characters unlikely to be typed by keyboard
				// This is to avoid accidentally splitting the actual contents
				tmpColDelim = String.fromCharCode(11), // vertical tab character
				tmpRowDelim = String.fromCharCode(0), // null character
		
				// actual delimiter characters for CSV format
				colDelim = '","',
				rowDelim = '"\r\n"',
		
				// Grab text from table into CSV formatted string
				csv = '"' + $rows.map(function (i, row) {
					var $row = $(row), $cols = $row.find('td,th');
		
					return $cols.map(function (j, col) {
						var $col = $(col), text = $col.text();
		
						return text.replace(/"/g, '""'); // escape double quotes
		
					}).get().join(tmpColDelim);
		
				}).get().join(tmpRowDelim)
					.split(tmpRowDelim).join(rowDelim)
					.split(tmpColDelim).join(colDelim) + '"',
		
				
		
				// Data URI
				csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

				if (window.navigator.msSaveBlob) { // IE 10+
					//alert('IE' + csv);
					window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv")
					
				} 
				else {
					
					$(this).attr({ 'download': filename, 'href': csvData, 'target': '_blank' }); 
				}
			}
			$("#exportIP").click(function(){
				var filename = $("#IP").val();
				exportTableToCSV.apply(this, [$('#dg'), filename+'-IP-XREF.csv']);
			});
			$("#btncaptureIPAllBack").click(function(e){
				e.preventDefault();
				$("#findIP").click();
			});
		</script>
		<style>
			.datagrid-toolbar, .datagrid-pager{padding:5px;}
			.datagrid-row-selected{background:#E0ECFF;}
			a{color:#000000;}
		</style>
		<?php
		}
		else{
			echo 0;
		}
	}
	else{
		echo 0;
	}
?>