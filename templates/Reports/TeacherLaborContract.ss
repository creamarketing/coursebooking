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
			table.infoTable tr td {
				width: auto;
			}
			table.infoTable tr td.heading {
				width: 25%;
				font-weight: bold;
			}
			
			table.signatureTable tr td:first-child {
				width: 25%;
				padding-bottom: 10px;
			}			

			table.dataTable tr th:first-child {
				width: 70%;
			}
			table.dataTable tr td:first-child {
				padding-left: 20px;
			}			
			
			table.signatureTable tr td, 
			table.infoTable tr td {
				width: auto;
			}
			
			table.dataTable tfoot th {
				border-top: 1px solid #000;
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
				/*font-weight: bold;*/
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
		
		<title><% _t("TeacherLaborContract.TITLE","Labor contract") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="3" class="report-heading"><% _t("TeacherLaborContract.TITLE","Labor contract") %><br/></td>
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
					<td class="heading"><% _t('Teacher.NAME', 'Name') %></td>
					<td>$Teacher.Surname $Teacher.FirstName</td>
					<td class="heading"><% _t('Teacher.PHONE', 'Phone') %></td>
					<td>$Teacher.Phone</td>
				</tr>
				<tr>
					<td class="heading"><% _t('Teacher.PERSONALNUMBER', 'Personal number') %></td>
					<td>$Teacher.PersonalNumber</td>
					<td class="heading"><% _t('Teacher.BANKACCOUNTNUMBER', 'Bank account') %></td>
					<td>$Teacher.BankAccountNumber</td>					
				</tr>
				<tr>
					<td class="heading"><% _t('Teacher.TITLE', 'Title') %></td>
					<td>$Teacher.Title</td>
					<td class="heading"><% _t('TeacherSalaryClass.SINGULARNAME', 'Salary class') %></td>
					<td>$Teacher.DefaultSalaryClass.Name $Teacher.DefaultSalaryClass.SalaryHour</td>
				</tr>				
				<tr>
					<td class="heading"><% _t('Teacher.PROFESSION', 'Profession') %></td>
					<td>$Teacher.Profession</td>
				</tr>				
				<tr>
					<td class="heading"><% _t('Teacher.POSTADDRESS', 'Post address') %></td>
					<td>$Teacher.PostAddress</td>
				</tr>
				<tr>
					<td class="heading"><% _t('Teacher.POSTCODE', 'Post code') %></td>
					<td>$Teacher.PostCode</td>
				</tr>				
				<tr>
					<td class="heading"><% _t('Teacher.POSTOFFICE', 'Post office') %></td>
					<td>$Teacher.PostOffice</td>
				</tr>								
				<tr>
					<td class="heading"><% _t('Teacher.EMAIL', 'Email') %></td>
					<td>$Teacher.Email</td>
				</tr>								
				
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td class="heading"><% _t('TeacherLaborContract.EMPLOYMENTPERIOD', 'Employment period') %></td>
					<td>$EmploymentPeriod</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				
				<tr>
					<td class="heading"><% _t('Employer.SINGULARNAME', 'Employer') %></td>
					<td colspan="3">$Teacher.Employer.Name</td>
				</tr>
				<tr>
					<td class="heading"><% _t('Employer.POSTADDRESS', 'Address') %></td>
					<td colspan="3">$Teacher.Employer.PostAddress, $Teacher.Employer.PostCode, $Teacher.Employer.PostOffice</td>
				</tr>
				<tr>
					<td class="heading"><% _t('Employer.PHONE', 'Phone') %></td>
					<td colspan="3">$Teacher.Employer.Phone</td>
				</tr>				
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4" class="heading"><% _t('TeacherLaborContract.LABORAGREEMENTAPPLIED', 'Labor agreement that applies') %></td>
				</tr>
				<tr>
					<td style="font-weight: normal" colspan="4">$Teacher.Employer.LaborAgreement</td>
				</tr>				
			</table>
			
			<br/>
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th><% _t('Course.PLURALNAME', 'Courses') %></th>
					<th><% _t('Course.MINPARTICIPATORS', 'Min. participators') %></th>
					<th><% _t('TeacherLaborContract.HOURS', 'Hours') %></th>
				</thead>
				
				<% control Courses %>
					<tr>
						<td><b>$CourseCode</b><span style="margin-left: 10px">$Name</span><td>
					</tr>
					<tr>
						<% control MainLocation %>
						<td>$Short.Name, $Short.PostAddress, $Short.PostCode, $Short.PostOffice</td>
						<% end_control %>
						<td>$MinParticipators</td>
						<td>$Hours</td>
					</tr>
					<tr>
						<td>$StartDate - $EndDate</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				<% end_control %>
				
				<tfoot>
					<th colspan="2"><% _t('TeacherLaborContract.TOTALHOURS', 'Total') %></th>
					<th>$TotalHours</th>
				</tfoot>
			</table>
			
			<br />
			<br />
			<br />
			<table cellpadding="0" cellspacing="0" class="signatureTable">
				<tr>
					<td colspan="2">&nbsp;</td>
					<td><% _t('TeacherLaborContract.SIGNATURE_LOCATION', 'Location') %></td>
					<td>_____________________</td>
				</tr>
				<tr>
					<td>$Teacher.Employer.PostOffice</td>
					<td>____/____/____________</td>
					
					<td><% _t('TeacherLaborContract.SIGNATURE_DATE', 'Date') %></td>
					<td>____/____/____________</td>					
				</tr>
				<tr>
					<td><% _t('TeacherLaborContract.SIGNATURE', 'Signature') %></td>
					<td>_____________________</td>
					<td><% _t('TeacherLaborContract.SIGNATURE', 'Signature') %></td>
					<td>_____________________</td>
				</tr>				
				<tr>
					<td>&nbsp;</td>
					<td>$Teacher.Employer.LaborAgreementSigner</td>
					<td>&nbsp;</td>
					<td>$Teacher.FirstName $Teacher.Surname</td>
				</tr>
			</table>
		</div>
				
		<% if DocumentType != pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
	</body>
</html>
