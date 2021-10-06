<a href="javascript:void(0)"
   class="mypopover"
   title="{{ $branch_city. '/' . $branch_district . ' - ' . $branch_name . ' ' .$user_type . ' - ' .$display_name }}"
   data-toggle="popover-custom-bg"
   data-bg-class="text-light bg-premium-dark"
   data-content="{{$name_surname }}">{{ Str::words($name_surname, 4, '...') }}

</a>
