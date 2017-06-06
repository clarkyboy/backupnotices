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
    
	include('classes/notices_controllers.php');
    $notices = new Notices;
	
	$type = 'user_activities';
	$desc = 'Captures IP: <strong>' . $_POST['ip'] . '</strong>';
	$user = $_SESSION['notices']['user'];
	$notices->update_rights_holder($type,$desc,$user);	
	
	if($notices->getIPCapturesTotal($_POST['ip'])){
?>
	<div id="tbl-pagi">
	<a href="" id="btncaptureIPAllBack" style="position:absolute;left:0px;top:-20px;display:none;"><i class="glyphicon glyphicon-backward"></i> Back to home details</a>
    <table id="dg" title="IP <?php echo $_POST['ip']; ?> Captures" class="easyui-datagrid" style="height:600px;"
            url="_capturesIPAll.php?ip=<?php echo $_POST['ip']; ?>"
            toolbar="#toolbar" fitColumns="true" singleSelect="true"
			rownumbers="true" pagination="true">
        <thead>
            <tr>
                <th field="IP" width="30">IP</th>
                <th field="Port" width="20">PORT</th>
			    <th field="VerifyStatus" width="20">Status</th>
                <th field="PeerID" width="50">Peer ID</th>
                <th field="TorrentHash" width="30">Torrent Hash</th>
                <th field="Torrentname" width="50">Torrent Name</th>
                <th field="OrderNumber" width="30">Order Number</th>
                <th field="Bitfield" width="50">Bit Field</th>
                <th field="SessionStart" width="50">Session Start</th>
                <th field="TransferEnd" width="50">Transfer End</th>
                <th field="SessionEnd" width="30">Session End</th>
                <th field="Owner" width="50">Rights Holder</th>
                
            </tr>
        </thead>
    </table>
	<div id="toolbar">
	   <a href="#" id="exportCapture" onClick="return exportCapture();" class="easyui-linkbutton" plain="true"><img src="img/export.png"> EXPORT</a>
	</div>
    </div>
	
	<div id="dlready" class="easyui-dialog" style="width:350px;padding:10px 20px;display:none;" closed="true" buttons="#dlready-buttons">
		<div class="ftitle">Download</div>
		<div class="fcontent">Download is ready. Press download to continue</div>
		<div id="dlready-buttons">
			<a href="javascript:void(0)" id="dlreadyok" class="easyui-linkbutton c6" onclick="javascript:$('#dlready').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Download</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlready').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
		</div>
	</div>
  
    <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
    <!--<link rel="stylesheet" type="text/css" href="css-easyui/icons.css">-->
    <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
    <script type="text/javascript" src="js-easyui/easyui.js"></script>
    <script type="text/javascript">
		$("#btncaptureIPAllBack").click(function(e){
			e.preventDefault();
			$("#findIP").click();
		});
	</script>
    <style>
		#tbl-pagi .pagination table{display:block !important;}
        .datagrid-toolbar, .datagrid-pager{padding:5px;}
        .datagrid-row-selected{background:#E0ECFF;}
        a{color:#000000;}
    </style>
<?php } else{ echo 0;} ?>