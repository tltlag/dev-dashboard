@section('title', __('Your password has been successfully updated'))
@include('emails.layouts.header')
<h1>{{__('Your password has been successfully updated')}}</h1>
<p>{{__('Dear :name', ['name' => $user->name])}},</p>
<p>{{__('Your password has been successfully updated. You can now fully access and enjoy all the features of :name', ['name' => config('app.name')])}}</p>
<p>{{__('If you have any questions or need assistance, please feel free to contact our support team.')}}</p>
@include('emails.layouts.footer')