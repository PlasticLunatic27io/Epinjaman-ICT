<html>
<?php include('header.php'); ?>
<?php include('session.php'); ?>

    <body>
       <?php include('navbar.php'); ?>
       <div class="container-fluid">
        <div class="row-fluid">
				<?php include('sidebar.php'); ?>
	
             <div class="span9" id="content">
	
					<p></p>
				
					<?php	
					 $count_item=mysqli_query($conn,"select * from item where item_status='Available'") or die(mysqli_error());
					 $count = mysqli_num_rows($count_item);
					 ?>	 

								
               <div id="block_bg" class="span12 pull-right block collapse in">
						   
						   <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"> <H4 style="color:green"> <i class="icon-user"></i> Available Items / Stocks List</H4></div>
								<div class="muted pull-right">Number of Items / Stock: <span class="badge badge-info"><?php  echo $count; ?></span></div>
						   </div>
							
				   <div class="container-fluid">
                     <div class="row-fluid">


						<form action="delete_item.php" method="post">
  									<table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                    <p></p>
							
										<thead style="background-color: #e4f5e8; color: black;">
                                        <tr>
                                            <th style="padding: 12px 15px;">Item Name</th>
                                            <th style="padding: 12px 15px;">Item Tag No</th>
                                            <th style="padding: 12px 15px;">Item Status / Remarks </th>	
                                            <th style="padding: 12px 15px;">Item Qty </th>	
                                            <th style="padding: 12px 15px;">Item Description </th>
                                            <th style="padding: 12px 15px;">Item Category </th>		                         
                                        </tr>
										</thead>
										<tbody>
													<?php
													$pemohon_query = mysqli_query($conn,"select * from item where item_status='Available'")or die(mysqli_error());
													while($row = mysqli_fetch_array($pemohon_query)){
													$id = $row['item_id'];
													?>
									
												<tr>
                                                <td><?php echo $row['item_name']; ?></td>
                                                <td><?php echo $row['item_tag']; ?></td>
                                                <td><?php echo $row['item_status']; ?></td>
                                                <td><?php echo $row['item_quantity']; ?></td>	
                                                <td><?php echo $row['item_description']; ?></td>
                                                <td><?php echo $row['item_category']; ?></td>					
												</tr>
												<?php } ?>
										</tbody>
                              </table>
							</form>
                         </div>
                       </div>
                    </div>
				</div>
              </div>
		<?php include('footer.php'); ?>
		<?php include('script.php'); ?>
    </body>
</html>