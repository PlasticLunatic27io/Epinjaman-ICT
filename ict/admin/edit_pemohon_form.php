   <div class="row-fluid">
   <a href="pemohon.php" class="btn btn-info" id="add" data-placement="right" title="Click to Add Staff/Student" ><i class="icon-plus-sign icon-large"></i> Add Staff/Student</a>
   <script type="text/javascript">
	$(document).ready(function(){
	$('#add').tooltip('show');
	$('#add').tooltip('hide');
	});
	</script>
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><i class="icon-pencil icon-large"></i> Edit Staff/Student</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
								<?php
								$query = mysqli_query($conn,"select * from pemohon where pemohon_id = '$get_id'")or die(mysqli_error());
								$row = mysqli_fetch_array($query);
								?>
								<form method="post">
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo $row['pemohon_name']; ?>" name="name" id="focusedInput" type="text" placeholder = "Fullname" required>
                                          </div>
                                        </div>

										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo $row['pemohon_college_id']; ?>" name="collegeid" id="focusedInput" type="text" placeholder = "Fullname" required>
                                          </div>
                                        </div>
										
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo $row['pemohon_position']; ?>" name="position" id="focusedInput" type="text" placeholder = "Fullname" required>
                                          </div>
                                        </div>
										
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo $row['pemohon_no_tel']; ?>" name="no_tel" id="focusedInput" type="text" placeholder = "Fullname" required>
                                          </div>
                                        </div>
										
										<div class="control-group">
                                          <div class="controls">
                                            <input class="input focused" value="<?php echo $row['pemohon_status']; ?>" name="status" id="focusedInput" type="text" placeholder = "Fullname" required>
                                          </div>
                                        </div>
																									

										<div class="control-group">
                                          <div class="controls">
												<button name="update" class="btn btn-success" id="update" data-placement="right" title="Click to Update"><i class="icon-save icon-large"></i> Update</button>
												<script type="text/javascript">
	                                            $(document).ready(function(){
	                                            $('#update').tooltip('show');
	                                            $('#update').tooltip('hide');
	                                            });
	                                            </script>

                                          </div>
                                        </div>
                                </form>
								</div>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
			<?php		
if (isset($_POST['update'])){

$name = $_POST['name'];
$collegeid= $_POST['collegeid'];
$position= $_POST['position'];
$no_tel = $_POST['no_tel'];
$status = $_POST['status'];


mysqli_query($conn,"update pemohon set pemohon_name='$name' , pemohon_college_id='$collegeid' , pemohon_position='$position' , pemohon_no_tel='$no_tel' , pemohon_status = '$status' where pemohon_id = '$get_id' ")or die(mysqli_error());
//mysqli_query($conn,"insert into activity_log (date,username,action) values(NOW(),'$admin_username','Edit system User $firstname $lastname')")or die(mysqli_error());	
?>
<script>
  window.location = "pemohon.php"; 
  $.jGrowl("Staff/Student Successfully Update", { header: 'Staff/Student Update' });
</script>
<?php
}
?>