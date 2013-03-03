$(document).ready(function(){
	$('a.view_grades').click(function(e) {
		// prevent the default action when a nav button link is clicked
		e.preventDefault();
		$('#loading').show();
		$('#content').hide();
		// ajax query to retrieve the HTML view without refreshing the page.
		$('#content').load($(this).attr('href'), function () {
			$('#loading').hide();
			$('#content').show();
			scrollToPageHeader();
		});
	});
	
	$(".studentinfocell").change(function() {
		var callback = site_url + 'updatestatistics/updateStudentInfo';
		var changed_cell = $(this);
		
		$.ajax({
			type: 'post',
			url: callback,
			data: {
				personid: $(this).attr('id'),
				changedfield_name: $(this).attr("data-changedfieldname"),
				changedfield_value: $(this).val()
			},
			dataType: 'html',
			success: function (retVal) {
				if (retVal == 'true') {
					$(changed_cell).css('background-color','#AAFFCC').css("color","#555555");
					$(changed_cell).prop("title", null);
					setTimeout(function() { $(changed_cell).css("background-color","white"); }, 250);
				} else {
					// alert(retVal);
					$(changed_cell).prop("title", retVal);
					$(changed_cell).css("background-color","#CF0220").css("color","white");
				}
			},
			error: function(){
				alert("Call to database failed.");
				$(changed_cell).css("background-color","#CF0220").css("color","white");
			}
		});
	});
	
    $.tablesorter.addParser({
        id: 'input',
        is: function(s) {
            return false;
        },
        format: function(s, table, cell) {
			return $('input', cell).val();
		},
        type: 'text'
    });

    $("#students").tablesorter({
        headers: {
            0: {sorter:'input'},
			1: {sorter:'input'},
			2: {sorter:'input'},
			3: {sorter:'input'},
			4: {sorter:'input'},
			5: {sorter:false},
        },
		theme : "bootstrap",
		widthFixed: true,
		headerTemplate : '{content} {icon}',
		widgets : [ "uitheme" ]
	});
});

$(function() {
  $.extend($.tablesorter.themes.bootstrap, {
    // these classes are added to the table. To see other table classes available,
    // look here: http://twitter.github.com/bootstrap/base-css.html#tables
    table      : 'table table-bordered',
    header     : 'bootstrap-header', // give the header a gradient background
    footerRow  : '',
    footerCells: '',
    icons      : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
    sortNone   : 'bootstrap-icon-unsorted',
    sortAsc    : 'icon-chevron-up',
    sortDesc   : 'icon-chevron-down',
    active     : '', // applied when column is sorted
    hover      : '', // use custom css here - bootstrap class may not override it
    filterRow  : '', // filter row class
    even       : '', // odd row zebra striping
    odd        : ''  // even row zebra striping
  });
});