@extends('layouts.print')
@section('content')
    @foreach($sales_activity->marketing_material as $no => $marketing_material)
        <div>
            @if($marketing_material->file_type == "jpg" || $marketing_material->file_type == "png")
                <img src="{{ asset($marketing_material->file_path) }}" style="display: block;background-repeat:no-repeat;width:100%;">
            @endif
        </div>

        @if($no + 1 < sizeof($sales_activity->marketing_material))
            <div style="page-break-after:always;"></div>
        @endif
    @endforeach
@endsection
