$(function(){
	$('input.required, textarea.required, select.required').prev('label').append('<em class="required">*</em>');
	
	$.validator.addMethod('money',function(value,element){
		if(value <= 0) return false;
		return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
	},"Invalid Amount");

    $.validator.addMethod('percent',function(value,element){
        return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
    },"Invalid Percentage");
	
	
	$.validator.addMethod('greaterThan',function(value,element,param){
		if(value <= 0) return false;
		
		var other = $(param).val();
		return ( Number(value) > Number(other));
		
	},"Invalid Amount");

    $.validator.addMethod('website_url',function(value, element){
        return this.optional(element) || /^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$/i.test(value);
    },'Please Enter website of format facebook.com');
	
	$.validator.addMethod('phone_fgm',function(value, element){
		 return this.optional(element) || /^\(\d{3}\)\d{3}[-]\d{3,4}$/i.test(value); 
	},'Please Enter phone number of format (000)000-0000');
	
	$.validator.addMethod('alphabetsOnly',function(value,element){
		 return this.optional(element) || /^[a-z]+$/i.test(value); 
	},"Invalid Characters");
	
	$.validator.addMethod('rate',function(value,element){
		if( isNaN(value) ) return false;
		return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
	},"Invalid Rate");

	$.validator.addMethod('house_fgm',function(value, element){
		 return this.optional(element) || /[A-Za-z0-9\-\/]$/i.test(value); 
	},'Only alphaphanumeric  and -/ characters allowed');

	$.validator.addMethod('apartment_fgm',function(value, element){
		 return this.optional(element) || /[A-Za-z0-9\-\/]$/i.test(value); 
	},'Only alphaphanumeric  and -/ characters allowed');

    $.validator.addMethod('number_only', function(value, element){
        return this.optional(element) || /[0-9]$/i.test(value);
    },'Numeric value only')
});

Yarsha.utility = {
		
	months : ['January','February','March','April','May','June','July','August','September','October','November','December'],	
	monthShort : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
	buildSelectOption : function (obj,map){
		if(typeof(obj) !== 'object')
			return '';
		if(obj.length == 0)
			return '';
		
		var html = '';
		
		$.each(obj,function(i,item){
			var value = map.call(window,item,'value'),
				name = map.call(window,item,'name');
			html+= '<option value="'+value+'">'+name+'</option>';
		});
		return html;
	},
	
	countrySelectOptions: function(def){
		var html = '';
		$.each(Yarsha.allcountries, function(ind, c){
			html += '<option value="'+c.id+'"';
			
			if( typeof(def) != undefined && def == c.id)
				html += ' selected="selected"';
			
			html += '>'+c.name+'</option>';
		});
		return html;
	},

    prepareSelectOptions: function(obj){
        if(typeof(obj) !== 'object')
            return '';
        if(obj.length == 0)
            return '';

        var html = '';

        $.each(obj,function(i,item){
            html+= '<option value="'+i+'">'+item+'</option>';
        });
        return html;
    }
};

Yarsha.Table = function(selector){
	if(selector == '' || typeof selector != 'string')
		return;
	
	var _table = $(selector);
	if(!_table.is('table'))
		return false;
	
	return new YarshaTable(_table);
};

YarshaTable = function(table){
	this.table = table;
};

YarshaTable.prototype.appendRow = function(data){
	//var numCol = this.table.find('tr').eq(1).children('td').length;
	var numCol = this.table.find('tr:first').children('td').length;
		numCol = (numCol == 0 )? this.table.find('tr:first').children('th').length : numCol;
	var newRow = $('<tr></tr>');
	for(var i = 0; i < numCol; i++){
		var cell = $('<td></td>').appendTo(newRow);
		cell.html(data[i]);
	}
	newRow.appendTo(this.table);
	return newRow;
};

YarshaTable.prototype.appendNote = function(data){
	var numCol = 3;
	var newRow = $('<tr></tr>');
	for(var i = 0; i<3; i++){
		var cell = $('<td></td>').appendTo(newRow);
		cell.html(data[i]);
	}
	newRow.appendTo(this.table);
	return newRow;
};

Yarsha.toggleStatus = function(obj, remoteURL){
    obj = jQuery(obj);
    var _objID = obj.data('object-id');
    if( _objID == 'undefined' || _objID == "" ){ return false; }
    $.ajax({
        type: 'GET',
        url: Yarsha.config.base_url + remoteURL + '/' + _objID,
        success: function(res){
            var data = $.parseJSON(res);
            if( data.status && data.status == 'success' ){
                if( data.currentStatus && data.currentStatus == true){
                    obj.removeClass('bg-maroon').addClass('bg-olive').html('Enabled');
                }else{
                    obj.removeClass('bg-olive').addClass('bg-maroon').html('Disabled');
                }
            }
        }
    });

};

Yarsha.notify = function(type, message){

    // success  error warn info

    $.notify(message, type, {globalPosition : 'top center'});
}

Yarsha.alert = function(msg){

    var out = '';
    out += '<div class="modal fade" id="yarshaAlertImformation" tabindex="-1" role="dialog" aria-labelledby="xoFormLabel" aria-hidden="true">';
    out += '<div class="modal-dialog  modal-lg">';
    out += '<div class="modal-content">';
    out += '<div class="alert alert-danger">';
    out += '<span class="icon"><i class="fa fa-warning"></i></span>';
    out += '<span class="message">'+msg+'</span>';
    out += '<span style="float:right" data-dismiss="modal"><i class="fa fa-times"></i></span>';
    out += '</div></div></div>';

    var obj = jQuery('#yarshaAlertImformation');

    console.log(obj);


    jQuery('body').append(obj);

    obj.modal('show');

};

Date.prototype.addDays = function(days){
    this.setDate(this.getDate() + days);
    return this;
};

Yarsha.sleep = function(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
};