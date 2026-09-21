<?php include('header.php'); ?>
<?php //include('session.php'); ?>
    <body>
		<?php //include('navbar.php'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
				<?php include('sidebar.php'); ?>
		
						<div class="span9" id="content">
		                    <div class="row-fluid">
							
		                        <!-- block -->
		                        <div class="block">
		                            <div class="navbar navbar-inner block-header">
		                                <div class="muted pull-left">Add New Application</div>                       
		                            </div>
									
		                <div class="block-content collapse in">	
                         <div> <i class="icon-info-sign"></i> Please Fill in required details</div>						
							<form class="form-horizontal" method="post">
                                <div class="alert alert-success">Applicant Detail / Maklumat Peminjam</div>
								
								<div class="control-group">
								<label class="control-label" for="pemohon_name">Name </label>
								<div class="controls">
								    <input type="text" class="span8" name="pemohon_name" id="pemohon_name" placeholder="Applicant Name" required>
								</div>
								</div>
								<table>
                                    <tr><td>  
                                        <div class="control-group">
                                        <label class="control-label" for="pemohon_college_id">No. Staff or Student ID</label>
                                        <div class="controls">
                                            <input type="text" class="span12" name="pemohon_college_id" id="pemohon_college_id" placeholder="Applicant No. ID" required>
                                        </div>
                                        </div></td>
                                    <td>                        
                                        <div class="control-group">
                                        <label class="control-label" for="pemohon_no_tel">Contact No.</label>
                                        <div class="controls">
                                            <input type="text" class="span12" name="pemohon_no_tel" id="pemohon_no_tel" placeholder="Applicant Contact No." required>
                                        </div>
                                        </div></td>
                                    </tr>
                                </table>
								<div class="control-group">
								<label class="control-label" for="pemohon_status">Position</label>
								<div class="controls">
								    <input type="text" class="span8" name="pemohon_status" id="pemohon_status" placeholder="Applicant Position" required>
								</div>
                                </div>

                                <div class="control-group">
								<label class="control-label" for="pemohon_dept">Unit / Department</label>
								<div class="controls">
								    <input type="text" class="span8" name="pemohon_dept" id="pemohon_dept" placeholder="Applicant Unit / Department" required>
								</div>
                                </div>

                             <div class="alert alert-success">Borrowing Detail / Maklumat Peminjaman</div>

                                <div class="control-group">
								<label class="control-label" for="type">Booking Type</label>
								<div class="controls">
                                <select id="type" name="type" required>
                                    <option value="">--Select an option--</option>
                                    <option value="Dalaman">Dalaman</option>
                                    <option value="Luaran">Luaran</option>
                                </select>   
								</div>
                                </div>  
                                
                                
								<div class="control-group">
								<label class="control-label" for="borrow_item">Item(s) Name or Model </label>
								<div class="controls">
								    <textarea type="text" class="span8" rows="2" name="borrow_item" id="borrow_item" placeholder="Item(s) require and no. of item" required></textarea>
								</div>
								</div>                                
                                
								<div class="control-group">
								<label class="control-label" for="purpose">Booking Purpose </label>
								<div class="controls">
								    <input type="text" class="span8" name="purpose" id="purpose" placeholder="Purpose of booking" required>
								</div>
								</div>
								
                                <table>
                                    <tr><td>
                                        <div class="control-group">
                                        <label class="control-label" for="start_date">Booking Start Date</label>
                                        <div class="controls">
                                            <input type="datetime-local" class="span12" id="start_date" name="start_date" required>
                                        </div>
                                        </div></td>

                                        <td>
                                        <div class="control-group">
                                        <label class="control-label" for="end_date">Booking End Date</label>
                                        <div class="controls">
                                            <input type="datetime-local" class="span12" id="end_date" name="end_date" required>
                                        </div>
                                        </div></td>
                                        </tr>
                                </table>
                                
								<div class="control-group">
								<label class="control-label" for="location">Location</label>
								<div class="controls">
								    <input type="text" class="span8" name="location" id="location" placeholder="Location or address of use" required>
								</div>
                                </div>

                                <div class="control-group">
								<label class="control-label" for="technician">Need Technician</label>
								<div class="controls">
                                <select id="technician" name="technician" required>
                                    <option value="">--Select an option--</option>
                                    <option value="Ya">Yes / Ya</option>
                                    <option value="Tidak">No / Tidak</option>
                                </select>   
								</div>
                                </div>                                
                                	
										<div class="control-group">
										<div class="controls">
										<button name="save" id="save" name="singlebutton" data-placement="right" title="Click here to Save your new data." class="btn btn-primary"><i class="icon-save"></i> Save</button>				
										</div>
										</div>
										<script type="text/javascript">
										$(document).ready(function(){
										$('#save').tooltip('show');
										$('#save').tooltip('hide');
										});
										</script>
							</form>
<?php
if (isset($_POST['save'])){
//$item_id = $_POST['item_id'];
$pemohon_name = $_POST['pemohon_name'];
$pemohon_college_id = $_POST['pemohon_college_id'];
$pemohon_no_tel = $_POST['pemohon_no_tel'];
$pemohon_status = $_POST['pemohon_status'];
$pemohon_dept = $_POST['pemohon_dept'];	
    
$borrow_item = $_POST['borrow_item'];
$borrow_start_date = $_POST['start_date'];
$borrow_end_date = $_POST['end_date'];
$borrow_status = $_POST['status'];
$borrow_type = $_POST['type'];	
$borrow_purpose = $_POST['purpose'];
$borrow_location = $_POST['location'];	
$borrow_technician = $_POST['technician'];
$request_date = date('d-m-Y H:i:s');
						
$query = mysqli_query($conn,"select * from pemohon where pemohon_college_id = '$pemohon_college_id' ")or die(mysqli_error($conn));
$count = mysqli_num_rows($query);

if ($count > 0){

    mysqli_query($conn,"insert into borrowing (pemohon_college_id, borrow_item, request_date,start_date,end_date,status, type, purpose, location, need_technician) values('$pemohon_college_id','$borrow_item','$request_date','$borrow_start_date','$borrow_end_date','$borrow_status','$borrow_type','$borrow_purpose','$borrow_location','$borrow_technician')")or die(mysqli_error($conn));
                            
}else{
    mysqli_query($conn,"insert into pemohon (pemohon_name,pemohon_college_id,pemohon_no_tel,pemohon_status,pemohon_dept) values('$pemohon_name','$pemohon_college_id','$pemohon_no_tel','$pemohon_status','$pemohon_dept')")or die(mysqli_error($conn));

    mysqli_query($conn,"insert into borrowing (pemohon_college_id, borrow_item, request_date,start_date,end_date,status, type, purpose, location, need_technician) values('$pemohon_college_id','$borrow_item','$request_date','$borrow_start_date','$borrow_end_date','$borrow_status','$borrow_type','$borrow_purpose','$borrow_location','$borrow_technician')")or die(mysqli_error($conn));		
?>
                            
<script>
window.location = "borrower_list.php";
$.jGrowl("Application Successfully added", { header: 'New application add' });
</script>
<?php
}
}
?>
																										
		                            </div>
		                        </div>
		                        <!-- /block -->
		                    </div>
		                </div>
            </div>
		<?php include('footer.php'); ?>
        </div>
		<?php include('script.php'); ?>
    </body>