@extends('errors::minimal')

@section('title', __('Aşırı İstek!'))
@section('code', '429')
@section('message', __('Aşırı İstek!'))
@section('message_ex', __('Aşırı İstekte bulundunuz, şu anda isteğinizi gerçekleştiremiyoruz. Lütfen kısa bir süre sonra tekrar deneyiniz.'))
