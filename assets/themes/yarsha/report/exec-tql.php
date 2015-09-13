<style>
img.action-image {
	float: right;
	margin-left: 10px;
	cursor: pointer; 
}
</style>
<?php echo form_open('','name = "addreport" class="validate"');?>

<?php if (!$xls){ ?>
<div class="row">
    <div class="col-md-12 ">
        <div class="inner-placeholder noprint">
            <img class="export action-image" data-url="<?php echo site_url('report/dumpPdf/'.$report->getSlug())?>" src="<?php echo base_url() ?>assets/images/pdf_16x16.png" alt="Print" title="Export Report as PDF" style="display:none;" />
            <img class="export action-image" data-url="<?php echo site_url('report/dumpxls/'.$report->getSlug())?>" src="<?php echo base_url() ?>assets/images/excel_16x16.png" alt="Print" title="Export Report as XLS" style="display:none;" />
            <img class="printbtn action-image" src="<?php echo base_url() ?>assets/images/print_16x16.png" alt="Print" title="Print Report" style="display:none;" />
        </div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title"><?php echo ucfirst($report->getName())?></h3></div>
            <?php echo $query_result;?>
        </div>
    </div>
</div>

<?php if ($hasResult){ ?>
    <div class="row">
        <div class="col-md-12 ">
            <div class="inner-placeholder noprint">
                <img class="export action-image" data-url="<?php echo site_url('report/dumpPdf/'.$report->getSlug())?>" src="<?php echo base_url() ?>assets/images/pdf_16x16.png" alt="Print" title="Export Report as PDF" style="display:none;" />
                <img class="export action-image" data-url="<?php echo site_url('report/dumpxls/'.$report->getSlug())?>" src="<?php echo base_url() ?>assets/images/excel_16x16.png" alt="Print" title="Export Report as XLS" style="display:none;" />
                <img class="printbtn action-image" src="<?php echo base_url() ?>assets/images/print_16x16.png" alt="Print" title="Print Report" style="display:none;" />
            </div>
        </div>
    </div>
<?php } ?>


<?php echo form_close();?>

<script type="text/javascript">
$(function() {								
	$('input.datepicker').datepicker({
		dateFormat:'yy-mm-dd'
	});
	$('input.datepicker').change(function() {
		$('.generate').show();
	});

	<?php if ($hasResult):?> 
		$('.inner-placeholder .printbtn, .inner-placeholder .export-report, .export').show();
	<?php endif;?>
	$('.filter-area').find('input, textarea, select').bind('keyup, change',function(){
		$('#submit-filter').show();
	}); 

	$('input[name=gen-report]').live('click', function(){ $('form[name="addreport"]').attr('action', '<?php echo site_url('report/result/'.$report->getSlug()); ?>').submit();  });

	$('.export').live('click',function(){ $('form[name="addreport"]').attr('action', $(this).data('url')).submit(); });

	$('.export-report').click(function() {
		$('form[name="dumpxls"]').submit();
	});

	$('.printbtn').live('click', function() {
		window.print();
	});

	$('#gen-report-wrap').find('table.genreport').each(function(i,e) {
		rows = $(this).find('tr').not('.aggregate');
		rows.each(function(j,e) {
			(j < 1) ? $(this).prepend('<th width="1%">SN</th>') 
				: $(this).prepend('<td align="center">' + j + '</td>');
		})
		$(this).find('tr.aggregate').prepend('<td>&nbsp;</td>');
	});
	
});

</script>