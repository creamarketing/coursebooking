<h1><% _t('AccountInformationEmail.GREETING','Hello') %>, $FirstName.</h1>
<p><% _t('AccountInformationEmail.INTRO1','You can login to the website using the credentials listed below') %>:<br/>
	<ul>
		<li><strong><% _t('Member.EMAIL', 'Email') %>: </strong>$Email</li>
		<li><strong><% _t('Member.PASSWORD', 'Password') %>: </strong>********</li>
	</ul>
</p>

<h3><% _t('AccountInformationEmail.CONTACTINFO','Contact Information') %></h3>
<ul>
	<li><strong><% _t('Participator.NAME','Name') %>: </strong>$FirstName $Surname</li>
	<li><strong><% _t('Participator.PHONE','Phone') %>: </strong>$Phone</li>
	<li><strong><% _t('Participator.ADDRESS','Address') %>: </strong>$PostAddress, $PostCode, $PostOffice</li>
</ul>