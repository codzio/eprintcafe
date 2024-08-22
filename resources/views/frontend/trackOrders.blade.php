@extends('vwFrontMaster')

@section('content')

<style type="text/css">
  .vision-text h2 {
    font-family: inherit;
  }

  .heading span {
    font-family: inherit;
  }

  /*21-08-2024*/
  .track-order-main .form-group.track_form{
    margin:1% 0;
  }

  #passwordHelpInline{
/*    margin-top:12%;*/
    background: var(--secondary-color-3);
  }
  .btn-main-row{
    display:flex;
    align-items:center;
    row-gap:20px;
    column-gap:5%;
  }
  #passwordHelpInline button:hover{
    background:var(--secondary-color-3)!important;
  }
  #passwordHelpInline button:focus{
    background:var(--secondary-color-3)!important;
  }
  .track-order-main label{
    font-size:20px;
  }
  .track-order-main input{
    border: 2px solid var(--secondary-color-3);
    padding: 23px 16px;
    color: #000;
  }
   .track-order-main input::placeholder{
    color:#000;
   }
   .form-group.track_form{
    width:58%;
   }


   @media (max-width:600px){
    .sub-bnr{
      min-height:180px;
    }

   }

  /*21-08-2024*/
</style>
    
<!--======= SUB BANNER =========-->
<section class="sub-bnr" data-stellar-background-ratio="0.5">
  <div class="position-center-center">
    <div class="container">
      <h4>Track Orders</h4>
      <ol class="breadcrumb">
        <li><a href="{{ route('homePage') }}">Home</a></li>
        <li class="active">Track Orders</li>
      </ol>
    </div>
  </div>
</section>

<div id="content" class="track-order-main"> 
  <section class="light-gray-bg padding-top-50" style="background:#fff!important; margin:2% 0;">
    <div class="container">
      <div class="col-sm-12">
        <div class="panel panel-primary" style="border-color:#376513 !important; margin-bottom: 0;">
          <div class="panel-heading" style="background:#333 !important; border-color:#376513 !important; font-size:24px; padding-top:13px; color: #fff; padding-bottom:13px; text-align: center; font-weight:700;">TRACK ORDER</div>
          <br>

          <center>
            <form id="trackOrderForm" class="form-inline" method="post" action="{{ route('doTrackOrder') }}">
              <div class="form-group track_form">
                <div class="btn-main-row">
                  <div style="width:100%;">
                    <input type="text" id="orderId" name="orderId" style="width:100%;" class="form-control mx-sm-3" placeholder="Order Id">
                  </div>
                  <div id="passwordHelpInline" class="text-muted" style="border-radius:11px;">
                    <button id="trackOrderFormBtn" class="btn btn-lg btn-primary" type="submit" style="border-color:var(--secondary-color-3)!important;padding: 1px 27px; border-radius:6px; font-size:16px;">Track Order</button>
                  </div>
                </div>
              </div>
              <div class="form-group track_form" style="margin:0%">
                <div class="btn-main-row">
                  <div id="msg" style="width:100%;text-align: left;"></div>
                </div>
              </div>
            </form>
          </center>

          <br>  
        </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript">
  $("#trackOrderForm").submit(function(event) {
    event.preventDefault();

    url = $(this).attr('action');
    formData = $(this).serialize();

    $.ajax({
      url: url,
      type: 'POST',
      dataType: 'JSON',
      data: formData,
      beforeSend: function() {
        $("#trackOrderFormBtn").html('Please Wait...');
        $("#msg").html('');
      },
      success: function(res) {

        if (res.error == true) {
          if (res.eType == 'field') {
            $("#msg").html(res.errors.orderId[0]).css('color', 'red');
          } else {
            $("#msg").html(res.msg).css('color', 'red');
          }
        } else {
          $("#msg").html(res.msg).css('color', res.color);
        }

        $("#trackOrderFormBtn").html('Track Order');
      }
    })
    

  });
</script>

@endsection