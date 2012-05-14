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
		
		<title><% _t("TeacherAllEmployeesReport.TITLE","Employed teachers hours and salary report") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="5" class="report-heading"><% _t("TeacherAllEmployeesReport.TITLE","Employed teachers hours and salary report") %><br/></td>
				</tr>
				<tr><td colspan="5">&nbsp;</td></tr>
				<tr>
					<td class="date dark-line" colspan="5">$Today</td>
				</tr>
			</table>
		</div>
		
		<% if DocumentType = pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
		<div class="page first">			
			<table cellpadding="0" cellspacing="0" class="dataTable">
				<thead>
					<th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th><% _t('TeacherAllEmployeesReport.PAYMENTMONTH', 'PM') %></th>
					<th><% _t('TeacherAllEmployeesReport.HOURTYPE', 'HT') %></th>
					<th><% _t('TeacherAllEmployeesReport.TEACHER', 'Teacher') %></th>
					<th><% _t('TeacherAllEmployeesReport.DATESTART', 'Start date') %><br/><% _t('TeacherAllEmployeesReport.DATEEND', 'End date') %></th>
					<th><% _t('TeacherAllEmployeesReport.EXPENSEACCOUNT', 'Expense account') %></th>				
					<th class="right"><% _t('TeacherAllEmployeesReport.LESSIONS', 'Lessions') %></th>
					<th class="right"><% _t('TeacherAllEmployeesReport.SALARYHOUR', 'A-price') %></th>
					<th class="right"><% _t('TeacherAllEmployeesReport.TOTAL', 'Total') %></th>
				</thead>
				
				<% control ExpenseAccounts %>
					<% control Teachers %>
						<tr>
							<td colspan="7">&nbsp;</td>
						</tr>
						<% control Dates %>
							<% if Last %>
								<tr>
									<td>$RowNumber</td>
									<td>$PaymentMonth<% if Locked %>*<% end_if %></td>
									<td>$HourType</td>
									<td>$TeacherName</td>
									<td>$TimeStart<br/>$TimeEnd</td>
									<td>$ExpenseAccount.Name $ExpenseAccount.ExpensePlace $ExpenseAccount.Account</td>
									<td class="right">$Lessions</td>
									<td class="right">$SalaryHour</td>
									<td class="right">$Salary</td>
								</tr>
							<% else %>
								<tr>
									<td>$RowNumber</td>
									<td>$PaymentMonth<% if Locked %>*<% end_if %></td>
									<td>$HourType</td>
									<td>$TeacherName</td>
									<td>$TimeStart<br/>$TimeEnd</td>
									<td>$ExpenseAccount.Name $ExpenseAccount.ExpensePlace $ExpenseAccount.Account</td>
									<td class="right">$Lessions</td>
									<td class="right">$SalaryHour</td>
									<td class="right">$Salary</td>
								</tr>						
							<% end_if %>
						<% end_control %>
						<tr>
							<td class="">&nbsp;</td>
							<td class="">&nbsp;</td>
							<td class="">&nbsp;</td>
							<td class="">&nbsp;</td>
							<td class="">&nbsp;</td>
							<td class="bold right"><% _t('TeacherAllEmployeesReport.TEACHERSUM', 'Teacher sum') %></td>
							<td class="bold right">$TotalLessions</td>
							<td class="bold right">&nbsp;</td>
							<td class="bold right">$TotalSalary</td>
						</tr>
					<% end_control %>
					<tr>
						<td class="dark-line">&nbsp;</td>
						<td class="dark-line">&nbsp;</td>
						<td class="dark-line">&nbsp;</td>
						<td class="dark-line">&nbsp;</td>
						<td class="dark-line">&nbsp;</td>
						<td class="dark-line bold right"><% _t('TeacherAllEmployeesReport.EXPENSEACCOUNTSUM', 'Expense account sum') %></td>
						<td class="dark-line bold right">$TotalLessions</td>
						<td class="dark-line bold right">&nbsp;</td>
						<td class="dark-line bold right">$TotalSalary</td>
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
