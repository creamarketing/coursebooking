<% require javascript(coursebooking/javascript/jquery.cookie.js)  %>

<div class="ParticipatorProfile">
	<% if IsLoggedIn %>
		<div id="Profile">
			$ProfileForm
		</div>
	<% else %>
	<div id="Actions">
		<input id="LoginAction" type="radio" name="loginOrRegister" checked="checked" onclick="SetAction();" /><label for="LoginAction"><% _t('MyCoursesPage.LOGIN','Login') %></label>
		<input id="RegisterAction" type="radio" name="loginOrRegister" onclick="SetAction();" /><label for="RegisterAction"><% _t('MyCoursesPage.REGISTER','Register') %></label>
	</div>
	<div class="clear">&nbsp;</div>
	<div id="Forms">
		<div id="Login">
			$ParticipatorLoginForm
		</div>
		<div id="Registration" style="display:none;">
			$ParticipatorRegistrationForm
		</div>
	</div>
	<% end_if %>
</div>
	
<script type="text/javascript">
	// show or hide the correct member action form
	function SetAction(){
		if (jQuery('#LoginAction').attr('checked')) {
			jQuery('#Registration').hide();
			jQuery('#Login').show();
			jQuery.cookie('eCourse-login-registration', 'login');
		}
		else {
			jQuery('#Login').hide();
			jQuery('#Registration').show();
			jQuery.cookie('eCourse-login-registration', 'registration');			
		}
	}
	
	jQuery(function() {
		var lor = jQuery.cookie('eCourse-login-registration');
		
		if (lor === null || lor == 'login') {
			jQuery('#Registration').hide();
			jQuery('#Login').show();			
			jQuery('#LoginAction').attr('checked', 'checked')
		}
		else {
			jQuery('#Login').hide();
			jQuery('#Registration').show();
			jQuery('#RegisterAction').attr('checked', 'checked')
		}
	});
</script>