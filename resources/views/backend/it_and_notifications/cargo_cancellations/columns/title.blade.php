<a class="text-primary font-weight-bold" title="{{$title}}"
   href="{{route('admin.systemSupport.TicketDetails', ['TicketID' => $id])}}">
    {{'#D-'.$id . ' - ' . Str::Words($title, 3 , '...')}}
</a>


