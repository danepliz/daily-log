<?php echo form_open('','name = "addreport" class="validate"');?>

<div class="row">

    <?php echo textAreaWrapper('sqlquery', 'SQL Query', $this->input->post('sqlquery'), 'style="height:200px" class="required form-control" rows="20"', 'col-md-12'); ?>
    <div class="col-md-12"><input type="submit" value="Generate Report" class="btn btn-primary" name="gen-report" id="report-gen"/></div>


</div>

<div class="clear" style="height:5rem; width:100%"></div>

<?php 
	if(isset($query_result)) $this->load->theme('report/result');
    echo form_close();
?>