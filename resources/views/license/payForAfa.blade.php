@extends('template.app')
@section('main-content')

<livewire:license.pay-for-afa :application_id="$application_id" />
<script>
    document.addEventListener('go-back', () => {
        window.history.back();
    });
</script>
@endsection
