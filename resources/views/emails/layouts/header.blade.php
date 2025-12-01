<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', '') - {{ config('global.SITE_TITLE', '') }}</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{url('frontend-assets/images/logo/2.png')}}" alt="{{config('app.name')}}" style="width: 200px !important" />
        </div>
        <div class="content">