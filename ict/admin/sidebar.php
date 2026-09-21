     <div class="span3" id="sidebar">
	              <img id="admin_avatar" class="img-polaroid" src="<?php echo $row['adminthumbnails']; ?>"> 
                  <ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
                           <li class=""> 
						   <a href="dashboard.php"><i class="icon-chevron-right"></i><i class="icon-home icon-large"></i>&nbsp;Dashboard</a>						   </li>
						 
						 <!------/.* manage device sidebar*------->						
						 <li class="active">						
						    <a href="javascript:;" role="button" class="dropdown-toggle" data-toggle="collapse" data-target="#bs"><i class="icon-chevron-right"></i><i class="icon-folder-open-alt icon-large"></i>&nbsp;Registered File
						    <div class="muted pull-right"><i class="caret"></i></div></a>					
						    <ul id="bs" class="collapse">						
                            <li class="">
                            <a href="item.php"><i class="icon-chevron-right"></i><i class="icon-desktop"></i> Item </a>                            </li>
                            <li class="">
                            <a href="pemohon.php"><i class="icon-chevron-right"></i><i class="icon-group"></i> Staff(s) & Student(s)</a>                            </li> 					   							
						    </ul>
						</li>
						
		
						 <!------/.* tracsaction sidebar*------->	
					     <li class="active">						
					       <a href="javascript:;" role="button" class="dropdown-toggle" data-toggle="collapse" data-target="#bs2"><i class="icon-chevron-right"></i><i class="icon-retweet icon-large"></i>&nbsp;Transaction
						    <div class="muted pull-right"><i class="caret"></i></div></a>	
							<ul id="bs2" class="collapse">
                            <li class="">
                            <a href="add_borrower.php"><i class="icon-chevron-right"></i><i class="icon-share"></i> Application</a> 
                            <li class="">
                            <a href="borrow.php"><i class="icon-chevron-right"></i><i class="icon-share"></i> New Application</a>                            </li>										
                            <li class="">
                            <a href="borrow_approved.php"><i class="icon-chevron-right"></i><i class="icon-share"></i> Print Application</a>                            </li>
						    <li class="">
                            <a href="return.php"><i class="icon-chevron-right"></i><i class="icon-share"></i> Return</a>                            </li>
						    </ul>
						</li>
                       
                      
                        <!------/.* Record *------->	
						<li class="active">						
						    <a href="javascript:;" role="button" class="dropdown-toggle" data-toggle="collapse" data-target="#bs3"><i class="icon-chevron-right"></i><i class="icon-book"></i>&nbsp;Records
							<div class="muted pull-right"><i class="caret"></i></div></a>						
						    <ul id="bs3" class="collapse">						
						    <li class="">
                            <a href="view_return.php"><i class="icon-chevron-right"></i><i class="icon-eye-open"></i> View Return Item</a>                            </li>
							<li class="">
                            <a href="View_release_item.php"><i class="icon-chevron-right"></i><i class="icon-eye-open"></i> View Release Item</a>                            </li>
						    </ul>
						</li>
                      
						
					  <!------/.* manage user sidebar*------->	
						<li class="active">						
						    <a href="javascript:;" role="button" class="dropdown-toggle" data-toggle="collapse" data-target="#bs4"><i class="icon-chevron-right"></i><i class="icon-group"></i>&nbsp;Manage  User
							<div class="muted pull-right"><i class="caret"></i></div></a>						
						    <ul id="bs4" class="collapse">						
						    <li class="">
                            <a href="add_user.php"><i class="icon-chevron-right"></i><i class="icon-user"></i>&nbsp;System User</a>                            </li>
						    </ul>
						</li>
						
  </ul>					
																			
</div>