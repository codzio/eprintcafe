@extends('vwFrontMaster')

@section('content')
<style type="text/css">
  #documents {
    padding: 100px;
  }
</style>

<!-- Content -->
<div id="content">
    
    <form id="documents" method="post" enctype="multipart/form-data" action="{{ route('doUploadDropbox') }}">
      <input type="file" name="documents">
      @csrf
      <input type="submit" value="Upload">
    </form>

</div>

<script type="text/javascript">
  $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  
  });
</script>

@endsection

