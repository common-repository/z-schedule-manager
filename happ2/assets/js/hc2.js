jQuery(document).on( 'click', '.hcj2-target ul.hcj2-dropdown-menu', function(e)
{
	e.stopPropagation();
//	e.preventDefault();
});

jQuery(document).on( 'click', '.hcj2-confirm', function(event)
{
	if( window.confirm("Are you sure?") ){
		return true;
	}
	else {
		event.preventDefault();
		event.stopPropagation();
		return false;
	}
});

jQuery(document).on( 'submit', '.hcj2-alert-dismisser', function(e)
{
	jQuery(this).closest('.hcj2-alert').hide();
	return false;
});

jQuery(document).on( 'click', '.hcj2-action-setter', function(event)
{
	var thisForm = jQuery(this).closest('form');
	var actionFieldName = 'action';
	var actionValue = jQuery(this).attr('name');

	thisForm.find("input[name='" + actionFieldName + "']").each( function(){
		jQuery(this).val( actionValue );
	});
});

/*
this displays more info divs for radio choices
*/
jQuery(document).on( 'change', '.hcj2-radio-more-info', function(event)
{
	// jQuery('.hcj2-radio-info').hide();
	var total_container = jQuery( this ).closest('.hcj2-radio-info-container');
	total_container.find('.hcj2-radio-info').hide();

	var my_container = jQuery( this ).closest('label');
	var my_info = my_container.find('.hcj2-radio-info');
	my_info.show();
});

/* toggle */
jQuery(document).on('click', '.hcj2-toggle', function(e)
{
	var this_target_id = jQuery(this).data('target');
	if( this_target_id.length > 0 ){
		this_target = jQuery(this_target_id);
		if( this_target.is(':visible') ){
			this_target.hide();
		}
		else {
			this_target.show();
		}
	}
	return false;
});

/* tab toggle */
jQuery(document).on('click', '.hcj2-tab-toggler', function(e)
{
	var total_parent = jQuery(this).closest('.hcj2-tabs');
	var menu_parent = total_parent.find('.hcj2-tab-links');;
	var panes_parent = total_parent.find('.hcj2-tab-content');

	var new_tab_id = jQuery(this).data('toggle-tab');
	panes_parent.find('.hcj2-tab-pane').hide();
	// menu_parent.find('li').removeClass('hc-active');
	menu_parent.find('a').removeClass('hc-active');

	panes_parent.find('[data-tab-id=' + new_tab_id + ']').show();
	// jQuery(this).parent('li').addClass('hc-active');
	jQuery(this).addClass('hc-active');

	jQuery(this).trigger({
		type: 'shown.hc.tab'
	});

	return false;
});

/* collapse next */
jQuery(document).on('click', '.hcj2-collapse-next,[data-toggle=collapse-next]', function(e)
{
	var this_target = jQuery(this).closest('.hcj2-collapse-panel').children('.hcj2-collapse');

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('hcj-open');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('hcj-open');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});

		if( jQuery(this).hasClass('hcj-collapser-hide')){
			jQuery(this).closest('li').hide();
		}
	}
//	this_target.collapse('toggle');

	if( jQuery(this).attr('type') != 'checkbox' ){
		/* scroll into view */
//		var this_parent = jQuery(this).parents('.collapse-panel');
//		this_parent[0].scrollIntoView();
		return false;
	}
	else {
		return true;
	}
});

/* collapse other */
jQuery(document).on('click', '.hcj2-collapser', function(e)
{
	var targetUrl = jQuery(this).attr('href');
	if(
		( targetUrl.length > 0 ) &&
		( targetUrl.charAt(targetUrl.length-1) == '#' )
		){
		return false;
	}

	var this_target = jQuery(targetUrl);

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('hcj-open');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('hcj-open');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});
	}
//	this_target.collapse('toggle');
	if( jQuery(this).attr('type') != 'checkbox' ){
		return false;
	}
	else {
		return true;
	}
});

/* collapse other */
jQuery(document).on('click', '.hcj2-collapse-closer', function(e)
{
	var this_target = jQuery(this).closest('.hcj2-collapse');

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('in');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('in');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});
	}

	if( jQuery(this).attr('type') != 'checkbox' ){
		return false;
	}
	else {
		return true;
	}
});

jQuery(document).on('click', '.hcj2-dropdown-menu select', function()
{
	return false;
});

jQuery(document).on( 'click', 'a.hcj2-toggler', function(event)
{
	jQuery('.hcj2-toggled').toggle();
	return false;
});

jQuery(document).on('change', '.hcj2-collector-wrap input.hcj2-collect-me', function(event){
	var my_val = jQuery(this).val();
	var me_remove = ( jQuery(this).is(":checked") ) ? 0 : 1;
	var input_name = jQuery(this).attr('name');

	/* find an input of the same name in the collector form */
	var collector_form = jQuery(this).closest('.hcj2-collector-wrap').find('form.hcj2-collector-form');
	var collector_input = collector_form.find("input[name^='" + input_name + "']");

	if( collector_input.length ){
		var current_value = collector_input.val();
		if( current_value.length ){
			current_value = current_value.split('|');
		}
		else {
			current_value = [];
		}

		var my_pos = jQuery.inArray(my_val, current_value);

	/* remove */
		if( me_remove ){
			if( my_pos != -1 ){
				current_value.splice(my_pos, 1);
			}
		}
	/* add */
		else {
			if( my_pos == -1 ){
				current_value.push(my_val);
			}
		}

		current_value = current_value.join('|');
		collector_input.val( current_value );
	}
});

jQuery(document).on( 'click', '.hcj2-all-checker', function(event)
{
	var thisLink = jQuery( this );
	var firstFound = false;
	var whatSet = true;

	var moreCollect = thisLink.data('collect');
	if( moreCollect ){
		var myParent = thisLink.closest('.hcj2-collector-wrap');
		if( myParent.length > 0 )
			myParent.first();
		else
			myParent = jQuery('#nts');

		myParent.find("input[name^='" + moreCollect + "']").each( function()
		{
			if( 
				( jQuery(this).attr('type') == 'checkbox' )
				){
				if( ! firstFound ){
					whatSet = ! this.checked;
					firstFound = true;
				}
				// this.checked = whatSet;
				jQuery(this)
					.prop("checked", whatSet)
					.change()
					;
			}
		});
	}

	if(
		( thisLink.prop('tagName').toLowerCase() == 'input' ) &&
		( thisLink.attr('type').toLowerCase() == 'checkbox' )
		){
		return true;
	}
	else {
		return false;
	}
});

/* color picker */
jQuery(document).on('click', 'a.hcj2-color-picker-selector', function(event)
{
	var my_value = jQuery(this).data('color');

	var my_form = jQuery(this).closest('.hcj2-color-picker');
	my_form.find('.hcj2-color-picker-value').val( my_value );
	my_form.find('.hcj2-color-picker-display').css('background-color', my_value);

	/* close collapse */
	return false;
});

/* icon picker */
jQuery(document).on('click', 'a.hcj2-icon-picker-selector', function(event)
{
	var my_value = jQuery(this).data('icon');

	var my_form = jQuery(this).closest('.hcj2-icon-picker');
	my_form.find('.hcj2-icon-picker-value').val( my_value );
	my_form.find('.hcj2-icon-picker-display').html( jQuery(this).html() );

	/* close collapse */
	return false;
});

/* observe forms */
function hc_observe_input( this_input )
{
	// var my_form = this_input.closest('form');
	var my_form = this_input.closest('.hcj2-observe');

	my_form.find('[data-hc-observe]').each( function(){
		var my_this = jQuery(this);
		var whats = my_this.data('hc-observe').toString().split(' ');

		var my_holder = my_this.closest('.hcj2-input-holder');
		if( my_holder.length ){
			my_holder.hide();
		}
		else {
			my_this.hide();
		}

		for( var ii = 0; ii < whats.length; ii++ ){
			var what_parts = whats[ii].split('=');
			var what_param = what_parts[0];
			var what_value = what_parts[1];
// alert( this_input.attr('name') + 'observe: ' + what_param + ' = ' + what_value + '?' );

			var show_this = false;

			var search_name = what_param;
			if( what_param.substring(0,3) != 'hc-' ){
				search_name = 'hc-' + search_name;
			}
			search_name = search_name.replace(':', '\\:');

			var find_this = '[name=' + search_name + ']';
			// alert( find_this );

			trigger_input = my_form.find('[name="' + search_name + '"]');
			if( ! trigger_input ){
				continue;
			}

			if( trigger_input.prop('type') == 'select-one' ){
				trigger_val = trigger_input.val();
			}
			else if( trigger_input.prop('type') == 'radio' ){
				trigger_val = my_form.find('[name=' + search_name + ']:checked').val();
			}
			else if( trigger_input.prop('type') == 'checkbox' ){
				trigger_val = my_form.find('[name=' + search_name + ']:checked').val();
			}
			else {
				trigger_val = trigger_input.val();
			}

// alert( trigger_input.prop('type') + '=' + trigger_val );
// alert( 'search_name = ' + search_name + ', trigger_val = ' + trigger_val + ', what_val = ' + what_value );

			if( what_value == trigger_val ){
				show_this = true;
			}
			else if( what_value == '*' && trigger_val ){
				show_this = true;
			}

			if( show_this ){
				if( my_holder.length ){
					my_holder.show();
					my_this.show();
				}
				else {
					my_this.show();
				}
				break;
			}
		}
		// alert( jQuery(this).data('hc-observe') );
	});
	return false;
}

jQuery(document).on('change', '.hcj2-observe input, select', function(event)
{
	return hc_observe_input( jQuery(this) );
});

function hc2_init_page( where )
{
	if( typeof where !== 'undefined' ){
	}
	else {
		if( jQuery(document.body).find("#nts").length ){
			where = jQuery("#nts");
		}
		else {
			where = jQuery(document.body);
		}
	}

	where.find('.hcj2-observe input, select').each( function(){
		hc_observe_input( jQuery(this) );
	});

	where.find('.hcj2-radio-more-info:checked').each( function(){
		var my_container = jQuery( this ).closest('label');
		var my_info = my_container.find('.hcj2-radio-info');
		my_info.show();
	});

	if( where.find('.hc-datepicker2').length ){
		where.find('.hc-datepicker2').hc_datepicker2({
			})
			.on('changeDate', function(ev)
				{
				var dbDate = 
					ev.date.getFullYear() 
					+ "" + 
					("00" + (ev.date.getMonth()+1) ).substr(-2)
					+ "" + 
					("00" + ev.date.getDate()).substr(-2);

			// remove '_display' from end
				var display_id = jQuery(this).attr('id');
				var display_suffix = '_display';
				var value_id = display_id.substr(0, (display_id.length - display_suffix.length) );

				jQuery(this).closest('form').find('#' + value_id)
					.val(dbDate)
					.trigger('change')
					;
				});
	}
}

jQuery(document).ready( function()
{
	hc2_init_page();

	/* add icon for external links */
	// jQuery('#nts a[target="_blank"]').append( '<i class="fa fa-fw fa-external-link"></i>' );

	jQuery('#nts a[target="_blank"]').each(function(index){
		var my_icon = '<i class="fa fa-fw fa-external-link"></i>';
		var common_link_parent = jQuery(this).closest('.hcj2-common-link-parent');
		if( common_link_parent.length > 0 ){
			// common_link_parent.prepend(my_icon);
		}
		else {
			jQuery(this).append(my_icon);
		}
	});

/*
	jQuery('#nts a[target="_blank"]')
		.attr('style', 'position: relative; overflow: hidden;')
		.append( '<i class="fa fa-fw fa-external-link" style="position: absolute; top: 0; right: 0; border: red 1px solid;"></i>' )
		;
*/
	/* scroll into view */
	if ( typeof nts_no_scroll !== 'undefined' ){
		// no scroll
	}
	else {
		// document.getElementById("nts").scrollIntoView();	
	}

	/* auto dismiss alerts */
	jQuery('.hcj2-auto-dismiss').delay(4000).slideUp(200, function(){
		// jQuery('.hcj2-auto-dismiss .alert').alert('close');
	});
});

jQuery(document).on( 'keypress', '.hcj2-ajax-form input', function(e){
	if( (e.which && e.which == 13) || (e.keyCode && e.keyCode == 13) ){
		var this_form = jQuery(this).closest('.hcj2-ajax-form');
		this_form.trigger('hc2-submit');
		return false;
	}
	else {
		return true;
	}
});

var hc2 = {};

var hc2_spinner = '<span class="hc-m0 hc-p0 hc-fs5 hc-spin hc-inline-block">&#9788;</span>';
var hc2_absolute_spinner = '<div class="hc-fs5 hc-spin hc-inline-block hc-m0 hc-p0" style="position: absolute; top: 45%;"><span class="hc-m0 hc-p0">&#9788;</span></div>';
var hc2_full_spinner = '<div class="hcj2-full-spinner hc-bg-silver hc-muted-2 hc-align-center" style="z-index: 1000; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">' + hc2_absolute_spinner + '</div>';

jQuery(document).on( 'click', '.hcj2-action-trigger', function(event)
{
	var receiver = jQuery(this).closest('.hcj2-action-receiver');
	receiver.trigger( 'receive', jQuery(this).data() );
	return false;
});


jQuery(document).on( 'click', '.hcj2-ajax-loader', function(event)
{
	var me = jQuery(this);
	var ajax_url = me.attr('href');
	if( ! ajax_url ){
		return false;
	}

	var my_parent = me.closest('.hcj2-ajax-parent');
	if( my_parent.length ){
		var target_container = my_parent.find('.hcj2-ajax-container').filter(":first");
	}
	else {
		var target_container = me.closest('.hcj2-ajax-container');
	}

	if( target_container.length ){
		// already loaded? then close
		var current_url = target_container.data('src');
		if( current_url == ajax_url ){
			if( target_container.is(':visible') ){
				// target_container.data('src', '');
				// target_container.html('');
				target_container.hide();
			}
			else {
				target_container.show();
			}
		}
		else {
			hc2_ajax_load( ajax_url, target_container );
			target_container.data('src', ajax_url);
		}

		return false;
	}
});

jQuery(document).on( 'click', '.hcj2-ajax-form:not(.hcj2-custom-handled) input[type="submit"]', function(event)
{
	/* stop form from submitting normally */
	event.preventDefault(); 
	/* get some values from elements on the page: */
	var this_form = jQuery(this).closest('.hcj2-ajax-form');
	var this_form_data = this_form.find('select, textarea, input').serializeArray();

	var target_container = jQuery(this).closest('.hcj2-ajax-container');
	if( target_container.length ){
		var this_referer = target_container.data('src');
		this_form_data.push( {name: "hc-referrer", value: this_referer} );
	}

	var current_html = this_form.html();
	this_form.prepend( hc2_full_spinner );

	var target_url = this_form.attr('action');

	jQuery.ajax({
		type: 'POST',
		url: target_url,
//		dataType: "json",
		dataType: "text",
		data: this_form_data,
		success: function(data, textStatus){
			var is_json = true;
			try {
				var json_data = jQuery.parseJSON( data );
			}
			catch( err ){
				is_json = false;
			}

			if( is_json ){
				this_form.trigger('hc2-json-received', json_data );
				this_form.find('.hcj2-full-spinner').remove();
				// this_form.html( current_html );
			}
			else {
			// html returned
				if( target_container.length ){
					target_container.html(data);
				}
				else {
					this_form.html(data);
				}
			}
		}
		})
		.fail( function(jqXHR, textStatus, errorThrown){
			alert( 'Ajax Error: ' + target_url );
			alert( jqXHR.responseText );
			this_form.html( current_html );
			})
		;
	return false;
});

function hc2_ajax_load( ajax_url, target_container )
{
	target_container.prepend( hc2_full_spinner );
	target_container.load( ajax_url, function(){
		hc2_init_page( target_container );
		target_container.data('src', ajax_url);
	});
}

/*
template engine
from https://github.com/jasonmoo/t.js

Simple interpolation: {{=value}}
Scrubbed interpolation: {{%unsafe_value}}
Name-spaced variables: {{=User.address.city}}
If/else blocks: {{value}} <<markup>> {{:value}} <<alternate markup>> {{/value}}
If not blocks: {{!value}} <<markup>> {{/!value}}
Object/Array iteration: {{@object_value}} {{=_key}}:{{=_val}} {{/@object_value}}
*/

(function() {
	var blockregex = /\{\{(([@!]?)(.+?))\}\}(([\s\S]+?)(\{\{:\1\}\}([\s\S]+?))?)\{\{\/\1\}\}/g,
		valregex = /\{\{([=%])(.+?)\}\}/g;

	function Hc2Template(template) {
		this.Hc2Template = template;
	}

	function scrub(val) {
		return new Option(val).innerHTML.replace(/"/g,"&quot;");
	}

	function get_value(vars, key) {
		var parts = key.split('.');
		while (parts.length) {
			if (!(parts[0] in vars)) {
				return false;
			}
			vars = vars[parts.shift()];
		}
		return vars;
	}

	function render(fragment, vars) {
		return fragment
			.replace(blockregex, function(_, __, meta, key, inner, if_true, has_else, if_false) {

				var val = get_value(vars,key), temp = "", i;

				if (!val) {

					// handle if not
					if (meta == '!') {
						return render(inner, vars);
					}
					// check for else
					if (has_else) {
						return render(if_false, vars);
					}

					return "";
				}

				// regular if
				if (!meta) {
					return render(if_true, vars);
				}

				// process array/obj iteration
				if (meta == '@') {
					// store any previous vars
					// reuse existing vars
					_ = vars._key;
					__ = vars._val;
					for (i in val) {
						if (val.hasOwnProperty(i)) {
							vars._key = i;
							vars._val = val[i];
							temp += render(inner, vars);
						}
					}
					vars._key = _;
					vars._val = __;
					return temp;
				}

			})
			.replace(valregex, function(_, meta, key) {
				var val = get_value(vars,key);

				if (val || val === 0) {
					return meta == '%' ? scrub(val) : val;
				}
				return "";
			});
	}

	Hc2Template.prototype.render = function (vars) {
		return render(this.Hc2Template, vars);
	};

	window.Hc2Template = Hc2Template;
})();

function hc2_widget_set_value( obj, value )
{
	obj.data('value', value);
	obj.trigger('change');
}
function hc2_widget_value( obj )
{
	return obj.data('value');
}

function hc2_print_r( thing )
{
	var out = '';
	for( var i in thing ){
		if( typeof thing[i] == 'object' ){
			out += i + ": ";
			for( var j in thing[i] ){
				out += j + ": " + thing[i][j] + ";\n";
			}
			out += "\n";
		}
		else {
			out += i + ": " + thing[i] + "\n";
		}
	}
	alert(out);	
}

function hc2_make_hca( params )
{
	var out = '';
	out += params.slug;

	if( params.params ){
		var params_string = [];
		for( var k in params.params ){
			var this_param = params.params[k];
			if( typeof this_param != 'string' ){
				this_param = this_param.join('|');
			}
			params_string.push( k + '/' + this_param );
		}
		out += ':' + params_string.join('/');
	}
	return out;
}

function hc2_parse_hca( url )
{
	my_return = {
		slug: '',
		params: {}
	}

	var newAdditionalURL = "";

	var tempArray = url.split("?");
	var baseURL = tempArray[0];
	var additionalURL = tempArray[1];
	var hca_param = 'hca';
	var hca = '';

	var temp = "";
	if( additionalURL ){
		tempArray = additionalURL.split("&");
		for( var i = 0; i < tempArray.length; i++ ){
			var tempArray2 = tempArray[i].split('=');
			if( tempArray2[0] == hca_param ){
				hca = tempArray2[1];
				break;
			}
		}
	}

	// hca = 'items/ajax:one/two/three/1|2';

	if( hca ){
		var tempArray3 = hca.split(':');
		my_return.slug = tempArray3[0];
		if( tempArray3[1] ){
			var params = {};

			var tempArray4 = tempArray3[1].split('/');
			for( var ii = 0; ii < tempArray4.length; ii+=2 ){
				var this_var = tempArray4[ii+1];

				if( this_var.indexOf('|') > 0 ){
					this_var = this_var.split('|');
				}
				else {
					var this_var = String(this_var);
				}
				params[ tempArray4[ii] ] = this_var;
			}
			my_return.params = params;
		}
	}
	return my_return;
}

function hc2_update_url_parameter( url, param, paramVal )
{
	var newAdditionalURL = "";
	var tempArray = url.split("?");
	var baseURL = tempArray[0];
	var additionalURL = tempArray[1];
	var temp = "";
	if (additionalURL) {
		tempArray = additionalURL.split("&");
		for (i=0; i<tempArray.length; i++){
			if(tempArray[i].split('=')[0] != param){
				newAdditionalURL += temp + tempArray[i];
				temp = "&";
			}
		}
	}
	var rows_txt = temp + "" + param + "=" + paramVal;
	return baseURL + "?" + newAdditionalURL + rows_txt;
}

// various php functions in javascript taken from locutus.io
function hc2_php_number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
	var n = !isFinite(+number) ? 0 : +number
	var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
	var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
	var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
	var s = ''

	var toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec)
		return '' + (Math.round(n * k) / k)
		.toFixed(prec)
	}

	// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || ''
		s[1] += new Array(prec - s[1].length + 1).join('0')
	}
	return s.join(dec)
}

function hc2_submit_form( target_url, form_data, this_form )
{
	jQuery.ajax({
		type: 'POST',
		url: target_url,
		dataType: "text",
		data: form_data,
		success: function(data, textStatus){
			var is_json = true;
			try {
				var json_data = jQuery.parseJSON( data );
			}
			catch( err ){
				is_json = false;
			}

			if( is_json ){
				this_form.trigger('hc2-json-received', json_data );
				this_form.find('.hcj2-full-spinner').remove();
			}
			else {
			// html returned
				this_form.html(data);
			}
		}
		})
		.fail( function(jqXHR, textStatus, errorThrown){
			var error_msg = 'Ajax error: ' + target_url + ', ' + jqXHR.responseText + '<br/>';
			this_form.find('.hcj2-full-spinner').remove();
			this_form.prepend( error_msg );
			})
		;
}

function Hc2LinkedSelects( select_options, inputs, labels, defaults )
{
	this.select_options = select_options;
	this.inputs = inputs;
	this.labels = labels;
	this.defaults = defaults;

	var self = this;

	this.input_hide = function( input )
	{
		input.html('');

		var holder = input.closest('.hcj2-input-holder');
		if( holder.length ){
			holder.hide();
			input.hide();
		}
		else {
			input.hide();
		}
	}

	this.input_set_default = function( input_index, default_value )
	{
		var this_input = self.inputs[input_index];

		var exists = false;
		this_input.find('option').each( function(){
			if( this.value.toLowerCase() == default_value.toLowerCase() ){
				default_value = this.value;
				exists = true;
				return false;
			}
		});

		if( exists ){
			this_input.val( default_value );
			this_input.trigger('change');
		}
	}

	this.input_show = function( input_index )
	{
		var input = self.inputs[input_index];

		var holder = input.closest('.hcj2-input-holder');
		if( holder.length ){
			holder.show();
			input.show();
		}
		else {
			input.show();
		}
	}

	this.run = function( me_auto_submit )
	{
		if (me_auto_submit === undefined ){
			me_auto_submit = true;
		}

	// init
		for( var ii = 0; ii < self.inputs.length; ii++ ){
			self.input_hide( self.inputs[ii] );
		}

		var me_visible = false;
		for( var ii = 0; ii < self.inputs.length; ii++ ){
			var this_visible = self.inputs[ii].is(':visible');
			if( this_visible ){
				me_visible = true;
				break;
			}
		}

		var rex_ii = -1;
		var options_for_first = [];
		var html_for_first = '';

		while( options_for_first.length < 2 ){
			options_for_first = [];
			rex_ii++;
			for( var jj = 0; jj < self.select_options.length; jj++ ){
				if( ! self.select_options[jj][rex_ii].length ){
					continue;
				}
				if( jQuery.inArray(self.select_options[jj][rex_ii], options_for_first) >= 0 ){
					continue;
				}
				options_for_first.push( self.select_options[jj][rex_ii] );
			}
		}

		html_for_first = jQuery.map( options_for_first, function(opt){
			return '<option value="' + opt + '">' + opt + '</option>'
		}).join('');
		if( options_for_first.length > 1 ){
			// html_for_first = '<option value="-none-">' + ' - ' + self.labels[rex_ii] + ' - ' + '</option>' + html_for_first;
			html_for_first = '<option value="">' + ' - ' + self.labels[rex_ii] + ' - ' + '</option>' + html_for_first;
		}

		for( var ii = 0; ii < self.inputs.length; ii++ ){
			self.inputs[ii].change( function(){
				var total_count = self.inputs.length;
				var my_ii = jQuery(this).data('linked-index') - 1;

				var my_val = jQuery(this).val();
				var my_options_count = jQuery(this).find('option').length;
// alert( 'my options = ' + my_options_count );

			// if the last one then submit form
				if( my_ii >= (total_count - 1) ){
					var submit_this = false;
					if( jQuery(this).is(':visible') ){
						if( my_val.length ){
							submit_this = true;
						}
					}
					else {
						submit_this = true;
					}

					if( submit_this ){
						var this_form = jQuery(this).closest('.hcj2-ajax-form');
						this_form.trigger('hc2-submit');
					}

					return true;
				}

				var check_vals = [];
				for( var kk = 0; kk < my_ii; kk++ ){
					check_vals.push( self.inputs[kk].val() );
				}
				check_vals.push( my_val );

			// hide others
				for( var kk = my_ii+2; kk < self.inputs.length; kk++ ){
					self.input_hide( self.inputs[kk] );
				}

			// options for the very next
				// if( my_val != '-none-' ){
				if( (! my_options_count) || ((my_val !== null) && my_val.length) ){
					options_for_next = [];
					for( var jj = 0; jj < self.select_options.length; jj++ ){
						// alert( 'compare ' + self.select_options[jj][my_ii-1] + ' vs ' + my_val );

					// check if this one is ok
						// if( self.select_options[jj][my_ii] != my_val ){
							// continue;
						// }
						this_one_ok = true;
						for( var kk = 0; kk < check_vals.length; kk++ ){
							if( check_vals[kk] == null ){
								continue;
							}

// alert( 'compare ' + self.select_options[jj][kk] + ' vs ' + check_vals[kk] );
							if( self.select_options[jj][kk] != check_vals[kk] ){
								this_one_ok = false;
								break;
							}
						}

						if( ! this_one_ok ){
							continue;
						}

						var this_next_option = self.select_options[jj][my_ii+1];
						if( ! this_next_option.length ){
							continue;
						}

						if( jQuery.inArray(this_next_option, options_for_next) >= 0 ){
							continue;
						}
						options_for_next.push( this_next_option );
					}

					if( options_for_next.length ){
						html_for_next = jQuery.map( options_for_next, function(opt){
							return '<option value="' + opt + '">' + opt + '</option>'
						}).join('');

						if( options_for_next.length > 1 ){
							// html_for_next = '<option value="-none-">' + ' - ' + self.labels[my_ii+1] + ' - ' + '</option>' + html_for_next;
							html_for_next = '<option value="">' + ' - ' + self.labels[my_ii+1] + ' - ' + '</option>' + html_for_next;
						}

					// next input
						self.input_show( my_ii+1 );
						self.inputs[my_ii+1].html( html_for_next );
						self.input_set_default( my_ii+1, self.defaults[my_ii+1] );

						if( options_for_next.length <= 1 ){
							self.inputs[my_ii+1].val( options_for_next[0] );
							self.inputs[my_ii+1].trigger('change');
						}
					}
					else {
						// not the last one
						if( (my_ii+1) <= (total_count - 1) ){
// alert( 'WILL TRIGGER ' + (my_ii+1) );
							// self.input_show( self.inputs[my_ii+1] );
							self.inputs[my_ii+1].trigger('change');
						}
						else {
							self.input_hide( self.inputs[my_ii+1] );
							var this_form = jQuery(this).closest('.hcj2-ajax-form');
							this_form.trigger('hc2-submit');
						}
					}
				}
				else {
					self.input_hide( self.inputs[my_ii+1] );
				}
			});
		}

		self.input_show( rex_ii );
		self.inputs[rex_ii].html( html_for_first );
		self.input_set_default( rex_ii, self.defaults[rex_ii] );

	// if autosubmit 
		if( me_auto_submit ){
			// if the last one set
			// var last_ii = self.inputs.length - 1;
			// alert( self.inputs[last_ii].val() );
			// alert( self.inputs.length - 1 );
			// alert( self.inputs[ self.inputs.length - 1 ].val().length );
		}

		var options_for_next = [];
		var html_for_next = '';
		var this_one_ok = false;
	}

	this.submit = function()
	{
	// if the last one set
		var ii = self.inputs.length - 1;

		var last_input = self.inputs[ii];
		while( (last_input.val() === null) && (ii >= 1) ){
			ii--;
			last_input = self.inputs[ii];
		}

		if( (last_input.val() !== null) && last_input.val().length ){
			var this_form = last_input.closest('.hcj2-ajax-form');
			this_form.trigger('hc2-submit');
		}
	}
}
