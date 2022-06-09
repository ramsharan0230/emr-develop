@forelse($notifications as $notification)
    <div class="iq-sub-card notify-bak mark-read"  data-id="{{ $notification->id ?? '' }}" data-url="{{ route('notification.mark.read',$notification->id ?? '') }}">
        <div class="media align-items-center">
            <label>{{ (isset($notification->data) && $notification->data['data'] && $notification->data['data']['message']) ?  $notification->data['data']['message'] :''}}
            </label>
        </div>
    </div>
@empty
    <a href="#" class="iq-sub-card">
        <div class="media align-items-center">
            <h6>No new notifications available</h6>
        </div>
    </a>
@endforelse
{{--{{ $notifications->links() }}--}}
{{--@if( (isset($notifications) && $notifications->count() >0))--}}
{{--    <a href="javascript:void(0);"--}}
{{--       data-url="{{ route('notification.mark.all.read') }}"--}}
{{--       class="mark-all-read">Mark all read</a>--}}
{{--@endif--}}
