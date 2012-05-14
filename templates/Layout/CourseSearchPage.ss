<% require javascript(sapphire/thirdparty/jquery/jquery.js) %>
<% require javascript(dialog_dataobject_manager/javascript/jquery-ui-1.8.6.custom.min.js) %>
<% require css(dialog_dataobject_manager/css/smoothness/jquery-ui-1.8.6.custom.css) %>

<% require css(coursebooking/css/Pages/CourseSearchPage.css) %>
<% require javascript(coursebooking/javascript/Pages/CourseSearchPage.js) %>

<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% include TopBar %>
	
	<% if showResults = true %>
		<% include CourseSearchResults %>
	<% else %>
		<% include CourseQuickSearch %>
		<% include CourseDetailedSearch %>
	<% end_if %>
	
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>