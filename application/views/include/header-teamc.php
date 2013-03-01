<script>
$(document).ready(function(){
	$('#sr').removeClass('active');
	$('#cs').removeClass('active');
	$('#et').removeClass('active');
	$('#us').addClass('active');
	$('#ab').removeClass('active');	

	$('#up').addClass('active');
	$('#ed').removeClass('active');
	$('#bu').removeClass('active');
	$('#re').removeClass('active');
	$('#rs').removeClass('active');	

	$('a.teamcnav').click(function(e) {
		// prevent the default action when a nav button link is clicked
		e.preventDefault();
		
		$actionid = $(this).parent('li').attr('id');
		
		$('#up').removeClass('active');
		$('#ed').removeClass('active');
		$('#bu').removeClass('active');
		$('#re').removeClass('active');
		$('#rs').removeClass('active');	
		
		if($actionid == "up"){
			$('#up').addClass('active');
		}else if($actionid == "ed"){
			$('#ed').addClass('active');
		}else if($actionid == "bu"){
			$('#bu').addClass('active');
		}else if($actionid == "re"){
			$('#re').addClass('active');
		}else if($actionid == "rs"){
			$('#rs').addClass('active');
		}
		// hide page contents and show loading gif
		$('#loading').show();
		$('#content').hide();
		$('#content').load($(this).attr('href'), function () { // called when done loading
			$('#loading').hide();
			$('#content').show();
		});
	});
});
</script>

<div class="span2">
    <div class="well" id="sidebar" style ="
	padding: 0px;
	margin-left: -25%;
	width:150px;
	">
    <ul class="nav nav-list-team-c">
		<li class="nav-header">Update Navigation</li>
		<li id="up"><a class="teamcnav" href="<?= site_url("updatestatistics/upload") ?>">Upload</a></li>
		<li id="ed"><a class="teamcnav" href="<?= site_url("updatestatistics/edit") ?>">Edit</a></li>
		<li id="bu"><a class="teamcnav" href="<?= site_url("updatestatistics/backup") ?>">Backup</a></li>
		<li id="re"><a class="teamcnav" href="<?= site_url("updatestatistics/restore") ?>">Restore</a></li>
		<li id="rs"><a class="teamcnav" href="<?= site_url("updatestatistics/sql") ?>">Run SQL</a></li>
	</ul>
    </div>
</div>

<div id = "container" style="
	width:75%;
	padding-left:30px;
	padding-top:0px;
	padding-bottom:20px;
	padding-right:10px;
	margin-left: -60px;
	overflow-x:auto;
	" class = "span4">
	<div id="loading" style="display:none;"><img src="<?=base_url('images/loading.gif')?>" alt="" /><br>Please wait...</div>
	<div id="content">