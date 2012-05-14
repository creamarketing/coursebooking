<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		<style>
			<% if DocumentType = pdf %>
				@page {
					size: A4 portrait;
					margin: 2cm 1cm;
				
					@top-left {
						content: element(header);
					}
				
					@bottom-left {
				        content: element(footer);
				    }
				}
				#pagenumber:before {
					content: counter(page);
				}
				#pagecount:before {
					content: counter(pages);
				}
				#header {
					position: running(header);
				}
				#footer {
					position: running(footer);
				}
				.page {
					page-break-before: always;
				}
				table.dataTable tr {
					page-break-before: avoid;
				}
				.page.first {
					page-break-before: avoid;
				}
			<% else %>
				.page {
					padding: 0;
				}
				.page.first {
					padding: 0;
				}
			<% end_if %>
			
			body {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 14px;
			}
			table {
				width: 700px;
			}
			#footer table {
				width: 720px;
			}
			td {
				text-align: left;
				vertical-align: top;
				width: 33%;
			}
			th {
				text-align: left;
				font-weight: bold;
				font-size: 14px;
				vertical-align: top;
			}
			table.infoTable tr td:first-child {
				width: 30%;
				font-weight: bold;
			}
			
			table.signatureTable tr td:first-child {
				width: 25%;
				padding-bottom: 10px;
			}			
			
			table.dataTable tr td,
			table.signatureTable tr td, 
			table.infoTable tr td {
				width: auto;
			}
			
			hr {
				height: 1px;
				border: none;
				color: #000;
				background-color: #000;
			}
			
			.report-heading {
				font-size: 18px;
				font-weight: bold;
				text-transform: uppercase;
			}
			
			.date {
				font-size: 12px;
				font-weight: bold;
			}
			
			.dark-line {
				border-bottom: 1px solid #000;
			}			
			
			.bold {
				font-weight: bold;
			}
			
			.right {
				text-align: right;
			}
			
		</style>
		
		<title><% _t("ParticipatorCoursesReport.TITLE","Participator courses report") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" class="report-heading"><% _t("ParticipatorCoursesReport.TITLE","Participator courses report") %><br/></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="date">$Today</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
		
		<% if DocumentType = pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
		<div class="page first">
			<table cellpadding="0" cellspacing="0" class="infoTable">
				<tr>
					<td colspan="3"><hr /></td>
				</tr>
				<tr>
					<td><% _t('Participator.NAME', 'Name') %></td>
					<td>$Participator.Surname $Participator.FirstName</td>
				</tr>
				<tr>
					<td><% _t('Participator.PERSONALNUMBER', 'Personal number') %></td>
					<td>$Participator.PersonalNumber</td>
				</tr>
				<tr>
					<td><% _t('Participator.NATIVELANGUAGE', 'Native language') %></td>
					<td>$Participator.NiceNativeLanguage</td>
				</tr>				
				<tr>
					<td><% _t('Participator.GENDER', 'Gender') %></td>
					<td>$Participator.NiceGender</td>
				</tr>				
				<tr>
					<td><% _t('Participator.PHONE', 'Phone') %></td>
					<td>$Participator.Phone</td>
				</tr>					
				<tr>
					<td><% _t('Participator.EMAIL', 'Email') %></td>
					<td>$Participator.Email</td>
				</tr>					
				<tr>
					<td><% _t('Participator.PROFESSION', 'Profession') %></td>
					<td>$Participator.Profession</td>
				</tr>				
				<tr>
					<td><% _t('Participator.EDUCATION', 'Education') %></td>
					<td>$Participator.NiceEducation</td>
				</tr>						
				<tr>
					<td><% _t('Participator.OCCUPATION', 'Occupation') %></td>
					<td>$Participator.NiceOccupation</td>
				</tr>										
				<tr>
					<td><% _t('Participator.REGISTRATIONMETHOD', 'Registration method') %></td>
					<td>$Participator.NiceRegistrationMethod</td>
				</tr>				
				<tr>
					<td><% _t('Participator.POSTADDRESS', 'Post address') %></td>
					<td>$Participator.PostAddress</td>
				</tr>
				<tr>
					<td><% _t('Participator.POSTCODE', 'Post code') %></td>
					<td>$Participator.PostCode</td>
				</tr>				
				<tr>
					<td><% _t('Participator.POSTOFFICE', 'Post office') %></td>
					<td>$Participator.PostOffice</td>
				</tr>								
				<tr>
					<td colspan="3"><hr /></td>
				</tr>
			</table>
			
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th><% _t('Course.COURSECODE', 'Course code') %></th>
					<th><% _t('Course.NAME', 'Course name') %></th>
					<th><% _t('TeacherHoursReport.DATE', 'Date') %></th>
					<th class="right"><% _t('CourseDate.LESSIONS', 'Lessions') %></th>
				</thead>
					
				<% control Courses %>
					<tr>
						<td>$CourseCode</td>
						<td>$Name</td>
						<td>$RecDateStart.Format(d.m.Y) - $RecDateEnd.Format(d.m.Y)</td>
						<td class="right">$TotalLessions</td>
					</tr>
				<% end_control %>
			</table>
		</div>
				
		<% if DocumentType != pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
	</body>
</html>
