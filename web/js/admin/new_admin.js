jQuery.extend(jQuery, {
	postMessage: function(severity, message) {
		var highlight = severity == 'info' ? 'highlight' : 'error';
		var display = jQuery('<div class="ui-widget ui-notify"><div class="ui-state-'+highlight+' ui-corner-all"><div><span class="ui-icon ui-icon-'+severity+'" /><span class="message"></span></div></div></div>').hide().appendTo("#admin_message");
		var message_container = display.find('.message');
		if(message.constructor === String) {
			message_container.text(message);
		} else {
			message_container.append(message);
		}
		display.show('blind');
		window.setTimeout(function() {
			display.hide('blind', function() {display.remove();});
		}, 10000);
	}
});

jQuery.fn.extend({
	tooltip: function(text) {
		var tooltip = this.data('tooltip-element');
		if(!tooltip) {
			tooltip = jQuery('<div/>').text(text).addClass('tooltip ui-widget ui-widget-content ui-corner-all').appendTo(document.body);
			this.data('tooltip-element', tooltip);
		}
		this.hover(function(event) {
			tooltip.show();
			tooltip.css({left: (event.pageX+3)+"px", top: (event.pageY+3)+"px"});
		}, function() {
			tooltip.hide();
		}).mousemove(function(event) {
			tooltip.css({left: (event.pageX+3)+"px", top: (event.pageY+3)+"px"});
		});
	},
});

jQuery('.cmos-button:not(.ui-state-disabled), .cmos-clickable').live("mouseover", function() {
	jQuery(this).addClass('ui-state-hover');
}).live("mouseout", function() {
	jQuery(this).removeClass('ui-state-hover');
}).live("mousedown", function() {
	jQuery(this).parents('.fg-buttonset-single:first').find(".fg-button.ui-state-active").removeClass("ui-state-active");
	if(jQuery(this).is('.ui-state-active.fg-button-toggleable, .fg-buttonset-multi .ui-state-active')){
		jQuery(this).removeClass("ui-state-active");
	}
	else {
		jQuery(this).addClass("ui-state-active");
	}
}).live('mouseup', function() {
	if(!jQuery(this).is('.fg-button-toggleable, .fg-buttonset-single .fg-button, .fg-buttonset-multi .fg-button')) {
		jQuery(this).removeClass("ui-state-active");
	}
});

jQuery.extend(Widget, {
	notifyUser: jQuery.postMessage,
	
	tooltip: function(element, text) {
		jQuery(element).tooltip(text);
	},
	
	confirm: function(title, message, callback, cancelButtonText, okButtonText) {
		if(cancelButtonText === undefined) {
			cancelButtonText = AdminInterface.translations.cancelButtonText;
		}
		if(okButtonText === undefined) {
			okButtonText = AdminInterface.translations.okButtonText;
		}
		var dialog = jQuery('<div class="cmos_alert"><p><span class="ui-icon ui-icon-alert"></span><span class="text"></span></p></div>').attr('title', title).find('.ui-icon').css('float', 'left').end().find('.text').text(message).end();
		var destroy = function(result) {
			callback(!!result)
			dialog.dialog('destroy').remove();
		};
		var dialog_opts = {
			resizable: false,
			height: 140,
			modal: true,
			buttons: {},
			close: destroy.bind(dialog, false)
		};
		dialog_opts.buttons[okButtonText] = destroy.bind(dialog, true);
		dialog_opts.buttons[cancelButtonText] = destroy.bind(dialog, false);
		dialog.dialog(dialog_opts);
	},
	
	load: function() {
		window.AdminInterface.loader.data('load-count', window.AdminInterface.loader.data('load-count')+1).show();
	},
	
	end_load: function() {
		window.AdminInterface.loader.data('load-count', window.AdminInterface.loader.data('load-count')-1);
		if(window.AdminInterface.loader.data('load-count') <= 0) {
			window.AdminInterface.loader.hide();
		}
	},
	
	activity: function() {
		if(Widget.singletons.admin_menu !== undefined) {
			Widget.singletons.admin_menu.activity();
		}
	}, 
	
	end_activity: function() {
		if(Widget.singletons.admin_menu !== undefined) {
			Widget.singletons.admin_menu.end_activity();
		}
	}
	
});
