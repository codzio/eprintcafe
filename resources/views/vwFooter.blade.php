<script src="{{ asset('public/frontend') }}/js/bootstrap.min.js"></script> 
<script src="{{ asset('public/frontend') }}/js/own-menu.js"></script> 
<script src="{{ asset('public/frontend') }}/js/jquery.lighter.js"></script> 
<script src="{{ asset('public/frontend') }}/js/owl.carousel.min.js"></script> 

<!-- SLIDER REVOLUTION 4.x SCRIPTS  --> 
<script type="text/javascript" src="{{ asset('public/frontend') }}/rs-plugin/js/jquery.tp.t.min.js"></script> 
<script type="text/javascript" src="{{ asset('public/frontend') }}/rs-plugin/js/jquery.tp.min.js"></script> 
<script src="{{ asset('public/frontend') }}/js/main.js"></script> 
<script type="text/javascript">

// start hover dropdown

  function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }

  function openCityMobile(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
    setTimeout(function() {
      $(".mobile").addClass('open');
    }, 10);
  }

// end hover dropdown

  const counters = document.querySelectorAll(".counter");

counters.forEach((counter) => {
  counter.innerText = "0";

  const updateCounter = () => {
    const target = +counter.getAttribute("data-target");
    const c = +counter.innerText;

    const increment = target / 200;
    console.log(increment);

    if (c < target) {
      counter.innerText = `${Math.ceil(c + increment)}`;
      setTimeout(updateCounter, 1);
    } else {
      counter.innerText = target;
    }
  };

  updateCounter();
});

</script>

  {!! setting('footer_scripts') !!}

</body>
</html>