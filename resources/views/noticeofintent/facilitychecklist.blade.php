@extends('template.app')

@section('main-content')

<livewire:noticeofintent.facilitychecklist-component :results="$results"/>
<script>
    document.addEventListener('go-back', () => {
        window.history.back();
    });
</script>
@endsection
