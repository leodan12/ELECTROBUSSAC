@extends('layouts.admin')
@push('css')
<style type="text/css">

</style>

@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                    <div class="me-md-3 me-xl-5">
                        @if (session('message'))
                            <h2 class="alert alert-success">{{ session('message') }}</h2>
                        @endif
                    </div>

                </div>

            </div>
            <div class="row">
                <div class="col-md-3 ingresos" >  INGRESOS</div>
                <div class="col-md-3 ventas"></div>
                <div class="col-md-3 cotizaciones">  </div>
                <div class="col-md-3 otro"></div>
            </div>



        </div>
    </div>
    <div class="row">
        <div class="col">


        </div>
    </div>
@endsection


@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toast').toast();
            $('.toast').toast('show');
        });
    </script>
@endpush
