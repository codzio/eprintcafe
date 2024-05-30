<div class="col-sm-2">
  <label for="login-form-username">PAGES: </label>
  <input onchange="noOfPages()" id="noOfPages" name="noOfPages" class="required sm-form-control input-block-level" type="number" min="1" value="1">
</div>
<div class="col-sm-2">
  <label for="login-form-username">COPIES: </label>
  <input onchange="noOfCopy()" id="noOfCopies" name="noOfCopies" class="required sm-form-control input-block-level" type="number" min="1" value="1">
</div>
<div class="col-sm-2">
  <label for="login-form-username">PAPER SIZE: </label>
  <select onchange="updatePaperSize(this)" id="paperSize" name="paperSize" class="required sm-form-control input-block-level">
    <option value="">Select Paper Size</option>
    @if(!empty($paperSize))
    @foreach($paperSize as $paperSize)
    <option {{ ($defPaperSize->id == $paperSize->id)? 'selected':''; }} value="{{ $paperSize->id }}">{{ $paperSize->size }}</option>
    @endforeach
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">PAPER GSM: </label>
  <select onchange="updatePaperGsm(this)" id="paperGsm" name="paperGsm" class="required sm-form-control input-block-level">
    @if(!empty($defGsmOpt))
      {!! $defGsmOpt !!}
    @else
      <option value="">Select Paper GSM</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">PAPER TYPE: </label>
  <select onchange="updatePaperType(this)" id="paperType" name="paperType" class="required sm-form-control input-block-level">
    @if(!empty($defPaperTypeOpt))
      {!! $defPaperTypeOpt !!}
    @else
      <option value="">Select Paper Type</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">PRINT SIDE: </label>
  <select onchange="updatePaperSides(this)" id="paperSides" name="paperSides" class="required sm-form-control input-block-level">
    @if(!empty($defPaperSidesOpt))
      {!! $defPaperSidesOpt !!}
    @else
      <option value="">Select Sides</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">COLOR: </label>
  <select onchange="updateColor(this)" id="color" name="color" class="required sm-form-control input-block-level">
    @if(!empty($defPaperColorOpt))
      {!! $defPaperColorOpt !!}
    @else
      <option value="">Select Color</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">BINDING: </label>
  <select onchange="updateBinding(this)" id="binding" name="binding" class="required sm-form-control input-block-level">
    @if(!empty($defBindingOpt))
      {!! $defBindingOpt !!}
    @else
      <option value="">Select Binding</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">LAMINATION: </label>
  <select onchange="updateLamination(this)" id="lamination" name="lamination" class="required sm-form-control input-block-level">
    @if(!empty($defLaminationOpt))
      {!! $defLaminationOpt !!}
    @else
      <option value="">Select Lamination</option>
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">COVER: </label>
  <select id="cover" name="cover" class="required sm-form-control input-block-level">
    <option value="">Select Cover</option>
    @if(!empty($covers))
    @foreach($covers as $cover)
    <option value="{{ $cover->id }}">{{ $cover->cover }}</option>
    @endforeach
    @endif
  </select>
</div>
<div class="col-sm-2">
  <label for="login-form-username">&nbsp;</label>
  <button onclick="calculatePrice(this)" type="button" class="cal-button">Calculate Price</button>
</div>
