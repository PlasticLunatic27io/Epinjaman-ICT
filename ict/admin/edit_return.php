<?php include('header.php'); ?>
<?php include('session.php'); ?>
<?php $get_id = $_GET['id']; ?>
    <body>
		<?php include('navbar.php'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
				<?php //include('sidebar.php'); ?>
		
						<div class="span9" id="content">
		                    <div class="row-fluid">
		                        <div id="" class="block">
		                            <div class="navbar navbar-inner block-header">
		                                <div class="muted pull-left">Release Return Item</div>
										<div class="muted pull-right"><a id="return" data-placement="left" title="Click to Return" href="item.php"><i class="icon-arrow-left icon-large"></i> Back</a></div>
																<script type="text/javascript">
																$(document).ready(function(){
																	$('#return').tooltip('show');
																	$('#return').tooltip('hide');
																});
																</script>     
		                            </div>
		                            <div class="block-content collapse in">									
									
									<?php
									$query = mysqli_query($conn,"select * from item where item_tag = '$get_id'")or die(mysqli_error());
									$row = mysqli_fetch_array($query);
									?>
									
									    <form class="form-horizontal" method="post">
										
										<div class="control-group">
											<label class="control-label" for="inputPassword">Item Name</label>
											<div class="controls">
											<input type="text" class="span8" value="<?php echo $row['item_name']; ?>" name="item_name" id="inputPassword" placeholder="Item Name" required>
											</div>
										</div>

										<div class="control-group">
										<label class="control-label" for="inputPassword">Item Tag No</label>
											<div class="controls">
											<input type="text" class="span8" value="<?php echo $row['item_tag']; ?>" name="item_tag" id="inputPassword" placeholder="Item Tag No" required>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="inputPassword">Item_Category</label>
											<div class="controls">
											<input type="text" class="span8" value="<?php echo $row['item_category']; ?>" name="item_category" id="inputPassword" placeholder="Item Category" required>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="inputPassword">Item_Quantity</label>
											<div class="controls">
											<input type="text" class="span8" value="<?php echo $row['item_quantity']; ?>" name="item_quantity" id="inputPassword" placeholder="Item Qunatity" required>
											</div>
										</div>
										
										<div id="hide">
										<div class="control-group">
											<label class="control-label" for="inputPassword"  placeholder="Item Status" >Item Status</label>
											<div class="controls">
											<select value="" name="item_status" required>
													<option><?php echo $row['item_status']; ?></option>													
												</select>								
											</div>
										</div>
									   </div>
										
										<div class="control-group">
											<label class="control-label" for="inputPassword">Item Description</label>
											<div class="controls">
													<textarea name="item_description" id="ckeditor_full">
													<?php echo $row['item_description']; ?>
													</textarea>
											</div>
										</div>
										
										<div class="control-group">
										<div class="controls">
										
										<button id="update" data-placement="right" title="Click to update" name="update" type="submit" class="btn btn-info"><i class="icon-save icon-large"></i> Update</button>
										</div>
										</div>
										<script type="text/javascript">
										$(document).ready(function(){
										$('#update').tooltip('show');
										$('#update').tooltip('hide');
										});
										</script> 
         
                                            
 
                                            
                                            
                                            
                                            
										</form>
										
										<?php
										if (isset($_POST['update'])){
                                        $item_name = $_POST['item_name'];
                                        $item_tag = $_POST['item_tag'];
                                        $item_category = $_POST['item_category'];
                                        $item_qty = $_POST['item_quantity'];
										$item_status = $_POST['item_status'];
                                        $item_description = $_POST['item_description'];
									
										mysqli_query($conn,"update item set    item_name = '$item_name',
																		item_tag  = '$item_tag',
																		item_category = '$item_category',
																		item_quantity = '$item_qty',
																		item_status = '$item_status',
																		item_description = '$item_description'
																		where item_id = '$get_id' ")or die(mysqli_error());
																										
									    //mysqli_query($conn,"insert into activity_log (date,username,action) values(NOW(),'$admin_username','Edit item info $item_name $item_serial')")or die(mysqli_error());
										?>
										<script>										
										window.location = "item.php";
										$.jGrowl("Item Successfully Update", { header: 'Item update' });
										</script>
										<?php
										}
										
										
										?>
									
								
		                            </div>
		                        </div>
		                        <!-- /block -->
		                    </div>
		                </div>
            </div>
<script>
	/*jQuery(document).ready(function(){
		jQuery("#opt11").hide();
		jQuery("#opt12").hide();
		jQuery("#opt13").hide();		

		jQuery("#qtype").change(function(){	
			var x = jQuery(this).val();			
			if(x == '1') {
				jQuery("#opt11").show();
				jQuery("#opt12").hide();
				jQuery("#opt13").hide();
			} else if(x == '2') {
				jQuery("#opt11").hide();
				jQuery("#opt12").show();
				jQuery("#opt13").hide();
			}
		});
		
	});*/
</script>
		<?php include('footer.php'); ?>
        </div>
		<?php include('script.php'); ?>
    </body>

</html>