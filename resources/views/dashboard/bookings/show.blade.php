@extends('dashboard.base')

@section('title', 'Booking Details')

@section('content')

<div id="bookingDetails">

    <div class="py-20 text-center text-zinc-500">

        <i class="fa-solid fa-spinner fa-spin text-3xl"></i>

        <p class="mt-3">
            Loading Booking Details...
        </p>

    </div>

</div>

@endsection

@push('scripts')

<script>
window.bookingDetailsConfig = {
    url: "{{ route('dashboard.bookings.details', $booking->id) }}"
};
</script>

<script src="{{ asset('js/dashboard/booking/show/show.js') }}"></script>

@endpush