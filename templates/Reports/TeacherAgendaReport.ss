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
			
			table.dataTable tr th {
				text-align: center;
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
			
			.topLine {
				border-top: 1px solid #000;
			}
			.bottomLine {
				border-bottom: 1px solid #000;
			}			
			.rightLine {
				border-right: 1px solid #000;
			}						
			.leftLine {
				border-left: 1px solid #000;
			}							
			
		</style>
		
		<title><% _t("TeacherAgendaReport.TITLE","Teacher agenda report") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" class="report-heading"><% _t("TeacherAgendaReport.TITLE","Teacher agenda report") %><br/></td>
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
					<td><% _t('Teacher.NAME', 'Name') %></td>
					<td>$Teacher.Surname $Teacher.FirstName</td>
				</tr>
				<tr>
					<td><% _t('Teacher.PERSONALNUMBER', 'Personal number') %></td>
					<td>$Teacher.PersonalNumber</td>
				</tr>
				<tr>
					<td><% _t('Teacher.NATIVELANGUAGE', 'Native language') %></td>
					<td>$Teacher.NiceNativeLanguage</td>
				</tr>				
				<tr>
					<td><% _t('Teacher.GENDER', 'Gender') %></td>
					<td>$Teacher.NiceGender</td>
				</tr>				
				<tr>
					<td><% _t('Teacher.PHONE', 'Phone') %></td>
					<td>$Teacher.Phone</td>
				</tr>					
				<tr>
					<td><% _t('Teacher.EMAIL', 'Email') %></td>
					<td>$Teacher.Email</td>
				</tr>					
				<tr>
					<td><% _t('Teacher.PROFESSION', 'Profession') %></td>
					<td>$Teacher.Profession</td>
				</tr>				
				<tr>
					<td><% _t('Teacher.TITLE', 'Title') %></td>
					<td>$Teacher.Title</td>
				</tr>						
				<tr>
					<td><% _t('Teacher.KNOWLEDGEAREA', 'KnowledgeArea') %></td>
					<td>$Teacher.KnowledgeArea</td>
				</tr>
				<tr>
					<td><% _t('Teacher.POSTADDRESS', 'Post address') %></td>
					<td>$Teacher.PostAddress</td>
				</tr>
				<tr>
					<td><% _t('Teacher.POSTCODE', 'Post code') %></td>
					<td>$Teacher.PostCode</td>
				</tr>				
				<tr>
					<td><% _t('Teacher.POSTOFFICE', 'Post office') %></td>
					<td>$Teacher.PostOffice</td>
				</tr>								
				<tr>
					<td colspan="3"><hr /></td>
				</tr>
				<tr>
					<td colspan="3"><% _t('TeacherReports.WEEK', 'Week') %> $Week.Number, $Week.Start - $Week.End</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>
			
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th style="width: 14%"><% _t('CourseDate.MONDAY', 'Monday') %></th>
					<th style="width: 14%"><% _t('CourseDate.TUESDAY', 'Tuesday') %></th>
					<th style="width: 14%"><% _t('CourseDate.WEDNESDAY', 'Wednesday') %></th>
					<th style="width: 14%"><% _t('CourseDate.THURSDAY', 'Thursday') %></th>					
					<th style="width: 14%"><% _t('CourseDate.FRIDAY', 'Friday') %></th>
					<th style="width: 14%"><% _t('CourseDate.SATURDAY', 'Saturday') %></th>
					<th><% _t('CourseDate.SUNDAY', 'Sunday') %></th>
				</thead>

				<tr>
				<% control WeekDay %>
					<td class="topLine bottomLine leftLine">
						<% control 1 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>
					<td class="topLine bottomLine leftLine">
						<% control 2 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>
					<td class="topLine bottomLine leftLine">
						<% control 3 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>
					<td class="topLine bottomLine leftLine">
						<% control 4 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>
					<td class="topLine bottomLine leftLine">
						<% control 5 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>				
					<td class="topLine bottomLine leftLine">
						<% control 6 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>				
					<td class="topLine bottomLine leftLine rightLine">
						<% control 7 %>
							<strong>$TimeStart.Format(H:i)-$TimeEnd.Format(H:i)</strong>
							$Course.CourseCode<br/>$Course.Name
							<br/><br/>
						<% end_control %>
					</td>				
				<% end_control %>
				</tr>				
			</table>
		</div>
				
		<% if DocumentType != pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
	</body>
</html>
