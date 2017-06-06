<?php    
    $page = 'noticeinfos';
    include('header.php');
    // include_once('emailall.php');
    $status = $_GET['status'];
    $relatedtitleid = $_GET['relatedtitleid'];
    $sent = "";
    if($status == "notsent")
    {
        $sent = "Not Sent";
    }else{
        $sent = "Sent";
    }    
	
	$type = 'user_activities';
	$desc = 'View Notices '. $sent .' Related Title ID: <strong>' . $relatedtitleid . '</strong>';
	$user = $_SESSION['notices']['user'];
	$notices->update_rights_holder($type,$desc,$user);	
?>
    <br>
    
   <input type="hidden" id="relatedtitleid" value="no">
   <input type="hidden" id="status" value="notsent">
   <input type="hidden" id="currentUser" value="<?php echo $_SESSION['notices']['user']; ?>">
    <table id="dg" title=" Notices <?php echo $sent; ?> Details" class="easyui-datagrid" style="height:570px;"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true" view="scrollview" autoRowHeight="false" pageSize="50">
    </table>
     <?php

        if($status == "notsent")
        {
    ?>
             <div id="toolbar" style="display:none;">
			   <?php if(canAccess()) {?> 
               <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="editUser()"><img src="img/update.png" style="height:28px;"> Update</a>
			   <?php } ?>
               <input id="searchVal" placeholder="Search here..." style="line-height:22px;border:1px solid #ccc;width:250px;padding-left:5px;">
               <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="doSearch()"><img src="img/search.png" style="height:25px;"> Search</a>
               <span style="position:absolute;right:20px;<?php echo(isset($_SESSION['notices']['country']) && $_SESSION['notices']['country'])? 'display:none;':''; ?>">
                    Country : <select id="ispCountry" style="height:25px;">
                                    <option value="0">--select--</option>
                                    <option value="US">US</option>
                                    <option value="CA">CA</option>
                              </select>
               </span>   
             </div>
    <?php       
        }else{
    ?>
              <div id="toolbar" style="display:none;">
			  <?php if(canAccess()) {?> 
               <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="editUser()"><img src="img/update.png" style="height:28px;"> Update</a>
			   <?php } ?>
                <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="showIpAddress()"><img src="img/ip.png" style="height:25px;"> IP Address</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="showChart()"><img src="img/chart.png" style="height:30px;"> Chart</a>
                <input id="searchVal" placeholder="Search here..." style="line-height:22px;border:1px solid #ccc;width:250px;padding-left:5px;">
                <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="doSearch()"><img src="img/search.png" style="height:25px;"> Search</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="filterEmail()"><img src="img/dlicon.png" style="height:25px;">Download All Emails</a>
              </div>

    <?php        
        }   

    ?>

  <span id="numfilter"></span><!--<span id="displayMsg"></span>-->
   <!-- This is for the download all email modal -->
 <?php 
    $isp = $notices->getCAISPInfoSingle($relatedtitleid);
    require_once './BrowserDetection/lib/BrowserDetection.php';
    $browser = new BrowserDetection();
    if($browser->getName() == BrowserDetection::BROWSER_CHROME || $browser->getName() == BrowserDetection::BROWSER_OPERA ||
       $browser->getName() == BrowserDetection::BROWSER_EDGE){

 ?>    
  <span id="numfilter"></span><!--<span id="displayMsg"></span>-->
   <!-- This is for the download all email modal -->
  
  <div id="dlg-email" class="easyui-dialog" style="padding:10px 10px;display:none;"
        closed="true" buttons="#dlg-buttons1">
        <div class="ftitle">Choose on the filters available</div>
        <form id="filters" action="emailall.php" style="padding:10px;" method="post">
            <div class="fitem">
            <fieldset style="padding:10px 10px; border: solid 1px;"><legend><font size="3px;">Download All</font></legend>
            <label>Select Region and ISP</label><select name="ISPName"><option value="All">All ISP</option><?php foreach($isp as $is){?><option value="<?php echo $is['geoipisp'];?>"><?php echo $is['geoipregion']." | ".$is['geoipisp'];?></option><?php }?></select>
                <table>
                    <thead>
                        <th style="margin-left: 20%;">From</th>
                        <th>&nbsp;</th>
                        <th style="margin-left: 20%;">To</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="date" name="startdate"></td>
                        <td>&nbsp;</td>
                        <td><input type="date" name="enddate"></td>
                    </tr>
                        <tr>
                            <td><input type="hidden" name="country" value="CA" /></td>
                            <td><input type="hidden" name="relatedtitleID" value="<?php echo $relatedtitleid;?>" /></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr><td> <input type="submit"  class="l-btn l-btn-small l-btn-plain" name="dl" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download all Emails" /></td> <td>&nbsp;</td><td> <input type="submit"  class="l-btn l-btn-small l-btn-plain" name="el" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download Excel File" /></td>
                        </tr>
                    </tbody>
                </table>
                </fieldset>
                <fieldset style="padding:10px 10px; border: solid 1px;"><legend><font size="3px;">Select Type of Reply</font></legend>
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="Successful">Positive <br />
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="Not Successful">Negative <br />
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="All">All <br />
                <input type="submit" disabled="disabled"  class="l-btn l-btn-small l-btn-red" id="fl" name="fl"  style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download Filtered Emails"  />
                </fieldset>
                
            </div>
    </form>
        <div id="dlg-buttons1">
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="CANoticeSentDL()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Download</a> -->
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg-email').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
        </div>    
    </div>
    <script src="/js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            //This will check the status of radio button onload
            $('input:radio[name=filter]:checked').each(function() {
                $("#dl").attr('disabled',false);
            });

            //This will check the status of radio button onclick
            $('input:radio[name=filter]').click(function() {
                $("#fl").attr('disabled',false);
            });
        });
    </script>
<?php } else{ ?>
       <div id="dlg-email" class="easyui-dialog" style="padding:10px 10px;display:none;"
        closed="true" buttons="#dlg-buttons1">
        <div class="ftitle">Choose on the filters available</div>
        <form id="filters" action="emailall_mozilla.php" style="padding:10px;" method="post">
            <div class="fitem">
            <fieldset style="padding:10px 10px; border: solid 1px;"><legend><font size="3px;">Download All</font></legend>
            <label>Select Region and ISP</label><select name="ISPName"><option value="All">All ISP</option><?php foreach($isp as $is){?><option value="<?php echo $is['geoipisp'];?>"><?php echo $is['geoipregion']." | ".$is['geoipisp'];?></option><?php }?></select>
                <table>
                    <thead>
                        <th style="margin-left: 20%;">From</th>
                        <th>&nbsp;</th>
                        <th style="margin-left: 20%;">To</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="date" class="easyui-datebox" name="startdate"></td>
                        <td>&nbsp;</td>
                        <td><input type="date" class="easyui-datebox" name="enddate"></td>
                    </tr>
                        <tr>
                            <td><input type="hidden" name="country" value="CA" /></td>
                            <td><input type="hidden" name="relatedtitleID" value="<?php echo $relatedtitleid;?>" /></td>
                            <!-- <td></td> -->
                        </tr>
                        <tr><td> <input type="submit"  class="l-btn l-btn-small l-btn-plain" name="dl" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download all Emails" /></td> <td>&nbsp;</td><td> <input type="submit"  class="l-btn l-btn-small l-btn-plain" name="el" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download Excel File" /></td>
                        </tr>
                    </tbody>
                </table>
                </fieldset>
                <fieldset style="padding:10px 10px; border: solid 1px;"><legend><font size="3px;">Select Type of Reply</font></legend>
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="Successful">Positive <br />
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="Not Successful">Negative <br />
                            <input type="radio" style="padding:10px 10px;" name="filter" onclick="set_btn_status()" value="All">All <br />
                <input type="submit" disabled="disabled"  class="l-btn l-btn-small l-btn-red" id="fl" name="fl"  style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download Filtered Emails"  />
                </fieldset>
                
            </div>
    </form>
        <div id="dlg-buttons1">
        <!-- <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="CANoticeSentDL()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Download</a> -->
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg-email').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
        </div>    
    </div>
    <script src="/js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            //This will check the status of radio button onload
            $('input:radio[name=filter]:checked').each(function() {
                $("#dl").attr('disabled',false);
            });

            //This will check the status of radio button onclick
            $('input:radio[name=filter]').click(function() {
                $("#fl").attr('disabled',false);
            });
        });
    </script>
<?php } ?>
 <!-- This is for the edit part -->
  <div id="dlg" class="easyui-dialog" style="padding:10px 20px;display:none;"
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
            <div class="fitem">
                <label>ISP Shortcut:</label>
                <input name="ispshortcut" id="ispshortcut" alidType="email">
            </div>
        </form>

        <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="saveUser()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
        </div>    
    </div>
    
	<div id="dlg-fmc" class="easyui-dialog" style="padding:10px 20px;display:none;" closed="true" buttons="#dlg-buttons-fmc">
      <div class="ftitle" style="text-align:center">Please Type OK if you verified and confirm <br/>the content of all Test mails received.</div>
      <form id="fm" method="post" style="padding:10px;" novalidate>
			<input type="hidden" id="fmc-relid" value="0" />
			<input type="hidden" id="fmc-sentid" value="0" />
			<input type="hidden" id="fmc-ispname" value="" />
          <div class="fitem">
            <input type="text" id="fmc-ok" style="width:100%" placeholder="Please enter 'OK'">
          </div>
      </form>
      <div id="dlg-buttons-fmc">
          <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save_fmc()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> OK</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg-fmc').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
      </div>
	</div>
    <div id="imgloading" class="easyui-dialog" style="width:160px;padding:10px 20px;display:none;"
        closed="true">
        <center>
        <img src="img/loader-big.gif"><br><br>
        Please wait...
      </center>
    </div>   
    <script type="text/javascript">
           
         $(document).ready(function(){
                
                
                $("#searchVal").keyup(function(e){
                    if(e.keyCode == 13)
                    {
                        var v = $(this).val();    
                        if(v == "")
                        {
                            $.messager.alert('Message','Search filed is empty.');
                        }else{
                                search_dg(v);
                        } 
                    }
                    if($(this).val() == "")
                    {
                        load_datagrid();
                    }   
                });
                
                $("#ispCountry").change(function(){
                    var country = $(this).val(); 
                    if(country != 0)
                    {
                        filter_byCountry(country);
                    }else{
                        load_datagrid();
                    }   
                });
        }); 
         
        function filter_byCountry(country)
        {
            $('#dg').datagrid({
                url: 'isp-json-data.php?status=<?php echo $status; ?>&titleid=<?php echo $relatedtitleid;?>&country='+country,
                columns:[[
                    {field:'geoipregion',title:'Geo IP Region'},
					<?php 
					if($_SESSION['notices']['country']=='CA'){
					print "{field:'CanANCS',title:'CanANCS',formatter:formatCanAncs},";	
					}
					?>
                    {field:'active',title:'Active',formatter:formatActive},
                    {field:'geoipisp',title:'Geo IP ISP'},
					<?php if($status == "notsent"){ ?>
					{field:'active2',title:'Send Test Mail',formatter:formatSentTestMail},
					<?php } ?>
                    {field:'RelatedTitleAlias',title:'Related Title Alias'},
                    {field:'relatedtitleid',title:'Related Title ID'},
					<?php if($_SESSION['notices']['country']=='CA' && $sent=='Sent'){ ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>',formatter:CANoticeSentDL},
					<?php } else { ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>'},
					<?php } ?>
					<?php if($status =='notsent'){ ?>
                    {field:'Unique',title:'Unique'},
					<?php } ?>
					<?php 
					if($_SESSION['notices']['country']=='CA' && $sent =='Sent'){
					print "{field:'8',title:'Second Notices Sent',formatter:CANoticeSentInfo},";	
					print "{field:'total_responsereceived',title:'Response'},";
					}
					?>
                    {field:'ispEmail',title:'ISP Email'},
                    {field:'ISPContact',title:'ISP Contact'},
                    {field:'ISPAddress',title:'ISP Address'},
                    {field:'ISPPhone',title:'ISP Phone'},
                    {field:'ISPShortcut',title:'ISP Shortcut',hidden:true},
                    {field:'inet_ntoa(iip)',title:'ARIN',formatter:formatArin},
                ]],
                pagination: true,
                remoteFilter:false,
                pageList: [50,100,250,500,1500]

            }).datagrid('enableFilter'); 
            $('#dg').datagrid('reload');
        }
           
        function doSearch(){
            var s = $("#searchVal").val();
            if(s == "")
            {
                 $.messager.alert('Message','Search filed is empty.');
            }else{
               // $("#numfilter").html('<div class="panel-header" style="border-top:none;">Display : <select id="isplimit"><option>50</option><option>100</option><option>250</option><option>500</option><option>1000</option><option>All</option></select></div>');
                
                search_dg(s);
            } 
        }


         function search_dg(v)
         {
             $('#dg').datagrid({
                url: 'isp-json-data.php?status=<?php echo $status; ?>&titleid=<?php echo $relatedtitleid;?>&search='+v,
                columns:[[
                    {field:'geoipregion',title:'Geo IP Region'},
					<?php if(canAccess()) {?> 
					<?php 
					if($_SESSION['notices']['country']=='CA'){
					print "{field:'CanANCS',title:'CanANCS',formatter:formatCanAncs},";	
					}
					?>
                    {field:'active',title:'Active',formatter:formatActive},
					<?php } ?>
                    {field:'geoipisp',title:'Geo IP ISP'},
					<?php if($status == "notsent"){ ?>
					{field:'active2',title:'Send Test Mail',formatter:formatSentTestMail},
					<?php } ?>
                    {field:'RelatedTitleAlias',title:'Related Title Alias'},
                    {field:'relatedtitleid',title:'Related Title ID'},
					<?php if($_SESSION['notices']['country']=='CA' && $sent=='Sent'){ ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>',formatter:CANoticeSentDL},
					<?php } else { ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>'},
					<?php } ?>
					<?php if($status =='notsent'){ ?>
                    {field:'Unique',title:'Unique'},
					<?php } ?>
					<?php 
					if($_SESSION['notices']['country']=='CA' && $sent =='Sent'){
					print "{field:'8',title:'Second Notices Sent',formatter:CANoticeSentInfo},";	
					print "{field:'total_responsereceived',title:'Response'},";
					}
					?>
                    {field:'ispEmail',title:'ISP Email'},
                    {field:'ISPContact',title:'ISP Contact'},
                    {field:'ISPAddress',title:'ISP Address'},
                    {field:'ISPPhone',title:'ISP Phone'},
                    {field:'ISPShortcut',title:'ISP Shortcut',hidden:true},
                    {field:'inet_ntoa(iip)',title:'ARIN',formatter:formatArin},
                ]],
                pagination: true,
                remoteFilter:false,
                pageList: [50,100,250,500,1500]

            }).datagrid('enableFilter');  
            $('#dg').datagrid('reload');
         }    

         load_datagrid();

         function load_datagrid()
         {
            $('#dg').datagrid({
                url: 'isp-json-data.php?status=<?php echo $status; ?>&titleid=<?php echo $relatedtitleid;?>',
                columns:[[
                    {field:'geoipregion',title:'Geo IP Region'},
					<?php if(canAccess()) {?> 
					<?php 
					if($_SESSION['notices']['country']=='CA'){
					print "{field:'CanANCS',title:'CanANCS',formatter:formatCanAncs},";	
					}
					?>
                    {field:'active',title:'Active',formatter:formatActive},
					<?php } ?>
                    {field:'geoipisp',title:'Geo IP ISP'},
					<?php if($status == "notsent"){ ?>
					{field:'active2',title:'Send Test Mail',formatter:formatSentTestMail},
					<?php } ?>
                    {field:'RelatedTitleAlias',title:'Related Title Alias'},
                    {field:'relatedtitleid',title:'Related Title ID'},
					<?php if($_SESSION['notices']['country']=='CA' && $sent=='Sent'){ ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>',formatter:CANoticeSentDL},
					<?php } else { ?>
                    {field:'7',title:'Notices <?php echo $sent; ?>'},
					<?php } ?>
					<?php if($status =='notsent'){ ?>
                    {field:'Unique',title:'Unique'},
					<?php } ?>
					<?php 
					if($_SESSION['notices']['country']=='CA' && $sent =='Sent'){
					print "{field:'8',title:'Second Notices Sent',formatter:CANoticeSentInfo},";	
					print "{field:'total_responsereceived',title:'Response'},";
					}
					?>
                    {field:'ispEmail',title:'ISP Email'},
                    {field:'ISPContact',title:'ISP Contact'},
                    {field:'ISPAddress',title:'ISP Address'},
                    {field:'ISPPhone',title:'ISP Phone'},
                    {field:'ISPShortcut',title:'ISP Shortcut',hidden:true},
                    {field:'inet_ntoa(iip)',title:'ARIN',formatter:formatArin},
                ]],
                pagination: true,
                remoteFilter:false,
                pageList: [50,100,250,500,1500]

            }).datagrid('enableFilter'); 
            $('#dg').datagrid('reload');
         }

        function back()
        {
            close();
        }
        function editUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
               
                $("#isplabel").text('ISP NAME : ' + row.geoipisp.replace(/&amp;/g, '&'));
                $("#ispname").val(row.geoipisp.replace(/&amp;/g, '&'));
                $("#ispemail").val(row.ispEmail);
                $("#ispcontact").val(row.ISPContact);
                $("#ispaddress").val(row.ISPAddress);
                $("#ispphone").val(row.ISPPhone);
				$('#ispshortcut').val(row.ISPShortcut);
				
                $('#dlg').dialog({
                    modal: true
                });
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','<img src="https://cdn0.iconfinder.com/data/icons/gloss-basic-icons-by-momentum/32/notebook-edit.png" style="height:20px;"> Update Notice');
                

            }else{
                $.messager.alert('Message','Select grid row first for the update.');
            }
        }
        function filterEmail(){
           
                $('#dlg-email').dialog({
                    modal: true
                });
                $('#dlg-email').dialog('open').dialog('center').dialog('setTitle','<img src="https://cdn0.iconfinder.com/data/icons/gloss-basic-icons-by-momentum/32/notebook-edit.png" style="height:20px;"> Filter Email Download');
                

        }
        function saveUser(){
            var name = $("#ispname").val();
            var email = $("#ispemail").val();
            var contact = $("#ispcontact").val();
            var address = $("#ispaddress").val();
            var phone = $("#ispphone").val();
            var user = $("#currentUser").val();
			var shortcut = $('#ispshortcut').val();
			
            if(email == "")
            {
                $.messager.confirm('Confirm','Email is empty. Do you want to continue the update?',function(r){
                    if (r){
                        
                        $.post("update-email.php",{user:user,name: name, email: email, contact: contact, address: address, phone: phone,shortcut: shortcut},function(data,status){
                            if(status == "success")
                            {
                                $.messager.alert('Notification','ISP details has been updated.','',function(e){
                                    $('#dlg').dialog('close');
                                     $('#dg').datagrid('reload');
                                });
                            }   
                        });

                    }
                });
            }else{
                if(!isValidEmailAddress(email)) 
                {
                    $.messager.alert('Message','Invalid email address.');
                }else{
                   
                    $.messager.confirm('Confirm','Are you sure you want to update?',function(r){
                        if (r){
                            
                           $.post("update-email.php",{user:user,name: name, email: email, contact: contact, address: address, phone: phone, shortcut: shortcut},function(data,status){
                                if(status == "success")
                                {
                                    $.messager.alert('Notification','ISP details has been updated.','',function(e){
                                        $('#dlg').dialog('close');
                                         $('#dg').datagrid('reload');
                                    });
                                }   
                            });

                        }
                
                    });
                }
            }  

        }
        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
        }
        function loadChart(geoipisp,relatedtitleid,titlealias,geoipregion)
        {
            $('#imgloading').dialog({modal: true});
            $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 
			<?php
				$url = isset($_SESSION['notices'],$_SESSION['notices']['country'])? $_SESSION['notices']['country'] : ''; 
				$url = isset($_POST,$_POST['f_country'])? $_POST['f_country'] : $url;
				$url = $url=='CA'? 'chartisp-ca.php' : 'chartisp.php';
			?>
           $.post("<?php echo $url; ?>",
            {
                isp: geoipisp,
                titleid: relatedtitleid,
				title: titlealias,
                region: geoipregion
            },
            function(data,status)
            {
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
        }
		
		function save_fmc(){
			if($('#fmc-ok').val().toLowerCase()=='ok'){
				$('#dlg-fmc').dialog('close');
				updateISP( 0, $('#fmc-ispname').val() );
			}
		}
		
		function updateISP2(sendtitleID,relatedtitleID,status,ispname,dtemail){
			var sConfirm = false;
			if(status == 1)
            {
				if(dtemail != '0000-00-00 00:00:00'){
					$('#fmc-sentid').val(sendtitleID);
					$('#fmc-relid').val(relatedtitleID);
					$('#fmc-ispname').val(ispname);
					$('#dlg-fmc').dialog('open').dialog('center').dialog('setTitle','Confirmation');
					sConfirm = true;
				}
				else if(dtemail == '0000-00-00 00:00:00'){
					$.messager.alert('Activation','Your not allowed to activate the ISP: ' + ispname);
					sConfirm = true;
				}
            }
			
			if(!sConfirm){
				updateISP( (status==1?0:1) , ispname );
			}
		}
		
        function sendTestMail2(sendtitleID,relatedtitleID,ispName){
			$.post('notsent-status-email.php',{action:'sentmail',sendtitleID,relatedtitleID,ispName},function(_res){
				$.messager.alert('Test Email Sent','Test Email was successfully sent');
				$('#dg').datagrid('reload');
			});
		}
		
		function saveHistory(relatedtitleid,ip){
			var type = 'user_activities';
			var desc = 'View ARIN Notices <?php echo $sent; ?> Related Title ID: <strong>' + relatedtitleid +  '</strong> IP: <strong>' +ip+ '</strong>';
			
			$.post('insert-logs.php',{type,desc},function(_res){
				openArinWindow(ip);
			});
		}
		
        function formatArin(val,row){
            var ip = "'"+val+"'";
            return '<a onClick="saveHistory(<?php echo $relatedtitleid; ?>,'+ip+');" href="javascript:void(0);" hrefx="javascript:openArinWindow('+ip+')"> <i class=\"glyphicon glyphicon-link\"></i> View</a>';
        }
        
		function CANoticeSentInfo(val,row){
			return '<a href="notice-sent-info.php?ispname=' + row.geoipisp + '&relatedtitleid=' + row.relatedtitleid + '">' + val + '</a>';
		}
		
		function CANoticeSentDL(val,row){
			var id = row.geoipisp.split(' ').join('-');
			return   '<form action="" method="post" id="frmCADL-'+id+'">'
					+'<input type="hidden" name="ispname" value="'+row.geoipisp+'" />'
					+'<input type="hidden" name="relatedtitleid" value="'+row.relatedtitleid+'" />'
					+'<input type="hidden" name="country" value="CA" />'
					+'<input type="hidden" name="action" value="downloadallemailSent" />'
					+'</form>'
					+ val + ' <a href="javascript:void(0);" onClick="jQuery(\'#frmCADL-'+id+'\').submit();"><img src="img/dlicon.png" width="10" height="10"></button></a>';
		}
		
		function formatSentTestMail(val,row){
			var itemail = parseInt(row.iTestEmail);
			if(val ==1){
				return '<center><a href="javascript:void(0)" style="color:#ccc;text-decoration:none;">Send Test Mail</a></center>';   
			}
			else{
				if( itemail == 0 )
					return '<center><a href="javascript:void(0)" onClick="sendTestMail2('+row.SendTitleID+','+row.relatedtitleid+',\''+row.geoipisp+'\')" style="text-decoration:none;">Send Test Mail</a></center>';  
				else
					return '<center><a href="javascript:void(0)" style="color:#ccc;text-decoration:none;">Send Test Mail</a></center>';   
			}
		}
		
        function formatActive(val,row){
            var ip = "'"+val+"'";
            var ispname = "'"+row.geoipisp+"'";
			var dtemail = "'"+row.dTestEmail+"'";
           if(val == 1)
           {
                 //return '<input onchange=\"javascript:updateISP('+ip+','+ispname+')\" type="checkbox" checked>';
                 //return '<center><a href="javascript:void(0)"><img src=\"img/checkbox_full.ico\" style="width:16px;" onclick=\"javascript:updateISP('+ip+','+ispname+')\"></a></center>';               
				return '<center><a href="javascript:void(0)" onclick=\"updateISP2('+row.SendTitleID+','+row.relatedtitleid+',0,'+ispname+','+dtemail+')\"><img src=\"img/checkbox_full.ico\" style="width:16px;"></a></center>';
           }else{
                // return '<input onchange=\"javascript:updateISP('+ip+','+ispname+')\" type="checkbox">';
                //return '<center><a href="javascript:void(0)"><img src=\"img/checkbox_empty.ico\" style="width:15px;" onclick=\"javascript:updateISP('+ip+','+ispname+')\"></a></center>';
				return '<center><a href="javascript:void(0)"  onclick=\"updateISP2('+row.SendTitleID+','+row.relatedtitleid+',1,'+ispname+','+dtemail+')\"><img src=\"img/checkbox_empty.ico\" style="width:15px;"></a></center>';
		   } 
        }  
		
		function formatCanAncs(val,row){
			var ispname = "'"+row.geoipisp+"'";
			if(row.CanANCS == 1)
           {
                 return '<center><a href="javascript:void(0)"><img src=\"img/checkbox_full.ico\" style="width:16px;" onclick=\"javascript:updateISPInformation('+0+','+ispname+')\"></a></center>';               
           
           }else{
                return '<center><a href="javascript:void(0)"><img src=\"img/checkbox_empty.ico\" style="width:15px;" onclick=\"javascript:updateISPInformation('+1+','+ispname+')\"></a></center>';
           } 
		}
		
		function updateISPInformation(val,ispname){
			$.post('update-isp_information.php',{val,ispname},function(_res){
				$('#dg').datagrid('reload');
			});
		}
		
        function updateISP(val,ispname)
        {
            var user = $("#currentUser").val();
            if(val == 1)
            {
               $.messager.confirm('Confirm','Are you sure you want to <br>deactivate '+ispname+'?',function(r){
                    if (r){
                        var status = 0;
                        $.post("change_status.php",{user:user,ispname: ispname,status: status},function(data,status){

                            $.messager.alert('Notification',ispname+' has been deactivated.','',function(e){
                                //load_datagrid();
                                $('#dg').datagrid('reload');
                            });

                        });
                    
                    }
            
                });
            }else{
                $.messager.confirm('Confirm','Are you sure you want to <br>activate '+ispname+'?',function(r){
                    if (r){
                       var status = 1;
                        $.post("change_status.php",{user:user,ispname: ispname,status: status},function(data,status){

                            $.messager.alert('Notification',ispname+' has been activated.','',function(e){
                                //load_datagrid();
                                $('#dg').datagrid('reload');
                            });

                        });
                    }
            
                });
            }    
        }

        function openArinWindow(url)
        {
            var newurl = 'http://whois.arin.net/rest/ip/'+url;
            window.open(newurl, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=900,height=700");

        }
        function showChart(){
            var row = $('#dg').datagrid('getSelected');
            if (row){            
                   loadChart(row.geoipisp,row.relatedtitleid,row.RelatedTitleAlias,row.geoipregion);
            }else{
                $.messager.alert('Message','Select grid row first.');
            }
        }
        function showIpAddress()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){            
                
                $('#imgloading').dialog({modal: true});
                $('#imgloading').dialog('open').dialog('center').dialog('setTitle',''); 

                $.post("ipaddress.php",{geoipregion: row.geoipregion, geoipisp: row.geoipisp, titleid: row.relatedtitleid},function(data,status){
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
    
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px 30px;
        }
        .ftitle{
            font-size:14px;
            font-weight:bold;
            padding:5px 0;
            margin-bottom:10px;
            border-bottom:1px solid #ccc;
        }
        .fitem{
            margin-bottom:5px;
        }
        .fitem label{
            display:inline-block;
            width:80px;
        }
        .fitem input{
            width:200px;
        }
        .lg-container{width:100%;position:relative;}
         #ipsearch{position: absolute;right:0px;top:0px;}
         #ipsearch2{position: absolute;left:0px;top:0px;}
         .searchEnter{padding:3px;width:250px;}
         .custom-menu {
            display: none;
            z-index: 1000;
            position: absolute;
            overflow: hidden;
            border: 1px solid #CCC;
            white-space: nowrap;
            font-family: sans-serif;
            background: #FFF;
            color: #333;
            border-radius: 5px;
        }

        .custom-menu li {
            padding: 8px 12px;
            cursor: pointer;
        }

        .custom-menu li:hover {
            background-color: #DEF;
        }
        /*.pagination-info{display:none;}
        .datagrid .datagrid-pager{height:30px;}
        .pagination table{display:none;}*/
        .pagination-first{background-image:url('img/first1.png');}
        .pagination-prev{background-image:url('img/previous1.png');}
        .pagination-last{background-image:url('img/last1.png');}
        .pagination-next{background-image:url('img/next1.png');}
        .pagination-load{}
        .pagination-load{background-image:url('img/refresh.png');}
        /*.icon-save{
            background:url('https://cdn2.iconfinder.com/data/icons/UII_Icons/24x24/email.png') no-repeat center center;
        }*/
    </style>
   
</body>
</html>