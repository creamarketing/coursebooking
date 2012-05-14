<h1><% _t('ParticipatorSignupEmail.GREETING','Welcome') %>, $FirstName.</h1>
<p><% _t('ParticipatorSignupEmail.EMAILSIGNUPINTRO1','Thanks for signing up to become a new member, your details are listed below for future reference.') %></p>
<p><% _t('ParticipatorSignupEmail.EMAILSIGNUPINTRO2','You can login to the website using the credentials listed below') %>:<br/>
	<ul>
		<li><strong><% _t('Member.EMAIL', 'Email') %>: </strong>$Email</li>
		<li><strong><% _t('Member.PASSWORD', 'Password') %>: </strong>$Password</li>
	</ul>
</p>

<h3><% _t('ParticipatorSignupEmail.CONTACTINFO','Contact Information') %></h3>
<ul>
	<li><strong><% _t('Participator.NAME','Name') %>: </strong>$FirstName $Surname</li>
	<li><strong><% _t('Participator.PHONE','Phone') %>: </strong>$Phone</li>
	<li><strong><% _t('Participator.ADDRESS','Address') %>: </strong>$PostAddress, $PostCode, $PostOffice</li>
</ul>