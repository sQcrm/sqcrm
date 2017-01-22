<?php 
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  

/**
* Profile listing page
*/ 
?>
<!--<script type="text/javascript">
	window.location= '<?php echo NavigationControl::getNavigationLink("Settings","profile_list");?>' ;
</script>-->
<link href="/js/plugins/simplemde/simplemde.min.css" rel="stylesheet"> 
<script type="text/javascript" src="/js/plugins/simplemde/simplemde.min.js"></script>
<div class="row">
	<div class="col-md-9">
		<div class="col-md-12" id="note-area">
			<textarea id="note"></textarea>
			<br />
			<hr class="form_hr">
		</div>
		<div class="col-xs-3" id="add-project-permissions-button">
			<input type="button" class="btn btn-primary" id="add-project-permissions" value="<?php echo _('save')?>"/>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-9">
		<div class="col-md-12">
			<div id="note1"></div>
			<br /><hr class="form_hr">
			<div id="note2"></div>
			<br /><hr class="form_hr">
			<div id="note3"></div>
			<br /><hr class="form_hr">
		</div>
	</div>
</div>
<?php
$data  = '## Project Detail
The project is of creating the REST API using node.js and express.js. The dependencies would be used as\n

* Sweager UI for generating the end point
* Mysql as backend
* JSON API serializer for the JSON response.
* Waterine ORM and different adapter based connection.

In the first phase we will only have the core ready and `GET /resource` end points, in the next branch we will have `GET /resource/:id` for getting the singular data.

Checkout https://github.com/sQcrm for more details.

![Yes](https://i.imgur.com/sZlktY7.png)';

$current_user = $_SESSION["do_user"]->iduser ;
$do_user = new User() ;
$active_users = $do_user->get_active_users() ;
$active_users_but_me = array() ;
foreach ($active_users as $key=>$users) {
	if ($users["iduser"] == $current_user) continue ;
	$active_users_but_me[] = $users["user_name"].'('.$users["firstname"].' '.$users["lastname"].')' ;
}
$mention_users_json = json_encode($active_users_but_me) ;
?>
<script>
$(document).ready(function() {
	var simplemde = new SimpleMDE({
		element: document.getElementById("note"),
		hideIcons: ["guide", "side-by-side","fullscreen"],
		showIcons: ["code", "table","horizontal-rule"],
		spellChecker: false
	});
	
	var data = '## Project Detail\nThe project is of creating the REST API using node.js and express.js. The dependencies would be used as\n';
	data +='\n* Sweager UI for generating the end point';
	data += '* Mysql as backend\n';
	data += '* JSON API serializer for the JSON response.\n';
	data += '* Waterine ORM and different adapter based connection.\n';
	data += '\nIn the first phase we will only have the core ready and `GET /resource` end points, in the next branch we will have `GET /resource/:id` for getting the singular data.';
	data += '\nCheckout https://github.com/sQcrm for more details.\n\n';
	data += '![Yes](https://i.imgur.com/sZlktY7.png)';
	
	$("#note1").html(simplemde.options.previewRender(data));
	//simplemde.value(data);
	
	var mentionUsers = '<?php echo $mention_users_json;?>';
	$('textarea').textcomplete([
		{ // emoji strategy
			match: /\B:([\-+\w]*)$/,
			search: function (term, callback) {
				callback($.map(emojies, function (emoji) {
					return emoji.indexOf(term) === 0 ? emoji : null;
				}));
			},
			template: function (value) {
				return '<img width="20" height="20" src="/themes/images/emoji-pngs/' + value + '.png"></img>' + value;
			},
			replace: function (value) {
				return ':' + value + ': ';
			},
			index: 1
		},
		{ // mentions strategy
			mentions : $.parseJSON(mentionUsers),
			match: /\B@(\w*)$/,
			search: function (term, callback) {
				callback($.map(this.mentions, function (mention) {
					return mention.indexOf(term) === 0 ? mention : null;
				}));
			},
			index: 1,
			replace: function (mention) {
				var mentionedUserName = mention.split('(') ;
				return '@' + mentionedUserName[0] + ' ';
			}
		}
	]); 
	
	$('#note-area').on('click', '.fa-eye', function () {
		var render = simplemde.value();
		var parsedData = parseEmojiMentions(render);
		$('#note-area .editor-preview').html(simplemde.options.previewRender(parsedData));
	})
	
	function parseEmojiMentions(plainText) {
		var parsedData;
		$.ajax({
			type: 'POST',
			data : {note:plainText},
			async: false,
			<?php
			$e_add_per = new Event("Project->eventParseTaskNote");
			$e_add_per->setEventControler("/ajax_evctl.php");
			$e_add_per->setSecure(false);
			?>
			url: "<?php echo $e_add_per->getUrl(); ?>",
			success: function(data) {
				console.log('success');
				parsedData = data;
			}
		});
		return parsedData;
	}
});
</script>