var smar_old_btn_val = smartlocal.hidebutton;

function smar_strpos (haystack, needle, offset) {
  var i = (haystack+'').indexOf(needle, (offset || 0));
  return i === -1 ? false : i;
}

function smar_ucfirst(str) {
    var firstLetter = str.slice(0,1);
    return firstLetter.toUpperCase() + str.substring(1);
}
/* @test remove  does it still work
function smar_del_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}
*/
function smar_jump_to() {
    jQuery(document).ready(function(){
        window.location.hash="smar_respond_1";
    });
}
/* validate required fields */
function valsmarform_2(newid,oldid,err) {
    
    var myval = '';
	
	if (smartlocal.req_name == 'true') {
		if (newid === 'fname' && jQuery("#"+oldid).val() == "") {
			err.push(smartlocal.name);
		}
	}
	if (smartlocal.req_email == 'true') {
		if (newid === 'femail' && jQuery("#"+oldid).val() !== "") {
			myval = jQuery("#"+oldid).val();
			if (/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(myval) == false) {
				err.push(smartlocal.email);
			}
		}
		
		if (newid === 'femail' && jQuery("#"+oldid).val() == "") {
			err.push(smartlocal.email_empty);
		}

	}
	if (smartlocal.req_website == 'true') {
		if (newid === 'fwebsite' && jQuery("#"+oldid).val() == "") {
			err.push(smartlocal.website);
		}
	}
	if (smartlocal.req_title == 'true') {
		if (newid === 'ftitle' && jQuery("#"+oldid).val() == "") {
			err.push(smartlocal.title);
		}
	}
    if (newid === "ftext" && jQuery("#"+oldid).val().length < 5) {
        err.push(smartlocal.review);
    }
    if (newid === "fconfirm2" && jQuery("#fconfirm2").is(":checked") === false) {
        err.push(smartlocal.human);
    }
    if (newid === "fconfirm1" && jQuery("#fconfirm1").is(":checked") ) {
        err.push(smartlocal.human+" "+smartlocal.code2);
    }
    if (newid === "fconfirm3" && jQuery("#fconfirm3").is(":checked") ) {
        err.push(smartlocal.human+" "+smartlocal.code3);
    }
    
    return err;
}

function valsmarform() {	
    var frating = parseInt(jQuery("#frating").val(), 10);
    if (!frating) { frating = 0; }
    
    var err = [];
    
    jQuery("#smar_commentform").find('input, textarea').each(function(){
        var oldid = jQuery(this).attr('name');
        var newid = oldid;
        var pos = smar_strpos(oldid,'-',0) + 1;
        if (pos > 1) {
            newid = oldid.substring(pos);
        } else {
            newid = oldid;
        }
        err = valsmarform_2(newid,oldid,err);
    });
    
    if (frating < 1 || frating > 5) {
err.push(smartlocal.rating);
    }
    
    if (err.length) {
        var err2 = err.join("\n");
        alert(err2);
        jQuery("#smar_table_2").find("input:text:visible:first").focus();
        return false;
    }

	var f = jQuery("#smar_commentform");
	var newact = document.location.pathname + document.location.search;
	f.attr("action",newact).removeAttr("onsubmit");
    return true;
}

function smar_set_hover() {
    jQuery("#smar_commentform .smar_rating").unbind("click",smar_set_hover);
    smar_onhover();
}

function smar_onhover() {    
    jQuery("#smar_commentform .smar_rating").unbind("click",smar_set_hover);
    jQuery("#smar_commentform .base").hide();
    jQuery("#smar_commentform .status").show();
}

function smar_showform() {
    jQuery("#smar_respond_2").slideToggle();
    if (smar_old_btn_val == smartlocal.hidebutton) {
        smar_old_btn_val = jQuery("#smar_button_1").html();
        jQuery("#smar_button_1").html(smartlocal.hidebutton);
    } else {
        jQuery("#smar_button_1").html(smar_old_btn_val);
        smar_old_btn_val = smartlocal.hidebutton;
    }
    jQuery("#smar_table_2").find("input:text:visible:first").focus();
}

function smar_init() {
    
    jQuery("#smar_button_1").click(smar_showform);    
    jQuery("#smar_commentform").submit(valsmarform);

    jQuery("#smar_commentform .smar_rating a").click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var smar_rating = jQuery(this).html();
            var new_w = 20 * smar_rating + "%";

            jQuery("#frating").val(smar_rating);
            jQuery("#smar_commentform .base").show();
            jQuery("#smar_commentform .average").css("width",new_w);
            jQuery("#smar_commentform .status").hide();

            jQuery("#smar_commentform .smar_rating").unbind("mouseover",smar_onhover);
            jQuery("#smar_commentform .smar_rating").bind("click",smar_set_hover);
    });

    jQuery("#smar_commentform .smar_rating").bind("mouseover",smar_onhover);
}

jQuery(document).ready(smar_init);