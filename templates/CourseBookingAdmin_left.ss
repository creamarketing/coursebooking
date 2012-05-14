<div id="leftContainer">
	<ul id="sitetree" class="tree unformatted">
		<li class="Root last">
			<a href="{$Link}"><% _t('CourseBookingAdmin.MENUTITLE', 'e-Course') %></a>
			<ul>
				<% if isAdmin %>
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_COURSES', 'Manage courses') %></a>
						<ul>
							<li><a href="{$Link}editcourses"><% _t('CourseBookingAdmin.EDIT_COURSES', 'Edit courses') %></a></li>
							<li><a href="{$Link}editcourseadmins"><% _t('CourseBookingAdmin.EDIT_COURSEADMINS', 'Edit course administrators') %></a></li>
							<li><a href="{$Link}exportcoursedata"><% _t('CourseBookingAdmin.EXPORT_COURSEDATA', 'Export course data') %></a></li>
						</ul>
					</li>	
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_DATES', 'Manage dates') %></a>
						<ul>
							<li><a href="{$Link}editcoursedates"><% _t('CourseBookingAdmin.EDIT_COURSEDATES', 'Edit coursedates') %></a></li>							
							<li><a href="{$Link}editterms"><% _t('CourseBookingAdmin.EDIT_TERMS', 'Edit terms') %></a></li>						
						</ul>			
					</li>				
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_PARTICIPATORS', 'Manage participators') %></a>
						<ul>
							<li><a href="{$Link}editparticipators"><% _t('CourseBookingAdmin.EDIT_COURSEPARTICIPATORS', 'Edit course participators') %></a></li>
							<li><a href="{$Link}editcourserequests"><% _t('CourseBookingAdmin.EDIT_COURSEREQUESTS', 'Edit course requests') %></a></li>
						</ul>			
					</li>				
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_TEACHERS', 'Manage teachers') %></a>
						<ul>
							<li><a href="{$Link}editteachers"><% _t('CourseBookingAdmin.EDIT_TEACHERS', 'Edit teachers') %></a></li>
							<li><a href="{$Link}editsalaryclasses"><% _t('CourseBookingAdmin.EDIT_SALARYCLASSES', 'Edit salary classes') %></a></li>
						</ul>			
					</li>
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_REPORTS', 'Manage reports') %></a>
						<ul>
							<li><a href="{$Link}teacherhoursreports"><% _t('CourseBookingAdmin.TEACHERHOURSREPORTS', 'Hour reports') %></a></li>
							<li><a href="{$Link}teachersalaryreports"><% _t('CourseBookingAdmin.TEACHERSALARYREPORTS', 'Salary reports') %></a></li>
							<li><a href="{$Link}teacherlaborcontract"><% _t('CourseBookingAdmin.TEACHERLABORCONTRACT', 'Labor contract') %></a></li>
							<li><a href="{$Link}coursereports"><% _t('CourseBookingAdmin.COURSEREPORTS', 'Course reports') %></a></li>
							<li><a href="{$Link}participatorreports"><% _t('CourseBookingAdmin.PARTICIPATORREPORTS', 'Participator reports') %></a></li>
							<li><a href="{$Link}teacherreports"><% _t('CourseBookingAdmin.TEACHERREPORTS', 'Teacher reports') %></a></li>
						</ul>
					</li>
					<li class="children">
						<a><% _t('CourseBookingAdmin.MANAGE_MISC', 'Manage other') %></a>
						<ul>
							<li><a href="{$Link}editemployers"><% _t('CourseBookingAdmin.EDIT_EMPLOYERS', 'Edit employers') %></a></li>
							<li><a href="{$Link}editcourselanguages"><% _t('CourseBookingAdmin.EDIT_COURSELANGUAGES', 'Edit course languages') %></a></li>
							<li><a href="{$Link}editcourseunits"><% _t('CourseBookingAdmin.EDIT_COURSEUNITS', 'Edit course units') %></a></li>
							<li><a href="{$Link}editcoursesubjects"><% _t('CourseBookingAdmin.EDIT_COURSESUBJECTS', 'Edit course subjects') %></a></li>
							<li><a href="{$Link}editeducationareas"><% _t('CourseBookingAdmin.EDIT_EDUCATIONAREAS', 'Edit educationareas') %></a></li>
							<li><a href="{$Link}editcoursetypes"><% _t('CourseBookingAdmin.EDIT_COURSETYPES', 'Edit coursetypes') %></a></li>
							<li><a href="{$Link}editcoursemainclasses"><% _t('CourseBookingAdmin.EDIT_COURSEMAINCLASSES', 'Edit course main classes') %></a></li>
							<li><a href="{$Link}editagegroups"><% _t('CourseBookingAdmin.EDIT_AGEGROUPS', 'Edit age groups') %></a></li>
							<li><a href="{$Link}editeducationtypes"><% _t('CourseBookingAdmin.EDIT_EDUCATIONTYPES', 'Edit educationtypes') %></a></li>							
							<li><a href="{$Link}edithourtypes"><% _t('CourseBookingAdmin.EDIT_HOURTYPES', 'Edit hourtypes') %></a></li>							
							<li><a href="{$Link}editincomeaccounts"><% _t('CourseBookingAdmin.EDIT_INCOMEACCOUNTS', 'Edit income accounts') %></a></li>
							<li><a href="{$Link}editexpenseaccounts"><% _t('CourseBookingAdmin.EDIT_EXPENSEACCOUNTS', 'Edit expense accounts') %></a></li>							
						</ul>
					</li>								
				<% end_if %>
			</ul>
		</li>
	</ul>
	
	<a id="showOrHideLeft" href='#' onclick="showOrHideLeft(); return false;">&nbsp;</a>
</div>

<script type="text/javascript">
	
	var leftWidth = 0;
	function showOrHideLeft() {
		jQuery('.qtip').remove();
		if (jQuery('#left').width() > 12) {
			leftWidth = jQuery('#left').width();
			jQuery('#left').animate(
				{
					width: 12
				},
				{
					duration: 400,
					step: function(){
						fixRightWidth();
					},
					complete: function() {
						jQuery('#sitetree').hide();
						jQuery('#showOrHideLeft').css('background-image', 'url(coursebooking/images/arrowRight.gif)');
						jQuery(window).resize();
					}
				}
			);
		}
		else {
			jQuery('#sitetree').show();
			jQuery('#left').animate(
				{
					width: leftWidth
				},
				{
					duration: 400,
					step: function(){
						fixRightWidth();
					},
					complete: function() {
						jQuery('#showOrHideLeft').css('background-image', 'url(coursebooking/images/arrowLeft.gif)');
						jQuery(window).resize();
					}
				}
			);
		}
	}
	
</script>
