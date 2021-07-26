@extends('errors::minimal')

@section('title', __('Aşırı İstek!'))
@section('code', '429')
@section('message', __('Aşırı İstek!'))
@section('message_ex', __('Aşırı İstekte bulundunuz, lütfen bir süre sonra tekrar deneyiniz.'))
