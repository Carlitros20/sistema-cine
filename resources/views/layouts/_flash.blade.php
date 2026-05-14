@if(session('success'))
    <div class="alert-cine alert-cine-success">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if($errors->has('devolucion'))
    <div class="alert-cine alert-cine-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first('devolucion') }}
    </div>
@endif
