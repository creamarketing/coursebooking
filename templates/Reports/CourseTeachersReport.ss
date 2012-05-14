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
			table.infoTable tr td.heading {
				width: 15%;
				font-weight: bold;
			}			
			
			table.infoTable tr td:first-child {
				width: 25%;
				font-weight: bold;
			}
			table.infoTable tr td {
				width: 25px;
			}
						
			table.dataTable tr td,
			table.signatureTable tr td {
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
		
		<title><% _t("CourseTeachersReport.TITLE","Course teachers report") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" class="report-heading"><% _t("CourseTeachersReport.TITLE","Course teachers report") %><br/></td>
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
					<td colspan="4"><hr /></td>
				</tr>
				<tr>
					<td><% _t('Course.COURSECODE', 'Course code') %></td>
					<td>$Course.CourseCode</td>
					<td class="heading"><% _t('Course.STATUS', 'Course status') %></td>
					<td>$Course.CourseStatus</td>					
				</tr>				
				<tr>
					<td><% _t('Term.SINGULARNAME', 'Term') %></td>
					<td>$Course.Term.Name</td>
					<td class="heading"><% _t('Course.COMPLETED_CHECKBOX', 'Completed') %></td>
					<td>$Course.Completed.Nice</td>
				</tr>				
				<tr>
					<td><% _t('CourseUnit.SINGULARNAME', 'Unit') %></td>
					<td>$Course.CourseUnit.Name</td>
					<td class="heading"><% _t('Course.PARTICIPATORS', 'Participators') %></td>
					<td>$Course.Participators.Count ($Course.MinParticipators / $Course.MaxParticipators)</td>
				</tr>				
				<tr>
					<td><% _t('Course.COURSERESPONSIBLE', 'Course responsible') %></td>
					<td>$Course.CourseResponsible.Name</td>
					<td class="heading"><% _t('Course.PRICE', 'Price') %></td>
					<td>$Course.CoursePrice €</td>
				</tr>
				<tr>
					<td><% _t('Course.NAME', 'Name') %></td>
					<td>$Course.NameList</td>
				</tr>
				<tr>
					<td><% _t('Course.DESCRIPTION', 'Description') %></td>
					<td>$Course.DescriptionList</td>
				</tr>				
				<tr>
					<td><% _t('Course.COURSEBOOKS', 'Course books') %></td>
					<td>$Course.CourseBooks</td>
				</tr>					
				<tr>
					<td><% _t('EducationArea.SINGULARNAME', 'Education area') %></td>
					<td>$Course.EducationArea.Name</td>
				</tr>								
				<tr>
					<td><% _t('CourseSubject.PLURALNAME', 'Course subjects') %></td>
					<td>$Course.NiceSubjects</td>
				</tr>				
				<tr>
					<td><% _t('CourseLanguage.PLURALNAME', 'Course languages') %></td>
					<td>$Course.NiceLanguages</td>
				</tr>				
				<tr>
					<td><% _t('CourseType.SINGULARNAME', 'Course type') %></td>
					<td>$Course.CourseType.Name</td>
				</tr>					
				<tr>
					<td><% _t('AgeGroup.SINGULARNAME', 'Age group') %></td>
					<td>$Course.AgeGroup.Name</td>
				</tr>					
				<tr>
					<td><% _t('EducationType.SINGULARNAME', 'Education type') %></td>
					<td>$Course.EducationType.Name</td>
				</tr>
				<tr>
					<td><% _t('IncomeAccount.SINGULARNAME', 'Income account') %></td>
					<td>$Course.IncomeAccount.Name</td>
				</tr>				
				<tr>
					<td><% _t('ExpenseAccount.SINGULARNAME', 'Expense account') %></td>
					<td>$Course.ExpenseAccount.Name</td>
				</tr>
				<tr>
					<td><% _t('Course.MAINLOCATION', 'Main location') %></td>
					<% control Course.MainLocation %>
						<td>$Short.Name, $Short.PostAddress, $Short.PostCode, $Short.PostOffice</td>
					<% end_control %>
				</tr>
				<tr>
					<td><% _t('CourseSearchResults.COURSEDATES', 'Dates') %>: </td>
					<% control Course %>
					<td>$RecDateStart.Format(d.m.Y) - $RecDateEnd.Format(d.m.Y) ($TotalLessions <% _t('CourseSearchResults.LESSIONS', 'lessions') %>)<br/>
						$MainTimes.Full
					</td>					
					<% end_control %>
				</tr>
				<tr>
					<td colspan="4"><hr /></td>
				</tr>
			</table>
			
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th><% _t('Teacher.SURNAME', 'Surname') %></th>
					<th><% _t('Teacher.FIRSTNAME', 'Firstname') %></th>
					<th><% _t('Teacher.PHONE', 'Phone') %></th>
					<th><% _t('Teacher.EMAIL', 'Email') %></th>
				</thead>
					
				<% control Course.Teachers %>
					<tr>
						<td>$Surname</td>
						<td>$FirstName</td>
						<td>$Phone</td>
						<td>$Email</td>
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
