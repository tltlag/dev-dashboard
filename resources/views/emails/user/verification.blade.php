@section('title', __('Verify Your Email Address'))
@include('emails.layouts.header')
<h1>{{__('Verify Your Email Address')}}</h1>
<p>{{__('Dear :name', ['name' => $user->name])}},</p>
<p>{{__('Thank you for registering with :name. To complete your registration and access all our features, please click the button below to verify your email address:', ['name' => config('app.name')])}}</p>
<a href="{{$user->getEmailVeifyLink()}}">{{__('Verify Email Address')}}</a>
<p>{{__('If you didn\'t sign up for an account on :name, you can ignore this email. Your account will not be activated until you verify your email.', ['name' => config('app.name')])}}</p>
<p>{{__('Thank you for choosing :name. If you have any questions or need assistance, please don\'t hesitate to contact our support team.')}}</p>
@include('emails.layouts.footer')