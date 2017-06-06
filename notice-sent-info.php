<?php   
$page = 'noticeinfos2'; 
include('header.php');
$ispName = isset($_GET['ispname'])? $_GET['ispname'] : '';
$relatedtitleID = isset($_GET['relatedtitleid'])? $_GET['relatedtitleid'] : '';
$geoipregion = isset($_GET['geoipregion'])? $_GET['geoipregion'] : '';

//$data = $notices->getCANoticeSentInfo($ispName,$relatedtitleID);

?>
    <br>
    <div id="dg-wrapper" style="height:570px;"></div>
	<div style="text-align:right">
		<form id="frm-dl" style="display:none;" method="POST" action="notice-sent-info.php?ispname=<?php echo $ispName; ?>&relatedtitleid=<?php echo $relatedtitleID; ?>">
			<input type="hidden" name="ispname" value="<?php echo $ispName; ?>" />
			<input type="hidden" name="relatedtitleid" value="<?php echo $relatedtitleID; ?>" />
			<input type="hidden" name="country" value="CA" />
			<input type="hidden" name="action" value="downloadallemail" />
			<input type="hiddeN" name="filter" id="frm-dl-filter" value="" />
			<input type="submit" class="l-btn l-btn-small l-btn-plain" style="text-decoration: none;padding: 6px;margin-top: 4px;border-width: 1px;" value="Download Selected Emails" />
		</form>
	</div>
	<div id="responseemail-dlg" class="easyui-dialog" style="padding:10px 20px;display:none;width: 400px;"   closed="true" buttons="#dlg-buttons">
		<div style="color: #005eff;margin: 6px 5px;text-align: center;">
			Please identify the necessary parts of the message to identify a positive or negative ISP feedback by removing the rest.
			e.g. Keep 'could not be forwarded' and select "Not successful"
		</div>
		<form id="frm-re" method="post" style="padding:10px;" novalidate>
			<input type="hidden" name="action" value="update response email"/>
			<input type="hidden" id="noticeid" name="noticeid" value=""/>
			<input type="hidden" id="ispname" name="ispname" value="<?php echo $ispName; ?>"/>
            <div class="fitem">
                <textarea id="responseemail" name="responseemail" style="margin: 0px; width: 340px; height: 145px;"></textarea>
            </div>
			<div class="fitem">
				<label for="responsestatus">Status</label>
				<select id="responsestatus" name="responsestatus">
					<option value="Successful">Successful</option>
					<option value="Not Successful">Not Successful</option>
				</select>
			</div>
        </form>

        <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="updateResponseEmail(this)" style="width:90px"><i class="glyphicon glyphicon-floppy-save"></i> Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:closeResponseEmail()" style="width:90px"><i class="glyphicon glyphicon-log-out"></i> Close</a>
        </div>    
    </div>
	<script>
		var ureSubmit=false,selre,row,rowindex,filter1=false,filter2=false,filter3=false,filter4=false,filter5=false,filter6=false;
		
		loadgrid();
		
		function loadgrid(){
			$.post('notice-sent-info.php',{action:'get ca notice sent info', ispName: '<?php echo $ispName; ?>', relatedtitleID: '<?php echo $relatedtitleID; ?>', geoipregion: '<?php echo $geoipregion; ?>'}, function(_res){
				$('#dg-wrapper').html(_res);
				initdg();
				ureSubmit = false;
			});
		}
		
		function showresponseemaildialog(el,nid,sel){
			row = $('#dg').datagrid('getSelected');

			rowindex = $('#dg').datagrid('getRowIndex', row);
			selre = sel;
			
			$('#frm-re').find('.msg').remove();
			$('#noticeid').val(nid);
			$('#responsestatus').val( (sel==1? row.iResponsStatus : row.iResponsStatus2) );
			$('#responseemail').val( (sel==1? row.CF1data : row.CF2data) );
			$('#responseemail-dlg').dialog('open').dialog('setTitle','Update Response Email');
		}
		
		function updateResponseEmail(el){
			if(ureSubmit){
				alert( 'Can not save data process still ongoing.' );
				return false;
			}
			ureSubmit = true;
			$('#frm-re').find('.msg').remove();
			$.post('notice-sent-info.php', $('#frm-re').serialize(), function(res){
				$('#frm-re').prepend(res);
				if(selre==1){
					row.CF1data = $('#responseemail').val();
					row.iResponsStatus = $('#responsestatus');
					row.CF = $('#responsestatus');
				}
				else{
					row.CF2data = $('#responseemail').val();
					row.iResponsStatus2 = $('#responsestatus');
					row.CF2 = $('#responsestatus');
				}
				
				$('#dg').datagrid('updateRow',{index:rowindex,row:row});
				closeResponseEmail();
				if(_res.indexOf('rel="patternset"')){
					setTimeout(function(){
						window.location = window.location.href;
					});
				}
				else{
					loadgrid();
				}
			});
		}
		
		function closeResponseEmail(){
			$('#frm-re')[0].reset();
			$('#responseemail-dlg').dialog('close');
		}
		
		$.extend($.fn.datagrid.defaults.filters,{
			checkbox:{
				init: function(container, options){
					var span = $('<div style="padding:4px;"><span class="tree-checkbox tree-checkbox0"></span> '+ options.label+'</div>').appendTo(container);
					span.children('span.tree-checkbox').bind('click',function(){
						if ($(this).hasClass('tree-checkbox0')){
							$(this).removeClass('tree-checkbox0').addClass('tree-checkbox1');
							this.v = 'checked';
						} else {
							$(this).removeClass('tree-checkbox1').addClass('tree-checkbox0');
							this.v = 'unchecked';
						}
						if (options.onChange){
							options.onChange.call(this, this.v);
						}
					})
					return span.children('span.tree-checkbox');
				},
				getValue: function(target){
					return target.v;
				},
				setValue: function(target, value){
					$(target).removeClass('tree-checkbox0 tree-checkbox1 tree-checkbox2');
					if (value == 'checked'){
						$(target).addClass('tree-checkbox1');
					} else if (value == 'indeterminate'){
						$(target).addClass('tree-checkbox2');
					} else {
						$(target).addClass('tree-checkbox0');
					}
				},
				resize: function(target, width){

				}
			}
		});
		
		function initdg(){
			var dg = $('#dg').datagrid({
						filterBtnIconCls:'icon-filter',
						height: '570px'
					})
					.datagrid('enableFilter', [
					{
						field:'FirstNotice',
						type:'checkbox',
						options:{
							label: 'only show available notices', 
							onChange:function(value){
								if(value == 'checked'){
									filter1 = true;
									dg.datagrid('addFilterRule', {
										field: 'FirstNoticeFileStatus',
										op: 'equal',
										value: 1
									});
								}
								else{
									filter1 = false;
									dg.datagrid('removeFilterRule', 'FirstNoticeFileStatus');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					},
					{
						field:'SecondNotice',
						type:'checkbox',
						options:{
							label: 'only show available notices', 
							onChange:function(value){
								if(value == 'checked'){
									filter2 = true;
									dg.datagrid('addFilterRule', {
										field: 'SecondNoticeFileStatus',
										op: 'equal',
										value: 1
									});
								}
								else{	
									filter2 = false;
									dg.datagrid('removeFilterRule', 'SecondNoticeFileStatus');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					},
					{
						field:'RE',
						type:'checkbox',
						options:{
							label: 'only show witch response', 
							onChange:function(value){
								if(value == 'checked'){
									filter3 = true;
									dg.datagrid('addFilterRule', {
										field: 'REStatus',
										op: 'equal',
										value: 1
									});
								}
								else{	
									filter3 = false;
									dg.datagrid('removeFilterRule', 'REStatus');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					},
					{
						field:'RE2',
						type:'checkbox',
						options:{
							label: 'only show witch response', 
							onChange:function(value){
								if(value == 'checked'){
									filter4 = true;
									dg.datagrid('addFilterRule', {
										field: 'RE2Status',
										op: 'equal',
										value: 1
									});
								}
								else{	
									filter4 = false;
									dg.datagrid('removeFilterRule', 'RE2Status');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					},
					{
						field:'CF',
						type:'checkbox',
						options:{
							label: 'only show confirmed', 
							onChange:function(value){
								if(value == 'checked'){
									filter5 = true;
									dg.datagrid('addFilterRule', {
										field: 'REStatus',
										op: 'equal',
										value: 1
									});
								}
								else{	
									filter5 = false;
									dg.datagrid('removeFilterRule', 'REStatus');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					},
					{
						field:'CF2',
						type:'checkbox',
						options:{
							label: 'only show confirmed', 
							onChange:function(value){
								if(value == 'checked'){
									filter6 = true;
									dg.datagrid('addFilterRule', {
										field: 'RE2Status',
										op: 'equal',
										value: 1
									});
								}
								else{	
									filter6 = false;
									dg.datagrid('removeFilterRule', 'RE2Status');
								}
								dg.datagrid('doFilter');
								checkdisplayDLBtn();
							}
						}
					}])
					.datagrid('destroyFilter', 'FirstNoticeID')
					.datagrid('destroyFilter', 'SecondNoticeID');
		}
		
		function checkdisplayDLBtn(){
			if(!filter1 && !filter2 && !filter3 && !filter4){
				$('#frm-dl').hide();
			}
			else{
				$('#frm-dl').show();
				if(filter1 && filter2) $('#frm-dl-filter').val(3);
				else if(filter1) $('#frm-dl-filter').val(1);
				else if(filter2) $('#frm-dl-filter').val(2);
				else if(filter3 && filter4) $('#frm-dl-filter').val(35);
				else if(filter3) $('#frm-dl-filter').val(15);
				else if(filter4) $('#frm-dl-filter').val(25);
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