<?php
	session_start();
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $search = $_POST['search']; 
	
	$type = 'user_activities';
	$desc = 'Search IP: <strong>' . $search . '</strong>';
	$user = $_SESSION['notices']['user'];
	$notices->update_rights_holder($type,$desc,$user);	
	
?>
	
    <?php
        if($notices->search_ip($search))
        {
    ?>
           <a href="" id="ipsearch2" style="position:absolute;left:0px;top:-20px;"><i class="glyphicon glyphicon-backward"></i> Back to home details</a>
           <table id="dg" title="Notices Per IP" class="easyui-datagrid" style="height:600px;"
                url="get_users.php"
                toolbar="#toolbar" pagination="true"
                rownumbers="true" fitColumns="true" singleSelect="true">
            <thead>
                <tr>
					<th field="noticesid" width="20">Reference #</th>
                    <th field="geoipregion" width="50">IP</th>
                    <th field="geoipisp" width="50">Timestamp (UTC)</th>
                    <th field="relatedtitlealias" width="50">Geo IP Region</th>
                    <th field="relatedtitleid" width="60">Geo IP ISP</th>
                    <th field="noticenotsent" width="60">ISP EMAIL</th>
                    <th field="ispemail" width="60">Related Title Alias</th>
                    <th field="ispcontact" width="50">Notice Sent</th>
					<?php echo isset($_SESSION['notices']['country']) && $_SESSION['notices']['country'] == 'CA'? '<th field="ispcontact2">Notice Type</th>':''; ?>
                </tr>
            </thead>
            <tbody>
               <?php 
                   foreach($notices->search_ip($search) as $s)
                    {
                        echo '<tr>';
						echo "<td width=\"50\">".$s['nid']."</td>";
                        echo "<td width=\"50\">".$s[0]."</td>";
                        echo "<td width=\"50\">".$s[1]."</td>";
                        echo "<td width=\"50\">".$s[2]."</td>";
                        echo "<td width=\"50\">".$s[3]."</td>";
                        echo "<td width=\"50\">".$s[4]."</td>";
                        echo "<td width=\"50\">".$s[5]."</td>";
						if($_SESSION['notices']['country'] && $_SESSION['notices']['country'] == 'CA'){
							if(trim($s[6])){
								$refno = $s['nid'];
								$file = '/mnt/evirdgoogle/_Notices/CA/' . substr( $refno, 0, strlen($refno)-6) . '000000/' . $refno . '.eml' ;
								
								if(file_exists($file))
									echo "<td width=\"50\"><a target=\"_blank\" style=\"color:#186fff\" href=\"download.php?refno=".$s['nid']."\">".$s[6]." <img src=\"img/dlicon.png\" width=\"10\" height=\"10\"/></a></td>";
								else 
									echo "<td width=\"50\">".$s[6]."</td>";
							}
							else{
								echo "<td width=\"50\">".$s[6]."</td>";
							}
						}
						else{
							if(trim($s[6])){
								$refno = $s['nid'];
								$file = '/mnt/evirdgoogle/_Notices/US/' . substr( $refno, 0, strlen($refno)-6) . '000000/' . $refno . '.eml' ;
								
								if(file_exists($file))
									echo "<td width=\"50\"><a target=\"_blank\" style=\"color:#186fff\" href=\"download.php?refno=".$s['nid']."\">".$s[6]." <img src=\"img/dlicon.png\" width=\"10\" height=\"10\"/></a></td>";
								else 
									echo "<td width=\"50\">".$s[6]."</td>";
							}
							else{
								echo "<td width=\"50\">".$s[6]."</td>";
							}
						}
                        echo isset($_SESSION['notices']['country']) && $_SESSION['notices']['country'] == 'CA'? '<td width="100%">'.(isset($s[7])?$s[7]:'').'</td>':''; 
                    }   
                   
               ?>
			<tbody>
			</table>
            <div style="text-align:right">
				<?php if( file_get_contents('https://rightsenforcement.com/index.php/submit/?action=check_sstatus&user=george&pass=Xa4ExNU2DufZ2ppw&ip=' . $search) ): ?>
				<a href="" rel="<?php echo $search; ?>" id="rmLogin" class="l-btn l-btn-small l-btn-plain" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;">Remove Login</a>
				<?php endif; ?>
				
				<?php if(canAccess() || true) {?> 
				<a href="" id="showXRef" class="l-btn l-btn-small l-btn-plain" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;">Show XRef</a>
				<a href="" id="showAllCaptures" class="l-btn l-btn-small l-btn-plain" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;">Show All Captures</a>
				<?php } ?>
			</div>
			
			<?php
			$data = file_get_contents('https://rightsenforcement.com/index.php/submit/?action=get_history&user=george&pass=Xa4ExNU2DufZ2ppw&ip=' . $search);
			$_data = '';
			if($data){
				$data = json_decode($data);
				if($data->status == 'success'){
					$_data = $data->data;

					echo '<div id="loginAttempts" style="margin-top:10px;">';
					echo '<table id="dg2" title="Login Attempts" class="easyui-datagrid-x" style="height:200px;" fitColumns="true">';
					echo '<thead>';
					echo '<tr>';
					echo '<th field="caseNo" width="20">Case No#</th>';
					echo '<th field="ip" width="50">IP</th>';
					echo '<th field="userIP" width="50">User IP</th>';
					echo '<th field="hitDate" width="50">Hit Date</th>';
					echo '<th field="isp" width="50">ISP</th>';
					echo '<th field="owner" width="50">Owner</th>';
					echo '<th field="loginTime" width="50">Login Time</th>';
					echo '<th field="userAgent" width="50">User Agent</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					
					if($_data){
						foreach($_data AS $i){
							echo '<tr>';
							echo '<td>' . $i->caseNo . '</td>';
							echo '<td>' . $i->ip . '</td>';
							echo '<td>' . $i->userIP . '</td>';
							echo '<td>' . $i->hitDate . '</td>';
							echo '<td>' . $i->isp . '</td>';
							echo '<td>' . $i->owner . '</td>';
							echo '<td>' . $i->loginTime . '</td>';
							echo '<td>' . $i->userAgent . '</td>';
							echo '</tr>';
						}
					}
					
					echo '</tbody>';
					echo '</table>';
					echo '</div>';
				}
			}
			
			?>
			
             <div id="toolbar">
				<?php if(canAccess()) {?> 
				<a href="javascript:void(0)" id="exportIP" class="easyui-linkbutton" plain="true"><img src="img/export.png"> EXPORT</a>
				<?php } ?>
             </div>
    
             <input type="hidden" id="IP" value="<?php echo $search; ?>">
            <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
            <link rel="stylesheet" type="text/css" href="css-easyui/icons.css">
            <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
            <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
            <script type="text/javascript" src="js-easyui/easyui.js"></script>
           <script>
				function removeLogin(id, caseno, ip){
					if(confirm('Press OK to proceed.')){
						$.post('https://rightsenforcement.com/index.php/submit/',{action:'remove login',id,caseno,ip},function(_res){
							console.log(_res);
							$('#trcase-' + id + '-' + caseno).remove();
							$('#dg3').datagrid();
						}).fail(function(){
							alert('An error occured. Please try again later.');
						});
					}
				}
		
				var myview = $.extend({},$.fn.datagrid.defaults.view,{
					onAfterRender:function(target){
						$.fn.datagrid.defaults.view.onAfterRender.call(this,target);
						var opts = $(target).datagrid('options');
						var vc = $(target).datagrid('getPanel').children('div.datagrid-view');
						vc.children('div.datagrid-empty').remove();
						if (!$(target).datagrid('getRows').length){
							var d = $('<div class="datagrid-empty"></div>').html(opts.emptyMsg || 'no records').appendTo(vc);
							d.css({
								position:'absolute',
								left:0,
								top:50,
								width:'100%',
								textAlign:'center'
							});
						}
					}
				});
				<?php if(!$_data) : ?>
				$('#dg2').datagrid({data: [], view: myview, emptyMsg: 'No records found'});
				<?php else: ?>
				$('#dg2').datagrid();
				<?php endif; ?>
				
                $(document).ready(function(){

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
                            
                            console.log(csv);
                            
                            if (window.navigator.msSaveBlob) { // IE 10+
                                //alert('IE' + csv);
                                window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv")
                                
                            } 
                            else {
                                
                                $(this).attr({ 'download': filename, 'href': csvData, 'target': '_blank' }); 
                            }
                    } 
                    
					$('#rmLogin').click(function(e){
						e.preventDefault();
						if(confirm('Press OK to proceed.')){
							$('#rmLogin').hide();
							$.post('https://rightsenforcement.com/index.php/submit/',{action:'remove login',ip:$('#rmLogin').attr('rel')},function(_res){
								$.post('insert-history.php',{ip:$('#rmLogin').attr('rel')},function(_res){});
								alert('IP from Rights Enforcement was disabled.');
							}).fail(function(){
								$('#rmLogin').show();
								alert('An error occured. Please try again later.');
							});
						}
					});
					
                    $("#exportIP").click(function(){
                        var filename = $("#IP").val();
						exportTableToCSV.apply(this, [$('#dg'), filename+'-IP.csv']);
                    });

					$("#showAllCaptures").click(function(e){
						e.preventDefault();
						$('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
						$.post('capturesIPAll.php',{ip:$('#txtipsearch').val()},function(data,status){
							if(data != 0)
							{
								$("#ipcontainer").html(data);
								$('#imgloading').dialog('close'); 
							}else{
								$.messager.alert('Notification','IP not found.','',function(e){
								   $('#imgloading').dialog('close'); 
								});
							}    

						});
					});
					
					$('#showXRef').click(function(e){
						e.preventDefault();
						$('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
						$.post('showXREF.php',{ip:$('#txtipsearch').val()},function(data,status){
							if(data != 0)
							{
								$("#ipcontainer").html(data);
								$('#imgloading').dialog('close'); 
							}else{
								$.messager.alert('Notification','IP not found.','',function(e){
								   $('#imgloading').dialog('close'); 
								});
							}    

						});
					});
                });
            </script>
            <style>
                .datagrid-toolbar, .datagrid-pager{padding:5px;}
                .datagrid-row-selected{background:#E0ECFF;}
                a{color:#000000;}
                #ipsearch2{position: absolute;left:0px;top:0px;}
            </style>
    <?php        
        }else{
            echo '0';
        }
    ?>