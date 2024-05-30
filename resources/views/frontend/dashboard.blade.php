@extends('vwFrontMaster')

@section('content')
    
<!--======= SUB BANNER =========-->
<!-- <section class="sub-bnr" data-stellar-background-ratio="0.5">
<div class="position-center-center">
  <div class="container">
    <h4>DASHBOARD</h4>
    <p>We're Ready To Help! Feel Free To Contact With Us</p>
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">DASHBOARD</li>
    </ol>
  </div>
</div>
</section> -->

<style type="text/css">
		/*root css*/
		:root{
			--main-color: #dd1d26;
			--primary-color: #1F242B;
			--secondary-color: #25292F;
			--white-color: #fff;
			--black-color: #000;
			--light-gray-color: #F4F4F2;
			--dark-gray: #8B694E;
			--linear-gradient: linear-gradient(to right, #8B694E, #F5CF96);
		}

		/*dashboard css*/
		.shipping-list{
			width:100%;
			border-radius:4px;
			height:48px;
			border:1px solid #000;
			color:#a0a0a0;
			padding:0 20px;
			font-weight:600;

		}
		.account-detail-input .m-set.ff-row .after{
			width:49%;
		}
		#accountDetailForm{
			flex-direction:column;
		}
		#changePasswordForm{
			flex-direction: column;
		}
		.data.account-forms .form{
			padding-bottom:40px;
		}
 		.account-detail-input{
				display:flex;
				justify-content:space-between;
		}
		.account-detail-input .form-field{
			width:49%;
		}
		.account-detail-input .m-set{
			display:flex;
			justify-content:space-between;
			width:100%;
		}
		.btn-flex-end{
			width:100%!important;
			display:flex;
			justify-content:flex-end;
			align-items:end;
		}
		#shippingAddressFormBtn{
			margin-left:8px;
		}
		.main-form-control.full-row{
			width:100%;
		}
		.main-form-control.full-row .form-field{
			width:100%;
		}
		.form_row{
			width:100%;
			display:flex;
			justify-content:space-between;
		}
		.main-form-control{
			width:49%;
		}
		/*typography css*/
		.tab-head-font p{
			margin-bottom:0;
			font-family:'Montserrat';
			font-weight:700;
			font-size:16px;
		}
		.tab-head-font{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #fff;
		}
		.tab-body-main-font{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 18px;
			line-height: 29px;
			letter-spacing: 0.5px;
			color: #000;
		}
		.tab-body-para-font{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 400;
			font-size: 13px;
			line-height: 29px;
			letter-spacing: 1px;
			text-decoration-line: underline;
			color: #000;
		}
		.tab-form-btn-font{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 800;
			font-size: 10px;
			line-height: 17px;
			text-align: center;
			letter-spacing: 0.16em;
			color: #fff;
		}
		/* my account */
		.my-account{
			padding-top: 60px;
			padding-bottom: 122px;
		}
		.my-account .content{
			display: flex;
			justify-content:center;
		}
		.tab-head{
			width: max-content;
			height: auto;
			background: #f4f4f2;
			border-right: 1px solid var(--green-color); 	
			border-left:1px solid var(--green-color);
			border-bottom:1px solid var(--green-color);
			border-radius: 5px 0px 0px 5px;
			position: relative;
		}
		.tab-head ul{
			padding-left: 0;
/*			padding-top: 18.5px;*/
			list-style: none;
			position: relative;
		}
		.tab-head .tab-arr{
			position: absolute;
			top: 17px;
			right: 3%;
			display: none;
		}
		/*.tab-head ul li{
			padding-top: 16.5px;
			padding-bottom: 16.5px;
			padding-left: 36px;
			padding-right: 32px;
			border-top: 0.5px solid #1F242B;
		}*/
		.tab-head ul li:hover > a{
/*			background: #dd1d26;*/
			background:var(--green-color);
/*			border-top: 0.5px solid #fff;*/
			color: #fff;
		}
		.tab-head ul li a p{
			color: #000;
		}
		.tab-head ul li a.active{
/*			background: #dd1d26;*/
			background:var(--green-color);
/*			border-top: 0.5px solid #fff;*/
		}
		/*.tab-head ul li.active a{color: #fff;}*/
		/*.tab-head ul li:hover > a{
			color: #fff;
		}*/
		.tab-head ul li a{
			display: flex;
			align-items: center;
				padding-top: 16.5px;
			padding-bottom: 16.5px;
			padding-left: 14px;
			padding-right: 14px;
			border-top: 0.5px solid var(--green-color);
			font-family:'open-sans-regular';
		}
		.tab-head ul li img{
			margin-right: 15px;
		}

		.tab-body{
			width: calc(100% - 271.28px);
			background: #F4F4F2;
/*			border: 0.5px solid #1F242B;*/
			border: 0.5px solid var(--green-color);
			border-radius: 0px 5px 5px 0px;
			padding-top: 30px;
			padding-left: 36px;
			padding-right: 34px;
			border-left:0;
		}
		.tab-body .account-forms p{
			margin-bottom: 18px;
		}
		.tab-body input{
			/*width: 258px;*/
			width: 100%;
			height: 48px;
			background: transparent;
			border:  1px solid #000;
			border-radius: 5px;
			margin-bottom: 24px;
			padding: 0 20px;
			position: relative;
			z-index: 2;
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #a0a0a0;
		}
		.tab-body textarea{
			/*width: 258px;*/
			width: 100%;
			height: 127px;
			background: #fff;
			border:  1px solid #000;
			border-radius: 5px;
			margin-bottom: 24px;
			padding: 8px 20px;
			position: relative;
			resize: none;
		}
		.tab-body .form-field{
/*			width: 258px;*/
			position: relative;
		}
		.tab-body .select{
			/*width: 258px;*/
			width: 100%;
			height: 48px;
			background: #fff;
			border:  1px solid #000;
			border-radius: 5px;	
			margin-bottom: 24px;
			padding: 0 20px;
			color: #a0a0a0;
			position: relative;
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
		}
		/*.tab-body select option{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #a0a0a0;
			position: absolute;
			left: 4%;
			top: 12px;
		}*/
		.tab-body  .after .plc{
			color: #a0a0a0;
			padding-left: 2px;
		}
		.tab-body .form-field .imp{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #dd1d26;
			position: absolute;
			left: 0;
			top: 0;
			width: 100%;
			height: 48px;
			background: #fff;
			text-align: left;
			padding: 12px 16px;
			z-index: 1;
			border-radius: 5px;
		}
		.tab-body .form-field .plc{
			color: #a0a0a0;
			padding-left: 1px;
		}
		.tab-body .after .imp{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #dd1d26;
			position: absolute;
			left: 5%;
			top: 12px;
		}
		.tab-body .after{
			display: inline-block;
			position: relative;
/*			width: 258px;*/
			width:100%;
		}
		.tab-body .after span.arw{
			display: inline-block;
			width: 48px;
			height: 48px;
			background: var(--green-color);
			border:1px solid var(--green-color);
			border-radius: 0px 5px 5px 0px;
			position: absolute;
			top: 0;
			right: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.tab-body .account-forms{display: none;}
		.tab-body .account-forms.active{display: block;}
		.form-btn{
			padding: 11.5px 50.5px;
			background: var(--green-color);
			border: 1px solid var(--green-color);
			font-family:'Montserrat';
			font-size:15px;
			letter-spacing:0;
		}
		.mr-42{margin-right: 24px;}
		.mr-36{margin-right: 24px;}
		.plc-adjust .imp{text-align: left;}
		.plc-adjust input{height: 68px;}
		.account-forms form{
			text-align: right;
			display: flex;
			justify-content: space-between;
			flex-wrap: wrap;
		}
		.account-forms form button{
			margin-top: 7px;
		}
		.account-forms form button:hover{
			background: #000;
			color: #fff;
			border: 1px solid #000;
		}
		.butn{width: 100%;}
		/*.tab-body .data{display: none;}*/
		.tab-body .d-input{
			width: 100%;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
		}
		.tab-body .d-input .form-field{
			width: 47%;
		}
		.tab-body .d-input .after .imp{left: 10px;}
		.tab-body .d-input .after{
			width: 100%;
		}
		.tab-body .d-input .select{
			padding: 0 13px;
		}
		.tab-body .kandoraMeasurement .butn{
			margin-bottom: 38px;
		}
		.accountDetail .butn button:last-child{padding: 13.5px 28.5px}
		.accountDetail .butn button{padding: 8.5px 17.5px;} 
		.accountDetail .butn{display: flex; justify-content: flex-end;}
		.accountDetail p{text-decoration: none;}
		.accountDetail p span{color: #E00000;}
		.dashboardMsg{
			width: 100%;
			height: 133px;
			background: #E0F1C1;
			border: 0.5px solid #91C03B;
			border-radius: 5px;
			padding: 25px 14px 22px 21px;
		}
		.dashboard h1{margin-bottom: 19px;}
		.dashboardMsg h2{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 700;
			font-size: 14px;
			line-height: 23px;
			text-align: justify;
			letter-spacing: 0.07em;
			color: #5B8E25;
			padding-bottom: 6px;
			margin: 0;
		}
		.dashboardMsg p{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 23px;
			text-align: justify;
			letter-spacing: 0.07em;
			color: #5B8E25;
		}
		.my-orders table{
			width: 100%;
			border-collapse: separate;
			border-spacing: 0 10px;
		}
		.my-orders table thead tr{
/*			background: #dd1d26;*/
				background:var(--green-color);
		}
		.my-orders table thead{margin-bottom: 10px;}
		.my-orders table thead tr td:first-child{border-top-left-radius: 5px;border-bottom-left-radius: 5px;}
		.my-orders table thead tr td:last-child{border-top-right-radius: 5px;border-bottom-right-radius: 5px;}
		.my-orders table thead tr td{
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 14px;
			line-height: 22px;
			text-align: justify;
			color: #fff;
			padding: 17px 23px;
		}
/*		.orders{padding-bottom: 47px;}*/
		.orders h1{
			margin-bottom: 9px;
		}
		.my-orders table tbody tr td{
			font-family: 'Montserrat';
			font-weight:600;
			font-style: normal;
			font-size: 13px;
			line-height: 16px;
			color: #1F242B;
			padding: 25px 24px 26px 24px;
		}
		.my-orders table tbody tr{background: #fff;}
		.changePassword .butn{display: flex; justify-content: flex-end;}
		.changePassword .butn button:last-child{padding: 13.5px 15.5px;}
		.changePassword .butn button{padding: 11.5px 16.5px;}
		.changePassword p{text-decoration: none;}
		.paymentMsg{
			box-sizing: border-box;
			width: 100%;
			/*height: 74px;*/
			background: #E0F1C1;
			border: 0.5px solid #91C03B;
			border-radius: 5px;
			padding: 25px 22px 10px 21px;
		}
		.paymentMsg p{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 700;
			font-size: 16px;
			line-height: 23px;
			text-align: justify;
			letter-spacing: 0.07em;
			color: #5B8E25;
		}
		.payment-tab{
			display: flex;
			margin-bottom: 27px;
			flex-wrap: wrap;
		}
		.paymentMethod p{text-decoration: none;}
		.paymentMethod .payment-tab a{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 12px;
			line-height: 29px;
			letter-spacing: 1px;
			color: #8B694E;
			background: #fff;
			border: 1px solid #8B694E;
			border-radius: 39px;
			padding: 3.5px 24px;
			display: flex;
			align-items: center;
			margin-right: 32px;
			margin-bottom: 16px;
		}
		.paymentMethod .payment-tab a.active{
			background: #8B694E;
			color: #fff;
		}
		.paymentMethod .payment-tab a:hover{
			background: #8B694E;
			color: #fff;
		}
		.paymentMethod .payment-tab a:hover span{
			border: 1px solid #fff;
		}
		.paymentMethod .payment-tab a.active span{
			border: 1px solid #fff;
		}
		.paymentMethod .payment-tab a:hover span:before{
			content: '';
			width: 7px;
			height: 7px;
			background: #fff;
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			border-radius: 50%;
		}
		.paymentMethod .payment-tab a.active span:before{
			content: '';
			width: 7px;
			height: 7px;
			background: #fff;
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%, -50%);
			border-radius: 50%;
		}
		.paymentMethod .payment-tab a span{
			display: inline-block;
			width: 14px;
			height: 14px;
			border: 1px solid #8B694E;
			box-sizing: border-box;
			border-radius: 50%;
			margin-right: 12px;
			position: relative;
		}
		.payment-tab-body .data{display: none;}
		.payment-tab-body .data.active{display: block;}
		.paymentMethod .hide{display: none;}
		.addressesMsg{
			width: 100%;
			background: #E0F1C1;
			border: 0.5px solid #91C03B;
			border-radius: 5px;
			padding: 25px 22px 10px 21px;
			margin-bottom: 35px;
		}
		.addressesMsg p{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 700;
			font-size: 16px;
			line-height: 23px;
			text-align: justify;
			letter-spacing: 0.07em;
			color: #5B8E25;
		}
		.addresses button{padding: 13.5px 18px}
		.addresses p{text-decoration: none;}
		.address-tab{margin-bottom: 21px;}
		.address-tab a{
			background: #fff;
			border: 1px solid #000;
			border-radius: 39px;
/*			padding: 7.5px 34px;*/
			padding:11px 34px;
			font-family: 'Montserrat';
			font-style: normal;
			font-weight: 600;
			font-size: 12px;
			line-height: 29px;
			text-align: center;
			letter-spacing: 1px;
			color: #000;
			margin-right:8px;
		}
		.address-tab a.active{
			background: var(--green-color);
			color: #fff;
			border-color: var(--green-color);
		}
		.address-tab a:hover{
			background: var(--green-color);
			color: #fff;
			border-color: var(--green-color);
		}
/*		.address-tab a:last-child{padding: 7.5px 43.5px;}*/
		.address-tab-body .data{display: none;}
		.address-tab-body .active{display: block;}
		.add_msg{display: none;}
		.billingAddress{padding-bottom: 22px;}
		.shippingAddress{padding-bottom: 53px;}
		.billingAddress p{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 400;
			font-size: 14px;
			line-height: 23px;
			text-align: justify;
			letter-spacing: 0.07em;
			color: #000;
		}
		.account-tab-body.tab-body textarea{
			background: transparent;
			z-index: 2;
		}
		.account-tab-body.tab-body textarea + .imp{height: 127px;z-index: 1;}
		.account-login{
			padding-top: 60px;
			padding-bottom: 99px;
		}
		.account-login .main .content p{
			text-decoration: none;
			margin-bottom: 13px;
		}
		.account-login .main{
			width: 60%;
			background: #F4F4F2;
			margin-left: auto;
			margin-right: auto;
			padding-top: 32px;
			padding-bottom: 32px;
		}
		.account-login .main .content{
			width: 90%;
			background: #fff;
			margin-left: auto;
			margin-right: auto;
			padding: 27px 50px 45px 49px;
		}
		.account-login form .form-field{
			position: relative;
			margin-bottom: 31px;
		}
		.account-login form input{
			width: 100%;
			height: 48px;
			background: #fff;
			border: 1px solid #BD9C68;
			border-radius: 5px;
			position: relative;
			padding: 8px 16px;
		}
		.account-login form span.imp{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 16px;
			line-height: 22px;
			color: #FF0000;
			position: absolute;
			left: 4%;
			top: 12px;
		}
		.account-login form span.plc{
			color: #a0a0a0;
			padding-left: 1px;
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
		}
		.account-login form .butn{
			margin-top: 40px;
			margin-bottom: 35px;
		}
		.account-login form button{width: 100%;}
		.account-login form button:hover{
			background: #fff;
			color: #8B694E;
			border: 1px solid #8B694E;
		}
		.social-account .seperator{
			width: 100%;
			height: 1px;
			background: #C4C4C4;
		}
		.social-account p{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 400;
			font-size: 13px;
			line-height: 22px;
			text-align: center;
			color: #999999;
		}
		.switch-account{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 400;
			font-size: 13px;
			line-height: 22px;
			text-align: center;
			color: #999999;
			margin-top: 29px;
			/*margin-bottom: 40px!important;*/
		}
		.social-account .icons img{width: 46px;}
		.login-logo img{width: 68%;}
		.account-login .login .switch-account{
			margin-bottom: 40px!important;
		}		
		.account-login .f-password .switch-account{
			margin-bottom: 40px!important;
		}
		.switch-account a{
			font-weight: 500;
			color: #999999;
			text-decoration: underline;
		}
		.social-account .icons{
			padding-top: 12px;
			padding-bottom: 32px;
			text-align: center;
		}
		.social-account .icons img:first-child{margin-right: 25px;}
		.login-logo{text-align: center;}
		.sh-pass{
			position: absolute;
			top: 50%;
			right: 19.64px;
			transform: translate(-50%, -50%);
			cursor: pointer;
		}
		.account-login .note p{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 400;
			font-size: 12px;
			line-height: 16px;
			text-align: justify;
			color: #999999;
		}
		.account-login .note p a{
			color: #8B694E;
		}
		.mb-9{margin-bottom: 9px!important;}
		.mt-16{margin-top: 16px!important;}
		.account-login .register .switch-account{
			margin-bottom: 0!important;
		}
		.account-login .main .register{
			padding: 27px 50px 29px 49px;
		}
		.account-login .f-password .login-logo{
			margin-bottom: 226px;
		}

		/* custom select */
		.form-field-select{
			position: relative;
		}
		.form-field-select .select{
			/*width: 258px;*/
			width: 100%;
			height: 48px;
			background: #fff;
			border:  1px solid #BD9C68;
			border-radius: 5px;	
			margin-bottom: 24px;
			padding: 0 20px;
			color: #a0a0a0;
			position: relative;
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
		}
		.form-field-select  .after .plc{
			color: #a0a0a0;
			padding-left: 2px;
		}
		.form-field-select .imp{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #E80000;
			position: absolute;
			left: 0;
			top: 0;
		}
		.form-field-select .plc{
			color: #a0a0a0;
			padding-left: 1px;
		}
		.form-field-select .after .imp{
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 22px;
			color: #E80000;
			position: absolute;
			left: 5%;
			top: 12px;
		}
		.form-field-select .after{
			display: inline-block;
			position: relative;
			width: 85%;
		}
		.form-field-select .after span.arw{
			display: inline-block;
			width: 48px;
			height: 48px;
			background: #BD9C68;
			border-radius: 0px 5px 5px 0px;
			position: absolute;
			top: 0;
			right: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.after{position: relative;cursor: pointer;}
		.after .select{
			position: relative;
		}
		.after .custom-select{
			position: absolute;
			top: 48px;
			left: 0;
			width: 100%;
			z-index: 99;
			display: none;
		}
		.after .custom-select ul{
			list-style: none;
			font-family: 'open-sans-regular';
			font-style: normal;
			font-weight: 600;
			font-size: 13px;
			line-height: 20px;
			color: #7B7B7B;
			padding: 0;
			max-height: 195px;
			overflow-y: auto;
		}
		.after .custom-select ul li{
			background: #fff;
			border: 1px solid #ddd;
			border-top: none;
			border-radius: 0px;
			padding: 13px 20.5px 14px 20.5px;
			text-align: left;
			cursor: pointer;
		}

		@media (min-width:1200px){
			/* my account */
/*			.tab-body .form-field{width: 326px;}*/
			.tab-body input{
				/*width: 326px;*/
				width: 100%;
			}
			.tab-body .select{
				/*width: 326px;*/
				width: 100%;
			}
			.tab-body .after{width: 100%;}
			.tab-body{
/*				padding-left: 74px;*/
/*				padding-right: 54px;*/
			}
/*			.tab-body .bodyMeasurement{padding-right: 16px;}*/
/*			.tab-body .kandoraMeasurement{padding-right: 16px;}*/
/*			.tab-body .accountDetail{padding-right: 16px;}*/
/*			.tab-body .changePassword{padding-right: 16px;}*/
/*			.tab-body .paymentMethod{padding-right: 16px;}*/
/*			.tab-body .addresses{padding-right: 16px;}*/
			.butn{width: 100%;}

			/* rkm listing */
			.list-items a.content-box{width: calc(95%/3);}

			/* account */
			.tab-body .d-input .form-field{
				width: 30%;
			}
			.tab-body .d-input .after{
				width: 30%;
			}
			.tab-body .d-input .after .imp{left: 12px;}
			.addresses button{padding: 13.5px 34px}
			.paymentMethod .payment-tab a{margin-bottom: 0;}
			.plc-adjust input{height: 48px;}
			.accountDetail .butn button{padding: 11.5px 50.5px;} 
			.changePassword .butn button{padding: 11.5px 50.5px;}
			.account-login .main{width: 40%;}
		}

		@media (min-width:1400px){
			/* account tabs */
			.account-forms form{
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
			}
			.tab-head{
				width: 266px;
				/*height: 587px;*/
				height: auto;
			}
			.tab-body{
				width: calc(100% - 266px);
				padding-top: 30px;
/*				padding-left: 101px;*/
/*				padding-right: 62px;*/
			}
/*			.tab-body .bodyMeasurement{padding-right: 37px;}*/
/*			.tab-body .kandoraMeasurement{padding-right: 37px;}*/
/*			.tab-body .accountDetail{padding-right: 37px;}*/
/*			.tab-body .changePassword{padding-right: 37px;}*/
/*			.tab-body .paymentMethod{padding-right: 37px;}*/
/*			.tab-body .addresses{padding-right: 37px;}*/
/*			.tab-body .form-field{width: 391px;}*/
			.tab-body input{
				/*width: 391px;*/
				width: 100%;
				height: 48px;
				margin-bottom: 24px;
				padding: 0 20px;
			}
			.tab-body .select{
				/*width: 391px;*/
				width: 100%;
				height: 48px;	
				margin-bottom: 31px;
				padding: 0 20px;
				font-size: 16px;
			}
/*			.tab-body .after{width: 391px;}*/
			.tab-body .after span.arw{
				width: 48px;
				height: 48px;
			}
			.form-btn{
				padding: 13.5px 52.5px;
			}
			.mr-42{margin-right: 42px;}
			.mr-36{margin-right: 36px;}
			.butn{width: 100%;}
			.tab-body .after .imp{
				left: 4%;
				font-size: 16px;
			}
			.tab-body .d-input .after .imp{left: 6%;}
			.tab-body .form-field .imp{font-size: 16px;}
			.dashboardMsg h2{font-size: 16px;font-family: Montserrat;}
			.dashboardMsg p{font-size: 16px;}
			.addresses button{padding: 13.5px 40px;}
			.address-tab a{font-size: 14px;}
			.paymentMethod .payment-tab a{
				font-size: 14px;
				padding: 3.5px 30px;
				margin-right: 38px;
			}
			.accountDetail .butn button:last-child{padding: 13.5px 39.5px;}
			.changePassword .butn button:last-child{padding: 13.5px 23.5px;}
			.account-login form span.plc{font-size: 16px;}
			.account-login .main{width: 674px;}
			.account-login .main .content{width: 574px;}
			.social-account p{font-size: 16px;}
			.switch-account{font-size: 16px;}
			.social-account .icons img{width: auto;}
			.login-logo img{width: auto;}
			.after .custom-select ul{font-size: 15px;}
		}


		@media (max-width:990.99px){
			/* my account */	
			.tab-head ul li a{
				padding-left:15px;
				padding-right:15px;
			}

			.my-account .content{
				flex-direction: column;
			}
			.tab-head{
				width: 100%;
				border-radius: 5px 5px 0px 0px;
				margin-bottom: 2px;
			}
			.tab-head ul{
				height: 57px;
				padding-top: 0;
				margin-bottom: 0;
			}
			.tab-head ul li{
				border-radius: 5px 5px 0px 0px;
			}
			.tab-head ul li a:not(.active){
				float: left;
/*				display: none;*/
				width: 100%;
			}
			.tab-head ul li a:not(.active).SH{display: flex;}
			.tab-head ul li a.active{height: 57px;}
			.tab-body{
				width: 100%;
				padding-top: 10px;
				padding-left: 15px;
				padding-right: 15px;	
/*				padding-bottom: 48px;*/
				border-radius: 0px 0px 5px 5px;
				border-left: 1px solid var(--green-color);
			}
/*			.tab-body .form-field{width: 48%;}*/
			.tab-body input{
				/*width: 48%;*/
				width: 100%;
			}
/*			.tab-body .after{width: 48%;}*/
			.tab-body .select{
				width: 100%;
			}
			.mr-42{margin-right: 0;}
			.account-forms form{
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
			}
			.butn{width: 100%;}
			.tab-head .tab-arr{display: block;}

			/* account */
			.addresses .m-set{width: 48%;}
			.addresses .m-set .after{width: 100%;}
			.addresses .m-set .form-field{width: 100%;}
			.addresses .butn{display: flex;justify-content: space-between;flex-wrap: wrap;}
			.addresses button{padding: 13.5px 8.5%;}
			.accountDetail .butn button{padding: 11.5px 32.5px;}
			.changePassword .butn button{padding: 11.5px 50.5px;}

			/* rmk inner page */
			.rmk-innerpage .main .detail-slider{flex-direction: column;}
			.rmk-innerpage .detail-slider .slider{
				width: 80%;
				margin-left: auto;
				margin-right: auto;
				margin-bottom: 32px;
			}
			.slider-detail{width: 100%;}

			/* ai first page */
			.ai-first-steps .input-form form{width: 100%;}
			/*.ai-first-steps .step-1 .input-form .form-field-select .after{width: 100%;}*/
			.ai-first-steps .step-2 .taking-front-photo .butn{
				display: flex;
				flex-direction: column;
			}
			.ai-first-steps .step-2 .taking-front-photo .butn button:first-child{
				margin-right: 0;
				margin-bottom: 36px;
			}
			.ai-first-steps .step-4 .taking-front-photo .butn{
				display: flex;
				flex-direction: column;
			}
			.ai-first-steps .step-4 .taking-front-photo .butn button:first-child{
				margin-right: 0;
				margin-bottom: 36px;
			}
			.ai-first-steps .step-6 .taking-front-photo .butn{
				display: flex;
				flex-direction: column;
				align-items: center;
			}
			.get-measurement .taking-btn .butn span{margin: 8px 0;}
		}

		@media (max-width:767.99px){
			.shipping-list{
				margin-bottom:24px;
			}
			.selec-row{
				width:100%!important;
			}
			.form_row{
				flex-direction:column;
			}
			.main-form-control{
				width:100%;
			}
			.account-detail-input{
				flex-direction:column;
			}
			.m-set.ff-row{
				flex-direction:column;
			}
			.account-detail-input .m-set.ff-row .after{
				width:100%;
			}
			/* my account */
			.tab-body .form-field{width: 100%;}
			.tab-body input{width: 100%;}
			.tab-body .after{width: 100%;}
			.tab-body .form-field .imp{left: 0;}
			.tab-body .after .imp{left: 2.5%;}
			.dashboardMsg{
				height: auto;
				padding: 25px 14px 8px 21px;
			}
			.my-orders{overflow-y: scroll;}
			.my-orders table{width: 800px;}
			.addresses .m-set{width: 100%;}
			.address-tab div{
				display: flex;
				flex-wrap: wrap;
			}
			.address-tab div a{margin-bottom: 16px;}
			.account-login .main{width: 90%;}
			.account-login form .form-field{margin-bottom: 22px;}
			.account-login form .butn{
				margin-top: 28px;
				margin-bottom: 24px;
			}
			.account-login .main .content{width: 95%;}
			.social-account .icons{margin-bottom: 0;}
			.account-login .login .switch-account{margin-bottom: 24px!important;}
			.switch-account{margin-top: 22px;}
		}

		@media (max-width:468px){
			/* my account */
/*			.tab-body .form-field .imp{left: 3%;}*/
			.tab-body .after .imp{left: 4%;}
			.addresses button{margin-right: 0;}
			.address-tab div{justify-content: center;}
			.address-tab div a{margin-right: 0;}
			.payment-tab{justify-content: center;}
			.accountDetail .butn{flex-direction: column;}
			.changePassword .butn{flex-direction: column;}
		}
		.accountDetail .butn button{
			margin-right:0!important;
		}

		@media (max-width:)
	</style>
</head>
<body>
	<div class="container">
		<div class="my-account">
			<div class="width fadeInDown">
				<div class="main">
					<div class="content">
						<div class="tab-head">
							<ul>
								<li>
									<a href="#dashboard" class="tab-head-font active">
										<img src="images/dashboard.png" alt="">
										<p>My Dashboard</p>
									</a>
								</li>
								<li>
									<a href="#orders" class="tab-head-font">
										<img src="images/orders.png" alt="">
										<p>My Orders</p>
									</a>
								</li>
								<li>
									<a href="#addresses" class="tab-head-font">
										<img src="images/addresses.png" alt="">
										<p>Address</p>
									</a>
								</li>
								<!-- <li>
									<a href="#paymentMethod" class="tab-head-font">
										<img src="images/payment-method.png" alt="">
										<p>Payment Methods</p>
									</a>
								</li> -->
								<li>
									<a href="#accountDetail" class="tab-head-font">
										<img src="images/account-detail.png" alt="">
										<p>Account Details</p>
									</a>
								</li>
								<li>
									<a href="#changePassword" class="tab-head-font">
										<img src="images/change-password.png" alt="">
										<p>Change Password</p>
									</a>
								</li>
								<li>
									<a onclick="logout('{{ route("customerLogout") }}')" href="{{ route('customerLogout') }}" class="tab-head-font">
										<img src="images/logout.png" alt="">
										<p>Logout</p>
									</a>
								</li>
							</ul>
							<span class="tab-arr"><img src="{{ asset('public/frontend') }}/images/ExpandMore.png" alt=""></span>
						</div>
						<div class="tab-body account-tab-body">
							<div class="data account-forms dashboard active" id="dashboard">
								<div class="form">
									<h1 class="tab-body-main-font uppercase">My Dashboard</h1>
									<div class="dashboardMsg">
										<h2>Hello {{ $customer->name }} (If not {{ $customer->name }}) <a href="{{ route('customerLogout') }}">Log out?</a></h2>
										<p>From your account Dashboard you can view your recent orders, manage your shipping, update billing addresses, edit your password and account details.</p>
									</div>
								</div>
							</div>
							<div class="data account-forms orders" id="orders">
								<div class="form">
									<h1 class="tab-body-main-font uppercase">My Orders</h1>
									<div class="my-orders">
										<table border="0">
											<thead>
												<tr>
													<td>Order ID</td>
													<td>Order Date</td>
													<td>Product Name</td>
													<td>Qty</td>
													<td>Price</td>
													<td>Status</td>
												</tr>
											</thead>
											<tbody>
												@if(!empty($orders))
												@foreach($orders as $order)
												<tr>
													<td>{{ strtoupper($order->order_id) }}</td>
													<td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
													<td>{{ $order->product_name }}</td>
													<td>{{ $order->qty }}</td>
													<td>&#8377;{{ $order->paid_amount }}</td>
													<td>
														@if($order->status == 'unpaid')
															<a href="{{ route('payNow', ['orderid' => $order->order_id]) }}">Pay Now</a>
														@else
															{{ strtoupper($order->status) }}
														@endif
													</td>
												</tr>
												@endforeach
												@endif
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="data account-forms addresses" id="addresses">
									<h1 class="tab-body-main-font uppercase">Your Addresses</h1>
									<p class="tab-body-para-font">Mandatory fields are marked <span>*</span></p>
									<div class="address-tab">
										<div>
											<a href="#shippingAddress" class="active">Shipping Address</a>
											<a href="#billingAddress">Billing Address</a>
										</div>
									</div>
									<div class="address-tab-body">
										<p id="shippingAddressFormMsg"></p>
										<div class="data shippingAddress active" id="shippingAddress">
											<p>The following addresses will be used on the checkout page by default.</p>
											<form id="shippingAddressForm" method="post">
												<div class="form_row">
													<div class="main-form-control">
														<div class="form-field">
															<input type="text" name="shippingName" class="mr-42" value="{{ isset($address->shipping_name)? $address->shipping_name:'' }}">
															<span class="imp">*<span class="plc">Shipping Name</span></span>
														</div>
														<span class="errors" id="shippingNameErr"></span>
													</div>
													
													<div class="main-form-control">
														<div class="form-field">
															<input type="text" name="shippingEmail" class="mr-42" value="{{ isset($address->shipping_email)? $address->shipping_email:'' }}">
															<span class="imp">*<span class="plc">Shipping Email</span></span>
															<span class="errors" id="shippingEmailErr"></span>
														</div>
												</div>
												</div>
												<div class="main-form-control">
													<select class="shipping-list">
														<option data-state-shipping="">Select state</option>
		                        <option data-state-shipping="AN">Andaman and Nicobar Islands</option>
		                        <option data-state-shipping="AP">Andhra Pradesh</option>
		                        <option data-state-shipping="AR">Arunachal Pradesh</option>
		                        <option data-state-shipping="AS">Assam</option>
		                        <option data-state-shipping="BR">Bihar</option>
		                        <option data-state-shipping="CH">Chandigarh</option>
		                        <option data-state-shipping="CT">Chhattisgarh</option>
		                        <option data-state-shipping="DN">Dadra and Nagar Haveoption</option>
		                        <option data-state-shipping="DD">Daman and Diu</option>
		                        <option data-state-shipping="DL">Delhi</option>
		                        <option data-state-shipping="GA">Goa</option>
		                        <option data-state-shipping="GJ">Gujarat</option>
		                        <option data-state-shipping="HR">Haryana</option>
		                        <option data-state-shipping="HP">Himachal Pradesh</option>
		                        <option data-state-shipping="JK">Jammu and Kashmir</option>
		                        <option data-state-shipping="JH">Jharkhand</option>
		                        <option data-state-shipping="KA">Karnataka</option>
		                        <option data-state-shipping="KL">Kerala</option>
		                        <option data-state-shipping="LA">Ladakh</option>
		                        <option data-state-shipping="LD">Lakshadweep</option>
		                        <option data-state-shipping="MP">Madhya Pradesh</option>
		                        <option data-state-shipping="MH">Maharashtra</option>
		                        <option data-state-shipping="MN">Manipur</option>
		                        <option data-state-shipping="ML">Meghalaya</option>
		                        <option data-state-shipping="MZ">Mizoram</option>
		                        <option data-state-shipping="NL">Nagaland</option>
		                        <option data-state-shipping="OR">Odisha</option>
		                        <option data-state-shipping="PY">Puducherry</option>
		                        <option data-state-shipping="PB">Punjab</option>
		                        <option data-state-shipping="RJ">Rajasthan</option>
		                        <option data-state-shipping="SK">Sikkim</option>
		                        <option data-state-shipping="TN">Tamil Nadu</option>
		                        <option data-state-shipping="TG">Telangana</option>
		                        <option data-state-shipping="TR">Tripura</option>
		                        <option data-state-shipping="UP">Uttar Pradesh</option>
		                        <option data-state-shipping="UT">Uttarakhand</option>
		                        <option data-state-shipping="WB">West Bengal</option>
													</select>
													<span class="errors" id="shippingCityErr"></span>
												</div>

												<div class="main-form-control">
													<span class="m-set">
														
														<div class="form-field">
															<input type="text" name="shippingCity" class="mr-42" value="{{ isset($address->shipping_city)? $address->shipping_city:''; }}">
															<span class="imp">*<span class="plc">Shipping City</span></span>
															<span class="errors" id="shippingCityErr"></span>
														</div>
													</span>
												</div>
												<div class="main-form-control">
													<div class="form-field">
														<input type="text" name="shippingPincode" class="mr-42" value="{{ isset($address->shipping_pincode)? $address->shipping_pincode:'' }}">
														<span class="imp"><span class="plc">Shipping Pincode</span></span>
														<span class="errors" id="shippingPincodeerr"></span>
													</div>
												</div>
												
												<div class="main-form-control">
													<div class="form-field">
														<input type="text" name="shippingPhone" class="mr-42" value="{{ isset($address->shipping_phone)? $address->shipping_phone:'' }}">
														<span class="imp">*<span class="plc">Shipping Phone</span></span>
														<span class="errors" id="shippingPhoneErr"></span>
													</div>
												</div>
												<div class="main-form-control full-row">
														<div class="form-field">
															<input type="text" name="shippingCompanyName" value="{{ isset($address->shipping_company_name)? $address->shipping_company_name:'' }}">
															<span class="imp">*<span class="plc">Shipping Company Name</span></span>
														</div>
														<span class="errors" id="shippingNameErr"></span>
													</div>
												<div class="main-form-control full-row">
													<div class="form-field">
														<textarea  type="text" name="shippingAddress">{{ isset($address->shipping_address)? $address->shipping_address:'' }}</textarea>
														<span class="imp">*<span class="plc">Shipping Address</span></span>
														<span class="errors" id="shippingAddressErr"></span>
													</div>
												</div>
												<div class="main-form-control btn-flex-end">
													<div class="form-field ">
														<div class="butn">
															<button class="uppercase form-btn tab-form-btn-font">Cancel</button>
															<button id="shippingAddressFormBtn" class="uppercase form-btn tab-form-btn-font">Save Address</button>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="data billingAddress" id="billingAddress">
										<p id="billingAddressFormMsg"></p>

											<p>The following addresses will be used on the checkout page by default.</p>
											<form id="billingAddressForm" method="post">
												<div class="form-field">
													<input type="text" name="billingName" class="mr-42" value="{{ isset($address->billing_name)? $address->billing_name:'' }}">
													<span class="imp">*<span class="plc">Billing Name</span></span>
												</div>
												<span class="errors" id="billingNameErr"></span>
							
												<div class="form-field">
													<input type="text" name="billingCompanyName" value="{{ isset($address->billing_company_name)? $address->billing_company_name:'' }}">
													<span class="imp">*<span class="plc">Billing Company Name</span></span>
												</div>
												<span class="m-set">
													<span class="after">
														<div class="select"></div>
														<div class="custom-select">
															<ul class="billing-list">
																<li data-state-billing="">Select state</li>
				                        <li data-state-billing="AN">Andaman and Nicobar Islands</li>
				                        <li data-state-billing="AP">Andhra Pradesh</li>
				                        <li data-state-billing="AR">Arunachal Pradesh</li>
				                        <li data-state-billing="AS">Assam</li>
				                        <li data-state-billing="BR">Bihar</li>
				                        <li data-state-billing="CH">Chandigarh</li>
				                        <li data-state-billing="CT">Chhattisgarh</li>
				                        <li data-state-billing="DN">Dadra and Nagar Haveli</li>
				                        <li data-state-billing="DD">Daman and Diu</li>
				                        <li data-state-billing="DL">Delhi</li>
				                        <li data-state-billing="GA">Goa</li>
				                        <li data-state-billing="GJ">Gujarat</li>
				                        <li data-state-billing="HR">Haryana</li>
				                        <li data-state-billing="HP">Himachal Pradesh</li>
				                        <li data-state-billing="JK">Jammu and Kashmir</li>
				                        <li data-state-billing="JH">Jharkhand</li>
				                        <li data-state-billing="KA">Karnataka</li>
				                        <li data-state-billing="KL">Kerala</li>
				                        <li data-state-billing="LA">Ladakh</li>
				                        <li data-state-billing="LD">Lakshadweep</li>
				                        <li data-state-billing="MP">Madhya Pradesh</li>
				                        <li data-state-billing="MH">Maharashtra</li>
				                        <li data-state-billing="MN">Manipur</li>
				                        <li data-state-billing="ML">Meghalaya</li>
				                        <li data-state-billing="MZ">Mizoram</li>
				                        <li data-state-billing="NL">Nagaland</li>
				                        <li data-state-billing="OR">Odisha</li>
				                        <li data-state-billing="PY">Puducherry</li>
				                        <li data-state-billing="PB">Punjab</li>
				                        <li data-state-billing="RJ">Rajasthan</li>
				                        <li data-state-billing="SK">Sikkim</li>
				                        <li data-state-billing="TN">Tamil Nadu</li>
				                        <li data-state-billing="TG">Telangana</li>
				                        <li data-state-billing="TR">Tripura</li>
				                        <li data-state-billing="UP">Uttar Pradesh</li>
				                        <li data-state-billing="UT">Uttarakhand</li>
				                        <li data-state-billing="WB">West Bengal</li>
															</ul>
														</div>
														<input id="billing" type="hidden" value="{{ isset($address->billing_state)? $address->billing_state:'' }}" name="billingState">
														<span class="imp set_default_billing">*<span class="plc" data-default="{{ isset($address->billing_state)? $address->billing_state:'' }}"></span></span>
														<span class="arw"><img src="{{ asset('public/frontend') }}/images/ExpandMore.png" alt=""></span>
													</span>
													<div class="form-field">
														<input type="text" name="billingCity" class="mr-42" value="{{ isset($address->billing_city)? $address->billing_city:'' }}">
														<span class="imp">*<span class="plc">Billing City</span></span>
														<span class="errors" id="billingCityErr"></span>
													</div>
												</span>
												<div class="form-field">
													<textarea type="text" name="billingAddress">{{ isset($address->billing_address)? $address->billing_address:'' }}</textarea>
													<span class="imp">*<span class="plc">Billing Address</span></span>
													<span class="errors" id="billingAddressErr"></span>
												</div>
												<div class="form-field">
													<input type="text" name="billingPincode" class="mr-42" value="{{ isset($address->billing_pincode)? $address->billing_pincode:'' }}">
													<span class="imp"><span class="plc">Billing Pincode</span></span>
													<span class="errors" id="billingPincodeerr"></span>
												</div>
												<div class="form-field">
													<input type="text" name="billingEmail" class="mr-42" value="{{ isset($address->billing_email)? $address->billing_email:'' }}">
													<span class="imp">*<span class="plc">Billing Email</span></span>
													<span class="errors" id="billingEmailErr"></span>
												</div>
												<div class="form-field">
													<input type="text" name="billingPhone" class="mr-42" value="{{ isset($address->billing_phone)? $address->billing_phone:'' }}">
													<span class="imp">*<span class="plc">Billing Phone</span></span>
													<span class="errors" id="billingPhoneErr"></span>
												</div>
												<div class="form-field">
													<div class="butn">
														<button class="uppercase form-btn tab-form-btn-font mr-36">Cancel</button>
														<button id="billingAddressFormBtn" class="uppercase form-btn tab-form-btn-font">Update Address</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								<div class="add_msg">
									<div class="addressesMsg">
										<p>No saved Addresses currently added or available.</p>
									</div>
									<div class="butn">
										<button class="uppercase form-btn tab-form-btn-font mr-36">Add Billing Address</button>
										<button class="uppercase form-btn tab-form-btn-font">Add Shipping Address</button>
									</div>
								</div>
							</div>
							<!-- <div class="data account-forms paymentMethod" id="paymentMethod">
								<div class="form">
									<h1 class="tab-body-main-font uppercase">Choose Payment Method</h1>
									<p class="tab-body-para-font">Mandatory fields are marked <span>*</span></p>
									<div class="payment-tab">
										<div>
											<a href="#cards" class="active">
												<span></span>
												Credit/Debit Card
											</a>
										</div>
										<div>
											<a href="#cashod" class="">
												<span></span>
												Cash on Delivery
											</a>
										</div>
										<div>
											<a href="#cardod" class="">
												<span></span>
												Cash on Delivery
											</a>
										</div>
									</div>
									<div class="payment-tab-body">
										<div class="data cards active" id="cards">
											<form action="">
												<div class="form-field">
													<input type="text" name="cardnumber" class="mr-42">
													<span class="imp">*<span class="plc">Card Number</span></span>
												</div>
												<div class="form-field">
													<input type="text" name="mmyy">
													<span class="imp">*<span class="plc">MM/YY</span></span>
												</div>
												<div class="form-field">
													<input type="text" name="cvv" class="mr-42">
													<span class="imp">*<span class="plc">CVV</span></span>
												</div>
												<div class="form-field">
													<input type="text" name="nameoncard">
													<span class="imp">*<span class="plc">Name on Card</span></span>
												</div>
												<div class="butn">
													<button class="uppercase form-btn tab-form-btn-font mr-36">Update</button>
													<button class="uppercase form-btn tab-form-btn-font">Remove</button>
												</div>
											</form>
										</div>
										<div class="data cashod" id="cashod">
											<div class="paymentMsg">
												<p>You have chosen cash on delivery payment method for your account.</p>
											</div>
										</div>
										<div class="data cardod" id="cardod">
											<div class="paymentMsg">
												<p>You have chosen card on delivery payment method for your account. </p>
											</div>
										</div>
									</div>
								</div>
								<div class="paymentMsg hide">
									<p>No saved Payment Methods found.</p>
								</div>
							</div> -->

							<div class="data account-forms accountDetail" id="accountDetail">
								<p id="accountDetailFormMsg"></p>
								<div class="form">
									<h1 class="tab-body-main-font uppercase">Account Details</h1>
									<p class="tab-body-para-font">Mandatory fields are marked <span>*</span></p>
									<form id="accountDetailForm" method="post">
										<div class="account-detail-input">
											<div class="form-field">
												<input type="text" name="name" class="mr-42" value="{{ $customer->name }}">
												<span class="imp">*<span class="plc">Name</span></span>
												<span class="errors" id="nameErr"></span>
											</div>
											<div class="form-field">
												<input type="text" name="email" value="{{ $customer->email }}">
												<span class="imp">*<span class="plc">Email</span></span>
												<span class="errors" id="emailErr"></span>
											</div>
										</div>
										<div class="account-detail-input">
											<div class="form-field">
												<input type="text" name="phone" class="mr-42" value="{{ $customer->phone }}">
												<span class="imp">*<span class="plc">Phone</span></span>
												<span class="errors" id="phoneErr"></span>
											</div>

											<div class="form-field">
												<input type="text" name="address" class="mr-42" value="{{ $customer->address }}">
												<span class="imp">*<span class="plc">Address</span></span>
												<span class="errors" id="addressErr"></span>
											</div>
										</div>

										<div class="account-detail-input">
											<span class="m-set ff-row">
												<div class="selec-row" style="width:49%;">
													<select class="shipping-list">
															<option data-state-code="">Select state</option>
				                        <option data-state-code="AN">Andaman and Nicobar Islands</option>
				                        <option data-state-code="AP">Andhra Pradesh</option>
				                        <option data-state-code="AR">Arunachal Pradesh</option>
				                        <option data-state-code="AS">Assam</option>
				                        <option data-state-code="BR">Bihar</option>
				                        <option data-state-code="CH">Chandigarh</option>
				                        <option data-state-code="CT">Chhattisgarh</option>
				                        <option data-state-code="DN">Dadra and Nagar Haveoption</option>
				                        <option data-state-code="DD">Daman and Diu</option>
				                        <option data-state-code="DL">Delhi</option>
				                        <option data-state-code="GA">Goa</option>
				                        <option data-state-code="GJ">Gujarat</option>
				                        <option data-state-code="HR">Haryana</option>
				                        <option data-state-code="HP">Himachal Pradesh</option>
				                        <option data-state-code="JK">Jammu and Kashmir</option>
				                        <option data-state-code="JH">Jharkhand</option>
				                        <option data-state-code="KA">Karnataka</option>
				                        <option data-state-code="KL">Kerala</option>
				                        <option data-state-code="LA">Ladakh</option>
				                        <option data-state-code="LD">Lakshadweep</option>
				                        <option data-state-code="MP">Madhya Pradesh</option>
				                        <option data-state-code="MH">Maharashtra</option>
				                        <option data-state-code="MN">Manipur</option>
				                        <option data-state-code="ML">Meghalaya</option>
				                        <option data-state-code="MZ">Mizoram</option>
				                        <option data-state-code="NL">Nagaland</option>
				                        <option data-state-code="OR">Odisha</option>
				                        <option data-state-code="PY">Puducherry</option>
				                        <option data-state-code="PB">Punjab</option>
				                        <option data-state-code="RJ">Rajasthan</option>
				                        <option data-state-code="SK">Sikkim</option>
				                        <option data-state-code="TN">Tamil Nadu</option>
				                        <option data-state-code="TG">Telangana</option>
				                        <option data-state-code="TR">Tripura</option>
				                        <option data-state-code="UP">Uttar Pradesh</option>
				                        <option data-state-code="UT">Uttarakhand</option>
				                        <option data-state-code="WB">West Bengal</option>
														</select>
												</div>
												<div class="form-field">
													<input type="text" name="city" class="mr-42" value="{{ $customer->city }}">
													<span class="imp">*<span class="plc">City</span></span>
													<span class="errors" id="cityErr"></span>
												</div>
											</span>
										
										</div>
										<div >
											<div class="form-field btn-flex-end">
												<div class="butn">
													<button class="uppercase form-btn tab-form-btn-font" style="margin-right: 8px;">Cancel</button>
													<button id="accountDetailFormBtn" class="uppercase form-btn tab-form-btn-font">Save Changes</button>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>						
							<div class="data account-forms changePassword" id="changePassword">

								<span id="changePasswordFormMsg" class="msgErr errors"></span>
								<div class="form">
									<h1 class="tab-body-main-font uppercase">Change Password</h1>
									<p class="tab-body-para-font">Mandatory fields are marked <span>*</span></p>
									<form id="changePasswordForm" method="post">
										<div class="account-detail-input">
											<div class="form-field">
												<input type="password" name="password" class="mr-42">
												<span class="imp">*<span class="plc">Current Password</span></span>

												<span class="errors" id="passwordErr"></span>

											</div>
											<div class="form-field">
												<input type="password" name="newPassword">
												<span class="imp">*<span class="plc">New Password</span></span>

												<span class="errors" id="newPasswordErr"></span>

											</div>
										</div>
										<div class="form-field">
											<input type="password" name="confirmPassword" class="mr-42">
											<span class="imp">*<span class="plc">Confirm Password</span></span>

											<span class="errors" id="confirmPasswordErr"></span>

										</div>
										<div class="form-field">
											<div class="butn">
												<button class="uppercase form-btn tab-form-btn-font" style="margin-right: 8px;">Cancel</button>
												<button id="changePasswordBtn" class="uppercase form-btn tab-form-btn-font">Update Password</button>
											</div>
										</div>
									</form>
								</div>
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
	// account tabs
  if($(window).width() < 991){

    $('.tab-head ul').on('click', ".active", function() {
      var a = $(this).closest("ul").children('li').children('a:not(.active)').toggleClass('SH');
      console.log(a);
    });

    var allOption = $('.tab-head ul').children('li').children('a:not(.active)');
    // console.log(allOption)
    $('.tab-head ul').on('click', 'a:not(.active)', function() {
      allOption.removeClass('selected');
      $(this).addClass('selected');
      $('.tab-head ul li').children('.active').html($(this).html());
      var id = $(this).attr('href');
      $('.tab-body .account-forms').removeClass('active');
      $(id).addClass('active');
      allOption.toggleClass('SH');
    });
    
  }else{

    $('.tab-head ul li a').click(function() {
    	event.preventDefault();
      $('.tab-head ul li a').removeClass('active');
      $(this).addClass('active');
      var id = $(this).attr('href');
      $('.tab-body .account-forms').removeClass('active');
      $(id).addClass('active');
      // console.log(id);
    });

  }

  // address tab
  $('.address-tab div a').click(function(e) {
    e.preventDefault();
    $('.address-tab div a').removeClass('active');
    $(this).addClass('active');
    var id = $(this).attr('href');
    $('.address-tab-body div').removeClass('active');
    $(id).addClass('active');
  });

  // payment tab
  $('.payment-tab div a').click(function(){
    $('.payment-tab div a').removeClass('active');
    $(this).addClass('active');
    var id = $(this).attr('href');
    $('.payment-tab-body .data').removeClass('active');
    $(id).addClass('active');
  });

  // checkout payment method tab
  $('.choose-payment-method-tab .cpmt-tab .tab a').click(function(){
    $('.choose-payment-method-tab .cpmt-tab .tab a').removeClass('active');
    $(this).addClass('active');
    var id = $(this).attr('href');
    $('.choose-payment-method-tab .cpmt-tab .tab-body-content').removeClass('active');
    $(id).addClass('active');
  });

  // checkout address detail tab 
  $('.billing-detail-form .tab a').click(function(){
    $('.billing-detail-form .tab a').removeClass('active');
    $(this).addClass('active');
    var id = $(this).attr('href');
    $('.billing-detail-form .tab-body-content').removeClass('active');
    $(id).addClass('active');
  });


  // remove plc from input on click
  $('.form-field input').focusin(function(){
    $(this).next('.imp').css('opacity','0');
    $(this).css('background','#fff');
  });
  $('.form-field input').focusout(function(){
    $(this).next('.imp').css('opacity','1');
    $(this).css('background','transparent');
    // var a = $(this).val();
    if($(this).val()){
      // console.log(a)
      $(this).next('.imp').css('opacity','0');
      $(this).css('background','#fff');
    }
    // console.log(a);
    // console.log('input clicked');
  });
   $('.form-field textarea').focusin(function(){
    $(this).next('.imp').css('opacity','0');
    $(this).css('background','#fff');
    // console.log('input clicked');
  });
  $('.form-field textarea').focusout(function(){
    $(this).next('.imp').css('opacity','1');
    $(this).css('background','transparent');
    if ($(this).val()){
      $(this).next('.imp').css('opacity','0');
      $(this).css('background','#fff');
    }
    // console.log('input clicked');
  });
  $('.form-field-input input').focusin(function(){
    $(this).next('.imp').css('opacity','0');
    $(this).css('background','#fff');
    // console.log('input clicked');
  });
  $('.form-field-input input').focusout(function(){
    $(this).next('.imp').css('opacity','1');
    $(this).css('background','transparent');
    if ($(this).val()){
      $(this).next('.imp').css('opacity','0');
      $(this).css('background','#fff');
    }
    // console.log('input clicked');
  });
  $('.form-field-textarea textarea').focusin(function(){
    $(this).next('.imp').css('opacity','0');
    $(this).css('background','#fff');
    // console.log('input clicked');
  });
  $('.form-field-textarea textarea').focusout(function(){
    $(this).next('.imp').css('opacity','1');
    $(this).css('background','transparent');
    if ($(this).val()){
      $(this).next('.imp').css('opacity','0');
      $(this).css('background','#fff');
    }
    // console.log('input clicked');
  });

$('.form-field input').each(function() {
    var checkVal = $(this).val();
    if (checkVal) {
        $(this).siblings('.imp').css('opacity', '0');
        $(this).css('background', '#fff');
    }
});
$('.form-field textarea').each(function() {
    var checkVal = $(this).val();
    if (checkVal) {
        $(this).siblings('.imp').css('opacity', '0');
        $(this).css('background', '#fff');
    }
});

  // custom select
  $('.after').click(function(){
   var a = $(this).children('.custom-select').slideToggle('slow');
   var b = $(this).children('.imp').text();
   var text = $(this).children('.imp');
   // var b = $(a).next('.custom-select');
   // console.log(b);
   var c = $(this).children('.custom-select').children('ul').children('li');
   // console.log(c)
   $(c).click(function(){
    var d = $(this).text();
    $(text).text(d);
    $(text).css('color','#a0a0a0');
    // b.html(d);
    // console.log(b);
   });
  });

  // customize section tabs
  $('.customize-section .customize-tab .tab-main').click(function(){
    $('.customize-section .customize-tab .tab-main').removeClass('active');
    $(this).addClass('active');
    var $id = $(this).attr('href');
    $('.customize-section .customize-tab .tab-content').removeClass('active');
    $($id).addClass('active');
  });
  $('.customize-section .customize-tab .tab-content div a').click(function(){
    $('.customize-section .customize-tab .tab-content div a').removeClass('toggleSelect');
    $(this).addClass('toggleSelect');
  });

  // increament decreament counter
    const minus = $('.quantity__minus');
    const plus = $('.quantity__plus');
    const input = $('.quantity__input');
    minus.click(function(e) {
      e.preventDefault();
      var value = input.val();
      if (value > 1) {
        value--;
      }
      input.val(value);
    });
    
    plus.click(function(e) {
      e.preventDefault();
      var value = input.val();
      value++;
      input.val(value);
    });

</script>
<!-- <script src="js/script.js"></script> -->

<script>
$(document).ready(function(){
  $('.state-list li').click(function(){
    var dataVal = $(this).attr('data-state-code');
    $('#state').val(dataVal);
   });
  $('.shipping-list li').click(function(){
    var dataVal = $(this).attr('data-state-shipping');
    $('#shipping').val(dataVal);
    // console.log(dataVal)
   });
  $('.billing-list li').click(function(){
    var dataVal = $(this).attr('data-state-billing');
    $('#billing').val(dataVal);
    // console.log(dataVal)
   });

  var liData = document.querySelectorAll('.state-list li[data-state-code]');
  var dData = $('.set_default_state .plc').attr('data-default');
  var liData1 = document.querySelectorAll('.shipping-list li[data-state-shipping]');
  var dData1 = $('.set_default_shipping .plc').attr('data-default');
  var liData2 = document.querySelectorAll('.billing-list li[data-state-billing]');
  var dData2 = $('.set_default_billing .plc').attr('data-default');
  
  liData.forEach(function(li){
  	if(li.getAttribute('data-state-code') === dData){
  		var defaultLi = li.innerText;
  		$('.set_default_state .plc').html(defaultLi)
  	}
  });
  liData1.forEach(function(li){
  	if(li.getAttribute('data-state-shipping') === dData1){
  		var defaultLi = li.innerText;
  		$('.set_default_shipping .plc').html(defaultLi)
  	}
  });
  liData1.forEach(function(li){
  	if(li.getAttribute('data-state-billing') === dData1){
  		var defaultLi = li.innerText;
  		$('.set_default_billing .plc').html(defaultLi)
  	}
  });

});
</script>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#changePasswordForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("customerChangePassword") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#changePasswordFormBtn").html('Sending...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#changePasswordFormMsg').html(res.msg);
              }
          } else {
              $("#changePasswordForm")[0].reset();
              $('#changePasswordFormMsg').html(res.msg).show();
          }


          $("#changePasswordFormBtn").html('Save Changes');
        }
      })

    });
  
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#accountDetailForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doUpdateAccDetails") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#accountDetailFormBtn").html('Sending...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#accountDetailFormMsg').html(res.msg);
              }
          } else {
              // $("#accountDetailForm")[0].reset();
              $('#accountDetailFormMsg').html(res.msg).show();
          }


          $("#accountDetailFormBtn").html('Save Changes');
        }
      })

    });
  
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#shippingAddressForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doSaveShippingAddress") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#shippingAddressFormBtn").html('Sending...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#shippingAddressFormMsg').html(res.msg);
              }
          } else {
              // $("#shippingAddressForm")[0].reset();
              $('#shippingAddressFormMsg').html(res.msg).show();
          }


          $("#shippingAddressFormBtn").html('Save Changes');
        }
      })

    });
  
  });
</script>


<script type="text/javascript">

	function logout(url) {
		window.location.href = url;
	}

  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#billingAddressForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("doSaveBillingAddress") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#billingAddressFormBtn").html('Sending...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#billingAddressFormMsg').html(res.msg);
              }
          } else {
              // $("#billingAddressForm")[0].reset();
              $('#billingAddressFormMsg').html(res.msg).show();
          }


          $("#billingAddressFormBtn").html('Save Changes');
        }
      })

    });
  
  });
</script>



@endsection