<?php   
$page = 'statusPatternManagement'; 
include('header.php');
$ispName = isset($_GET['ispname'])? $_GET['ispname'] : '';
$relatedtitleID = isset($_GET['relatedtitleid'])? $_GET['relatedtitleid'] : '';
$geoipregion = isset($_GET['geoipregion'])? $_GET['geoipregion'] : '';
//$data = $notices->getCANoticeSentInfo($ispName,$relatedtitleID);

?>
<script type="text/javascript">
	$("#dlg").dialog({
		  autoOpen: false
	});

	function openUpdateStatus(sel,nid)
	{
			row = $('#dlg').datagrid('getSelected');

			rowindex = $('#dlg').datagrid('getRowIndex', row);
			selre = sel;
			
			$('#updatestatusid').val(nid);
			$('#updatestatusgeoipisp').val( row.statuspatterngeoipisp);
			$('#updatestatuspattern').val(  row.statuspattern);
			$('#updatestatus').val(  row.statuspatternstatus);
			$('#dlg').dialog('open').dialog('setTitle','Update Status Pattern');
	}
</script>

    <br>
    <div id="dg-wrapper" style="height:570px;"></div>
    <!--Pop-up Window for Update and Delete-->
    <!--
     <div style="margin:20px 0;">
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#dlg').dialog('open')">Open</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="$('#dlg').dialog('close')">Close</a>
    </div>
	<div id="dlg3" class="easyui-dialog" title="Update Status Pattern for " style="width:400px;height:200px;padding:10px;"
            data-options="
                iconCls: 'icon-save',
                toolbar: '#dlg-toolbar',
                buttons: '#dlg-buttons'
            ">
        <div>
        	<input id="updatestatusid" type="text" name="geoipisp" value="Geo IP Isp" disabled="disabled" style="width:100%;" /><br />
        	<textarea id="updatestatuspattern" name="pattern" style="width:100%;">This is the Pattern Here</textarea>
        </div>
    </div> -->
    <!--
    <div id="dlg-toolbar" style="padding:2px 0">
        <table cellpadding="0" cellspacing="0" style="width:100%">
            <tr>
                <td style="padding-left:2px">
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true">Edit</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-help',plain:true">Help</a>
                </td>
                <td style="text-align:right;padding-right:2px">
                    <input class="easyui-searchbox" data-options="prompt:'Please input somthing'" style="width:150px"></input>
                </td>
            </tr>
        </table>
    </div> -->
 
	<!--End of Pop-up window for Update and Delete-->
	<script>

		var ureSubmit=false,selre,row,rowindex,filter1=false,filter2=false,filter3=false,filter4=false,filter5=false,filter6=false;
		
		loadgrid();
		
		function loadgrid(){
			$.post('notice-sent-info.php',{action:'get status patttern info', ispName: '<?php echo $ispName; ?>', relatedtitleID: '<?php echo $relatedtitleID; ?>', geoipregion: '<?php echo $geoipregion; ?>'}, function(_res){
				$('#dg-wrapper').html(_res);
				initdg();
				ureSubmit = false;
			});
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
