@extends('template.app')

@section('main-content')
 <livewire:noticeofintent.schoolfees-component :results="$results" />
 <script>
        document.addEventListener('go-back', () => {
        window.history.back();
    });
 </script>
@endsection
