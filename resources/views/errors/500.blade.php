@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Server Error'))
{{--@section('message_ex', __('İlglileniyoruz, kısa süre içerisinde düzelteceğiz..'))--}}
@section('message_ex', __('Görünüşe göre geliştiricilerden biri uyuya kalmış.'))
