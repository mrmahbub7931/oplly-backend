{!! EmailHandler::prepareData(str_replace('{{ reset_link }}', $link, '

{{ header }}

<strong>Hello there,</strong> <br /><br />

You are receiving this email because you need to set a new password for your account. <br /><br />

<a href="{{ reset_link }}">Set New password</a> <br /><br />

Regards, <br />

<hr />

If you’re having trouble clicking the "Set New password" button, copy and paste the URL below into your web browser: {{ reset_link }}

{{ footer }}

')) !!}
