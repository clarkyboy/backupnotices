<?php
	$page = 'view100';
	include('header.php');

	$country = $_GET['country'];
	$titleid = $_GET['relatedtitleid'];
	// $view = $notices->get_top100_pertitle($titleid, $country);
	$moviename = $notices->getMovieName($titleid);
?>
<div style="position: center;">
 <table id="dg" title=" TOP 100 IP of <?php echo $moviename; ?> Notices" class="easyui-datagrid" style="height:570px; height:500px;"
            toolbar="#toolbar" pagination="false"
            rownumbers="true" fitColumns="true" singleSelect="true" view="scrollview" autoRowHeight="false" pageSize="100">
 </table>
 <div id="toolbar" style="display:none;">
</div>
</div>
<script>
load_datagrid();
function load_datagrid()
         {
            $('#dg').datagrid({
                url: 'view100-json-data.php?status=<?php echo $country; ?>&titleid=<?php echo $titleid;?>',
                columns:[[
                    {field:'IP',title:'IP', width:100},
                    {field:'Notices_Sent',title:'Notices_Sent', width:50},
                    {field:'ISP',title:'ISP', width:100},
                    {field:'Region',title:'Region', width:100},
                ]],
                pagination: false,
                remoteFilter:false,
                pageList: [10,20,30,50,100]

            }).datagrid('enableFilter'); 
            $('#dg').datagrid('reload');
         }
</script>
