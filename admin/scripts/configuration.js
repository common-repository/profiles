var loader = jQuery("#profiles-title > span");
var info = jQuery("#profiles_info");
var error = jQuery("#profiles_error");
jQuery(document).ready(function(){
	console.log("Hello World!");
	
	jQuery(".profiles-deletecat-button").live("click",function(){deletecat_dialog(this);return false;});
	jQuery("#add_newcat").live("click",function(){newcat_dialog();return false;});
	
});

function handleAJAX(data, textStatus) {
	if(data.message.info != undefined) {
		info.html(data.message.info.replace('\n',"<br />"));
		console.info(data.message.info);
		info.slideDown('slow');
	}
	if(data.message.error != undefined) {
		error.html(data.message.error.replace('\n',"<br />"))
		console.warn(data.message.error);
		error.slideDown('slow');
	}
}

function reloadContent() {
	loader.show();
	var form = jQuery("#profiles-content-reload-form");
	var data = new Object();
	form.children('input').each(function() {
		data[jQuery(this).attr('name')] = jQuery(this).attr('value');
	});
	data.ajax = true;	
	jQuery.post(profiles_siteurl+"/wp-admin/admin-ajax.php",data,function(data){
		jQuery("#profiles-content-holder").fadeOut('slow').html(data).fadeIn('slow');
		loader.hide();
	});
}

function cleanScreen() {
	info.slideUp('fast');
	error.slideUp('fast');
}

function newcat_dialog() {
	cleanScreen();
	jQuery("#profiles-newcat-dialog").dialog({
		resizable: false,
		height: 200,
		modal: true,
		overlay: {
			backgroundColor: '#123456',
			opacity: 0.5
		},
		buttons: {
			"Add Category" : function() {
				loader.show();
				var form = jQuery("#profiles-newcat-form");
				var data = new Object();
				form.children('input').each(function() {
					data[jQuery(this).attr('name')] = jQuery(this).attr('value');
				});
				data.ajax = true;
				console.log("Added a Category '" + data.category_name + "'");
				jQuery(this).dialog('close');
				jQuery.post(profiles_siteurl+"/wp-admin/admin-ajax.php",data,function(data,textStatus){handleAJAX(data,textStatus);loader.hide();reloadContent();},"json");
			},
			"Cancel" : function() {
				jQuery(this).dialog('close');
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
	return false;
}

function deletecat_dialog(me) {
	
	cleanScreen();
	
	// Setup vars
	var form = jQuery(me).parent();
	var name = form.find("input[name=catname]").attr('value');
	
	// Create the messages
	var dialog = jQuery("#profiles-deletecat-confirm");
	dialog.attr('title','Delete ' + name + '?');
	var message = dialog.find("p");
	var messageholder = message.text();
	message.text(message.text().replace('$',name));
	
	dialog.dialog({
		resizable: false,
		height:200,
		modal: true,
		overlay: {
			backgroundColor: '#123456',
			opacity: 0.4
		},
		buttons: {
			"Delete $": function() {
				loader.show();
				console.log('Deleting ' + name);
				var data = new Object();
				form.children('input').each(function() {
					data[jQuery(this).attr('name')] = jQuery(this).attr('value');
				});
				data.ajax = true;
				jQuery(this).dialog('close');
				jQuery.post(profiles_siteurl+"/wp-admin/admin-ajax.php",data,function(data,textStatus){handleAJAX(data,textStatus);loader.hide();reloadContent();},"json");
			},
			'Cancel': function() {
				jQuery(this).dialog('close');
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
			message.text(messageholder);
		}
	});
	//jQuery(this).parent().attr('name','myform');
	return false;
}