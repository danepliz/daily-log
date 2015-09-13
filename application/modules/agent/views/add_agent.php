<form class="validate" method="post">
	<div class="grid_12">
		
		<h2>Add an Agent</h2>
		
		<div class="section">
			<p class="element">
				<label for="name">Name</label>
				<input type="text" name="name" id="name" class="required" value="<?php echo set_value('name')?>"/>
			</p>
			
			<p class="element">
				<label for="description">Description</label>
				<textarea name="description" id="description" class="required" cols="40" rows="4"><?php echo set_value('description')?></textarea>
			</p>

			<p class="element">
				<label for="account_number">Account No.</label>
				<input type="text" name="account_number" id="account_number" class="required" value="<?php echo set_value('account_number')?>"/>
			</p>

			<p class="element">
				<label for="district">District</label>
				<select name="district" class="required" id="agent_dist">
					<option value="">Select</option>
					<?php foreach ($districts as $d):?> 
						<option value="<?php echo $d['id']?>"><?php echo $d['name']?></option>
					<?php endforeach?>
				</select>
			</p>
			
			<p class="element">
				<label for="address">Address</label>
				<input type="text" name="address" id="address" class="required" value="<?php echo set_value('address')?>"/>
			</p>
			
			<p class="element">
				<label for="phone">Land Phone</label>
				<input type="text" name="phone" id="phone" class="required" value="<?php echo set_value('phone')?>"/>
			</p>

			<p class="element">
				<label for="fax">Fax</label>
				<input type="text" name="fax" id="fax" class="" value="<?php echo set_value('fax')?>"/>
			</p>


			<p class="element">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" class="required" value="<?php echo set_value('email')?>"/>
			</p>

			<p class="element">
				<label for="contact_person">Contact Person</label>
				<input type="text" name="contact_person" id="contact_person" class="required" value="<?php echo set_value('contact_person')?>"/>
			</p>

			<p class="element">
				<label for="mobile">Mobile</label>
				<input type="text" name="mobile" id="mobile" class="required" value="<?php echo set_value('mobile')?>"/>
			</p>

			<p class="element">
				<label for="timezone">TimeZone</label>
				<select name="timezone" class="required" id="timezone">
					<option value="">Select</option>
					<?php foreach ($timezones as $d):?> 
						<option value="<?php echo $d['id']?>"><?php echo $d['name']?> [GMT<?php echo ($d['gmt_offset']>0)?'+'.$d['gmt_offset']:$d['gmt_offset']?>]</option>
					<?php endforeach?>
				</select>
			</p>
			
			<p class="element">
				<label for="forexBatch">Forex Batch</label>
				<select name="forexBatch" class="required" id="forexBatch">
					<option value="">Select</option>
					<?php foreach ($forexes as $d):?> 
						<option value="<?php echo $d['id']?>"><?php echo $d['created']?></option>
					<?php endforeach?>
				</select>
			</p>

			<p class="element">
				<label for="taxable">Taxable</label>
				<input type="radio" name="taxable" id="taxable" value="1"/> Yes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="taxable" id="taxable" checked="checked" value="0"/> No
			</p>

			<p class="element">
				<label for="parentAgent">Parent Agent</label>
				<select name="parentAgent" class="" id="parentAgent">
					<option value="">None</option>
					<?php foreach ($agents as $d):?> 
						<option value="<?php echo $d['id']?>"><?php echo $d['name']?> </option>
					<?php endforeach?>
				</select>
			</p>
			
			<p class="element">
				<label for="discr">Agent Type</label>
				<select name="discr" class="" id="discr">
					<option value="">Select</option>
						<option value="bank">Bank</option>
						<option value="domestic">Domestic</option>
						<option value="international">International</option>
					</select>
			</p>

			
			
			<p class="element">
				<label for="active">Is Active?</label>
				<input type="checkbox" name="active" id="active" checked="checked" />
			</p>
	


		</div>
	</div>
	<p>

        					<label>&nbsp;</label>

        					<input type="submit" value="Save" />

        					<input type="button" value="Cancel" class="cancelaction" />

        				</p>

</form>