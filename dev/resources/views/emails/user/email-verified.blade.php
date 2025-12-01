@section('title', __('Email Verification Successful'))
@include('emails.layouts.header')
<h1>{{__('Email Verification Successful')}}</h1>
<p>{{__('Dear :name', ['name' => $user->name])}},</p>
<p>{{__('Your email address has been successfully verified. You can now fully access and enjoy all the features of :name', ['name' => config('app.name')])}}</p>
<p>{{__('If you have any questions or need assistance, please feel free to contact our support team.')}}</p>
@include('emails.layouts.footer')