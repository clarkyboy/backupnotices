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
	
    //  $notices->ip_address($_POST['geoipregion'],$_POST['titleid'],$_POST['geoipisp']);
	//echo $_POST['geoipisp'].' '.$_POST['geoipregion'].' '.$_POST['titleid'];
	//include('header.php');
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

	<table id="dg" title="IP <?php echo $_POST['ip']; ?> Details" class="easyui-datagrid" style="height:600px;"
            url="get_users.php"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="oip" width="30">IP</th>
                <th field="port" width="20">PORT</th>
                <th field="timestamp" width="50">Timestamp (UTC)</th>
                <th field="client" width="30">Client</th>
                <th field="filename" width="50">Filename</th>
                <th field="sha1" width="50">SHA1</th>
                <th field="titlealias" width="50">Related Title Alias</th>
                <th field="region" width="30">Geo IP Region</th>
                <th field="isp" width="50">Geo ISP</th>
                <th field="email" width="40">ISP Email</th>
                <th field="noticesent" width="40">Notice Sent</th>
            </tr>
        </thead>
        <tbody>
           <?php 
                foreach($notices->captures_ip($_POST['region'],$_POST['titleid'],$_POST['isp'],$_POST['ip']) as $us)
                {
                    echo '<tr>';
                    echo "<td width=\"30\">".$us[0]."</td>";
                    echo "<td width=\"20\">".$us[1]."</td>";
                    echo "<td width=\"50\">".$us[2]."</td>";
                    echo "<td width=\"30\">".$us[3]."</td>";
                    echo "<td width=\"30\">".$us[4]."</td>";
                    echo "<td width=\"50\">".$us[5]."</td>";
                    echo "<td width=\"50\">".$us[6]."</td>";
                    echo "<td width=\"30\">".$us[7]."</td>";
                    echo "<td width=\"50\">".$us[8]."</td>";
                    echo "<td width=\"40\">".$us[9]."</td>";
                    echo "<td width=\"40\">".$us[10]."</td>";
                   
                    echo '</tr>';
                }   
               
           ?>
            </tbody>
    </table>
   
   
    <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
    <!--<link rel="stylesheet" type="text/css" href="css-easyui/icons.css">-->
    <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
    <script type="text/javascript" src="js-easyui/easyui.js"></script>
    
    <style>
        .datagrid-toolbar, .datagrid-pager{padding:5px;}
        .datagrid-row-selected{background:#E0ECFF;}
        a{color:#000000;}
    </style>