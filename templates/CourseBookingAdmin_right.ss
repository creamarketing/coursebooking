<script type="text/javascript">
	var courseBookingAdminHref = '{$BaseHref}admin/coursebooking/';
	var changeURL = courseBookingAdminHref + 'change';
</script>

<% if view = default %>
	<div id="WelcomeMessage">
		<h1><% _t('CourseBookingAdmin.MENUTITLE','e-Course') %></h1>
		<p><% _t('CourseBookingAdmin.WELCOMEMESSAGE','Welcome to e-Kurs!') %></p>
		<% if isAdmin %>
		<p><span><strong><% _t('CourseBookingAdmin.SHORTCUTS','Shortcuts') %><strong></span><br/>
			<a href="{$Link}editcourserequests"><% _t('CourseRequest.PLURALNAME', 'Course requets') %></a> | 
			<a href="{$Link}editcourses"><% _t('Course.PLURALNAME', 'Courses') %></a> | 
			<a href="{$Link}editteachers"><% _t('Teacher.PLURALNAME', 'Teachers') %></a> | 
			<a href="{$Link}editparticipators"><% _t('Participator.PLURALNAME', 'Participators') %></a>
		</p>
		<% else_if isTeacher %>
		<p><span><strong><% _t('CourseBookingAdmin.SHORTCUTS','Shortcuts') %><strong></span><br/>
			<a href="{$Link}editcourses"><% _t('Course.PLURALNAME', 'Courses') %></a>
		</p>		
		<% end_if %>
	</div>
<% else_if view = editcourses %>
	$EditCoursesForm   
	<div id="Legend">
		<ul>
			<li>
				<div class="legendColor" style="background-color: #FF8080;"></div>
				<label><% _t('Course.LABEL_HASISSUES', 'Has issues') %></label>
			</li>
			<li>
				<div class="legendColor" style="background-color: #EBEBE4;"></div>
				<label><% _t('Course.LABEL_PASSIVE', 'Passive') %></label>
			</li>
		</ul>
	</div>
<% else_if view = editterms %>
	$EditTermsForm   
<% else_if view = editcourselanguages %>
	$EditCourseLanguagesForm   
<% else_if view = editcourseunits %>
	$EditCourseUnitsForm
<% else_if view = editcoursesubjects %>
	$EditCourseSubjectsForm
<% else_if view = editteachers %>
	$EditTeachersForm
<% else_if view = editparticipators %>
	$EditParticipatorsForm
<% else_if view = editeducationareas %>
    $EditEducationAreasForm	
<% else_if view = editcourserequests %>
    $EditCourseRequestsForm		
<% else_if view = editsalaryclasses %>
    $EditSalaryClassesForm
<% else_if view = teacherhoursreports %>
	$TeacherHoursReportsForm	
<% else_if view = teachersalaryreports %>
	$TeacherSalaryReportsForm
<% else_if view = coursereports %>
	$CourseReportsForm
<% else_if view = participatorreports %>
	$ParticipatorReportsForm
<% else_if view = teacherreports %>
	$TeacherReportsForm	
<% else_if view = teacherlaborcontract %>
	$TeacherLaborContractForm	
<% else_if view = editemployers %>
	$EditEmployersForm		
<% else_if view = edithourtypes %>
    $EditHourTypesForm
<% else_if view = editcoursedates %>	
	$EditCourseDatesForm
	<div id="Legend">
		<ul>
			<li>
				<div class="legendColor" style="background-color: #FF8080;"></div>
				<label><% _t('CourseDate.LABEL_CONFLICTING', 'Conflicting') %></label>
			</li>
			<li>
				<div class="legendColor" style="background-color: #FFF080;"></div>
				<label><% _t('CourseDate.LABEL_NOLESSIONS', 'No lessions') %></label>
			</li>
		</ul>
	</div>
<% else_if view = editcoursetypes %>	
	$EditCourseTypesForm
<% else_if view = editcoursemainclasses %>
	$EditCourseMainClassesForm	
<% else_if view = editagegroups %>	
	$EditAgeGroupsForm
<% else_if view = editeducationtypes %>	
	$EditEducationTypesForm
<% else_if view = editcourseadmins %>	
	$EditCourseAdminsForm
<% else_if view = exportcoursedata %>	
	$ExportCourseDataForm
<% end_if %>


<%-- ****************** --%>
<%-- **   Accounts   ** --%>
<%-- ****************** --%>

<% if view = editincomeaccounts %>
	$EditIncomeAccountsForm

<% else_if view = editexpenseaccounts %>
	$EditExpenseAccountsForm
<% end_if %>