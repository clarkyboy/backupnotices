<?php
	include('classes/notices_controllers.php');
    $notices = new Notices;
    $status = "";
    if($_POST['status'] == "notsent")
    {
        $status = "Not Sent";
    }else{
        $status = "Sent";
    }  
?>
	<table id="dg" title="Notices <?php echo $status; ?> Details" class="easyui-datagrid" style="height:600px;"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="geoipregion" width="30">Geo IP Region</th>
                <th field="geoipisp" width="50">Geo IP ISP</th>
                <th field="relatedtitlealias" width="50">Related Title Alias</th>
                <th field="relatedtitleid" width="30">Related Title ID</th>
                <th field="noticenotsent" width="30">Notices <?php echo $status; ?></th>
                <th field="ispemail" width="50">ISP Email</th>
                <th field="ispcontact">ISP Contact</th>
                <th field="ispaddress" width="50">ISP Address</th>
                <th field="ispphone">ISP Phone</th>  
                <th field="arin" width="20">ARIN</th>

            </tr>
        </thead>
        <tbody>
           <?php 
                $titleid = $_POST['titleid'];
                $status = $_POST['status'];
                foreach($notices->notice_not_sent($titleid,$status) as $us)
                {
                    echo '<tr>';
                    echo "<td width=\"30\">".$us[0]."</td>";
                    echo "<td width=\"50\">".$us[1]."</td>";
                    echo "<td width=\"50\">".$us[2]."</td>";
                    echo "<td width=\"30\">".$us[3]."</td>";
                    
                    if($status == "notsent")
                    {
                        echo "<td width=\"30\">".$us[4]."</td>";
                    }else{
                        echo "<td width=\"30\"><a href=\"javascript:loadChart('".$us[1]."','".$us[3]."')\" title='go to isp chart'>".$us[4]."</a></td>";
                    }    

                    echo "<td width=\"50\">".$us[5]."</td>";
                    echo "<td width=\"50\">".$us[7]."</td>";
                    echo "<td width=\"50\" width=\"50\">".$us[8]."</td>";
                    echo "<td width=\"50\">".$us[9]."</td>";   
                    echo "<td width=\"20\"><a href=\"javascript:openArinWindow('".$us[6]."')\"><i class=\"glyphicon glyphicon-link\"></i> View</a></td>";
                    echo '</tr>';
                }   
               
           ?>
            </tbody>
    </table>
   
    <?php

    	if($status == "notsent")
    	{
    ?>
    		 <div id="toolbar">
		       <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="editUser()"><i class="glyphicon glyphicon-edit"></i> Update Notice</a>
		     </div>
    <?php		
    	}else{
    ?>
              <div id="toolbar">
                 <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="showIpAddress()"><i class="glyphicon glyphicon-map-marker"></i> IP Address</a>
                  <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="showChart()"><i class="glyphicon glyphicon-stats"></i> Chart</a>
              </div>

    <?php        
        }	

    ?>
    
    <div id="dlg" class="easyui-dialog" style="padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle">ISP Information</div>
        <form id="fm" method="post" style="padding:10px;" novalidate>
            <div class="fitem">
                <label id="isplabel" style="width:100%;"></label>
                <input type="hidden" id="ispname">
            </div>
            <div class="fitem">
                <label>EMAIL:</label>
                <input name="email" id="ispemail" alidType="email">
            </div>
            <div class="fitem">
                <label>CONTACT:</label>
                <input name="email" id="ispcontact" alidType="email">
            </div>
            <div class="fitem">
                <label>ADDRESS:</label>
                <input name="email" id="ispaddress" alidType="email">
            </div>
            <div class="fitem">
                <label>PHONE:</label>
                <input name="email" id="ispphone" alidType="email">
            </div>
        </form>

        <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="saveUser()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
        </div>    
    </div>
    
    <link rel="stylesheet" type="text/css" href="css-easyui/easyui.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/icons.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/color.css">
    <link rel="stylesheet" type="text/css" href="css-easyui/demo.css">
    <script type="text/javascript" src="js-easyui/easyui.js"></script>
    <style>
        .datagrid-toolbar, .datagrid-pager{padding:5px;}
        .datagrid-row-selected{background:#E0ECFF;}
        a{color:#000000;}
    </style>