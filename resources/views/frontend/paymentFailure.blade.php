@extends('vwFrontMaster')

@section('content')
    
<!--======= SUB BANNER =========-->
<section class="sub-bnr" data-stellar-background-ratio="0.5">
<div class="position-center-center">
  <div class="container">
    <h4>Payment Failed</h4>
    <!--<p>We're Ready To Help! Feel Free To Contact With Us</p>-->
    <ol class="breadcrumb">
      <li><a href="{{ route('homePage') }}">Home</a></li>
      <li class="active">Payment Failed</li>
    </ol>
  </div>
</div>
</section>

<!-- Content -->
<div id="content"> 

<!--======= CONATACT  =========-->
<section class="contact padding-top-100 padding-bottom-100">
  <div class="container">
    <div class="contact-form">
      <h5 style="text-align: center;">Unable to Process Payment ! <br> Please try again.</h5>
    </div>
  </div>
</section>


</div>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#contactForm").submit(function(event) {
      event.preventDefault();

      formData = $(this).serialize();

      $.ajax({
        url: '{{ route("getContact") }}',
        type: 'POST',
        dataType: 'json',
        data: formData,
        beforeSend: function() {
          $("#contactFormBtn").html('Sending...');
          $(".errors").html('');
        }, success: function(res) {

          if (res.error == true) {
              if (res.eType == 'field') {
                  $.each(res.errors, function(index, val) {
                      $("#"+index+"Err").html(val);
                  });
              } else {
                  $('#contactFormMsg').html(res.msg);
              }
          } else {
              $("#contactForm")[0].reset();
              $('#contactFormMsg').html(res.msg).show();
          }


          $("#contactFormBtn").html('SEND Message');
        }
      })

    });
  
  });
</script>

@endsection

