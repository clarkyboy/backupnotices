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
	
    //print_r($notices->ip_address($_POST['geoipregion'],$_POST['titleid'],$_POST['geoipisp']));
	
?>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css">

<link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
<link rel="stylesheet" type="text/css" href="css-easyui/icons.css">
<link rel="stylesheet" type="text/css" href="css-easyui/color.css">
<link rel="stylesheet" type="text/css" href="css-easyui/demo.css">

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="js/myjs.js"></script>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.6.min.js"></script>
<script type="text/javascript" src="js-easyui/easyui.js"></script>
<!--<script type="text/javascript" src="js/datagrid-scrollview.js"></script>-->
<script type="text/javascript" src="js/datagrid-filter.js"></script>
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
        .pagination-btn-separator{border-left: 2px solid #ccc;
        border-right: 2px solid #fff;}        
    </style>

<table id="dg" title="<?php echo 'Geo IP Region : '.$_POST['geoipregion'].' '.'Geo IP ISP : '.$_POST['geoipisp'].' '.'Related Title ID : '.$_POST['titleid'];?>" class="easyui-datagrid" style="height:600px;"
            url="get_users.php"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="geoip" width="30">IP</th>
                <th field="geoipisp" width="50">First Seen Captures</th>
                <th field="relatedtitlealias" width="50">Last Seen Captures</th>
                <th field="relatedtitleid" width="30">Last Notice</th>
                <th field="noticenotsent" width="30">Amount of Notices</th>

            </tr>
        </thead>
        <tbody>
           <?php 
                foreach($notices->ip_address($_POST['geoipregion'],$_POST['titleid'],$_POST['geoipisp']) as $us)
                {
                    echo '<tr>';
                    echo "<td width=\"30\">".$us[0]."</td>";
                    echo "<td width=\"50\">".$us[1]."</td>";
                    echo "<td width=\"50\">".$us[2]."</td>";
                    echo "<td width=\"30\">".$us[3]."</td>";
                    echo "<td width=\"30\">".$us[4]."</td>";
                   
                    echo '</tr>';
                }   
               
           ?>
            </tbody>
    </table>
   
    <div id="toolbar">
       <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="showCapturesIP()"><img src="img/capture.png" style="height:28px;"> Captures IP</a>
    </div>
    <input type="hidden" id="ipregion" value="<?php echo $_POST['geoipregion']; ?>">
    <input type="hidden" id="titleid" value="<?php echo $_POST['titleid']; ?>">
    <input type="hidden" id="ipisp" value="<?php echo $_POST['geoipisp']; ?>">

    <div id="imgloading" class="easyui-dialog" style="width:160px;padding:10px 20px;display:none;"
        closed="true">
        <center>
        <img src="img/loader-big.gif"><br><br>
        Please wait...
      </center>
    </div>     

    <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/icons.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
    <script type="text/javascript" src="js-easyui/easyui.js"></script>
    <script>
        function showCapturesIP()
        {
            var row = $('#dg').datagrid('getSelected');
            if(row)
            {
                var ip = row.geoip;
                var region = $("#ipregion").val();
                var titleid = $("#titleid").val();
                var isp = $("#ipisp").val();
                
                 $('#imgloading').dialog({modal: true});
                $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 

                $.post("capturesIP.php",{ip: ip, region: region, titleid: titleid, isp: isp},function(data,status){
                    if(status=="success")
                    {
                        $('#imgloading').dialog('close');
                        var win=window.open('about:blank','_blank');
                        with(win.document)
                        {
                            open();
                            write(data);
                            close();
                        }   
                    }
                }); 

            }else{
                $.messager.alert('Message','Select grid row first.');
            }    
        }
    </script>
    <style>
        .datagrid-toolbar, .datagrid-pager{padding:5px;}
        .datagrid-row-selected{background:#E0ECFF;}
        a{color:#000000;}
    </style>

