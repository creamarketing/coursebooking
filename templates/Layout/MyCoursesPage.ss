<% require javascript(sapphire/thirdparty/jquery/jquery.js) %>
<% require javascript(dialog_dataobject_manager/javascript/jquery-ui-1.8.6.custom.min.js) %>
<% require css(dialog_dataobject_manager/css/smoothness/jquery-ui-1.8.6.custom.css) %>

<% require css(coursebooking/css/Pages/MyCoursesPage.css) %>
<% require javascript(coursebooking/javascript/Pages/MyCoursesPage.js) %>

<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% include TopBar %>
	<% if isAdmin %>
	
	<% else_if isTeacher %>
		<% include Teacher_MyCourses %>
		<% include Teacher_MyAgenda %>
		<% include Teacher_Profile %>
	<% else %>
		<% include Participator_MyCourses %>
		<% include Participator_MyAgenda %>
		<% include Participator_Profile %>
	<% end_if %>
		
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>