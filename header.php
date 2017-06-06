<?php
    session_start();

    include('classes/notices_controllers.php');
    $notices = new Notices;
    
    if($_SESSION['notices']['loginStat'] == '')
    {
        header('Location: ./') ;  
    }
    if(isset($_POST['logout']))
    {
	    $type = 'user_activities';
		$desc = 'Logout Account';
		$user = $_SESSION['notices']['user'];
		$notices->update_rights_holder($type,$desc,$user);	
		
		unset($_SESSION['notices']['country']);
		unset($_SESSION['notices']['ocountry']);
		unset($_SESSION['notices']['fcountry']);
        $_SESSION['notices']['loginStat'] = '';
        header('Location: ./');
       
    }   
    session_write_close();
	
	if($page != 'home'){
		if(!canAccess()){
			header('Location: home.php');
			exit;
		}
	}
	
	if(isset($_POST['action'])){
		if($_POST['action']=='update response email'){
			echo $notices->updateResponseEmail($_POST);
			exit;
		}
		elseif($_POST['action']== 'get ca notice sent info'){
			$data = $notices->getCANoticeSentInfo($_POST['ispName'],$_POST['relatedtitleID'],$_POST['geoipregion']);
			?>
			<table style="display:none;" id="dg" title="Secont Notices Sent Details" class="easyui-datagrid-x" style="max-height:570px; height:570px;"
					toolbar="#toolbar" pagination="false"
					rownumbers="true" fitColumns="true" singleSelect="true" view="scrollview" autoRowHeight="false" pageSize="5000">
				<thead>
					<tr>
						<th data-options="field:'FirstNoticeID',width:20">FirstNoticeID</th>
						<th data-options="field:'FirstNotice',width:20">FirstNotice</th>
						<th data-options="field:'FirstNoticeStatus',hidden:true">FirstNotice Status</th>
						<th data-options="field:'FirstNoticeFileStatus',hidden:true">FirstNotice File Status</th>
						<th data-options="field:'RE',width:20">Response</th>
						<th data-options="field:'CF',width:20">Confirmed</th>
						<th data-options="field:'REStatus',hidden:true">Response Status</th>
						<th data-options="field:'iResponsStatus',hidden:true">iResponse Status</th>
						<th data-options="field:'SecondNoticeID',width:20">SecondNoticeID</th>
						<th data-options="field:'SecondNotice',width:20">SecondNotice</th>
						<th data-options="field:'SecondNoticeFileStatus',hidden:true">SecondNotice File Status</th>
						<th data-options="field:'RE2',width:20">Response</th>
						<th data-options="field:'CF2',width:20">Confirmed</th>
						<th data-options="field:'RE2Status',hidden:true">Response2 Status</th>
						<th data-options="field:'iResponsStatus2',hidden:true">iResponse2 Status</th>
						<th data-options="field:'CF1data',hidden:true">CF1 Status</th>
						<th data-options="field:'CF2data',hidden:true">CF2 Status</th>
					</tr>
				</thead>
				<tbody>
				<?php if($data): foreach($data AS $k=>$i): ?>
				<?php $ii = explode('#$#', $i['SecondNoticeInfo']); ?>
					<tr>
						<td rel="<?php echo (int)$i['FirstNoticeID']; ?>"><?php echo $i['FirstNoticeID']; ?></td>
						<td>
						<?php 
						if($_SESSION['notices']['country'] && $_SESSION['notices']['country'] == 'CA'){
							echo getEMLDLLink($i['NoticeID1'],$i['FirstNotice']); 
						} 
						?>
						</td>
						<td><?php echo $i['FirstNotice']? 1 : 0; ?></td>
						<td><?php echo fileEMLDLLinkExist($i['NoticeID1'])? 1 : 0; ?></td>
						<td>
						<?php 
						if($_SESSION['notices']['country'] && $_SESSION['notices']['country'] == 'CA'){
							echo $res = getEMLDLLink($i['NoticeID1'],$i['NoticeID1'],'',1); 
						}  
						?>
						</td>
						<td>
						<?php
						if($res && $res != $i['NoticeID1']){
							if(!$i['iResponsStatus'])
								echo '<a href="javascript:showresponseemaildialog(this,'.$i['NoticeID1'].',1)">Define Status Pattern</a>';
							else
								echo $i['iResponsStatus'];
						}
						?>
						</td>
						<td><?php echo ($res && $res!= $i['NoticeID1'])? 1 : 0; ?></td>
						<td><?php echo $i['iResponsStatus']; ?></td>
						<td><?php echo $ii[0]; ?></td>
						<td>
						<?php 
						if($_SESSION['notices']['country'] && $_SESSION['notices']['country'] == 'CA'){
							echo getEMLDLLink($ii[1],$ii[2],$i['NoticeID1']); 
						} 
						?>
						</td>
						<td><?php echo fileEMLDLLinkExist($ii[1])? 1 : 0; ?></td>
						<td>
						<?php 
						if($_SESSION['notices']['country'] && $_SESSION['notices']['country'] == 'CA'){
							echo $res = getEMLDLLink($ii[1],$ii[1],'',1); 
						}  
						?>
						</td>
						<td>
						<?php
						if($res && $res != $ii[1]){
							if(!$ii[5])
								echo '<a href="javascript:showresponseemaildialog(this,'.$ii[1].',2)">Define Status Pattern</a>';
							else
								echo $ii[5];
						}
						?>
						</td>
						<td><?php echo ($res && $res!= $ii[1])? 1 : 0; ?></td>
						<td><?php echo $ii[3]; ?></td>
						<td><?php echo strip_tags(correct_encoding($i['CF'])); ?></td>
						<td><?php echo strip_tags(correct_encoding($ii[4]));//iconv(mb_detect_encoding($i['CF2'], mb_detect_order(), true), "UTF-8", $i['CF2']); ?></td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
			<?php
			exit;
		}
		elseif($_POST['action']== 'get status patttern info')
		{
			$status = $notices->get_status_patterns();
			//var_dump($data);
			//check data passing
			//echo '<script> alert("header: '.$_POST['geoipregion'].'");</script>';
		?>
			<table style="display:none;" id="dg" title="Status Pattern Management" class="easyui-datagrid-x" style="max-height:570px; height:570px;"
					toolbar="#toolbar" pagination="false"
					rownumbers="true" fitColumns="true"  view="scrollview" autoRowHeight="false" pageSize="5000">
				<thead>
					<tr>
						<th data-options="field:'statuspatterninid', hidden:true">ID</th>
						<th data-options="field:'statuspatternAction', width:10 ">Action</th>
						<th data-options="field:'statuspatterngeoipisp', width:20">Geo IP ISP</th>
						<th data-options="field:'statuspatternpattern', width:40">Pattern</th>
						<th data-options="field:'statuspatternstatus', width:20">Status</th>
						
					</tr>
				</thead>
				<tbody>
				<?php if($status): foreach($status AS $k=>$i): ?>
				<?php //$ii = explode('#$#', $i['SecondNoticeInfo']); ?>
					<tr>
						<td><?php echo $i['ID'];?></td>
						<td><a href='statusPatternHandler.php?statusID=<?php echo$i['ID'];?>&action=update'>Update</a> |
						<a href='statusPatternHandler.php?statusID=<?php echo$i['ID'];?>&action=delete'>Delete</a></td>
						<td><?php echo $i['GeoIpIsp'];?></td>
						<td><?php echo $i['Pattern'];?></td>
						<td><?php echo $i['Status'];?></td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
			</table>
			<?php
			exit;
		}//from Ed
		elseif($_POST['action']=='downloadallemailSent'){
			$ispName = isset($_POST['ispname'])? $_POST['ispname'] : '';
			$relatedtitleID = isset($_POST['relatedtitleid'])? $_POST['relatedtitleid'] : '';
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$data = $notices->getCANoticeSentInfoSingle($ispName,$relatedtitleID);

			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['noticesid'], 0, strlen($i['noticesid'])-6) . '000000/' . $i['noticesid'] . '.eml' ;
					if(file_exists($file1)){
						if ($zip->open("tmp/Notices-$time.zip",ZipArchive::CREATE)){
							$zip->addFile($file1, $i['noticesid'].'.eml');
							$zip->close();
						}
						$hasdl = true;
					}
				}
				
				if($hasdl){
					header("Content-type: application/zip");
					header("Content-disposition: attachment; filename=Notices-".date('Y-m-d').".zip");
					ob_clean(); 
					flush();
					readfile("tmp/Notices-$time.zip");
					unlink("tmp/Notices-$time.zip");
					exit;
				}
			}
		}
		elseif($_POST['action']=='downloadallemail'){

			$ispName = isset($_POST['ispname'])? $_POST['ispname'] : '';
			$relatedtitleID = isset($_POST['relatedtitleid'])? $_POST['relatedtitleid'] : '';
			$country = isset($_POST['country'])? $_POST['country'] : '';
			$filter = isset($_POST['filter'])? $_POST['filter'] : '';
			$data = $notices->getCANoticeSentInfo($ispName,$relatedtitleID);
			
			if($data){
				$zip = new ZipArchive;
				$time = time();
				$hasdl = false;
				foreach($data AS $k=>$i){
					$ii = explode('#$#', $i['SecondNoticeInfo']);
					$file1 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $i['NoticeID1'], 0, strlen($i['NoticeID1'])-6) . '000000/' . $i['NoticeID1'] . '.eml' ;
					$file2 = '/mnt/evirdgoogle/_Notices/'.$country.'/' . substr( $ii[1], 0, strlen($ii[1])-6) . '000000/' . $ii[1] . '.eml' ;
					if(file_exists($file1) || file_exists($file2)){
						if ($zip->open("tmp/Notices-$time.zip",ZipArchive::CREATE)){
							if($filter == 3 || $filter == 1 ){
								if(file_exists($file1)){ 
									$zip->addFile($file1, $i['NoticeID1'].'.eml');
									if(file_exists($file2)) 
										$zip->addFile($file2, $i['NoticeID1'] . '_' . $ii[1] .'.eml');
										
									$hasdl = true;
								}
							}
							elseif($filter == 2){
								if(file_exists($file2)){ 
									$zip->addFile($file2, $i['NoticeID1'] . '_' . $ii[1] .'.eml');
									if(file_exists($file1)) 
										$zip->addFile($file1, $i['NoticeID1'].'.eml');
										
									$hasdl = true;
								}
							}
							elseif($filter == 15){
								if($res = getEMLDLLink($i['NoticeID1'],$i['NoticeID1'],'',1,true)){
									$zip->addFile($res, $i['NoticeID1'].'_reply.eml');
									$hasdl = true;
								}								
							}
							elseif($filter == 25){
								if($res = getEMLDLLink($ii[1],$ii[1],'',1,true)){ 
									$zip->addFile($res, $ii[1] .'_reply.eml');
									$hasdl = true;
								}
							}
							elseif($filter == 35){
								$res1 = getEMLDLLink($i['NoticeID1'],$i['NoticeID1'],'',1,true);
								$res2 = getEMLDLLink($ii[1],$ii[1],'',1,true);
								if($res1  && $res2){
									$zip->addFile($res1, $i['NoticeID1'].'_reply.eml');
									$zip->addFile($res2, $ii[1] .'_reply.eml');
									$hasdl = true;
								}
							}
							$zip->close();
						}
					}
				}
				
				if($hasdl){
					header("Content-type: application/zip");
					header("Content-disposition: attachment; filename=Notices-".date('Y-m-d').".zip");
					ob_clean(); 
					flush();
					readfile("tmp/Notices-$time.zip");
					unlink("tmp/Notices-$time.zip");
					exit;
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NOTICES</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css">

 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>		
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/icons.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
    <link rel="stylesheet" type="text/css" href="css/SOL.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 
    <script src="js/myjs.js"></script>

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.6.min.js"></script>
    <script type="text/javascript" src="js-easyui/easyui.js"></script>
    <!--<script type="text/javascript" src="js/datagrid-scrollview.js"></script>-->
    <script type="text/javascript" src="js/datagrid-filter.js"></script>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
	<!-- <script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script> -->
	<!-- <script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script> -->
    <script>
        $(document).ready(function(){

            // Trigger action when the contexmenu is about to be shown


            $(".searchEnter").keyup(function(e){
                if(e.keyCode == 13)
                {
                    var searchVal = $(this).val();
                   $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
                    $.post("searchIP.php",{ search : searchVal},function(data,status){
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
                }    
            });

            $("#findIP").click(function(){
                var searchVal = $(".searchEnter").val();
                $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
                $.post("searchIP.php",{ search : searchVal},function(data,status){
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

             $("#findNoticeID").click(function(){
                var searchVal = $(".searchEnter").val();
                //alert(searchVal);
                $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
                $.post("searchNoticeID.php",
                	{ noticeID : searchVal},
                	function(data,status)
                	{
	                    if(data != 0)
	                    {
	                    	// alert("niagi sa header: naay sud ang data ");
	                        $("#ipcontainer").html(data);
	                        $('#imgloading').dialog('close'); 
	                    }else{
	                    	// alert("niagi sa header: walay sud ang data ");
	                        $.messager.alert('Notification','ID not found.','',function(e){
	                            $('#imgloading').dialog('close'); 
	                        });
	                    }    
                	}).fail(function(response) {
					    console.log('Error: ' + response.responseText);
					});
                //alert("niagi sa header: ");
            });

			$("#showCaptures").click(function(e){
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
					
            $(".searchEnter2").keyup(function(e){
                if(e.keyCode == 13)
                {
                    var searchVal = $(this).val();
                    $.messager.progress();
                    $.post("searchIP.php",{ search : searchVal},function(data,status){
                        if(data != 0)
                        {
                            $("#ipcontainer").html(data);
                            $.messager.progress("close");
                        }else{
                            $.messager.alert('Notification','IP not found.','',function(e){
                                $.messager.progress("close");
                            });
                        }    

                    });
                }    
            });
                    

            //$('#dg').datagrid({loadFilter:pagerFilter}).datagrid('loadData', getData());
        

            $("#rtaSearch").keyup(function(){
                
                var value = $(this).val();

                  $("table tr").each(function (index) {
                      if (!index) return;
                      $(this).find("td").each(function () {
                          var id = $(this).text().toLowerCase().trim();
                          var not_found = (id.indexOf(value) == -1);
                          
                          $(this).closest('tr').toggle(!not_found);
                          return not_found;
                          
                      });
                  });

            });
        });
        	
        function pagerFilter(data){
            if (typeof data.length == 'number' && typeof data.splice == 'function'){    // is array
                data = {
                    total: data.length,
                    rows: data
                }
            }
            var dg = $(this);
            var opts = dg.datagrid('options');
            var pager = dg.datagrid('getPager');
            pager.pagination({
                onSelectPage:function(pageNum, pageSize){
                    opts.pageNumber = pageNum;
                    opts.pageSize = pageSize;
                    pager.pagination('refresh',{
                        pageNumber:pageNum,
                        pageSize:pageSize
                    });
                    dg.datagrid('loadData',data);
                }
            });
            if (!data.originalRows){
                data.originalRows = (data.rows);
            }
            if (!opts.remoteSort && opts.sortName){
                var target = this;
                var names = opts.sortName.split(',');
                var orders = opts.sortOrder.split(',');
                data.originalRows.sort(function(r1,r2){
                    var r = 0;
                    for(var i=0; i<names.length; i++){
                        var sn = names[i];
                        var so = orders[i];
                        var col = $(target).datagrid('getColumnOption', sn);
                        var sortFunc = col.sorter || function(a,b){
                            return a==b ? 0 : (a>b?1:-1);
                        };
                        r = sortFunc(r1[sn], r2[sn]) * (so=='asc'?1:-1);
                        if (r != 0){
                            return r;
                        }
                    }
                    return r;
                });
            }
            var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
            var end = start + parseInt(opts.pageSize);
            data.rows = (data.originalRows.slice(start, end));
            return data;
        }

        function loadTab()
        {
            $('#logsTab').dialog({
                    modal: true
            });    
            $('#logsTab').dialog('open').dialog('center').dialog('setTitle','User Activity Logs');
        }
		
		/*10-17-2016*/
		function exportCapture(){
			if($('#exportCapture').attr('href') == '#'){
				$.post('_capturesIPTotal.php',{ip:$('#txtipsearch').val()},function(_res){
					_res = parseInt(_res);
					if(_res){
						$('#imgloading').dialog('open').dialog('center').dialog('setTitle','Extracting: 0%');
						cia_data = new Array();
						collectExportData($('#txtipsearch').val(),1,5000,_res);
					}
				});
			}
			return false;
		}
		function collectExportData(ip,page,rows,total){
			$.post('_capturesIPAllCSV.php?ip=' + ip,{page: page,rows: rows},function(_res){
				if( (page * rows) > total+rows){
					$('#imgloading').dialog('close');  
					viewExportData(ip);
				}
				else{
					$('#imgloading').dialog('setTitle','Extracting: '+ parseFloat( (((page * rows)/(total))*100) > 100? 100 : (((page * rows)/(total))*100) ).toFixed(2) +'%');
					page++;
					collectExportData(ip,page,rows,total);
				}
			});
		}
		function viewExportData(ip){
			window.open("https://securedservice.net/notices/_capturesIPAllDL.php?ip="+ip);
		}
		/*10-17-2016*/
    </script>
    <style>
        .datagrid-toolbar, .datagrid-pager{padding:5px;}
        .datagrid-row-selected{background:#E0ECFF;}
        #tblmenu tr td{padding-left:10px;padding-right: 10px;}
        #userlogin{ 
            font-size: 12px;
                    font-weight: bold;
                    color: #0E2D5F;
                    height: 16px;
                    line-height: 16px;
                }
        .pagination-btn-separator{border-left: 2px solid #ccc;border-right: 2px solid #fff;}        
		a#ipsearch2 {display: none;}
    </style>
</head>
<?php

    if($page == "home" || $page == "noticeinfos" || $page == 'noticeinfos2' || $page == 'view100' || $page == 'statusPatternManagement')
    {
?>

        <body>
  
            <table id="tblmenu">
                <tr>
                    <td style="padding-left:0px;">
                       <form method="POST">
                            <button name="logout" style="background:transparent;border:none;"><img src="img/logout.png"> Logout</button>
                            <?php if($page=='noticeinfos2') { ?>
							<a href="notice-information.php?status=sent&relatedtitleid=<?php echo $_GET['relatedtitleid']; ?>"><img src="img/back.png"> Back</a><br>   
							<?php } else { ?>
							 <?php if(canAccess()){ ?>
							<a href="home.php"><img src="img/home2.png"> Home</a>
							<?php } ?>
							<?php } ?>
                       </form> 
                    </td>
                    <td>
                      <div class="pagination-btn-separator"></div>
                    </td> 
                    <td>
                       <img src="img/user.png"> <span id="userlogin">Welcome <?php echo $_SESSION['notices']['user']; ?>!</span>
                    </td> 
                    <td>
                      <div class="pagination-btn-separator"></div>
                    </td>
                     <td>
						<?php if(canAccess()) {?> 
                        <a href="reportingusers.php"><img src="img/add-item.png"> Client Reports</a>
						<?php } ?>
                    </td> 
                    <td>
						<?php if(canAccess()) {?> 
                        <a href="statusPaternManagement.php" target="_blank"><img src="img/status-management.png" style="width:50px; height:50px;"> Manage Status Patterns </a>
						<?php } ?>
                    </td> 
                    <td>
						<?php if(canAccess()) {?> 
                        <a href="javascript:loadTab()"><img src="img/activities.png"> User Activities</a>
						<?php } ?>
                    </td>    
                </tr>
                  
            </table> 
			
			<?php $logss = $notices->user_logs(true); ?>
            <div id="logsTab" class="easyui-dialog" style="padding:10px 20px;display:none;" closed="true">
              <div class="easyui-tabs" style="width:800px;height:500px">
                    <div title="Right Holders Information" style="padding:10px">
                       <table class="table table-striped table-hover">
                       <?php
							if($logss){
								foreach($logss as $logs)
								{
									if($logs['logs_type'] == "rightholder")
									{
									   $date = $logs['logs_date'];
									   $mydate = strtoTime($date);
									   $printdate = date("F j, Y, g:i A", $mydate);
										echo '<tr>';
										  echo '<td>';
											 echo '<img src="img/logs.png"> User <b>'.$logs['logs_user'].'</b> '.$logs['logs_descripton'].' on <i>'.$printdate.'</i>';
										  echo '</td>';  
										echo '</tr>';
									}    

								}    
							}
							else{
								echo '<tr><td>No Logs available.</td></tr>';
							}
                       ?>
                         </table>
                    </div>
                    <div title="ISP Information" style="padding:10px">
                        <table class="table table-striped table-hover">
                       <?php
							if($logss){
								foreach($logss as $logs)
								{
									if($logs['logs_type'] == "ispInfo")
									{
										
									   $date = $logs['logs_date'];
									   $mydate = strtoTime($date);
									   $printdate = date("F j, Y, g:i A", $mydate);
									   
										echo '<tr>';
										  echo '<td>';
											 echo '<img src="img/logs.png"> User <b>'.$logs['logs_user'].'</b> '.$logs['logs_descripton'].' on <i>'.$printdate.'</i>';
										  echo '</td>';  
										echo '</tr>';
									}    

								}
							}
							else{
								echo '<tr><td>No Logs available.</td></tr>';
							}
                       ?>
                         </table>
                    </div>
                    <div title="Active/Inactive ISP"  style="padding:10px">
                        <table class="table table-striped table-hover">
                       <?php
							if($logss){
								foreach($logss as $logs)
								{
									if($logs['logs_type'] == "ispStatus")
									{
									   $date = $logs['logs_date'];
									   $mydate = strtoTime($date);
									   $printdate = date("F j, Y, g:i A", $mydate);

										echo '<tr>';
										  echo '<td>';
											 echo '<img src="img/logs.png"> User <b>'.$logs['logs_user'].'</b> '.$logs['logs_descripton'].' on <i>'.$printdate.'</i>';
										  echo '</td>';  
										echo '</tr>';
									}    

								}
							}
							else{
								echo '<tr><td>No Logs available.</td></tr>';
							}								
                       ?>
                         </table>
                    </div>
					<div title="User Access"  style="padding:10px">
						<table class="table table-striped table-hover">
						<?php
							if($useraccess = $notices->getUserAccess(!$_SESSION['notices']['ocountry']?true: false)){
								foreach( $useraccess AS $k=>$i ){
									echo '<tr><td><img src="img/logs.png"/> <strong>'.$i['user'].'</strong> '.$i['userInfo'].'</td></tr>';
								}
							}
							else{
								echo '<tr><td>No user available.</td></tr>';
							}
						?>
						</table>
					</div>
					<div title="User Activities"  style="padding:10px">
						<table class="table table-striped table-hover">
						<?php
							$logsEmpty = true;
							if($logss = $notices->user_logs(!$_SESSION['notices']['ocountry']?true: false)){
								foreach($logss as $logs)
								{
									if($logs['logs_type'] == "user_activities" && $logs['logs_user'] != $_SESSION['notices']['user'])
									{
										$date = $logs['logs_date'];
									   $mydate = strtoTime($date);
									   $printdate = date("F j, Y, g:i A", $mydate);

										echo '<tr>';
										  echo '<td>';
											 echo '<img src="img/logs.png"> User <b>'.$logs['logs_user'].'</b> '.$logs['logs_descripton'].' on <i>'.$printdate.'</i>';
										  echo '</td>';  
										echo '</tr>';
										
										$logsEmpty = false;
									}
								}
							}
							
							if($logsEmpty){
								echo '<tr><td>No Logs available.</td></tr>';
							}
						?>
						</table>
					</div>
                </div>

                <br>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#logsTab').dialog('close')" style="width:90px;"><i class="glyphicon glyphicon-remove"></i> Close</a>
          </div>

<?php        
    }    

?>