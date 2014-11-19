$(document).ready(function() {

$('a#dlconfig').attr({target: '_blank', 
                    href  : '../Portal/exportdb.php'});
					
$('.showhidebutton').click(function(e){
	if($("#info > .scrollContainer").css('display') == 'none') {
		$("#info > .scrollContainer").show( "slow" );
	} else {
		$("#info > .scrollContainer").hide( "slow" );
	}
});

  	$(function(){
		document.oncontextmenu = function() {return false;};
	});

	$(".chosen-select").chosen({
		width: "100%"
		//placeholder_text_multiple: "Allow Overrides"
		});
	blinkFont();
});

function blinkFont()
{
  document.getElementById("blink").style.color="#ff9522";
  setTimeout("setblinkFont()",1000);
}
function setblinkFont()
{
  document.getElementById("blink").style.color="";
  setTimeout("blinkFont()",1000);
}

$("select.multiple").change(function(){
    var theid = $(this).attr("id");
    var selMulti = $.map($("select#"+theid+" option:selected"), function (el, i) {
		return $(el).val();
    });
     $("."+theid).val(selMulti.join(","));
});

function updateSettings(section) {
	var contents = document.getElementById(section).getElementsByTagName('input'); //$("#result").html(contents);
	var origparams = 'section=' + section;
	var params = origparams;
	var addonnum = 0;
	for (i = 0; i < contents.length; i++) { //alert(contents[i].name+'='+contents[i].value);
		var value = contents[i].value;
		if (contents[i].type == 'checkbox') {
			if (contents[i].value == 'on') {
				value = 'true';
			} else {
				value = 'false';
			}
			params = params + '&' + contents[i].name + '=' + value;
		} else if (contents[i].type == 'radio') {
			var name = contents[i].name;
			while (contents[i].type == 'radio') {
				if (contents[i].checked && contents[i].name == name) { //alert(contents[i].name+' '+contents[i].value);
					value = contents[i].value;
					params = params + '&' + contents[i].name + '=' + encodeURIComponent(value);
				}
				i++;
			}
			i--;
		} else if (contents[i].name != '') {
			//alert(contents[i].name);
			if(contents[i].name == 'roomid') { addonnum++; }
			if(addonnum > 1) { 
				ajaxRequest(params,section);
				addonnum = 1;
				params = origparams;
			}
			params = params + '&' + contents[i].name + '=' + encodeURIComponent(value);
		}
	}

	var contents = document.getElementById(section).getElementsByTagName('select');
	for (i = 0; i < contents.length; i++) {
		if (contents[i].name != '') {
			var thecontents = '&' + contents[i].name + '=' + escape(contents[i++].value);
			//alert(thecontents);
			params += thecontents;
		}
	}	

	ajaxRequest(params,section);
}
function ajaxRequest(params,section){
//alert(params);
	$.ajax({
		type: 'GET',
		url: "settings.php?" + params,
		success: function(data) { // successful request; do something with the data
			if(data == 1){
			  $.pnotify({
						pnotify_title: 'Settings Saved for ' + section,
						pnotify_opacity: .5
					});
				var thissection = section.substring(0, 5);
				var thissection2 = section.substring(0, 11);	
				if(section != 'rooms-new' && thissection == 'rooms' && thissection2 != 'roomsaddons') {
					var newsection = section.split('-');
					var thissection = "roomsaddons-" + newsection[1];
					updateSettings(thissection);
				} else {
					setTimeout(function(){
						window.location.reload(true);
						}, 1500);
				}
			} else {
			  $.pnotify({
				  pnotify_title: 'Error!',
				  pnotify_text: data,
				  pnotify_type: 'error',
			  });
			}
		},
		error: function() { // failed request; give feedback to user
			alert("Error saving settings.");
		}
	});
}
function deleteRecord(tablename,rowid,navgroup){
navgroup = (navgroup === undefined) ? 0 : navgroup;
if(navgroup == 0) {
var r=confirm("Are you sure you want to remove this entry?");
} else {
var r=confirm("Are you sure you want to remove this Navigation Group?  This will remove all the entries in this group as well as the group.");
}
if (r==true)
  {
	$.ajax({
		type: 'GET',
		url: "settings.php?remove=yes&table=" + tablename + "&rowid=" + rowid + "&navgroup=" +navgroup,
		success: function(data) { // successful request; do something with the data
			if(data == 1){
			  $.pnotify({
						pnotify_title: 'Entry Deleted',
						pnotify_opacity: .5
					});
				setTimeout(function(){
				window.location.reload(true);
				}, 1500);
			} else {
			  $.pnotify({
				  pnotify_title: 'Error!',
				  pnotify_text: data,
				  pnotify_type: 'error',
			  });
			}
		},
		error: function() { // failed request; give feedback to user
			alert("Error saving settings.");
		}
	});
  } else {

  }
}
function updateVersion(){
	$.ajax({
		type: 'GET',
		url: "update.php?update=true",
		success: function(data) { // successful request; do something with the data
			if(data == 1){
        $.pnotify({
  	      pnotify_title: 'Updating version',
          pnotify_text: 'This will only update your COMMIT number to the latest one so you can be notified of new updates in the settings page. You will have to update MFP manually by downloading from Git to actually get the features.',
          pnotify_opacity: .9,
          pnotify_delay: 10000
	});
			} else {
			  $.pnotify({
				  pnotify_title: 'Error!',
				  pnotify_text: data,
				  pnotify_type: 'error'
			  });

			}
		},
		error: function() { // failed request; give feedback to user
			alert("Error saving settings.");
		}
	});
}

function addonselect (theroom) {
			$("#addons"+theroom).trigger("chosen:updated");
			/*
			var theaddons = [];
			$("#rooms-"+theroom+" ul.chosen-choices").each(function() {
				var addons = '';
				$(this).find('li.search-choice > span').each(function(){
					var current = $(this);
					if(current.children().size() > 0) {return true;}
					addons += $(this).text();
					addons += ",,";
				});
				theaddons.push(addons);


			});
			$.pnotify({
				  pnotify_title: 'Sent',
				  pnotify_text: theaddons +" - "+ theroom,
				  pnotify_type: 'alert',
				  pnotify_size: 'large'
			});*/
}