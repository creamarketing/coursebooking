<% require css(coursebooking/css/Pages/TopBar.css) %>

<div id="topbar">
	<div class="left"> 
		<% if IsLoggedIn %>
			<% _t('Profile.LOGGEDINAS', 'Logged in as') %>
			<% control currentUser %>
				<% if FirstName && Surname %> 
                  $FirstName $Surname 
               <% else_if FirstName %> 
                  $FirstName 
               <% else %> 
                  $Email 
               <% end_if %>
				</strong>
				(<a href="Security/logout"><% _t('Profile.LOGOUT', 'Logout') %></a>)
			<% end_control %>
		<% else %>
			<% _t('Profile.NOTLOGGEDIN', 'Not logged in') %> (<a id="login-link" href="$LoginLink?BackURL=$Link"><% _t('Profile.LOGIN', 'Login') %></a>)
		<% end_if %>
	</div>
	<div class="right">
	<% if GhostLifeLeft && GhostHasCourses %>
		<% if isTeacher || isAdmin %>
		
		<% else %>
			<div id="TimeLeftToConfirm"><% _t('MyCoursesPage.TIMELEFTTOCONFIRM', 'Time left to confirm') %> <span class="timer">$NiceGhostLifeLeft</span></div>
			$initGhostTimerScript
		<% end_if %>
	<% end_if %>	
	</div>
</div>