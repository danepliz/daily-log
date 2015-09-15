
<div class="row">
    <?php if (user_access_or( array( 'general setting', 'user setting' ))){ ?>
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <?php if (user_access('general setting')) { ?><li role="presentation" class="active"><a href="#general" data-toggle="tab">General Settings</a></li><?php } ?>
            <?php if (user_access('user setting')) { ?><li role="presentation"><a href="#user-config" data-toggle="tab">User Settings</a></li><?php } ?>
            <?php if (Current_User::isSuperUser()) { ?><li role="presentation"><a href="#siteMaintenance" data-toggle="tab">Site Maintenance Settings</a></li><?php } ?>

        </ul>
        <div class="tab-content">

            <?php if (user_access('general setting')) { ?>
            <div class="tab-pane active" id="general">
                <div class="col-md-12">
                    <form class="" action="" method="post" id="general-config-form" enctype="multipart/form-data">

                        <div class="form-group-sm">
                            <label for="config_adminemail">Admin Email</label>
                            <input type="text" name="config_adminemail" id="config_adminemail" value="<?php echo Options::get('config_adminemail','');?>" class="required full email form-control" size="60" />
                        </div>

                        <div class="form-group-sm">
                            <label for="config_technicalemail">Technical Email</label>
                            <input type="text" name="config_technicalemail" id="config_technicalemail" value="<?php echo Options::get('config_technicalemail','');?>" class="required full email form-control" size="60" />
                        </div>

                        <div class="form-group-sm">
                            <label for="config_accountemail">Account Email</label>
                            <input type="text" name="config_accountemail" id="config_accountemail" value="<?php echo Options::get('config_accountemail','');?>" class="required full email form-control" size="60" />
                        </div>

                        <div class="form-group-sm">
                            <label for="config_tpin">TPIN</label>
                            <input type="text" name="config_tpin" id="config_tpin" value="<?php echo Options::get('config_tpin','303760040');?>" class="required form-control" size="60" />
                        </div>

                        <div class="form-group-sm">
                            <label for="config_stamp">STAMP</label>
                            <input type="file" name="config_stamp" id="config_stamp" value="<?php echo Options::get('config_stamp','');?>" class="form-control" size="60" />
                            <?php if( Options::get('config_stamp', '') !== "" ) echo '<img src="'.Options::get('config_stamp', '').'" width="100"  >' ?>
                        </div>

                        <div class="form-group-sm margin">
<!--                            <label>&nbsp;</label>-->
                            <input type="submit" value="SAVE" id="submit-general-config" class="btn btn-primary"/>
                            <a href="<?php echo site_url('dashboard')?>" class="btn btn-danger">CANCEL</a>
                        </div>
                    </form>
                </div>
            </div><!-- general -->
            <?php } ?>

            <?php if (user_access('user setting')) { ?>
            <div class="tab-pane" id="user-config">
                <div class="col-md-12">
                    <form class="" action="" method="post">

                        <div class="form-group-sm">
                            <label for="userpwd_expiry_days">Password Expiry After</label>
                            <input type="text" name="userpwd_expiry_days" id="userpwd_expiry_days" class="form-control <?php echo (Options::get('userpwd_expirable','0')=='1') ? 'required':''?>" placeholder="Days" maxlength="3" minlength="2" value="<?php echo Options::get('userpwd_expiry_days','');?>"/>
                        </div>
                        <div class="form-group-sm">
                            <label>Enable Password Expiry</label>
                            <div class="input-group">
                                <input type="radio" name="userpwd_expirable" class="simple" value="1" id="userpwd_expiry-yes" <?php echo (Options::get('userpwd_expirable','0')=='1') ? 'checked="checked"':''?>/> YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="userpwd_expirable" class="simple" value="0" id="userpwd_expiry-no" <?php echo (Options::get('userpwd_expirable','0')=='0') ? 'checked="checked"':''?>/> NO
                            </div>
                        </div>

                        <div class="form-group-sm">
                            <label>Force Password Change In First Login</label>
                            <div class="input-group">
                                <input type="radio" name="user1st_login" class="simple" value="1" id="user1st_login-yes" <?php echo (Options::get('user1st_login','0')=='1') ? 'checked="checked"':''?>/> YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="user1st_login" class="simple" value="0" id="userpwd_expiry-no" <?php echo (Options::get('user1st_login','0')=='0') ? 'checked="checked"':''?>/> NO
                            </div>
                        </div>

                        <div class="form-group-sm margin">
                            <label>&nbsp;</label>
                            <input type="submit" value="SAVE" id="submit-user-config"  class="btn btn-primary"/>
                            <a href="<?php echo site_url('dashboard')?>" class="btn btn-danger">CANCEL</a>
                        </div>
                    </form>
                    <script>
                        $(function() {
                            if ($('input[name="userpwd_expirable"]').filter(':checked').val() == 1) $('#userpwd_expiry_days').addClass('required').addClass('reqd');
                            $('#userpwd_expiry-yes').click(function() {
                                $('#userpwd_expiry_days').addClass('required').addClass('reqd');
                                if ($('#userpwd_expiry_days').prev('label').find('.required').length != 1) $('#userpwd_expiry_days').prev('label').append('<em class="required">*</em>');
                            });
                            $('#userpwd_expiry-no').click(function() {
                                $('#userpwd_expiry_days').removeClass('required').removeClass('reqd').prev('label').find('.required').remove();
                            });
                        })
                    </script>
                </div>
            </div><!-- users -->
            <?php } ?>

            <?php if (Current_User::isSuperUser()) { ?>
            <div class="tab-pane" id="siteMaintenance">
                <div class="col-md-12">
                    <form class="" action="" method="post">

                        <div class="form-group-sm">
                            <label for="site_maintenance">Force Site Maintenance</label>
                            <div class="input-group">
                                <input type="radio" name="site_maintenance" class="simple" value="1" id="site_maintenance-yes" <?php echo (Options::get('site_maintenance','0')=='1') ? 'checked="checked"':''?>/> YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="site_maintenance" class="simple" value="0" id="site_maintenance-no" <?php echo (Options::get('site_maintenance','0')=='0') ? 'checked="checked"':''?>/> NO
                            </div>
                        </div>

                        <div class="form-group-sm">
                            <label>Auto Resume After</label>
                            <input type="text" name="site_maintenance_resume_after" id="site_maintenance_resume_after" class="form-control <?php echo (Options::get('site_maintenance','0')=='1' and Options::get('site_maintenance_resume','0')=='1') ? 'required':''?>" placeholder="Date Time" value="<?php echo Options::get('site_maintenance_resume_after','');?>" readonly="readonly"/>
                        </div>

                        <div class="form-group-sm">
                            <label>Enable Auto Resume</label>
                            <div class="input-group">
                                <input type="radio" name="site_maintenance_resume" value="1" id="site_maintenance_resume-yes" <?php echo (Options::get('site_maintenance_resume','0')=='1') ? 'checked="checked"':''?>/> YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="site_maintenance_resume" value="0" id="site_maintenance_resume-no" <?php echo (Options::get('site_maintenance_resume','0')=='0') ? 'checked="checked"':''?>/> NO
                            </div>
                        </div>

                        <div class="form-group-sm margin">
                            <label>&nbsp;</label>
                            <input type="submit" value="SAVE" id="submit-maintenance-config" class="btn btn-primary" />
                            <a href="<?php echo site_url('dashboard')?>" class="btn btn-danger">CANCEL</a>
                        </div>
                    </form>

                    <script>
                        $(function() {
                            if ($('input[name="site_maintenance_resume"]').filter(':checked').val() == 1 && $('input[name="site_maintenance"]').filter(':checked').val() == 1)  $('#site_maintenance_resume_after').addClass('required').addClass('reqd');
                            $('#site_maintenance_resume-yes').click(function() {
                                $('#site_maintenance_resume_after').addClass('required').addClass('reqd');
                                if ($('#site_maintenance_resume_after').prev('label').find('.required').length != 1) $('#site_maintenance_resume_after').prev('label').append('<em class="required">*</em>');
                            });
                            $('#site_maintenance_resume-no').click(function() {
                                $('#site_maintenance_resume_after').removeClass('required').removeClass('reqd').prev('label').find('.required').remove();
                            });
                            $('#site_maintenance-no').click(function() {
                                $('#site_maintenance_resume_after').removeClass('required').removeClass('reqd').prev('label').find('.required').remove();
                            });
                            $('#site_maintenance-yes').click(function() {
                                if ($('input[name="site_maintenance_resume"]').filter(':checked').val() == 1) $('#site_maintenance_resume-yes').trigger('click');
                            });

                            $('#site_maintenance_resume_after').datepicker({
                                format:'Y-m-d H:i:s'
                            });
                        })
                    </script>
                </div>
            </div><!-- maintenance -->
            <?php } ?>


        </div>
    </div><!-- col-md-12 -->
    <?php } ?>
</div><!-- row -->

<script>
$(function(){

	var hash = window.location.hash.substr(1,window.location.hash.length),
		active = ((indx = $('.tabs > div').index($('#' + hash + '-config'))) > 0) ? indx : 0;

	$('form').each(function(i,e) {
		$(this).attr('id', $(this).closest('div').attr('id') + '-form');
	});

	$('.required').addClass('reqd');

	$('.section.tabs').tabs({
							selected: active,
							select: function(e,ui){
										window.location.hash = $(ui.tab).attr('href').split('-')[0];
									}
							});
	window.onhashchange = function() {
		hash = window.location.hash.substr(1,window.location.hash.length);
		active = ((indx = $('.tabs > div').index($('#' + hash + '-config'))) > 0) ? indx : 0;
		$('.section.tabs').tabs({
			 selected: active,
			 select: function(e,ui) {
					window.location.hash = $(ui.tab).attr('href').split('-')[0];
				}
		});
	}

	$('#txn_halt_from, #txn_halt_to, #site_maintenance_resume_after').datepicker({dateFormat: 'yy-mm-dd',});
	$('#submit-transaction-config').click(function(){
		var from	= $('#txn_halt_from').val(),
			to		= $('#txn_halt_to').val();

		if ( from != '' && to != '' && (from.replace(/[^0-9\.]+/g, '') >= to.replace(/[^0-9\.]+/g, '')) )	{

			alert('\r\nEntire Transaction Halt TimeStamp range invalid.\r\n"From Date Time" must be earlier(before) than "To Date Time".');
			$('#txn_halt_from').val('');	$('#txn_halt_to').val('');
			return false;
		}

	});

	$('input[type="submit"]').bind('click', function() {

		$(this).closest('form').validate({
			errorElement: 'span',
			invalidHandler: function(form, validator) {
		        if (validator.numberOfInvalids()) { $('div.response.success').hide(); }
		    }
		});
		$('form').not('#' + $(this).closest('form').attr('id')).each(function(i,e) {
			$(this).find('input.required, textarea.required, select.required').removeClass('required');
		});

	});

});


</script>