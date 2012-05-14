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
					margin-left: 7px;
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
			#header table {
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
				width: 20%;
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
		
		<title><% _t("TeacherHoursMonthlyReport.TITLE","Month lessions report") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" class="report-heading"><% _t("TeacherHoursMonthlyReport.TITLE","Month lessions report") %><br/></td>
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
					<td><% _t('Teacher.PHONE', 'Phone') %></td>
					<td>$Teacher.Phone</td>
				</tr>					
				<tr>
					<td><% _t('Teacher.EMAIL', 'Email') %></td>
					<td>$Teacher.Email</td>
				</tr>					
				<tr>
					<td><% _t('Teacher.TITLE', 'Title') %></td>
					<td>$Teacher.Title</td>
				</tr>				
				<tr>
					<td><% _t('Teacher.PROFESSION', 'Profession') %></td>
					<td>$Teacher.Profession</td>
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
					<td><% _t('Employer.SINGULARNAME', 'Employer') %></td>
					<td>$Teacher.Employer.Name</td>
				</tr>
				<tr>
					<td><% _t('Employer.POSTADDRESS', 'Address') %></td>
					<td>$Teacher.Employer.PostAddress, $Teacher.Employer.PostCode, $Teacher.Employer.PostOffice</td>
				</tr>
				<tr>
					<td><% _t('Employer.PHONE', 'Phone') %></td>
					<td>$Teacher.Employer.Phone</td>
				</tr>				
				<tr>
					<td colspan="3"><hr /></td>
				</tr>				
			</table>
			
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th><% _t('TeacherHoursMonthlyReport.MONTH', 'Month') %></th>
					<th><% _t('TeacherHoursReport.COURSE', 'Course') %></th>
					<th><% _t('TeacherHoursReport.DATE', 'Date') %></th>
					<th class="right"><% _t('TeacherHoursReport.TIMES', 'Times') %></th>
					<th class="right"><% _t('TeacherHoursReport.LESSIONS', 'Lessions') %></th>
				</thead>
					
				<% control Sections %>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<% control Dates %>
						<% if Last %>
							<tr>
								<td>$Month</td>
								<td class ="dark-line">$CourseName</td>
								<td class ="dark-line">$TimeStart</td>
								<td class ="dark-line right">$Pos</td>
								<td class ="dark-line right">$Lessions</td>
							</tr>
						<% else %>
							<tr>
								<td>$Month</td>
								<td>$CourseName</td>
								<td>$TimeStart</td>
								<td class="right">$Pos</td>
								<td class="right">$Lessions</td>
							</tr>						
						<% end_if %>
					<% end_control %>
					<tr>
						<td>&nbsp;</td>
						<td class="bold"><% _t('TeacherHoursMonthlyReport.ALLHOURS', 'Month hours total') %></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td class="bold right">$TotalLessions</td>
					</tr>
				<% end_control %>
				
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan="6"><hr /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="bold" colspan="3"><% _t('TeacherHoursReport.ALLHOURS', 'Hours total') %></td>
					<td class="bold right">$TotalLessions</td>
				</tr>
			</table>
			
			<br />
			<table cellpadding="0" cellspacing="0" class="signatureTable">
				<tr>
					<td>$Teacher.Employer.PostOffice</td>
					<td>____/____/____________</td>
				</tr>
				<tr>
					<td><% _t('TeacherHoursReport.SIGNATURE', 'Signature') %></td>
					<td>_____________________</td>
				</tr>				
			</table>
		</div>
				
		<% if DocumentType != pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
	</body>
</html>
