@component('mail::layout')
{{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => 'https://mamahome360.com'])
            Mamahome360.com
        @endcomponent
    @endslot
Hello ,

Your Request is Rejected Contact ur TL !

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