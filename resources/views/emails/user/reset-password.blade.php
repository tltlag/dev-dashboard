@section('title', __('Reset your password'))
@include('emails.layouts.header')
<h1>{{__('Reset your password')}}</h1>
<p>{{__('Dear :name', ['name' => $user->name])}},</p>
<p>{{__('We received a request to reset your password. To create a new password for your account, click the below link:')}}</p>
<a href="{{$user->getResetPasswordLink()}}">{{__('Reset Password')}}</a>
<p>{{__('If you didn\'t request a password reset, you can ignore this email. Your password will remain unchanged.')}}</p>
@include('emails.layouts.footer')