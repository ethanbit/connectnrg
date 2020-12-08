<div style="width: 100%; display:block;">
<p>
	<a href="{{ URL::to('/') }}" class="logo">
        <img src="{{ URL::to('/resources/assets/images/site_images/smaller_logo_for_mail.png') }}" alt="">
	</a>
</p>
<p>
	<strong>
		<?php 
		if($data['customer'] != ''){
			echo $data['customer'];
		}else{
			echo 'Hi Admin!';
		}
		?>
   	</strong><br><br>
    
	First Name: {{ $data['name'] }}<br>
	Last Name: {{ $data['lastname'] }}<br>
	Phone: {{ $data['phone'] }}<br>
	Email: {{ $data['email'] }}<br>
	Company: {{ $data['company'] }}<br><br>
	
	{{ $data['message'] }}<br><br>
	<strong>{{ trans('labels.Sincerely') }},</strong><br>
	{{ trans('labels.regardsForThanks') }}
</p>
</div>