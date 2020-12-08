<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">			<meta name="robots" content="nofollow, noindex">
    @if(empty($result['commonContent']['setting'][18]->value))
    <title>@lang('website.Ecommerce') | {{ $pageTitle }}</title>
    @else
    <title><?=stripslashes($result['commonContent']['setting'][18]->value)?></title>
    @endif
    @if(!empty($result['commonContent']['setting'][86]->value))
    <link rel="icon" href="{{asset('').$result['commonContent']['setting'][86]->value}}" type="image/gif">
    @endif
    
    <meta name="DC.title"  content="<?=stripslashes($result['commonContent']['setting'][73]->value)?>"/>
    <meta name="description" content="<?=stripslashes($result['commonContent']['setting'][75]->value)?>"/>
    <meta name="keywords" content="<?=stripslashes($result['commonContent']['setting'][74]->value)?>"/>
	
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="Vectorcoder" content="http://ionicecommerce.com">
	
	@if(!empty(session("theme")))
		<link href="{!! asset('public/css/'.session("theme").'.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @else
		<link href="{!! asset('public/css/app.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    @endif
    <link href="{!! asset('public/css/owl.carousel.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    
    <link href="{!! asset('public/css/jquery-ui.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="{!! asset('public/css/font-awesome.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    
    <link href="{!! asset('public/css/rtl.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    <link href="{!! asset('public/css/responsive.css') !!} " media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css" media="all" />
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	
    <link href="{!! asset('public/css/custom.css') !!} " media="all" rel="stylesheet" type="text/css"/>
   
    {!! NoCaptcha::renderJs() !!} 
    <!--------- stripe js ------>
	<script src="https://js.stripe.com/v3/"></script>

    <link rel="stylesheet" type="text/css" href="{!! asset('public/css/stripe.css') !!}" data-rel-css="" />
    
    <!------- paypal ---------->
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
	
    <!---- onesignal ------>
    @if($result['commonContent']['setting'][54]->value=='onesignal')
	<link rel="manifest" href="{!! asset('public/onesignal/manifest.json') !!}" />
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
	<script>
    var OneSignal = window.OneSignal || [];
      OneSignal.push(function() {
		  //push here
      });
	  	
	//onesignal 
	OneSignal.push(["init", {
	  appId: "{{$result['commonContent']['setting'][55]->value}}",
	 // safari_web_id: oneSignalSafariWebId,
	  persistNotification: false,
	  notificationClickHandlerMatch: 'origin',
	  autoRegister: false,	
	  notifyButton: {
	   enable: false 
	  }
	 }]);  
	  
    </script>
    @endif
    
    @if(!empty($result['commonContent']['setting'][76]->value))
		<?=stripslashes($result['commonContent']['setting'][76]->value)?>
    @endif
	

    <?php 
    //echo "<!-- Ducnb <pre>"; print_r($result['commonContent']['holiday']); echo "</pre>".__FILE__.":".__LINE__."-->";
    $hl = '';
    foreach($result['commonContent']['holiday'] as $holiday){
    	if($hl == ''){
    		$hl = "'".$holiday->date."'";
    	}else{
    		$hl .= ",'".$holiday->date."'";
    	}
    }
    ?>
    <script type="text/javascript">
	var cutOffTime = parseInt('14');
	var unavailableDates = [<?php echo $hl ?>];
	function checkAvailability(date) {
		var dmy = ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth()+1)).slice(-2) + '/' + date.getFullYear();
		var day = date.getDay();
        if (jQuery.inArray(dmy, unavailableDates) != -1 || day == 0 || day == 6) {
            return [false, ""];
        }else {
            return [true, ""];
        }
	}
    </script>
    
     <script src="https://www.google.com/recaptcha/api.js"></script>
     
     <script>
       function onSubmit(token) {
         document.getElementById("contact-form").submit();
       }
     </script>
</head>