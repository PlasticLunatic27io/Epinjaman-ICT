<?php include('header.php'); ?>
<?php include('session.php'); ?>
    <body>
		<?php include('navbar.php'); ?>
        <?php //$get_id = $_GET['id']; ?>
        <?php $session_pemohonid = $_SESSION['pemohonid']; ?>
        
		<div class="container-fluid">
            <div class="row-fluid">
				<?php include('sidebar.php'); ?>
				 
				  <div class="span9" id="content">
                     <div class="row-fluid">
                        <div class="block">
						    <div class="block-content collapse in">
							
								<?php
								   $query = mysqli_query($conn,"select * from borrowing where pemohon_college_id = '$session_pemohonid'")or die(mysqli_error($conn));
								   $row = mysqli_fetch_array($query);
								?>	
								<form method="">
									<table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
										<thead>		
												<tr>
													<th>APPLICANT DETAILS / MAKLUMAT PEMINJAM</th>
													<th>APPLICANTION DETAILS / MAKLUMAT TEMPAHAN</th>			
												</tr>
										</thead>
										<tbody>
										<tr><td>
					
										<div class="">
											<?php
											$id= $row['pemohon_college_id'];
											$query1 = mysqli_query($conn, "SELECT * FROM pemohon WHERE pemohon_college_id = '$id'") or die(mysqli_error($conn));
											$row1 = mysqli_fetch_assoc($query1); ?>
											
											<label class="control-label" for="inputPassword">Name :&nbsp;&nbsp; <b>  <?php echo $row1['pemohon_name'];?> </b></label>	
											<label class="control-label" for="inputPassword">Contact No :&nbsp;&nbsp; <b> <?php echo $row1['pemohon_no_tel'];?>	</b></label>	
											<label class="control-label" for="inputPassword">Position :&nbsp;&nbsp; <b> <?php echo $row1['pemohon_position'];?>	</b></label>	
											<label class="control-label" for="inputPassword">Unit / Department :&nbsp;&nbsp; <b> <?php echo $row1['pemohon_dept'];?> </b></label>		
										</div>
										</td>
										<td>
							
										<div class="control-group">
											<?php
											$id= $row['pemohon_college_id'];
											$query2 = mysqli_query($conn, "SELECT * FROM borrowing WHERE pemohon_college_id = '$id'") or die(mysqli_error($conn));
											$row2 = mysqli_fetch_assoc($query2); ?>
											
											<label class="control-label" for="inputPassword">Purpose :&nbsp;&nbsp; <b>  <?php echo $row2['purpose'];?>	</b></label>	
											<label class="control-label" for="inputPassword">Date :&nbsp;&nbsp; <b> <?php echo $row2['start_date'];?>	hingga <?php echo $row2['end_date'];?> </b></label>	
											<label class="control-label" for="inputPassword">Location :&nbsp;&nbsp; <b> <?php echo $row2['location'];?>	</b></label>	
											<label class="control-label" for="inputPassword">Need Technician? :&nbsp;&nbsp; <b> <?php echo $row2['need_technician'];?>	</b></label>		
										</div>										
										</td></tr>
										</tbody>
									</table>
								
										<div class="control-group">
										    <label class="control-label" for="inputPassword" style="color: red;">Request Item(s) :&nbsp;&nbsp; <b> <?php echo $row2['borrow_item'];?>	</b></label>	
                                        </div>
                                </form>
						</div>
					</div>
				 </div>
        	</div>




	 <div class="span9" id="content">
           <div class="row-fluid">
                 <div class="block">
                    <div class="block-content collapse in">
                       <div class="span12"> 
					    	  
						<div class="control-group"> 	  	    
						<?php	
							 $count_item=mysqli_query($conn,"select * from item where item_status='Available'");
							 $count = mysqli_num_rows($count_item);
						 ?>	 
				   
					        <br>
					   		<div class="pull-right">
							<form name="search" action="" method="post" class="form-signin">
								  <input type="text"      class="input-block-level"   id="search" name="search" placeholder="Search" onChange="this.form.submit()" required>
							</form>
					   		</div>
			   
						<script>
							document.getElementById("search").onsubmit = function() {
							this.submit();
							};
						</script>
	 			</div>
         </div>
      </div>

	<form action="" method="post">
  	<table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
		<thead>		
		        <tr>
					<th>Item Name</th>
					<th>Item Tag No</th>
					<th>Item Status / Remarks </th>	
					<th>Item Category </th>	
                    <th class="empty"></th>					
		    	</tr>
		</thead>
<tbody>
<!-----------------------------------Content------------------------------------>
<?php
		
		if (isset($_GET['page_no']) && $_GET['page_no']!="") {
			$page_no = $_GET['page_no'];
			} else {
				$page_no = 1;
        }
		
		$total_records_per_page = 4;
		
		$offset = ($page_no-1) * $total_records_per_page;
		$previous_page = $page_no - 1;
		$next_page = $page_no + 1;
		$adjacents = "2";

		
		//get total number of page
		$result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM item where item_status='Available'")or die(mysqli_error($conn));
		$total_records = mysqli_fetch_array($result_count);
		$total_records = $total_records['total_records'];
		$total_no_of_pages = ceil($total_records / $total_records_per_page);
		$second_last = $total_no_of_pages - 1; // total pages minus 1
					
		$device_query = mysqli_query($conn,"select * from item where item_status='Available' ORDER BY item.item_id DESC LIMIT $offset, $total_records_per_page")or die(mysqli_error($conn));
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$search = $_POST['search'];
            //get total number of page
            $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM item where item_status='Available' and item_category LIKE '%$search%'");
            $total_records = mysqli_fetch_array($result_count);
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            $second_last = $total_no_of_pages - 1; // total pages minus 1

            $device_query = mysqli_query($conn,"select * from item where item_status='Available' and item_category LIKE '%$search%' ORDER BY item.item_id DESC LIMIT $offset, $total_records_per_page")or die(mysqli_error($conn));
        }
		
		while($row = mysqli_fetch_array($device_query)){
			$id = $row['item_id'];?>
											
			<tr>
				<td><?php echo $row['item_name']; ?></td>
				<td><?php echo $row['item_tag']; ?></td>
				<td><?php echo $row['item_status']; ?></td>
				<td><?php echo $row['item_category']; ?></td>					
				<td class="empty" width="100"><a rel="tooltip"  title="Add Borrow Item" id="i<?php echo $id; ?>" href="add_borrow.php<?php echo '?id='.$id; ?>" class="btn btn-success"><i class="icon-pencil"> Release</i></a></td>
		</tr>											
<?php  }  ?>

<tr><td><td colspan=8>

<div class="pagination pull-right">
<ul class="pagination">
<?php 
//if($page_no > 1){
//echo "<li><a href='?page_no=1'>First Page</a></li>";
//} ?>
    
<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
<a <?php if($page_no > 1){
echo "href='?page_no=$previous_page'";
} ?>>Previous</a>

<?php 
if ($total_no_of_pages <= 10){  	 
	for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
	if ($counter == $page_no) {
	echo "<li =><a class='active'>$counter</a></li>";	
	        }else{
        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
        }
}
elseif ($total_no_of_pages > 10){
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
		if ($counter == $page_no) {
		   echo "<li><a class='active'>$counter</a></li>";	
			}else{
			   echo "<li><a href='?page_no=$counter'>$counter</a></li>";
					}
	}
	echo "<li><a>...</a></li>";
	echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
	}
}
elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
echo "<li><a href='?page_no=1'>1</a></li>";
echo "<li><a href='?page_no=2'>2</a></li>";
echo "<li><a>...</a></li>";
for (
     $counter = $page_no - $adjacents;
     $counter <= $page_no + $adjacents;
     $counter++
     ) {		
     if ($counter == $page_no) {
	echo "<li ><a class='active'>$counter</a></li>";	
	}else{
        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
          }                  
       }
echo "<li><a>...</a></li>";
echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
}
else {
echo "<li><a href='?page_no=1'>1</a></li>";
echo "<li><a href='?page_no=2'>2</a></li>";
echo "<li><a>...</a></li>";
for (
     $counter = $total_no_of_pages - 6;
     $counter <= $total_no_of_pages;
     $counter++
     ) {
     if ($counter == $page_no) {
	echo "<li ><a class='active'>$counter</a></li>";	
	}else{
        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
	}                   
     }
}
?>
</li>
    
<li <?php if($page_no >= $total_no_of_pages){
echo "class='disabled'";
} ?>>
<a <?php if($page_no < $total_no_of_pages) {
echo "href='?page_no=$next_page'";
} ?>>Next</a>
</li>

<?php 
//if($page_no < $total_no_of_pages){
//echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
//} ?>
</ul>
</div>
</td></td></td>
</tbody>
</table>

</form>		
				  		
</div>
</div>
</div>
</div>



<?php include('footer.php'); ?>
</div>
<?php // include('script.php'); ?>
 </body>
</html>