<x-mail::message>
Reminder for Your Event

You scheduled to will attend {{ $data->title }} event.

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
