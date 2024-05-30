@php
		
	$docs = json_decode($fileList);

@endphp

@if(!empty($docs))
	@foreach($docs as $doc)
		<div>
			<p><a target="_blank" href="https://drive.google.com/file/d/{{ $doc->fileId }}">View Document</a></p>
			<div class="image-area">
		  		<img src="{{ asset('public/media') }}/dummy.jpg"  alt="">
		  		<a onclick="removeDoc(this)" data-url="{{ route('doDeleteDropbox') }}" data-id="{{ $doc->fileId }}" class="remove-image" href="javascript:void(0)" style="display: inline;">&#215;</a>
			</div>
		</div>
	@endforeach
@endif