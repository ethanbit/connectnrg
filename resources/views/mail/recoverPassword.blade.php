<div style="width: 100%; display:block;">
<img src="{{ URL::to('').'/resources/assets/images/site_images/smaller_logo_for_mail.png' }}" alt="{{ trans('labels.WelcomeEamailTitle') }}">
<h2>{{ trans('labels.EcommercePasswordRecovery') }}</h2>
<p>
	<strong>{{ trans('labels.Hi') }} {{ $existUser[0]->customers_firstname }} {{ $existUser[0]->customers_lastname }}!</strong><br>
	You have requested to reset your password.<br><br>

	Use this temporary password to access the ordering system. <br>
	Once you have logged in, simply go to your profile to change the password to one that you like.<br><br>
	
	Your temporary password is: <strong>{{ $existUser[0]->password }}</strong><br><br>

	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>