					<!-------------------------------------- return edit modal ------------------------------------->
					<div id="return_edit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h3 id="myModalLabel">Release the return items?</h3>
					</div>
					<div class="modal-body">
					<div class="alert alert-danger">
					<p>Fill in note before release the item return..</p>
                        
                    <label class="control-label" for="inputPassword">Item Name</label>
				    <input type="text" class="span8" value="<?php echo $row['item_name']; ?>" name="item_name" id="inputPassword" placeholder="Item Name" required>
				    </div>
					</div>
					</div>
					<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-large"></i> Cancel</button>
					<button class="btn btn-danger" name="edit_return"><i class="icon-check icon-large"></i> Yes</button>
					</div>
					</div>
					
				
                    