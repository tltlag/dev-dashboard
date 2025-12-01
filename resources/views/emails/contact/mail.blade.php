@section('title', __('New query raised for :name', ['name' => config('app.name')]))
@include('emails.layouts.header')
<h3>{{__('Subject: :subject', $contactData)}}</h3>
<p>{{__('Name: :name', $contactData)}}</p>
<p>{{__('Email: :email', $contactData)}}</p>
<p>{{__('Phone: :phone', $contactData)}}</p>
<p>{{__('Message: :message', ['message' => nl2br(($contactData['message'] ?? ''))])}}</p>
@include('emails.layouts.footer')