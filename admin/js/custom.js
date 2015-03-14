jQuery(document).ready(function(e) {
    
	var tooltips = jQuery( ".d_val[title]" ).tooltip();
	
	jQuery(function() {
		jQuery( "#tabs" ).tabs();
	});
	
	jQuery(".success").fadeOut(3000);
	
	jQuery(".choosen-select").chosen();		

});

function update_img(img_id, img_src){
	jQuery(document).ready(function(e) {
		jQuery('#'+img_id).attr('src', img_src);
		return false;
	});	
}


function install_template(url){
	
	if(url==''){
		alert('No template is selected.');
	}else{
		
		jQuery.ajax({
			type:	'POST',
			cache:	false,
			url:	Template_Ajax.ajaxurl,
			data:'action=ajax-installsinputtitleSubmit&nextNonce='+Template_Ajax.nextNonce+'&url='+url,
			beforeSend:function(){
				jQuery("#loading_id").addClass('dvao_loading');
			},
			success:function(ifr){
				if(ifr=='failed'){
					alert('Installation Failed.');
				}else{
					jQuery('.dv_admin_options form').append('<div class="success">Template Installed !</div>');
					jQuery('#loading_id').removeClass('dvao_loading');
					jQuery('.dv_admin_options form .success').fadeOut(5000);
				}
			}
			
		});	
		
		
	}
	
}

function subscribe_to_nl(){
	
	var name	=	jQuery('#dv_subs_name').val();
	var email	=	jQuery('#dv_subs_email').val();
	
	if(email==''){
		alert('Both fields are required.');
	}else{
		
		jQuery.ajax({
			type:	'POST',
			cache:	false,
			url:	Template_Ajax.ajaxurl,
			data:'action=ajax-installsinputtitleSubmit&nextNonce='+Template_Ajax.nextNonce+'&email='+email+'&name='+name,
			beforeSend:function(){
				jQuery("#loading_id").addClass('dvao_loading');
			},
			success:function(ifr){
				if(ifr=='failed'){
					alert('Installation Failed.');
				}else{
					
					if(ifr=='yes'){
						jQuery('.dv_admin_options form').append('<div class="success">Successfully Subscribe!</div>');
						jQuery('#loading_id').removeClass('dvao_loading');
						jQuery('.dv_admin_options form .success').fadeOut(5000);
					}else if(ifr=='no'){
						alert('Subscription Failed..');
					}else if(ifr=='ok'){
						jQuery('.dv_admin_options form').append('<div class="success">Template Installed !</div>');
						jQuery('#loading_id').removeClass('dvao_loading');
						jQuery('.dv_admin_options form .success').fadeOut(5000);
					}
					
				}
			}
			
		});	
		
		
	}
	
}
