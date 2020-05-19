@component('mail::layout')
{{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => 'https://mamahome360.com'])
            Mamahome360.com
        @endcomponent
    @endslot
Hello {{ $name }},

We have received your Approval request. We will verify your and Approve in a few minutes.

Time : {{ $time }}<br>
Address : {{ $address }}<br>

@component('mail::button', ['url' => 'https://mamahome360.com/webapp/blogin'])
Click Here to login
@endcomponent

Thanks,<br>
Mamahome Pvt. Ltd.

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        All Rights Reserved https://mamahome360.com
    @endcomponent
@endslot
@endcomponent