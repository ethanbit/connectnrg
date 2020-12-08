<div style="width: 100%; display:block;">
<img src="{{ URL::to('').'/resources/assets/images/site_images/smaller_logo_for_mail.png' }}" alt="{{ trans('labels.WelcomeEamailTitle') }}">
<h2>{{ trans('labels.WelcomeEamailTitle') }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $userData[0]->customers_firstname }} {{ $userData[0]->customers_lastname }}!</strong><br>
	{{ trans('labels.accountCreatedText') }}<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>