@extends('template.app')

@section('main-content')
<livewire:payments.allpayments :results="$results" />

@endsection
