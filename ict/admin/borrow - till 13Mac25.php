<html>
<head>
<style>

.center {
  text-align: center;
}

.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #b2e4f7;
}

.pagination a.active { background-color: #b2e4f7; color: white; border: 1px solid #ddd;}

.pagination a:hover:not(.active) {background-color: #ddd;}

        /* Style for input text fields */
        input[type="text"] {
            width: 100%;                /* Full-width input */
            padding: 10px;              /* Padding inside the input */
            font-size: 16px;            /* Text size */
            border: 2px solid #ccc;     /* Border color and thickness */
            border-radius: 5px;         /* Rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Light shadow */
            outline: none;              /* Remove blue outline on focus */
            transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
        }

        /* Style when the input is focused */
        input[type="text"]:focus {
            border-color: #66afe9;         /* Border color on focus */
            box-shadow: 0 0 5px rgba(102, 175, 233, 0.5); /* Shadow effect */
        }

        /* Placeholder text style */
        input[type="text"]::placeholder {
            color: #aaa;                   /* Lighter color for placeholder */
            font-style: italic;            /* Italicize placeholder */
        }
    </style>
</head>
<body>

<?php include('header.php'); ?>
<?php include('session.php'); ?>

<body>
		<?php include('navbar.php'); ?>
        <div class="container-fluid">
            <div class="row-fluid">
				<?php include('sidebar.php'); ?>
			
				<div class="span9" id="content">
                     <div class="row-fluid">

				<?php	
	             $count_item=mysqli_query($conn,"select * from borrowing where status='Baru' ");
	             $count = mysqli_num_rows($count_item);
                 ?>	 
				 
				   <div id="block_bg" class="block pull-right">
                        <div class="navbar navbar-inner block-header">
                          <div class="muted pull-left"> <H4 style="color:green"> <i class="icon-reorder icon-large"></i> New Application List</H4></div>
                          <div class="muted pull-right">Number of New Application: <span class="badge badge-info"><?php  echo $count; ?></span></div>
				   </div>
						  
                 <h4 id="sc">Item Stock List 
					<div align="right" id="sc">Date:
						<?php
                            $date = new DateTime();
                            echo $date->format('l, F jS, Y');
                         ?></div>
				 </h4>

<br>



				 
<div class="container-fluid">
  <div class="row-fluid"> 
     <div class="empty"> 
	     <div class="pull-left"> 
	       <ul class="nav nav-pills">
				  <?php	
					   $count_item=mysqli_query($conn,"select * from borrowing where status = 'Baru'");
					   $count = mysqli_num_rows($count_item);
				   ?>
		      <li class="active">
			       <a href="item.php">New Application&nbsp;<span class="label label-default"> <?php echo $count;?></span></a>
		      </li>
			</ul>
	     </div>
		 
	     <div class="pull-right">
		   <!-- <a href=".php" class="btn btn-info" id="print" data-placement="left" title="Click to Print"><i class="icon-print icon-large"></i> Print List</a> 		      
		   <script type="text/javascript">
		     $(document).ready(function(){
		     $('#print').tooltip('show');
		     $('#print').tooltip('hide');
		     });
		   </script>        	  -->  
		   
		   <div class="">
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
</div>
	
<div class="block-content collapse in">
    <div class="span12">
	<form action="" method="post">
  	<table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
		<thead style="background-color: #e4f5e8; color: black;">		
		        <tr>
					<th style="padding: 12px 15px;">Applicant Id</th>
					<th style="padding: 12px 15px;">Applicant Name</th>
					<th style="padding: 12px 15px;">List of request item(s)</th>
					<th style="padding: 12px 15px;">Applied Date </th>	
					<th style="padding: 12px 15px;">Start Date </th>	
					<th style="padding: 12px 15px;">End Date </th>
					<th style="padding: 12px 15px;">Type </th>	
					<th style="padding: 12px 15px;">Status </th>	
                    <th style="padding: 12px 15px;" class="empty"></th>					
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
		
		$total_records_per_page = 5;
		
		$offset = ($page_no-1) * $total_records_per_page;
		$previous_page = $page_no - 1;
		$next_page = $page_no + 1;
		$adjacents = "2";

		
		//get total number of page
		$result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM borrowing where status='Baru'");
		$total_records = mysqli_fetch_array($result_count);
		$total_records = $total_records['total_records'];
		$total_no_of_pages = ceil($total_records / $total_records_per_page);
		$second_last = $total_no_of_pages - 1; // total pages minus 1
					
		$device_query = mysqli_query($conn,"select * from borrowing where status='Baru' ORDER BY borrowing.borrow_id DESC LIMIT $offset, $total_records_per_page")or die(mysqli_error($conn));
		
		while($row = mysqli_fetch_array($device_query)){
			$id = $row['borrow_id'];
			$id2= $row['pemohon_college_id'];?>
											
			<tr>
				<td><?php echo $row['pemohon_college_id']; ?></td>
				<td><?php
						$device_query2 = mysqli_query($conn, "SELECT pemohon_name FROM pemohon WHERE pemohon_college_id = '$id2'") or die(mysqli_error($conn));
						while ($row2 = mysqli_fetch_assoc($device_query2)) {
							echo $row2['pemohon_name'];
						}
					  ?>
				</td>
				<td><?php echo $row['borrow_item']; ?></td>
				<td><?php echo $row['request_date']; ?></td>
				<td><?php echo $row['start_date']; ?></td>	
				<td><?php echo $row['end_date']; ?></td>
				<td><?php echo $row['type']; ?></td>	
				<td style="color: red;"><?php echo $row['status']; ?></td>				
				
				<?php $_SESSION['borrow_id']= $id; 
				      $session_pemohonid = $_SESSION['borrow_id']?>	
				<td class="empty" width="100"><a rel="tooltip"  title="Process Application" id="i<?php echo $id; ?>" href="release.php<?php echo '?id='.$session_pemohonid; ?>" class="btn btn-success"><i class="icon-pencil"> Process</i></a></td>
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
</div>
	
</div>	
<?php include('footer.php'); ?>
</div>
<?php include('script.php'); ?>
 </body>
</html>