@extends('template.app')

@section('main-content')
<livewire:expressionofinterest.pay-for-eoi :application_id="$application_id"/>
<script>
    document.addEventListener('go-back', () => {
        window.history.back();
    });
</script>
@endsection
