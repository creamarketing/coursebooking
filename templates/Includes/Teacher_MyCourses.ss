<div>
	<h2><% _t('MyCoursesPage.TITLE', 'My courses') %></h2>
		
	<div id="TeacherCourses">
		<h3><% _t('MyCoursesPage.TEACHER_COURSES', 'Courses') %></h3>
		<% if TeacherCourses.Count %>	
		<table id="resultsTable">
			<tr>
				<th width="12%">
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=code&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=code&sort_dir=ASC">
				<% end_if %>			
				<% _t('CourseSearchResults.COURSECODE', 'Course code') %>
				</a></th>

				<th>
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=name&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=name&sort_dir=ASC">
				<% end_if %>							
				<% _t('CourseSearchResults.COURSENAME', 'Course name') %>
				</a></th>

				<th width="13%">
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=location&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=location&sort_dir=ASC">
				<% end_if %>							
				<% _t('CourseSearchResults.COURSELOCATION', 'Course location') %>
				</a></th>

				<th width="12%">
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=freespots&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=freespots&sort_dir=ASC">
				<% end_if %>											
				<% _t('CourseSearchResults.CORRSEFREESPOTS', 'Free spots') %>
				</a></th>

				<th width="12%">
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=startdate&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=startdate&sort_dir=ASC">
				<% end_if %>
				<% _t('CourseSearchResults.COURSESTART', 'Start') %>
				</a></th>

				<th width="12%">
				<% if SortDir = ASC %>
					<a href="{$Link}?sort=stopdate&sort_dir=DESC">
				<% else %>
					<a href="{$Link}?sort=stopdate&sort_dir=ASC">
				<% end_if %>				
				<% _t('CourseSearchResults.COURSESTOP', 'Stop') %>
				</a></th>
				
				<th width="18px">&nbsp;</th>
			</tr>

			<% control TeacherCourses %>
				<% if Odd %>
				<tr class="course odd">
				<% else %>
				<tr class="course">
				<% end_if %>
					<td>$CourseCode</td>
					<td>$Name</td>
					<td>
						<% control MainLocation.Short %>
							<!--<a class="googleMapsLink" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=$PostAddress, $PostCode, $PostOffice&iwloc=A&output=embed&ie=UTF8">$PostOffice</a>-->
							$PostOffice
						<% end_control %>
					</td>
					<% if Full %>
						<td class="full"><% if Top.CensoredParticipators %><% _t('CourseSearchResults.NO', 'No') %><% else %>$FreeSpots / $MaxParticipators<% end_if %> <% if HasQueuedParticipators %>($QueuedParticipators.Count)<% end_if %></td>				
					<% else %>
						<td><% if Top.CensoredParticipators %><% _t('CourseSearchResults.YES', 'Yes') %><% else %>$FreeSpots / $MaxParticipators<% end_if %> <% if HasQueuedParticipators %>($QueuedParticipators.Count)<% end_if %></td>
					<% end_if %>
					<td>$RecDateStart.Format(d.m.Y)</td>
					<td>$RecDateEnd.Format(d.m.Y)</td>
					<td class="arrowContainer"><div>&nbsp</div></td>
				</tr>

				<% if Odd %>
				<tr class="odd desc">
				<% else %>
				<tr class="desc">
				<% end_if %>
					<td>&nbsp;</td>
					<td colspan="5">
						<table width="100%">
							<tr>
								<td width="20%"><% _t('CourseSearchResults.COURSELANGUAGES', 'Course languages') %>: </td>
								<td>$NiceLanguages</td>
								<td width="30%">&nbsp;</td>
							</tr>
							<tr>
								<td><% _t('CourseSearchResults.COURSETEACHERS', 'Teachers') %>: </td>
								<td>$NiceTeachers</td>
								<td>&nbsp;</td>
							</tr>						
							<tr>
								<td><% _t('CourseSearchResults.SIGNUPDATES', 'Signup') %>: </td>
								<td colspan="2">$SignupStart.Format(d.m.Y) - 
									<% if SignupExpiresNot = 1 %>
										<% _t('Term.NOEND', 'Non-stop') %>
									<% else %>
										$SignupEnd.Format(d.m.Y)
									<% end_if %>
								</td>
							</tr>							
							<tr>
								<td><% _t('CourseSearchResults.COURSEDATES', 'Dates') %>: </td>
								<td colspan="2">$RecDateStart.Format(d.m.Y) - $RecDateEnd.Format(d.m.Y) ($TotalLessions <% _t('CourseSearchResults.LESSIONS', 'lessions') %>)<br/>
									$MainTimes.Full
								</td>
							</tr>
							<tr>
								<td><% _t('CourseSearchResults.COURSELOCATION', 'Location') %>: </td>
								<td colspan="2"><% control MainLocation.Full %>
									<a class="googleMapsLink" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=$PostAddress, $PostCode, $PostOffice&iwloc=A&output=embed&ie=UTF8">$Name, $PostAddress, $PostCode, $PostOffice</a>
									<% if Last = 0 %>
										<br/>
									<% end_if %>
									<% end_control %>
								</td>
							</tr>
							<tr>
								<td><% _t('CourseSearchResults.COURSESUBJECTS', 'Subjects') %>: </td>
								<td>$NiceSubjects</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td><% _t('CourseSearchResults.COURSEDESCRIPTION', 'Description') %>: </td>
								<td>$CourseDescription</td>
								<td>&nbsp;</td>
							</tr>						
							<tr>
								<td><% _t('CourseSearchResults.COURSEBOOKS', 'Course litterature') %>: </td>
								<td>$CourseBooks</td>
								<td>&nbsp;</td>
							</tr>												
							<tr>
								<td><% _t('CourseSearchResults.COURSEPRICE', 'Price') %>: </td>
								<td>$CoursePrice €</td>
								<td>&nbsp;</td>
							</tr>
							<% if Participators.Count %>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>							
							<tr>
								<td style="font-size: 12px; font-weight: normal;"><strong><% _t('MyCoursesPage.PARTICIPATORLIST', 'Participator list') %></strong></td>
								<td>$Participators.Count <% control Participators %><% if Count = 1 %><% _t('Participator.SINGULARNAME', 'Participator') %><% else %><% _t('Participator.PLURALNAME', 'Participators') %><% end_if %><% end_control %></td>
								<td>&nbsp;</td>
							</tr>							
							<tr>
								<td colspan="3">
									<table width="100%" class="participators">
									<tr>
										<th><% _t('Participator.SURNAME', 'Surname') %></th>
										<th><% _t('Participator.FIRSTNAME', 'Firstname') %></th>
										<th><% _t('Participator.PHONE', 'Phone') %></th>
										<th><% _t('Participator.EMAIL', 'Email') %></th>
									</tr>
									<% control Participators %>
									<tr>
										<td>$Surname</td>
										<td>$FirstName</td>
										<td>$Phone</td>
										<td>$Email</td>
									</tr>
									<% end_control %>	
									</table>
								</td>
							<tr>
							<% end_if %>
							<tr>
								<td colspan="3">&nbsp;</td>
							</tr>							
							<tr>
								<td colspan="2"><strong><% _t('MyCoursesPage.SENDPARTICIPATORSMESSAGE', 'Send message to participators') %></strong></td>
								<td>&nbsp;</td>
							</tr>
							<form id="Form_CourseForm_{$ID}" class="participators-message-form" enctype="application/x-www-form-urlencoded" method="post" action="{$Top.Link}SendParticipatorsMessage">
								<fieldset>
								<tr>																	
									<td><% _t('IM_Message.SUBJECT', 'Subject') %>: </td>
									<td><input type="text" id="Form_CourseForm_{$ID}_IM_Subject" name="IM_Subject" style="width: 90%; margin-bottom: 3px"/></td>
									<td>&nbsp;</td>
								</tr>
								<tr>																	
									<td><% _t('IM_Message.BODY', 'Body') %>: </td>
									<td><textarea type="text" id="Form_CourseForm_{$ID}_IM_Body" name="IM_Body" rows="3" style="width: 90%;"></textarea>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>																	
									<td>&nbsp;</td>
									<td><button type="submit" id="Form_CourseForm_{$ID}_IM_SendButton" name="SendButton" class="send-button" style="margin-top: 3px;"><% _t('MyCoursesPage.SEND', 'Send') %></button><div class="send-loader-done" id="Form_CourseForm_{$ID}_SendStatus" style="display: inline-block; margin-left: 10px"></div><div class="send-loader" style="display: none"><img style="width: 12px; height: 12px; margin-left: 10px; margin-right: 10px; vertical-align: middle;" src="coursebooking/images/ajax-loader.gif" alt="Save in progress..." /><% _t('MyCoursesPage.SENDING', 'Sending') %>...</div></td>
									<td>&nbsp;</td>
								</tr>
								<input type="hidden" name="CourseID" value="{$ID}"/>
								</fieldset>
							</form>
							<tr>
								<td colspan="2"><button type="button" class="print-participators"><% _t('MyCoursesPage.PARTICIPATORLIST_PRINT', 'Print') %></button></td>
							</tr>							
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>				
			<% end_control %>		
		</table>
		<% else %>
			<span><% _t('MyCoursesPage.TEACHER_NOCOURSES', 'No courses') %></span>
		<% end_if %>	
	</div>
</div>
