@php
    $logs = getLast10PersonelLog();
@endphp

<div tabindex="-1" role="menu" aria-hidden="true"
     class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
    <div class="dropdown-menu-header mb-0">
        <div class="dropdown-menu-header-inner bg-deep-blue">
            <div class="menu-header-image opacity-1"
                 style="background-image: url(" assets
            /images/dropdown-header/city3.jpg");">
        </div>
        <div class="menu-header-content text-dark">
            <h5 class="menu-header-title">Bildirimler</h5>
            <h6 class="menu-header-subtitle">Okunmamış <b
                    id="TitleNotificationQuantity">{{Auth::user()->unReadNotifications->count()}}</b>
                bildiriminiz var.</h6>
        </div>
    </div>
</div>
<ul
    class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
    <li class="nav-item">
        <a role="tab" class="nav-link active" data-toggle="tab" href="#tab-errors-header">
            <span>Bildirimler</span>
        </a>
    </li>

    <li class="nav-item">
        <a role="tab" class="nav-link " data-toggle="tab"
           href="#tab-messages-header">
            <span>Son Loglar</span>
        </a>
    </li>
    {{--    <li class="nav-item">--}}
    {{--        <a role="tab" class="nav-link" data-toggle="tab" href="#tab-events-header">--}}
    {{--            <span>Duyurular</span>--}}
    {{--        </a>--}}
    {{--    </li>--}}
    {{--        <button id="beepBtn" onclick="play()" type="button">Beep!</button>--}}
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-errors-header" role="tabpanel">
        @if(Auth::user()->notifications->count())
            <div class="scroll-area-sm">
                <div class="scrollbar-container">
                    <div style="padding-top: 0px !important;" class="p-3">
                        <div class="notifications-box">
                            <div
                                class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                {{--Unread Notifications--}}
                                <div class="row mb-2">
                                    <div class="col-12 text-right">

                                        <a class="text-danger float-left ml-3"
                                           href="{{route('personel.notificationAndAnnouncements', 'Notifications')}}">Tümünü
                                            gör</a>

                                        @if(Auth::user()->unReadnotifications->count())
                                            <a style="display: inline-block;" href="javascript:void(0)"
                                               class="text-success markAllRead">Tümünü
                                                okundu olarak işaretle.</a>
                                        @endif
                                    </div>
                                </div>
                                @foreach(Auth::user()->unReadnotifications->take(10) as $notification)
                                    <div id="NotfDot-{{$notification->id}}"
                                         class="vertical-timeline-item dot-primary vertical-timeline-element NotfDot">
                                        <div>
                                            <span class="vertical-timeline-element-icon bounce-in"></span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <div style="float:right;cursor: pointer;"
                                                     title="Okundu olarak işaretle."
                                                     id="markReadBtn-{{$notification->id}}"
                                                     data-id="{{$notification->id}}"
                                                     class="mb-2 mr-2  badge badge-dot badge-dot-lg badge-primary mark-as-read markReadBtn-{{$notification->id}}">
                                                </div>
                                                <h4 class="timeline-title ">
                                                    <a href="{{$notification->data['link'] != '' ?  $notification->data['link'] : '#'}}"
                                                       id="NotfContent-{{$notification->id}}"
                                                       data-id="{{$notification->id}}"
                                                       class="text-primary NotfContent mark-as-read"> {{$notification->data['notification']}}
                                                    </a>
                                                </h4>
                                                <span id="NotfHumenTime-{{$notification->id}}"
                                                      class="NotfHumenTime text-primary ">{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</span>
                                                <span class="vertical-timeline-element-date"></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                {{--Read Notifications--}}
                                @foreach(Auth::user()->readnotifications->take(10) as $notification)
                                    <div id="NotfDot-{{$notification->id}}"
                                         class="vertical-timeline-item dot-dark vertical-timeline-element">
                                        <div>
                                            <span class="vertical-timeline-element-icon bounce-in"></span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title ">
                                                    <a href="{{$notification->data['link'] != '' ?  $notification->data['link'] : '#'}}"
                                                       id="NotfContent-{{$notification->id}}"
                                                       class="text-dark"> {{$notification->data['notification']}}
                                                    </a>
                                                </h4>
                                                <span
                                                    class="text-dark">{{\Carbon\Carbon::parse($notification->created_at)->diffForHumans()}}</span>
                                                <span class="vertical-timeline-element-date"></span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="scroll-area-sm">
                <div class="scrollbar-container">
                    <div class="no-results pt-3 pb-0">
                        <div class="swal2-icon swal2-success swal2-animate-success-icon">
                            <div class="swal2-success-circular-line-left"
                                 style="background-color: rgb(255, 255, 255);"></div>
                            <span class="swal2-success-line-tip"></span>
                            <span class="swal2-success-line-long"></span>
                            <div class="swal2-success-ring"></div>
                            <div class="swal2-success-fix"
                                 style="background-color: rgb(255, 255, 255);"></div>
                            <div class="swal2-success-circular-line-right"
                                 style="background-color: rgb(255, 255, 255);"></div>
                        </div>
                        <div class="results-subtitle">Burda hiç bildirim yok.</div>
                        <div class="results-title">Bildirim olduğu zaman burada görüntüleyebileceksiniz.</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="tab-pane" id="tab-messages-header" role="tabpanel">
        <div class="scroll-area-sm">
            <div class="scrollbar-container">
                <div class="p-3">
                    <div class="notifications-box">
                        <div
                            class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">

                            @foreach($logs as $log)
                                <div class="vertical-timeline-item dot-dark vertical-timeline-element">
                                    <div>
                                        <span class="vertical-timeline-element-icon bounce-in"></span>
                                        <div class="vertical-timeline-element-content bounce-in">
                                            <h4 class="timeline-title">{{$log->description}} </h4>
                                            <span
                                                class="text-primary">{{\Carbon\Carbon::parse($log->created_at)->diffForHumans()}}</span>
                                            <span class="vertical-timeline-element-date"></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    <div class="tab-pane" id="tab-events-header" role="tabpanel">--}}
    {{--        <div class="scroll-area-sm">--}}
    {{--            <div class="scrollbar-container">--}}
    {{--                <div class="p-3">--}}
    {{--                    <div--}}
    {{--                        class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div>--}}
    {{--                                    <span class="vertical-timeline-element-icon bounce-in">--}}
    {{--                                        <i class="badge badge-dot badge-dot-xl badge-success"></i>--}}
    {{--                                    </span>--}}
    {{--                                <div class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title">All Hands Meeting</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sic amet, today at <a--}}
    {{--                                            href="javascript:void(0);">12:00 PM</a></p>--}}
    {{--                                    <span class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-warning">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <p>Another meeting today, at <b--}}
    {{--                                            class="text-danger">12:00 PM</b></p>--}}
    {{--                                    <p>Yet another one, at <span--}}
    {{--                                            class="text-success">15:00 PM</span></p>--}}
    {{--                                    <span class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-danger">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title">Build the production--}}
    {{--                                        release</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sit amit,consectetur eiusmdd--}}
    {{--                                        tempor incididunt ut labore et dolore magna elit--}}
    {{--                                        enim at minim veniam quis nostrud</p><span--}}
    {{--                                        class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-primary">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title text-success">Something--}}
    {{--                                        not important</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sit amit,consectetur elit enim--}}
    {{--                                        at minim veniam quis nostrud</p><span--}}
    {{--                                        class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-success">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title">All Hands Meeting</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sic amet, today at <a--}}
    {{--                                            href="javascript:void(0);">12:00 PM</a></p>--}}
    {{--                                    <span class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-warning">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <p>Another meeting today, at <b--}}
    {{--                                            class="text-danger">12:00 PM</b></p>--}}
    {{--                                    <p>Yet another one, at <span--}}
    {{--                                            class="text-success">15:00 PM</span></p>--}}
    {{--                                    <span class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-danger">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title">Build the production--}}
    {{--                                        release</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sit amit,consectetur eiusmdd--}}
    {{--                                        tempor incididunt ut labore et dolore magna elit--}}
    {{--                                        enim at minim veniam quis nostrud</p><span--}}
    {{--                                        class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <div class="vertical-timeline-item vertical-timeline-element">--}}
    {{--                            <div><span--}}
    {{--                                    class="vertical-timeline-element-icon bounce-in"><i--}}
    {{--                                        class="badge badge-dot badge-dot-xl badge-primary">--}}
    {{--                                                                    </i></span>--}}
    {{--                                <div--}}
    {{--                                    class="vertical-timeline-element-content bounce-in">--}}
    {{--                                    <h4 class="timeline-title text-success">Something--}}
    {{--                                        not important</h4>--}}
    {{--                                    <p>Lorem ipsum dolor sit amit,consectetur elit enim--}}
    {{--                                        at minim veniam quis nostrud</p><span--}}
    {{--                                        class="vertical-timeline-element-date"></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
</div>
<ul class="nav flex-column">
    <li class="nav-item-divider nav-item"></li>
    <li class="nav-item-btn text-center nav-item">
        <a href="/SystemUpdates">
            <button class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">CKG-Sis'in Son Güncellemelerini
                Gör
            </button>
        </a>
    </li>
</ul>
</div>

