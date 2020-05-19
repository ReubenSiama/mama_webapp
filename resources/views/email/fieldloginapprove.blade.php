@component('mail::layout')
{{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => 'https://mamahome360.com'])
           Welcome
        @endcomponent
    @endslot
Hello ,

Your Request is Approved!

@component('mail::button', ['url' => 'https://mamahome360.com/webapp/login'])
Click Here To Login
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
