<div>
	<h2><% _t('CourseSearchResults.SEARCHRESULTS', 'Search results') %></h2>
	
	<% if nothingFound = true %>
		<p><% _t('CourseSearchResults.NOTHINGFOUND', 'No hits') %></p>
	<% else %>
	
	<table id="resultsTable">
		<tr>
			<th width="12%">
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=code&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=code&sort_dir=ASC">
			<% end_if %>			
			<% _t('CourseSearchResults.COURSECODE', 'Course code') %>
			</a></th>
			
			<th>
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=name&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=name&sort_dir=ASC">
			<% end_if %>							
			<% _t('CourseSearchResults.COURSENAME', 'Course name') %>
			</a></th>
			
			<th width="13%">
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=location&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=location&sort_dir=ASC">
			<% end_if %>							
			<% _t('CourseSearchResults.COURSELOCATION', 'Course location') %>
			</a></th>
			
			<th width="12%">
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=freespots&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=freespots&sort_dir=ASC">
			<% end_if %>											
			<% _t('CourseSearchResults.CORRSEFREESPOTS', 'Free spots') %>
			</a></th>
			
			<th width="12%">
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=startdate&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=startdate&sort_dir=ASC">
			<% end_if %>
			<% _t('CourseSearchResults.COURSESTART', 'Start') %>
			</a></th>
			
			<th width="12%">
			<% if sort_dir = ASC %>
				<a href="{$Link}showResults?sort=stopdate&sort_dir=DESC">
			<% else %>
				<a href="{$Link}showResults?sort=stopdate&sort_dir=ASC">
			<% end_if %>				
			<% _t('CourseSearchResults.COURSESTOP', 'Stop') %>
			</a></th>
			
			<th width="18px">&nbsp;</th>
		</tr>
		
		<% control Courses.Pagination %>
			<% if Odd %>
			<tr class="course odd<% if HasSignedUp %> signedup<% end_if %>">
			<% else %>
			<tr class="course<% if HasSignedUp %> signedup<% end_if %>">
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
					<td class="full"><% if Top.CensoredParticipators %><% _t('CourseSearchResults.NO', 'No') %><% else %>$FreeSpots / $MaxParticipators<% end_if %></td>
				<% else %>
					<td><% if Top.CensoredParticipators %><% _t('CourseSearchResults.YES', 'Yes') %><% else %>$FreeSpots / $MaxParticipators<% end_if %></td>
				<% end_if %>
				<td>$RecDateStart.Format(d.m.Y)</td>
				<td>$RecDateEnd.Format(d.m.Y)</td>
				<td class="arrowContainer"><div>&nbsp</div></td>
			</tr>
			
			<% if Odd %>
			<tr class="odd desc<% if HasSignedUp %> signedup<% end_if %>">
			<% else %>
			<tr class="desc<% if HasSignedUp %> signedup<% end_if %>">
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
							<td><% control MainLocation.Full %>
									<a class="googleMapsLink" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=$PostAddress, $PostCode, $PostOffice&iwloc=A&output=embed&ie=UTF8">$Name, $PostAddress, $PostCode, $PostOffice</a>
									<% if Last = 0 %>
										<br/>
									<% end_if %>
								<% end_control %>
							</td>
							<td>&nbsp;</td>
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
						<% if IsConfirmedCourse = 0 %>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>							
							<% if HasSignedUp %>
								<td style="text-align: right; padding-right: 20px"><button type="button" class="cancelButton" id="Cancel_$ID"><% _t('CourseSearchResults.CANCELBUTTON', 'Cancel this course') %></button></td>
							<% else_if CanSignUp %>
								<td style="text-align: right; padding-right: 20px"><button type="button" class="signupButton" id="Signup_$ID"><% _t('CourseSearchResults.SIGNUPBUTTON', 'Signup for this course') %></button></td>
							<% else %>
								<td>&nbsp;</td>
							<% end_if %>
						</tr>
						<% end_if %>
					</table>
				</td>
				<td>&nbsp;</td>
			</tr>
		<% end_control %>		
		<% if Courses.MoreThanOnePage %>
			<tr class="pagination">
				<td colspan="6">
				<% if Courses.PrevLink %><a href="$Courses.PrevLink">« <% _t('Pagination.PREV', 'Prev') %></a> | <% end_if %>
					<% control Courses.Pages %>
						<% if CurrentBool %>
							<strong>$PageNum</strong>
						<% else %>
							<a href="$Link" title="<% _t('Pagination.GO', 'GO') %> $PageNum">$PageNum</a>
						<% end_if %>
					<% end_control %>
					<% if Courses.NextLink %> | <a href="$Courses.NextLink"><% _t('Pagination.NEXT', 'Next') %> »</a><% end_if %>
				</td>
			</tr>
		<% end_if %>				
	</table>
	
	<% end_if %>
</div>