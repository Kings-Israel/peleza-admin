<?php require_once('../../Connections/connect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($connect,$theValue) : mysqli_escape_string($connect,$theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$errorcode='';

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "assignpackageclient")) {
  mysqli_select_db($connect,$database_connect);
  
  if(!empty($_POST['package_id'])){
// Loop to store and display values of individual checked checkbox.
foreach($_POST['package_id'] as $selected){
$togetpackagedetails=$selected;
/*$togetpackagedetails = $_POST['package_id'];*/
$togetclient = $_POST['client_id'];

mysqli_select_db($connect,$database_connect);
$query_getpackagedetails = "SELECT * FROM pel_package WHERE package_id='$togetpackagedetails'";
$getpackagedetails = mysqli_query_ported($query_getpackagedetails, $connect) or die(mysqli_error($connect));
$row_getpackagedetails = mysqli_fetch_assoc($getpackagedetails);
$totalRows_getpackagedetails = mysqli_num_rows($getpackagedetails);

$query_getclientdetails = "SELECT * FROM pel_client WHERE client_id='$togetclient'";
$getclientdetails = mysqli_query_ported($query_getclientdetails, $connect) or die(mysqli_error($connect));
$row_getclientdetails = mysqli_fetch_assoc($getclientdetails);
$totalRows_getclientdetails = mysqli_num_rows($getclientdetails);

$insertSQL = sprintf("INSERT INTO pel_client_package (package_name, package_id, status, added_by, added_date, client_id, client_name) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_getpackagedetails['package_name'], "text"),
                       GetSQLValueString($row_getpackagedetails['package_id'], "text"),
                       GetSQLValueString("22", "text"),
                       GetSQLValueString($_POST['added_by'], "text"),
                       GetSQLValueString($_POST['added_date'], "text"),
					   GetSQLValueString($row_getclientdetails['client_id'], "text"),
					   GetSQLValueString($row_getclientdetails['client_first_name'], "text"));
			
  mysqli_select_db($connect,$database_connect);
 $Result1 = mysqli_query_ported($insertSQL, $connect)or die(mysqli_error($connect));
}
  $client_id = $_POST['client_id'];
  $insertGoTo = "viewpackageclients.php?client_id=$client_id";

 /* if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}

else
{
$errorcode ='<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">
												<i class="ace-icon fa fa-times"></i>
											</button>

											<strong>
												<i class="ace-icon fa fa-times"></i>
												ERROR!!!!!
											</strong>

											Please Choose Atleast One Package.
											<br />
										</div>';

}
}
mysqli_select_db($connect,$database_connect);
$query_getallclients = "SELECT * FROM pel_client where status='11' ORDER BY client_first_name ASC";
$getallclients = mysqli_query_ported($query_getallclients, $connect) or die(mysqli_error($connect));
$row_getallclients = mysqli_fetch_assoc($getallclients);
$totalRows_getallclients = mysqli_num_rows($getallclients);

mysqli_select_db($connect,$database_connect);
$query_getpackages = "SELECT
pel_package.package_id,
pel_package.package_name,
pel_package.package_cost,
pel_package.package_status,
pel_package.package_added_by,
pel_package.package_added_date,
pel_package.package_data,
pel_package.package_verified_by,
pel_package.package_verified_date,
pel_package.dataset_id,
pel_package.package_currency,
pel_package.package_min,
pel_package.package_max,
pel_package.package_credits,
pel_dataset.dataset_name
FROM
pel_package
Inner Join pel_dataset ON pel_dataset.dataset_id = pel_package.dataset_id
where package_status='11'
ORDER BY package_name ASC
";
$getpackages = mysqli_query_ported($query_getpackages, $connect) or die(mysqli_error($connect));
$row_getpackages = mysqli_fetch_assoc($getpackages);
$totalRows_getpackages = mysqli_num_rows($getpackages);

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Assign  Package to Client - Peleza Admin</title>

		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="../../assets/css/bootstrap.css" />
		<link rel="stylesheet" href="../../assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
        <link rel="stylesheet" href="../../assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="../../assets/css/chosen.css" />
		<link rel="stylesheet" href="../../assets/css/datepicker.css" />
		<link rel="stylesheet" href="../../assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="../../assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="../../assets/css/bootstrap-datetimepicker.css" />
		<link rel="stylesheet" href="../../assets/css/colorpicker.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="../../assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="../../assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="../../assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
    </head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
<?php include('../header2.php');?>
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar                  responsive">
			  <script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>
              <?php include('../sidebarmenu2.php');?>
                
                
	<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
			  <script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
		  </div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
					  <script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Home</a>							</li>

							<li>
								<a href="#">Clients</a>							</li>
                                <li>
						  <a href="#">Packages</a>							</li>
							<li class="active">Assign Packages</li>
</ul><!-- /.breadcrumb -->

						<!-- #section:basics/content.searchbox -->
						<div class="nav-search" id="nav-search">
							<!-- <form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>								</span>
							</form> -->
						</div><!-- /.nav-search -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
				

<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<!--		
								<div class="hr hr-18 dotted hr-double"></div>

						<h4 class="pink">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
									<a href="#modal-table" role="button" class="green" data-toggle="modal"> Table Inside a Modal Box </a>								</h4>

								<div class="hr hr-18 dotted hr-double"></div>
-->
<div class="row">
									<div class="col-xs-12">
                                    <div  class="col-xs-6">
                                    
									  <h3 align="left" class="header smaller lighter blue">ASSIGN PACKAGES TO CLIENTS</h3>
                                      </div>
                                           
                                     
                                            <div  class="col-xs-6">    
                                        <h3 align="right" class="header smaller lighter blue">
									<i class="ace-icon fa fa-hand-o-right icon-animated-hand-pointer blue"></i>
								<a href="clientsusers.php">	
                                  <button class="btn btn-white btn-info btn-bold">
										  <i class="ace-icon bigger-120 green"></i>All CLients</button></a>&nbsp;</h3>
</div>
<div class="col-xs-12">

<?php
echo $errorcode;
?>

 
</div>
<div>
 
                                          	  <form method="POST" action="" class="form-horizontal" name="assignpackageclient" >
										
                                    <input type="hidden" id="status" name="status" value="22"/>
     <input type="hidden" id="added_by" name="added_by" value="<?php echo $_SESSION['MM_full_names']."(".$_SESSION['MM_USR_EMAIL'].")"; ?>"/>
           <input type="hidden" id="added_date" name="added_date" value="<?php echo date('d-m-Y H:m:s');?>"/>
															<div class="space-10"></div>

				
															<div class="form-group">
																<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Select Client</label>

															  <div class="col-sm-6"><span id="spryselect1">
															   	<select class="chosen-select form-control"  name="client_id" id="client_id" data-placeholder="Choose Package...">
															
														        <option value="000"></option>
														 <!--     <select name="package_id" id="package_id">
														        <option value="000">Select Institution</option>-->
														         <?php do { ?> 
                                                                 <option value="<?php echo $row_getallclients['client_id']; ?>"><?php echo $row_getallclients['client_first_name']; ?> <?php echo $row_getallclients['client_last_name']; ?> (<?php echo $row_getallclients['client_email_address']; ?> )</option> 
                                                                   <?php } while ($row_getallclients = mysqli_fetch_assoc($getallclients)); ?>
                                                              </select>
															 
														    <span class="selectInvalidMsg">*</span><span class="selectRequiredMsg">*</span></span></div>
								</div>
                                
                                <div class="space-4"></div>

														
<div class="clearfix">
											<div class="pull-right tableTools-container"></div>
									  </div>
										<div class="table-header">
									  Results for "Packages configured"										</div>

										<!-- div.table-responsive -->

										<!-- div.dataTables_borderWrap -->
										<div>
											<table id="dynamic-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<!--<label class="pos-rel">
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>															</label>-->				NO:                                                   	  </th>
												  		  <th>Package Name</th>
												                                                 	<th>Data Set Name</th>
                                                          
														<th class="hidden-480">Status</th>
                                                     
													</tr>
												</thead>

												<tbody>
                                                  <?php
												  
												  $x=1;												  
												  do { ?>
                                                  <tr>
                                                     	<td class="center">
														<label class="pos-rel">
																<input type="checkbox" class="ace" name="package_id[]" id="package_id[]" value="<?php echo $row_getpackages['package_id']; ?>">
																<span class="lbl"></span>												</label>														</td>
                                                      
                                               <td>
                                                        <a href="#"><?php echo $row_getpackages['package_name']; ?></a>														</td>
                                           <td><?php echo $row_getpackages['dataset_name']; ?></td>
                                                     
                                                  
                                                      <td class="hidden-480"><?php 
														
														if($row_getpackages['package_status']=='11')
														{
														?>
                                                        
                                                        <span class="label label-sm label-success">Active</span>	
                                                        <?php
														}
														if($row_getpackages['package_status']=='00')
														{
														?>
                                                        <span class="label label-sm label-danger">Deactivated</span>	
                                                         <?php
														}	
														if($row_getpackages['package_status']=='22')
														{
														?>
                                                        <span class="label label-sm label-warning">Unverified</span>	
                                                         <?php
														}	
														?>  </td>
                                                      
                                                  </tr>
                                                    <?php } while ($row_getpackages = mysqli_fetch_assoc($getpackages)); ?>
                                                    
                                                     
											  </tbody>
											</table>
                                              <div class="space-4"></div>
                                      
                                    <div class="space-4"></div>

                                <div class="clearfix form-actions">
													<div class="col-md-offset-3 col-md-9">
                                                    	<button onClick="submit" type="submit" value="submit" class="btn btn-info">
														<!--<button onClick="submit" class="btn btn-info" type="button">-->
															<i class="ace-icon fa fa-check bigger-110"></i>
															Save														</button>

											       
<button class="btn" type="reset">
															<i class="ace-icon fa fa-undo bigger-110"></i>
															Reset														</button>
													</div>
                                                </div>
							
										        <input type="hidden" name="MM_insert" value="assignpackageclient">
							  </form>  
                                            
										</div>
									</div>
							  </div>
                              	
</div><!-- /.col -->
					  </div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
</div><!-- /.main-content -->
</div>
			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Peleza</span>
							Admin &copy; 2018						</span>

&nbsp;&nbsp;											</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i></a>		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='../../assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../../assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="../../assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->
		<script src="../../assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="../../assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="../../assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="../../assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
        
        	<script src="../../assets/js/jquery-ui.custom.js"></script>
		<script src="../../assets/js/jquery.ui.touch-punch.js"></script>
		<script src="../../assets/js/chosen.jquery.js"></script>
		<script src="../../assets/js/fuelux/fuelux.spinner.js"></script>
		<script src="../../assets/js/date-time/bootstrap-datepicker.js"></script>
		<script src="../../assets/js/date-time/bootstrap-timepicker.js"></script>
		<script src="../../assets/js/date-time/moment.js"></script>
		<script src="../../assets/js/date-time/daterangepicker.js"></script>
		<script src="../../assets/js/date-time/bootstrap-datetimepicker.js"></script>
		<script src="../../assets/js/bootstrap-colorpicker.js"></script>
		<script src="../../assets/js/jquery.knob.js"></script>
		<script src="../../assets/js/jquery.autosize.js"></script>
		<script src="../../assets/js/jquery.inputlimiter.1.3.1.js"></script>
		<script src="../../assets/js/jquery.maskedinput.js"></script>
		<script src="../../assets/js/bootstrap-tag.js"></script>

		<!-- ace scripts -->
		<script src="../../assets/js/ace/elements.scroller.js"></script>
		<script src="../../assets/js/ace/elements.colorpicker.js"></script>
		<script src="../../assets/js/ace/elements.fileinput.js"></script>
		<script src="../../assets/js/ace/elements.typeahead.js"></script>
		<script src="../../assets/js/ace/elements.wysiwyg.js"></script>
		<script src="../../assets/js/ace/elements.spinner.js"></script>
		<script src="../../assets/js/ace/elements.treeview.js"></script>
		<script src="../../assets/js/ace/elements.wizard.js"></script>
		<script src="../../assets/js/ace/elements.aside.js"></script>
		<script src="../../assets/js/ace/ace.js"></script>
		<script src="../../assets/js/ace/ace.ajax-content.js"></script>
		<script src="../../assets/js/ace/ace.touch-drag.js"></script>
		<script src="../../assets/js/ace/ace.sidebar.js"></script>
		<script src="../../assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="../../assets/js/ace/ace.submenu-hover.js"></script>
		<script src="../../assets/js/ace/ace.widget-box.js"></script>
		<script src="../../assets/js/ace/ace.settings.js"></script>
		<script src="../../assets/js/ace/ace.settings-rtl.js"></script>
		<script src="../../assets/js/ace/ace.settings-skin.js"></script>
		<script src="../../assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="../../assets/js/ace/ace.searchbox-autocomplete.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
				//initiate dataTables plugin
				var oTable1 = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.dataTable( {
					bAutoWidth: false,
					"aoColumns": [
				null,null,null, 
					  { "bSortable": false }
					],
					"aaSorting": [],
			
					//,
					//"sScrollY": "200px",
					//"bPaginate": false,
			
					//"sScrollX": "100%",
					//"sScrollXInner": "120%",
					//"bScrollCollapse": true,
					//Note: if you are applying horizontal scrolling (sScrollX) on a ".table-bordered"
					//you may want to wrap the table inside a "div.dataTables_borderWrap" element
			
					//"iDisplayLength": 50
			    } );
				//oTable1.fnAdjustColumnSizing();
			
			
				//TableTools settings
				TableTools.classes.container = "btn-group btn-overlap";
				TableTools.classes.print = {
					"body": "DTTT_Print",
					"info": "tableTools-alert gritter-item-wrapper gritter-info gritter-center white",
					"message": "tableTools-print-navbar"
				}
			
				//initiate TableTools extension
				var tableTools_obj = new $.fn.dataTable.TableTools( oTable1, {
					"sSwfPath": "../../assets/js/dataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf", //in Ace demo ../assets will be replaced by correct assets path
					
					"sRowSelector": "td:not(:last-child)",
					"sRowSelect": "multi",
					"fnRowSelected": function(row) {
						//check checkbox when row is selected
						try { $(row).find('input[type=checkbox]').get(0).checked = true }
						catch(e) {}
					},
					"fnRowDeselected": function(row) {
						//uncheck checkbox
						try { $(row).find('input[type=checkbox]').get(0).checked = false }
						catch(e) {}
					},
			
					"sSelectedClass": "success",
			        "aButtons": [
						{
							"sExtends": "copy",
							"sToolTip": "Copy to clipboard",
							"sButtonClass": "btn btn-white btn-primary btn-bold",
							"sButtonText": "<i class='fa fa-copy bigger-110 pink'></i>",
							"fnComplete": function() {
								this.fnInfo( '<h3 class="no-margin-top smaller">Table copied</h3>\
									<p>Copied '+(oTable1.fnSettings().fnRecordsTotal())+' row(s) to the clipboard.</p>',
									1500
								);
							}
						},
						
						{
							"sExtends": "csv",
							"sToolTip": "Export to CSV",
							"sButtonClass": "btn btn-white btn-primary  btn-bold",
							"sButtonText": "<i class='fa fa-file-excel-o bigger-110 green'></i>"
						},
						
						{
							"sExtends": "pdf",
							"sToolTip": "Export to PDF",
							"sButtonClass": "btn btn-white btn-primary  btn-bold",
							"sButtonText": "<i class='fa fa-file-pdf-o bigger-110 red'></i>"
						},
						
						{
							"sExtends": "print",
							"sToolTip": "Print view",
							"sButtonClass": "btn btn-white btn-primary  btn-bold",
							"sButtonText": "<i class='fa fa-print bigger-110 grey'></i>",
							
							"sMessage": "<div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Optional Navbar &amp; Text</small></a></div></div>",
							
							"sInfo": "<h3 class='no-margin-top'>Print view</h3>\
									  <p>Please use your browser's print function to\
									  print this table.\
									  <br />Press <b>escape</b> when finished.</p>",
						}
			        ]
			    } );
				//we put a container before our table and append TableTools element to it
			    $(tableTools_obj.fnContainer()).appendTo($('.tableTools-container'));
				
				//also add tooltips to table tools buttons
				//addding tooltips directly to "A" buttons results in buttons disappearing (weired! don't know why!)
				//so we add tooltips to the "DIV" child after it becomes inserted
				//flash objects inside table tools buttons are inserted with some delay (100ms) (for some reason)
				setTimeout(function() {
					$(tableTools_obj.fnContainer()).find('a.DTTT_button').each(function() {
						var div = $(this).find('> div');
						if(div.length > 0) div.tooltip({container: 'body'});
						else $(this).tooltip({container: 'body'});
					});
				}, 200);
				
				
				
				//ColVis extension
				var colvis = new $.fn.dataTable.ColVis( oTable1, {
					"buttonText": "<i class='fa fa-search'></i>",
					"aiExclude": [0, 6],
					"bShowAll": true,
					//"bRestore": true,
					"sAlign": "right",
					"fnLabel": function(i, title, th) {
						return $(th).text();//remove icons, etc
					}
					
				}); 
				
				//style it
				$(colvis.button()).addClass('btn-group').find('button').addClass('btn btn-white btn-info btn-bold')
				
				//and append it to our table tools btn-group, also add tooltip
				$(colvis.button())
				.prependTo('.tableTools-container .btn-group')
				.attr('title', 'Show/hide columns').tooltip({container: 'body'});
				
				//and make the list, buttons and checkboxed Ace-like
				$(colvis.dom.collection)
				.addClass('dropdown-menu dropdown-light dropdown-caret dropdown-caret-right')
				.find('li').wrapInner('<a href="javascript:void(0)" />') //'A' tag is required for better styling
				.find('input[type=checkbox]').addClass('ace').next().addClass('lbl padding-8');
			
			
				
				/////////////////////////////////
				//table checkboxes
				$('th input[type=checkbox], td input[type=checkbox]').prop('checked', false);
				
				//select/deselect all rows according to table header checkbox
				$('#dynamic-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) tableTools_obj.fnSelect(row);
						else tableTools_obj.fnDeselect(row);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#dynamic-table').on('click', 'td input[type=checkbox]' , function(){
					var row = $(this).closest('tr').get(0);
					if(!this.checked) tableTools_obj.fnSelect(row);
					else tableTools_obj.fnDeselect($(this).closest('tr').get(0));
				});
				
			
				
				
					$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();

					e.stopPropagation();
					e.preventDefault();
				});
				
				
				//And for the first simple table, which doesn't have TableTools or dataTables
				//select/deselect all rows according to table header checkbox
				var active_class = 'active';
				$('#simple-table > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
					var th_checked = this.checked;//checkbox inside "TH" table header
					
					$(this).closest('table').find('tbody > tr').each(function(){
						var row = this;
						if(th_checked) $(row).addClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', true);
						else $(row).removeClass(active_class).find('input[type=checkbox]').eq(0).prop('checked', false);
					});
				});
				
				//select/deselect a row when the checkbox is checked/unchecked
				$('#simple-table').on('click', 'td input[type=checkbox]' , function(){
					var $row = $(this).closest('tr');
					if(this.checked) $row.addClass(active_class);
					else $row.removeClass(active_class);
				});
			
				
			$('#inst_logo , #id-input-file-2').ace_file_input({
					no_file:'No File ...',
					btn_choose:'Choose',
					btn_change:'Change',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					//whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});
				/********************************/
				//add tooltip for small view action buttons in dropdown menu
				$('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				
				//tooltip placement on right or left
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('table')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			})
		</script>

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="../../assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="../../docs/assets/js/themes/sunburst.css" />

		<script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="../../assets/js/ace/elements.onpage-help.js"></script>
		<script src="../../assets/js/ace/ace.onpage-help.js"></script>
		<script src="../../docs/assets/js/rainbow.js"></script>
		<script src="../../docs/assets/js/language/generic.js"></script>
		<script src="../../docs/assets/js/language/html.js"></script>
		<script src="../../docs/assets/js/language/css.js"></script>
		<script src="../../docs/assets/js/language/javascript.js"></script>
 	   
	    <script type="text/javascript">
<!--

var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"000", validateOn:["change"]});


//-->
</script>
</body>
</html>
<?php


mysqli_free_result($getpackages);


?>