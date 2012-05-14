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
					margin-left: 10px;
				}
				#footer {
					position: running(footer);
				}
				.page {
					page-break-before: always;
					position: relative;
					height: 960px;
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
					position: relative;
					height: 860px;
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
				width: auto;
			}
			th {
				text-align: left;
				font-weight: bold;
				font-size: 14px;
				vertical-align: top;
			}
			
			hr {
				height: 1px;
				border: none;
				color: #000;
				background-color: #000;
			}
			
			.report-heading {
				font-size: 14px;
				font-weight: bold;
				text-transform: uppercase;
			}
			
			.date {
				font-size: 14px;
				font-weight: bold;
				text-align: right;
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
			
			table.senderTable td {
				width: auto;
			}
			
			table.senderTable .middle {
				text-align: right;
				width: 20%;
			}
			table.senderTable .middle + td {
				width: 10%;
				text-align: right;
			}
			
			table.recipientTable td {
				font-weight: bold;
			}
			
			table.invoiceRows .totalcost {
				text-align: right;
				font-weight: bold;
			}
			
			table.boxedTable {
				position: absolute;
				bottom: 0;
			}
			
			table.boxedTable td {
				padding: 10px;
				width: 30%;
			}
			
			table.boxedTable tr td:first-child {
				width: 40%;
			}		
			
			.line-left {
				border-left-width: 1px;
				border-left-color: #000;
				border-left-style: solid;
			}
			
			.line-right {
				border-right-width: 1px;
				border-right-color: #000;
				border-right-style: solid;
			}			

			.line-top {
				border-top-width: 1px;
				border-top-color: #000;
				border-top-style: solid;
			}	
			
			.line-bottom {
				border-bottom-width: 1px;
				border-bottom-color: #000;
				border-bottom-style: solid;
			}				
			
		</style>
		
		<title><% _t("Invoice.SINGULARNAME","Invoice") %></title>
	</head>
	
	<body>
		
		<div id="header">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="2" class="report-heading dark-line"><% _t("Invoice.SINGULARNAME","Invoice") %><br/></td>
					<td class="date dark-line"><% control Invoice %>$InvoiceDate.Format(d.m.Y) <% end_control %></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>									
			</table>
		</div>
		
		<% if DocumentType = pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
		<div class="page first">
			<table cellpadding="0" cellspacing="0" class="senderTable">
				<tr>
					<td>$CourseUnit.BillingName</td>
					<td class="middle"><% _t('Invoice.INVOICENUMBER', 'Invoice number') %></td>
					<td>$Invoice.InvoiceNumber</td>
				</tr>
				<tr>
					<td colspan="3">$CourseUnit.BillingPostAddress</td>
				</tr>				
				<tr>
					<td colspan="3">$CourseUnit.BillingPostCode $CourseUnit.BillingPostOffice</td>
				</tr>								
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3"><span style="display: inline-block; min-width: 90px"><% _t('Invoice.PHONE', 'Phone') %></span>$CourseUnit.Phone</td>
				</tr>
				<tr>
					<td colspan="3"><span style="display: inline-block; min-width: 90px"><% _t('CourseUnit.BUSINESSID', 'Business ID') %></span>$CourseUnit.BusinessID</td>
				</tr>				
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>				
			</table>
			
			<table cellpadding="0" cellspacing="0" class="recipientTable">
				<tr>
					<td>$Participator.Surname $Participator.FirstName</td>
				</tr>
				<tr>
					<td>$Participator.PostAddress</td>
				</tr>				
				<tr>
					<td>$Participator.PostCode $Participator.PostOffice</td>
				</tr>		
				<tr><td>&nbsp;</td></tr>
			</table>
			
			<table cellpadding="0" cellspacing="0" class="invoiceRows">
				<thead>
					<tr>
						<th class="line-bottom"><% _t('InvoiceRow.CODE', 'Code') %></th>
						<th class="line-bottom"><% _t('InvoiceRow.NAME', 'Name') %></th>
						<th class="line-bottom"><% _t('InvoiceRow.AMOUNT', 'Amount') %></th>
						<th class="line-bottom"><% _t('InvoiceRow.UNITPRICE', 'Unit price') %></th>
						<th class="line-bottom"><% _t('InvoiceRow.DISCOUNTAMOUNT', 'Discount') %></th>
						<th class="line-bottom right"><% _t('InvoiceRow.TOTALCOST', 'Total') %></th>
					</tr>
				</thead>
				<tbody>	
				<% control Invoice.InvoiceRows %>
					<tr>
						<td>$Code</td>
						<td>$Name</td>
						<td>$Amount $AmountUnit</td>
						<td>$UnitPrice €</td>
						<td>$DiscountAmount %</td>
						<td class="right">$TotalCost €</td>
					<tr>
				<% end_control %>
					<tr>
						<td colspan="5">&nbsp;<br/>&nbsp;<br/>&nbsp;</td>
					</tr>
				</tbody>
			</table>
			
			<table cellpadding="0" cellspacing="0" class="invoiceInfo">
				<tr>
					<td>$CourseUnit.BillingText</td>
				</tr>
				<tr>
					<td>&nbsp;<br/>&nbsp;</td>
				</tr>
				<tr>
					<td><% _t('CourseUnit.PENALTYINTEREST', 'Penalty intererst') %> {$CourseUnit.PenaltyInterest}%</td>
				</tr>				
				<tr>
					<td>&nbsp;<br/>&nbsp;</td>
				</tr>				
				<tr>
					<td><strong><% _t('Invoice.REFERENCENUMBER', 'Reference number') %></strong> $Invoice.ReferenceNumber<span style="float: right"><strong><% _t('Invoice.TOTALCOST', 'Total') %></strong> $Invoice.TotalCost €</span></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>								
			</table>
			
			<table cellpadding="0" cellspacing="0" class="boxedTable"> 
				<tr>
					<td class="line-top line-left">
						<strong><% _t('BankAccount.ACCOUNTNUMBER', 'Account number') %></strong><br/>
						<% control CourseUnit.BankAccounts %>
							$AccountNumber
							<% if Last = 0 %>
								<br/>
							<% end_if %>
						<% end_control %>
					</td>
					<td rowspan="2" class="line-top line-left">
						<strong><% _t('BankAccount.IBAN', 'IBAN') %></strong><br/>
						<% control CourseUnit.BankAccounts %>
							$IBAN
							<% if Last = 0 %>
								<br/>
							<% end_if %>
						<% end_control %>						
					</td>
					<td rowspan="2" class="line-top line-left line-right">
						<strong><% _t('BankAccount.BIC', 'BIC') %></strong><br/>
						<% control CourseUnit.BankAccounts %>
							$BIC
							<% if Last = 0 %>
								<br/>
							<% end_if %>
						<% end_control %>
					</td>
				</tr>
				<tr>
					<td class="line-left line-top">
						<strong><% _t('Invoice.RECIPIENT', 'Recipient') %></strong><br/>
						$CourseUnit.BillingName
					</td>
				</tr>
				<tr>
					<td class="line-left line-top">
						<strong><% _t('Invoice.PAYER', 'Payer') %></strong><br/>
						$Participator.Surname $Participator.FirstName<br/>
						$Participator.PostAddress<br/>
						$Participator.PostCode $Participator.PostOffice
					</td>
					<td class="line-left line-top">
						<strong><% _t('Invoice.INVOICEDUEDATE', 'Due date') %></strong><br/>
						<% control Invoice %>
							$InvoiceDueDate.Format(d.m.Y)
						<% end_control %>
					</td>
					<td class="line-left line-top line-right">
						<strong><% _t('Invoice.REFERENCENUMBER', 'Reference number') %></strong><br/>
						$Invoice.ReferenceNumber
					</td>
				</tr>
				<tr>
					<td class="line-left line-top line-bottom">&nbsp;</td>
					<td colspan="2" class="line-left line-top line-bottom line-right">
						<span style="display: inline-block; min-width: 80px;"><strong><% _t('Invoice.TOTALCOST', 'Total') %></strong></span> $Invoice.TotalCost €
					</td>
				</tr>
			</table>
		</div>
				
		<% if DocumentType != pdf %>
			<div id="footer">
			</div>
		<% end_if %>
		
	</body>
</html>
