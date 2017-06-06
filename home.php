<?php
    $page = "home";
    include('header.php');
?>
  <br>
     <input type="hidden" id="currentUser" value="<?php echo $_SESSION['notices']['user']; ?>">
    <div style="width:100%;position:relative;">
      <span style="position:absolute;right:0px;top:-30px;">
         <input type="text" class="searchEnter" id="txtipsearch" placeholder="Type IP or Notice ID" style="width:150px;padding:3px;">  
         <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" id="findNoticeID"><img src="img/search.png" style="height:22px;" > Find ID</a>
         <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" id="findIP"><img src="img/search.png" style="height:22px;"> Show Notices</a>
		 <?php if(canAccess() || true){ ?>
		 | 
         <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" id="showCaptures"><img src="img/capture.png" style="height:22px;"> Show Capture</a> 
		 <?php } ?>
      </span>   
        <div id="ipcontainer">
            <table id="dg" title="Notices <?php echo !canAccess()? ' Per IP': (' ' . $sent . ' Details'); ?>" class="easyui-datagrid" style="height:600px;"
                  toolbar="#toolbar" pagination="true"
                  rownumbers="<?php echo !canAccess()? 'false':'true'; ?>" fitColumns="true" singleSelect="true">
				<?php if(!canAccess()) {?> 
					<thead>
						<tr><th data-options="field:'code',width:'99.i%'">Search IP first to view details</th></tr>
					</thead>
				<?php } ?>
          </table>
        </div>
    </div> 
   

    <div id="toolbar" style="display:none;">
      <?php if(canAccess()) {?> 
	  <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="updateNotice()"><img src="img/update.png" style="height:28px;"> Update</a>
      <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="titleChart()"><img src="img/chart.png" style="height:30px;"> Chart</a>
	  <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="addTitle()"><img src="img/add-item.png" style="height:30px;"> Add Title</a>
	  <a data-toggle="modal" href="#myModal" class="easyui-linkbutton" plain="true"><img src="img/top100.png" style="height:30px;"> Overall Top 100</a>
	  <?php } ?>
		<pre style="display:none">
		<?php var_dump($_SESSION); ?>
		</pre>
		<?php if(canAccess()){ ?>
		<?php if(isset($_SESSION['notices']['fcountry']) || !$_SESSION['notices']['country']): ?>
		<span style="position:absolute;right:20px">
			<?php
			if(!isset($_POST,$_POST['f_country'])){
				$_POST['f_country']  = $_SESSION['notices']['fcountry'];
			}
			?>
			Country : <select id="fcountry" style="height:25px;">
							<option value="0" >--select--</option>
							<option value="US" <?php echo (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='US')? 'selected':''; ?>>US</option>
							<option value="CA" <?php echo (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='CA')? 'selected':''; ?>>CA</option>
						</select>
	   </span>  
	   <?php endif; ?>
	   <?php } ?>
   </div> 
<?php
	$country = $_POST['notices']['country'];
	$get_top100 = $notices->get_overall_top_100($country);
	$rank = 0;
?>
<style>
			#myModal
			{
				margin:0;
				padding:0;
				overflow-y: scroll;
			}
			.modal-body
			{
				padding:20px;
				width: 100%;
				border-radius:5px;
				margin-top:25px;
			}
			#user_data{}
			.table_body{
				overflow-y: scroll;
			}
</style>
<div class="modal fade bs-example-modal-lg" id="myModal">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Top 100 IP Adresses in <?php if($_POST['notices']['country'] == "US"): echo "USA"; else: echo "Canada"; endif;?></h4>
    </div>
    <div class="modal-body">
      <table id="user_data" class="table table-striped" cellspacing="0" width="100%">
				<thead>
						<th>Rank</th>
						<th>IP Address</th>
						<th>Notices Sent</th>
						<th>Related Title Alias</th>
				</thead>
				<tbody class="table_body">
				<?php if($get_top100): foreach($get_top100 AS $k=>$i): ?>
				<?php //$ii = explode('#$#', $i['SecondNoticeInfo']); ?>
					<tr>
						<td><?php echo ++$rank;?></td>
						<td><?php echo $i['IP'];?></td>
						<td style="padding: 10px;"><?php echo $i['Notices_Sent'];?></td>
						<td><?php echo $i['Title_Alias'];?></td>
					</tr>
				<?php endforeach; endif; ?>
				</tbody>
</table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="dlg-ai" class="easyui-dialog" style="padding:10px 20px;display:none;" closed="true" buttons="#dlg-buttons-ai">
		<form id="fm-ai" method="post" style="padding:10px;" novalidate>
			<input type="hidden" id="isptitle-ai">
			<?php if(empty($_SESSION['notices']['lawyer'])): ?>
			<div class="fitem">
				<label for="lawyer-ai" style="width:115px;">Lawyer:</label>
				<select id="lawyer-ai" name="lawyer-ai" style="width:300px;">
				<?php
					$lawyers = $notices->get_lawyer();
					if($lawyers){
						foreach($lawyers AS $k=>$i){
							echo '<option value="' . $i['LawyerID'] . '">' . $i['cLayerName'] . '</option>';
						}
					}
				?>
				</select>
			</div>
			<?php endif; ?>
			<div class="fitem">
				<label for="title-ai" style="width:115px;vertical-align:top;">Title:</label>
				<select class="select" id="title-ai" name="title-ai" onChange="selectTitleData();" style="width:300px;">
				<option value="">Select Title</option>
				<?php
					$titlename = $notices->get_related_title_name();
					if($titlename){
						foreach($titlename AS $i){
							echo '<option value="'.$i['RelatedTItleID'].'">'.correct_encoding($i['RelatedTitlename']).'</option>';
						}
					}
				?>
				</select>
			</div>
			<div class="fitem">
				<label for="region-ai" style="width:115px;vertical-align:top">Region:</label>
				<div style="display:inline-block; width:300px;">
					<a href="javascript:void(0);" onClick="jQuery('.region-ai').click();if(jQuery(this).html()=='check all'){jQuery(this).html('uncheck all')}else{jQuery(this).html('check all')}">check all</a>
					<div style="padding:10px 5px;border:1px solid #ccc;overflow-y:auto; height:150px;">
					<?php
						$region = $notices->get_region();
						if($region){
							foreach($region AS $k=>$i){
								echo '<label style="display:block;width:100%;" for="region-ai-'.$k.'"><input style="width:auto;vertical-align:bottom;" type="checkbox" class="region-ai" id="region-ai-'.$k.'" name="region-ai[]" value="'.$i['regionname'].'"/> '.$i['regionname'].'</label>';
							}
						}
					?>
					</div>
				</div>
			</div>
			<div class="fitem">
				<label style="width:115px;">Owner:</label>
				<input type="text" id="owner-ai" name="owner-ai" alidType="email" style="width:300px;">
			</div>
			<div class="fitem">
				<label style="width:115px;">Contact:</label>
				<input type="text" id="contact-ai" name="contact-ai" alidType="email" style="width:300px;">
			</div>
			<div class="fitem">
				<label style="width:115px;">Address:</label>
				<input type="text" id="address-ai" name="address-ai" alidType="email" style="width:300px;">
			</div>
			<div class="fitem">
				<label style="width:115px;">Phone:</label>
				<input type="text" id="phone-ai" name="phone-ai" alidType="email" style="width:300px;">
			</div>
			<div class="fitem">
				<label style="width:115px;">Email:</label>
				<input type="text" id="email-ai" name="email-ai" alidType="email" style="width:300px;">
			</div>
			<div class="fitem">
				<label style="width:115px;">Title Shorcut:</label>
				<input type="text" id="titleshorcut-ai" name="titleshorcut-ai" alidType="email" style="width:300px;">
			</div>
			<div id="dlg-buttons-ai">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="send_ai()" style="width:140px;display:none;"><i class="glyphicon glyphicon-floppy-save"></i> Send Test Mail</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save_ai()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Save</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg-ai').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
			</div>
		</form>
	</div>
   <div id="dlg" class="easyui-dialog" style="width:450px;padding:10px 20px;display:none;" closed="true" buttons="#dlg-buttons">
      <form id="fm" method="post" style="padding:10px;" novalidate>
		  <div style="position:relative;">
			  <?php if( (isset($_SESSION['notices']['fcountry']) || !$_SESSION['notices']['country']) && ( (isset($_POST,$_POST['f_country']) && $_POST['f_country'] != 'US' && $_POST['f_country'] != 'CA' ) || !isset($_POST,$_POST['f_country']))){ ?>
			  <div class="fitem">
				<label style="width:105px;">Select Country: </label>
				<select id="fcountry2" style="height:25px;" onChange="loadupdateNotice();">
					<option value="US" <?php echo (isset($_SESSION['notices'],$_SESSION['notices']['fcountry']) && $_SESSION['notices']['fcountry'] == 'US') || (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='US')? 'selected':''; ?>>US</option>
					<option value="CA" <?php echo (isset($_SESSION['notices'],$_SESSION['notices']['fcountry']) && $_SESSION['notices']['fcountry'] == 'CA') ||(isset($_POST,$_POST['f_country']) && $_POST['f_country']=='CA')? 'selected':''; ?>>CA</option>
				</select>
			  </div>
			  <?php } ?>
			  <div class="wrapper-for-all" style="float: left;width: 100%;">
				  <div class="fitem">
					  <label id="rtitle" style="width:100%;"></label>
					  <input type="hidden" id="title">
					  <input type="hidden" id="at_id">
					  <input type="hidden" id="isptitle">
					  <input type="hidden" id="fm_country" />
				  </div>
				  <div class="fitem">
					  <label>Owner:</label>
					  <input type="text" id="owner" alidType="email">
				  </div>
				  <div class="fitem">
					  <label>Contact:</label>
					  <input type="text" id="contact" alidType="email">
				  </div>
				  <div class="fitem">
					  <label>Address:</label>
					  <input type="text" id="address" alidType="email">
				  </div>
				  <div class="fitem">
					  <label>Phone:</label>
					  <input type="text" id="phone" alidType="email">
				  </div>
				  <div class="fitem">
					  <label>Email:</label>
					  <input type="text" id="email" alidType="email">
				  </div>
			  </div>
			  <div class="wrapper-for-us" id="usregion-w" style="float: right;width: 100%;">
				
			  </div>
			  <div style="clear:both;"></div>
		  </div>
      </form>
      <div id="dlg-buttons">
          <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
      </div>
  </div>
  <div id="dlg-fmc" class="easyui-dialog" style="padding:10px 20px;display:none;" closed="true" buttons="#dlg-buttons-fmc">
      <div class="ftitle" style="text-align:center">Please Type OK if you verified and confirm <br/>the content of all Test mails received.</div>
      <form id="fm" method="post" style="padding:10px;" novalidate>
			<input type="hidden" id="fmc-relid" value="0" />
			<input type="hidden" id="fmc-sentid" value="0" />
          <div class="fitem">
            <input type="text" id="fmc-ok" style="width:100%" placeholder="Please enter 'OK'">
          </div>
      </form>
      <div id="dlg-buttons-fmc">
          <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save_fmc()" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> OK</a>
          <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg-fmc').dialog('close')" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Cancel</a>
      </div>
  <!--- This is used to transfer the country value to formatClick() js-->
  <div id= "target-country" style="display: none;">
  	<?php
  			if($_POST['notices']['country'] == "US"): $output = "US"; echo htmlspecialchars($output); else: $output = "CA"; echo htmlspecialchars($output); endif;
  	?>
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
		var ctry = '<?php echo (isset($_SESSION['notices'],$_SESSION['notices']['country']) && $_SESSION['notices']['country'] == 'US') || (isset($_SESSION['notices'],$_SESSION['notices']['fcountry']) && $_SESSION['notices']['fcountry'] == 'US') || (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='US')? 'US' : 'CA'; ?>';
		<?php if(canAccess()){ ?>
		loadDataGrid();
		function loadDataGrid(){
			$('#dg').datagrid({
				url: 'load-home-details.php<?php echo isset($_POST,$_POST['f_country'])? '?f_country='.$_POST['f_country']:''; ?>',
				columns:[[
					{field:'RelatedTitleAlias',title:'Related Title Alias',width:150},
					<?php if( true || (isset($_SESSION['notices'],$_SESSION['notices']['country']) && $_SESSION['notices']['country'] == 'US') || (isset($_SESSION['notices'],$_SESSION['notices']['fcountry']) && $_SESSION['notices']['fcountry'] == 'US') || (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='US')){ ?>
					<?php if(canAccess()) {?> 
					{field:'status', title:'Status',width:50},
					<?php } ?>
					<?php } ?>
					{field:'relatedtitleid',title:'Related Title ID',width:100},
					{field:'2',title:'Notices not sent  <span style="margin-left:10px;"></span>(<a href="notice-information.php?status=notsent&relatedtitleid=no">All</a>)',width:100,formatter:formatNotSent},
					{field:'3',title:'Notices sent <span style="margin-left:10px;"></span>(<a href="notice-information.php?status=sent&relatedtitleid=no">All</a>)',width:100,formatter:formatSent},
					{field:'Active_Torrents',title:'Active Torrents',width:100},
					{field:'Valid_Torrents',title:'Valid Torrents',width:100},
					<?php if( true || (isset($_SESSION['notices'],$_SESSION['notices']['country']) && $_SESSION['notices']['country'] == 'US') || (isset($_SESSION['notices'],$_SESSION['notices']['fcountry']) && $_SESSION['notices']['fcountry'] == 'US') || (isset($_POST,$_POST['f_country']) && $_POST['f_country']=='US')){ ?>
					<?php if(canAccess()) {?> 
					{field:'view', title:'View Top 100 <span style="margin-left:10px;"></span>', width:120, formatter:formatClick},
					{field:'action',title:'',width:70},
					<?php } ?>
					<?php } ?>
				]],
				pagination: true
			});
		}
		<?php } ?>
		$('#fcountry').change(function(){
			var fcountry = $(this).val();
			
			$('body').append('<form method="post" id="frm_grid_109" style="display:none;"></form>');
			$('#frm_grid_109').append('<input type="hidden" name="f_country" value="'+fcountry+'"/>');
			$('#frm_grid_109').submit();
		});
				
       function formatNotSent(val,row){
           var id = row.relatedtitleid;
           return '<a href="notice-information.php?status=notsent&relatedtitleid='+id+'">'+val+'</a>';
       }

       function formatSent(val,row){
           var id = row.relatedtitleid;
           return '<a href="notice-information.php?status=sent&relatedtitleid='+id+'">'+val+'</a>';
       }
	  function formatClick(val,row){
           var id = row.relatedtitleid;
           var div = document.getElementById("target-country");
    	   var country = div.textContent;
           return '<a href="top100.php?country='+country+'&relatedtitleid='+id+'">Click Here</a>';
       }
       function titleChart()
       {
            <?php
				$url = isset($_SESSION['notices'],$_SESSION['notices']['country'])? $_SESSION['notices']['country'] : ''; 
				$url = isset($_POST,$_POST['f_country'])? $_POST['f_country'] : $url;
				$url = $url=='CA'? 'chart-ca.php' : 'chart.php';
			?>
			
			var row = $('#dg').datagrid('getSelected');
              if (row){  
                $('#imgloading').dialog('open').dialog('center').dialog('setTitle','');
                $.post("<?php echo $url; ?>",
                {
                    titleid: row.relatedtitleid,
                    titlealias: row.RelatedTitleAlias
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


            }else{
                $.messager.alert('Message','Select grid row first for the update.');
            }
        }
		<?php if(canAccess()){ ?>
		function getUSRegion(titleid, cty){
			if(cty == 'US'){
				$('.wrapper-for-all,.wrapper-for-us').css({'width':'49%'});
				$.post('get-home-related-title-region.php',{titleid}, function(_res){
					$('.wrapper-for-us').show();
					$('#usregion-w').html(_res);
				});
			}
			else{
				$('.wrapper-for-us').hide();
				$('.wrapper-for-all').css({'width':'100%'});
			}
		}
		
		function loadupdateNotice(){
			var row = $('#dg').datagrid('getSelected');
            if (row){
			   $('#usregion-w').html('');
               $("#rtitle").text('Title ID : ' + row.relatedtitleid);
               $("#title").val(row.relatedtitleid);
               $("#isptitle").val(row.RelatedTitleAlias);
               $.post("get-home-details-value.php",{titleid: row.relatedtitleid,country: $('#fcountry2').val()},function(data,status){
                  var json = JSON.parse(data);
				  if(json[0]!= undefined){
					  $("#at_id").val(json[0].ID);
					  $("#fm_country").val(json[0].GeoIpCountry);
					  $("#owner").val(json[0].Owner);
					  $("#contact").val(json[0].OwnerContact);
					  $("#address").val(json[0].OwnerAddress);
					  $("#phone").val(json[0].OwnerPhone);
					  $("#email").val(json[0].OwnerEmail);
					  
					  getUSRegion(row.relatedtitleid,$('#fcountry2').val());
				  }
				  else{
					$.messager.alert('Message','No Related Title Information for the country ' + $('#fcountry2').val());
					$('#fcountry2')
				  }
              });

              $('#dlg').dialog('open').dialog('center').dialog('setTitle','Update Notice');

            }else{
                $.messager.alert('Message','Select grid row first for the update.');
            } 
		}
		
        function updateNotice()
        {
            var row = $('#dg').datagrid('getSelected');
            if (row){
              $('#usregion-w').html('');
              $("#rtitle").text('Title ID : ' + row.relatedtitleid);
               $("#title").val(row.relatedtitleid);
               $("#isptitle").val(row.RelatedTitleAlias);
               $.post("get-home-details-value.php",{titleid: row.relatedtitleid},function(data,status){
                  var json = JSON.parse(data);
				  $("#fcountry2").val(json[0].GeoIpCountry);
				  $("#at_id").val(json[0].ID);
				  $("#fm_country").val(json[0].GeoIpCountry);
                  $("#owner").val(json[0].Owner);
                  $("#contact").val(json[0].OwnerContact);
                  $("#address").val(json[0].OwnerAddress);
                  $("#phone").val(json[0].OwnerPhone);
                  $("#email").val(json[0].OwnerEmail);
				  
				  
				  getUSRegion(row.relatedtitleid,ctry);
              });

              $('#dlg').dialog('open').dialog('center').dialog('setTitle','Update Notice');

            }else{
                $.messager.alert('Message','Select grid row first for the update.');
            }
        }
		
        function save()
        {
            var owner = $("#owner").val();
            var contact = $("#contact").val();
            var address = $("#address").val();
            var phone = $("#phone").val();
            var email = $("#email").val();
            var titleid = $("#title").val();
            var country = $("#fm_country").val();
            var at_id = $("#at_id").val();
			
            var user = $("#currentUser").val();
            var emptyCtr = 0;
            if(owner == ""){ emptyCtr++; }
            if(contact == ""){ emptyCtr++; }
            if(address == ""){ emptyCtr++; }
            if(phone == ""){ emptyCtr++; }
            if(email == ""){ emptyCtr++; }
            //alert(user);
            var isptitle = $("#isptitle").val();
            if(emptyCtr > 4)
            {
                $.messager.alert('Message','All fields are empty.');
            }else{
				var regionsChk = new Array();
				var regionsUChk = new Array();
				
				$('.usregion-fld').each(function(i){
					if($(this).is(':checked')){
						regionsChk.push( $(this).val() );
					}
					else{
						regionsUChk.push( $(this).val() );
					}
				});
				
                if(emptyCtr > 0)
                {
                    $.messager.confirm('Confirm','Other field is empty. Do you want to continue update?',function(r){
                        if (r){
                        
                            $.post("update-home-info.php",{at_id:at_id, isptitle:isptitle,user: user,titleid: titleid,owner: owner, contact: contact, address: address, phone: phone, email: email, country: country, regionsA: regionsChk, regionsR: regionsUChk},function(data,status){
                                $.messager.alert('Message','Updated successfully.');
                                $('#dlg').dialog('close');
                                $('#dg').datagrid('reload');
                            });

                        }
                    });
                }else{
                    $.messager.confirm('Confirm','Do you want to update?',function(r){
                        if (r){
                            
                              $.post("update-home-info.php",{at_id:at_id,isptitle:isptitle,user:user,titleid: titleid,owner: owner, contact: contact, address: address, phone: phone, email: email, country: country, regionsA: regionsChk, regionsR: regionsUChk},function(data,status){
                                  $.messager.alert('Message','Updated successfully.');
                                  $('#dlg').dialog('close');
                                  $('#dg').datagrid('reload');
                              });

                        }
                    });
                }    
            }   

        }

		function selectTitleData(){
			$.post("get-home-title-value.php",{title: $('#title-ai').val()},function(data,status){
				if(data){
					var json = JSON.parse(data);
					$("#region-ai").val(json.GeoIpRegion);
					$("#owner-ai").val(json.Owner);
					//$("#contact-ai").val(json.OwnerContact);
					//$("#address-ai").val(json.OwnerAddress);
					$("#phone-ai").val(json.OwnerPhone);
					//$("#email-ai").val(json.OwnerEmail);
					$("#titleshorcut-ai").val(json.TitleShortCut);
				}
			});
		}
		
		function addTitle(){
			selectTitleData();
			$('#dlg-ai').dialog('open').dialog('center').dialog('setTitle','Add Title');
		}
		
		function save_ai(){
			var emptyCtr = 0;
			if(!$('.region-ai').is(':checked')){ emptyCtr++; }
			if($('#owner-ai').val() == ""){ emptyCtr++; }
            if($('#contact-ai').val() == ""){ emptyCtr++; }
            if($('#address-ai').val() == ""){ emptyCtr++; }
            if($('#phone-ai').val() == ""){ emptyCtr++; }
            if($('#email-ai').val() == ""){ emptyCtr++; }
			if(!isValidEmail( $('#email-ai').val() ) ){
				$.messager.alert('Message','Invalid Email.');
				return false;
			}
			if(emptyCtr > 4){
                $.messager.alert('Message','All fields are empty.');
            }
			else if(emptyCtr > 0){
				$.messager.confirm('Confirm','Other field is empty. Do you want to continue?',function(r){
					if (r){
						$.post("insert-title-info.php",$('#fm-ai').serialize(),function(data,status){
							$.messager.alert('Message','Added successfully.');
							$('#dlg-ai').dialog('close');
							$('#dg').datagrid('reload');
							clearFMAI();
						});
					}
				});
			}
			else{
				$.messager.confirm('Confirm','Do you want to continue?',function(r){
					if (r){
						
						$.post("insert-title-info.php",$('#fm-ai').serialize(),function(data,status){
							$.messager.alert('Message','Added successfully.');
							$('#dlg-ai').dialog('close');
							$('#dg').datagrid('reload');
							clearFMAI();
						});

					}
				});
			}
		}
		function send_ai(){
			if(!isValidEmail( $('#email-ai').val() ) ){
				$.messager.alert('Message','Invalid Email.');
				return false;
			}
			$.post("test-mail.php",{email:$('#email-ai').val()},function(data,status){
				if(data == '1'){
					$.messager.alert('Message','Test email was sent.');
				}
				else{
					$.messager.alert('Message',data);
				}
			});
		}
		function sendTestMail(sendtitleid,relatedtitleid){
			if(sendtitleid && relatedtitleid){
				$('#imgloading').dialog('open').dialog('center').dialog('setTitle','');
				$.post("send-test-mail.php",{sendtitleid:sendtitleid,relatedtitleid:relatedtitleid},function(data,status){
					$('#imgloading').dialog('close');
					$.messager.alert('Message','Successfully Send Test Mail.');
					loadDataGrid();
				});
			}
		}
		function changeStatus(sendtitleid,relatedtitleid,stats){
			if(sendtitleid && relatedtitleid){
				if(stats == 0){
					$('body').append('<div id="popup"><div id="popup-inner"><div id="popup-message"><p>Do you want to deactivate title? </p></div><div id="popup-button"><a id="btn-yes" href="javascript:void(0)" onClick="pro_changeStatus('+sendtitleid+','+relatedtitleid+','+stats+');">Yes</a><a id="btn-no" href="javascript:void(0)" onClick="jQuery(\'#popup\').remove();">No</a></div></div></div>');
				}
				else{
					$('#popup').remove();
					pro_changeStatus(sendtitleid,relatedtitleid,stats)
				}
			}
		}
		
		function pro_changeStatus(sendtitleid,relatedtitleid,stats){
			$('#imgloading').dialog('open').dialog('center').dialog('setTitle','');
			$.post("update-status.php",{sendtitleid:sendtitleid,relatedtitleid:relatedtitleid,status:stats},function(data,status){
				$('#imgloading').dialog('close');
				if(stats==1){
					if(parseInt(data)>0){
						$('#fmc-sentid').val(sendtitleid);
						$('#fmc-relid').val(relatedtitleid);
						$('#dlg-fmc').dialog('open').dialog('center').dialog('setTitle','Confirmation');
					}
					else{
						$.messager.alert('Message','Test mails are required.');
					}
				}
				else{
					$.messager.alert('Message','Status was changed.');
					loadDataGrid();
				}
			});
		}
		function save_fmc(){
			if($('#fmc-ok').val().toLowerCase()=='ok'){
				$('#fmc-ok').val('');
				$('#dlg-fmc').dialog('close');
				$('#imgloading').dialog('open').dialog('center').dialog('setTitle','');
				$.post("update-status.php",{sendtitleid:$('#fmc-sentid').val(),relatedtitleid:$('#fmc-relid').val(),status:3},function(data,status){
					$('#imgloading').dialog('close');	
					$.messager.alert('Message','Status was changed.');
					loadDataGrid();	
				});
			}
		}
		<?php } ?>
		function clearFMAI(){
			$('#owner-ai').val('');
			$('#contact-ai').val('');
			$('#address-ai').val('');
			$('#phone-ai').val('');
			$('#email-ai').val('');
		}
		function isValidEmail(email){
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}
    </script>
	<!--
	<script type="text/javascript" src="js/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="js/SOL.js"></script>
	<script type="text/javascript">
		(function($){
			if($('#title-ai').length){
				$('#title-ai').searchableOptionList({allowNullSelection: true});
			}
		})(jQuery);
		jQuery.noConflict();
	</script>
	-->
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
            width:160px;
        }
         a{color:#000000;}
         .home-dg{width:100%;position:relative;}
         #ipsearch{position: absolute;right:0px;top:0px;}
         #ipsearch2{position: absolute;left:0px;top:0px;}
         .searchEnter2{padding:3px;width:250px;}
         .pagination-info{display:none;}
        .datagrid .datagrid-pager{height:30px;}
        .pagination table{display:none;}
		
		#fm-ai .sol-option label {width: 100% !important;}
		#fm-ai .sol-option input {width: auto !important;}
		#fm-ai .sol-container {display: inline-block;}
		#fm-ai .sol-selection-container {height: 300px; overflow-y: auto;}
		#fm-ai .sol-selection {overflow: hidden;}
		#fm-ai .sol-option .sol-radio{margin-top:-2px;}
		
		div#popup {
			position: fixed;
			width: 100%;
			height: 100%;
			top: 0px;
			left: 0px;
			background: rgba(255, 255, 255, 0.5);
		}
		div#popup-inner {
			position: absolute;
			width: 300px;
			top: 50px;
			left: 50%;
			margin-left: -160px;
			border: 1px solid #ccc;
			background: #fff;
			box-shadow: 0px 1px 1px #ababab;
			padding: 20px 10px;
		}
		div#popup-message{
			padding-top: 5px;
			padding-bottom: 20px;
		}
		div#popup-button {
			text-align: right;
			margin-left: -10px;
			margin-right: -10px;
			margin-bottom: -20px;
			padding: 10px;
			border-top: 1px solid #ccc;
			background: #ececec;
		}
		#popup-button > a {
			padding: 3px 10px;
			color: #fff;
			margin-right: 2px;
			text-decoration:none;
		}
		#popup-button > a:hover{
			opacity:0.8;
		}
		#popup-button > a#btn-yes{
			border: 1px solid #1e76d2;
			background: #1e76d2;
		}
		#popup-button > a#btn-no{
			border: 1px solid red;
			background: red;
		}
		
    </style>
</body>
</html>