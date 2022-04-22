<div class="row">
	<div class="col-xs-5 col-sm-5 col-lg-3 panel">
		<br>
		<p><?php echo $this->Html->link('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add New Category', array('controller'=>'categories', 'action'=>'index'), array('title'=>'Add (or) Edit (or) Remove Category', 'class'=>'btn btn-default btn-xs', 'escape'=>false));?></p>
		<br>
		<?php
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$categoriesList = $this->Category->find('list', array('conditions'=>array('Category.store_id'=>$this->Session->read('Store.id'))));
		if(!empty($categoriesList)) {
			
			foreach($categoriesList as $categoryID=>$categoryName) {
			?>			
				<div style="margin-bottom:10px; border-bottom:1px dotted #ddd; padding-bottom: 2px;">
					
					<form method="post" style="" name="category_product_<?php echo $categoryID;?>" id="category_product_<?php echo $categoryID;?>" action="<?php echo $this->Html->url("/categories/delete/".$categoryID);?>">
						<?php echo $this->Html->link($categoryName, array('controller'=>'cashbook', 'action'=>'index', $categoryID), array('title'=>$categoryName.' - Add new record in this category', 'class'=>'floatLeft'));?>
						
						<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete category - <?php echo $categoryName;?>? This action will remove all the records related to <?php echo $categoryName;?> from the Cashbook.')) { $('#category_product_<?php echo $categoryID;?>').submit(); } event.returnValue = false; return false;" class="floatRight btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
						</a> 
						
						
						<span class="floatRight">&nbsp;|&nbsp;</span>
						<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', array('controller'=>'categories', 'action'=>'edit', $categoryID), array('title'=>'Edit Category - '.$categoryName, 'class'=>'floatRight btn btn-warning btn-xs', 'escape'=>false));?>				
					</form>
					<?php //echo $this->Form->postLink('Remove', array('controller'=>'categories', 'action'=>'delete', $categoryID), array('title'=>'Remove Category - '.$categoryName, 'class'=>'floatRight small button link red'), 'Are you sure you want to delete this category "'.$categoryName.'"');?>
					
					<div style="clear:both;"></div>
				</div>
			<?php
			}
			?>
			<?php
			echo '<p style="clear:both; padding:2px 0px; border-bottom:1px dotted #ddd; font-weight:bold;" >'.$this->Html->link('Show all cash book records', array('controller'=>'cashbook', 'action'=>'index'), array('title'=>'Show all category records', 'style'=>'font-weight:bold;')).'</p>';
		}
		else {
			echo 'No category found';
		}
		?>
	</div>
	<div class="col-xs-7 col-sm-7 col-lg-9">
		<h1>Cashbook</h1>		
		<div>
			<h2>Select category to add records</h2>
			<?php
			if(!empty($categoriesList)) {
			?>
			<select id="categoryList" name="category" style="width:200px;" onchange="selectCategory()">
				<option value="0">-- Select Category --</option>
			<?php
			foreach($categoriesList as $cat_id => $cat_name) {
			?>
				<option value="<?php echo $cat_id;?>" 
				<?php 
					echo (isset($categoryInfo['Category']['id']) and ($categoryInfo['Category']['id'] == $cat_id)) ? 'selected' : null;
				?>>
					<?php echo $cat_name;?>
				</option>
			<?php
			}
			?>
			</select>
			<script type="text/javascript">
			function selectCategory() {
				var catId = $('#categoryList').val();
				window.location = '/cashbook/index/'+catId;
			}
			</script>
			<?php
			} else {
				echo 'Create a new category to add records.';
			}
			?>
			<br><br>
		</div>
		
		
		<?php
		if($categoryInfo) {
			$expense = $categoryInfo['Category']['expense'];
			$income = $categoryInfo['Category']['income'];
			$showType = false;
			if(($income and $expense) or (!$income and !$expense)) {
				$showType = true;
				$type = 'Income/Expense';
			}
			else {		
				$type = ($income) ? 'income' : 'expense';
			}
		?>
			
			<div id="AddCashRecordDiv" class="well">
				<?php echo $this->Form->create('Cashbook', array('url'=>'/cashbook/add/'.$categoryInfo['Category']['id']));?>
					<div style="float:left; clear:none;">
						<?php
						if($showType) {
							$options = array('expense'=>'Expense', 'income'=>'Income');					
						}
						else {
							$type = ($income) ? 'income' : 'expense';	
							if($type == 'income') {
								$options = array('income'=>'Income');
							} else {
								$options = array('expense'=>'Expense');
							}
						}
										
						echo $this->Form->input('payment_type', array('type'=>'select', 'label'=>'Payment Type', 'required'=>true, 'title'=>'Payment Type', 'options'=>$options, 'style'=>'width:110px;'));			
						?>
					</div>
					
					<div style="float:left; clear:none; margin-left:10px;">
						<?php echo $this->Form->input('payment_date', array('label'=>'Date', 'required'=>true, 'type'=>'date'));?>
					</div>
					<div style="float:left; clear:both;">
						<?php 				
						echo $this->Form->input('payment_amount', array('type'=>'text', 'label'=>'Amount', 'required'=>true, 'title'=>'Amount', 'style'=>'width:100px;'));			
						?>
					</div>
					
					<div style="float:left; clear:none; margin-left:10px;">
						<?php echo $this->Form->input('description', array('label'=>'Description', 'type'=>'text', 'style'=>'width:250px;'));?>
					</div>				
					<div style="float:left; clear:none; margin-left:10px;">
						<br>
						<?php echo $this->Form->submit('Add Record');?>
					</div>
					<div style="clear:both; padding:0px;"></div>
				<?php echo $this->Form->end();?>
			</div>
		<?php	
		}
		?>
		
		
		<h2> 
			<?php 
			if($categoryInfo) { 
				echo 'Recent records in category "'.$categoryInfo['Category']['name'].'"'; 
				?>
				<span style="font-size:11px; font-style:italic;">[<?php echo $this->Html->link('Show all records', array('controller'=>'cashbook', 'action'=>'index'), array('title'=>'Show all category records'));?>]</span>
				<?php
			}
			else {
				echo 'All Records';
			}
			?>
		</h2>
		<?php 
		if($cashbook) { 
		?>
		<?php
			// prints X of Y, where X is current page and Y is number of pages
			echo 'Page '.$this->Paginator->counter();
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			
			// Shows the next and previous links
			echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			// Shows the page numbers
			echo $this->Paginator->numbers();
			
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
		?>
		<table class='table' style="width:100%;">
			<thead>
				<tr>
					<th style="width:20px;">#</th>
					<th style="width:150px;"><?php echo $this->Paginator->sort('category_name', 'Category'); ?></th>
					<th>Description</th>
					<th style="width:150px;"><?php echo $this->Paginator->sort('payment_amount', 'Payment Amount'); ?></th>
					<th style="width:100px;"><?php echo $this->Paginator->sort('payment_type', 'Type'); ?></th>
					<th style="width:100px;"><?php echo $this->Paginator->sort('payment_date', 'Date'); ?></th>
					<th style="width:50px;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i=0;
				foreach($cashbook as $row) {
					$i++;
				?>
				<tr>
					<td><?php echo $i;?></td>				
					<td><?php echo $row['Cashbook']['category_name'];?></td>
					<td><?php echo $row['Cashbook']['description'];?></td>
					<td><?php echo $row['Cashbook']['payment_amount'];?></td>
					<td><?php echo ucwords($row['Cashbook']['payment_type']);?></td>
					<td><?php echo date('d-m-Y', strtotime($row['Cashbook']['payment_date']));?></td>
					<td>
						<form method="post" style="" name="invoice_cashbook_product_<?php echo $row['Cashbook']['id'];?>" id="invoice_cashbook_product_<?php echo $row['Cashbook']['id'];?>" action="<?php echo $this->Html->url("/cashbook/remove/".$row['Cashbook']['id']);?>">							
							<a href="#" name="Remove" onclick="if (confirm('Are you sure you want to delete this record from the list?')) { $('#invoice_cashbook_product_<?php echo $row['Cashbook']['id'];?>').submit(); } event.returnValue = false; return false;" class="btn btn-danger btn-xs">
								<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
							</a> 
						</form>
						
						<?php 
						//echo $this->Form->postLink('Remove', array('controller'=>'cashbook', 'action'=>'remove', $row['Cashbook']['id']), array('title'=>'Remove this record', 'class'=>'small button link red'), 'Are you sure you want to delete this record?');
						?>				
					</td>
				</tr>
				<?php
				}
				?>			
			</tbody>
		</table>
		<?php
		if(count($cashbook) > 10) {
			// prints X of Y, where X is current page and Y is number of pages
			echo 'Page '.$this->Paginator->counter();
			echo '&nbsp;&nbsp;&nbsp;&nbsp;';
			
			// Shows the next and previous links
			echo '&laquo;'.$this->Paginator->prev('Prev', null, null, array('class' => 'disabled'));
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			// Shows the page numbers
			echo $this->Paginator->numbers();
			
			echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			echo $this->Paginator->next('Next', null, null, array('class' => 'disabled')).'&raquo;';
		}
		?>
		<?php } else { ?>
		<p>No records found.</p>
		<?php } ?>
	</div>
</div>	
