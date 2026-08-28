<style>
.notif-backdrop {
    position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(2px);
    z-index: 2998; opacity: 0; pointer-events: none;
    transition: opacity .3s ease;
}
.notif-backdrop.open { opacity: 1; pointer-events: auto; }

.notif-panel {
    position: fixed; top: 0; right: 0; height: 100vh;
    width: 100%; max-width: 400px; background: #fff;
    z-index: 2999; transform: translateX(100%);
    transition: transform .32s cubic-bezier(.16,1,.3,1);
    display: flex; flex-direction: column;
    box-shadow: -12px 0 40px rgba(15,23,42,0.10);
}
.notif-panel.open { transform: translateX(0); }

.notif-panel-header {
    padding: 22px 24px 18px;
    display: flex; align-items: flex-start; justify-content: space-between;
    flex-shrink: 0; border-bottom: 1px solid #f1f2f4;
}
.notif-panel-header h3 { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 2px; letter-spacing: -0.3px; }
.notif-panel-sub { font-size: 12.5px; color: #94a3b8; margin: 0; }

.notif-header-actions { display: flex; align-items: center; gap: 12px; padding-top: 2px; }
.notif-mark-all {
    font-size: 12.5px; font-weight: 600; color: #5b5bf0;
    background: #f2f1fe; border: none; padding: 7px 12px; border-radius: 8px;
    cursor: pointer; white-space: nowrap; transition: background .15s;
}
.notif-mark-all:hover { background: #e6e4fd; }

.notif-close-btn {
    width: 30px; height: 30px; border-radius: 50%; background: #f4f5f7;
    border: none; display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; padding: 0; transition: background .15s;
}
.notif-close-btn:hover { background: #e9eaed; }
.notif-close-btn svg { width: 14px; height: 14px; color: #64748b; }

.notif-panel-body { flex: 1; overflow-y: auto; background: #fafbfc; }
.notif-day-label {
    font-size: 11px; font-weight: 700; color: #a3aab7;
    text-transform: uppercase; letter-spacing: .6px; padding: 18px 24px 8px;
}
.notif-list { list-style: none; padding: 0 12px; margin: 0; }
.notif-item {
    display: flex; gap: 13px; padding: 14px 12px; background: transparent;
    border-radius: 12px; position: relative; cursor: pointer;
    transition: background .15s; margin-bottom: 2px;
}
.notif-item:hover { background: #f1f3f6; }
.notif-item.unread { background: #fff; box-shadow: 0 1px 2px rgba(15,23,42,0.04); }
.notif-item.unread:hover { background: #fdfdfe; }
.notif-item.unread::after {
    content: ''; position: absolute; top: 16px; right: 12px;
    width: 8px; height: 8px; border-radius: 50%; background: #5b5bf0;
}
.notif-icon {
    width: 38px; height: 38px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.notif-icon.customer { background: #eef0ff; color: #4338ca; }
.notif-icon.order     { background: #e8f8f0; color: #0d9354; }
.notif-icon.payment   { background: #fef1e8; color: #c2540c; }
.notif-icon.default   { background: #f1f2f4; color: #64748b; }
.notif-body { flex: 1; min-width: 0; padding-right: 14px; }
.notif-text { font-size: 13.5px; font-weight: 500; color: #1e293b; line-height: 1.45; margin-bottom: 3px; }
.notif-item:not(.unread) .notif-text { font-weight: 400; color: #475569; }
.notif-time { font-size: 11.5px; color: #a3aab7; }
.notif-empty { text-align: center; padding: 90px 32px 40px; }
.notif-empty-icon {
    width: 60px; height: 60px; border-radius: 50%; background: #eef0ff;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; color: #5b5bf0;
}
.notif-empty-title { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 5px; }
.notif-empty-desc { font-size: 13px; color: #94a3b8; line-height: 1.5; }
.notif-panel-body::-webkit-scrollbar { width: 6px; }
.notif-panel-body::-webkit-scrollbar-thumb { background: #d8dce2; border-radius: 3px; }
.notif-panel-body::-webkit-scrollbar-track { background: transparent; }
body.notif-open { overflow: hidden; }
</style>

<div class="notif-backdrop" id="notifBackdrop"></div>

<div class="notif-panel" id="notifPanel">

    <div class="notif-panel-header">
        <div>
            <h3>Notifications</h3>
            <p class="notif-panel-sub">Stay on top of your account activity</p>
        </div>
        <div class="notif-header-actions">
            <button type="button" class="notif-mark-all" id="markAllReadBtn">Mark all read</button>
            <button type="button" class="notif-close-btn" id="notifCloseBtn" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="notif-panel-body">

        @php
            $allNotifications = auth()->user()?->notifications ?? collect();
        @endphp

        @if($allNotifications->isEmpty())

            <div class="notif-empty">
                <div class="notif-empty-icon">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="notif-empty-title">You're all caught up</div>
                <div class="notif-empty-desc">No notifications right now — new updates will show up here.</div>
            </div>

        @else

            <ul class="notif-list" id="notifList">
                @php $lastDateLabel = null; @endphp

                @foreach($allNotifications as $notification)
                    @php
                        $tag = $notification->data['tag'] ?? 'default';
                        $isUnread = is_null($notification->read_at);
                        $createdAt = $notification->created_at;

                        $dateLabel = $createdAt->isToday()
                            ? 'Today'
                            : ($createdAt->isYesterday() ? 'Yesterday' : $createdAt->format('d M Y'));

                        $iconClass = match(strtolower($tag)) {
                            'customer' => 'customer',
                            'order'    => 'order',
                            'payment'  => 'payment',
                            default    => 'default',
                        };

                        $iconSvg = match($iconClass) {
                            'customer' => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                            'order'    => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
                            'payment'  => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>',
                            default    => '<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>',
                        };
                    @endphp

                    @if($dateLabel !== $lastDateLabel)
                        <li class="notif-day-label">{{ $dateLabel }}</li>
                        @php $lastDateLabel = $dateLabel; @endphp
                    @endif

                    <li class="notif-item {{ $isUnread ? 'unread' : '' }}" data-id="{{ $notification->id }}">
                        <div class="notif-icon {{ $iconClass }}">
                            {!! $iconSvg !!}
                        </div>
                        <div class="notif-body">
                            <div class="notif-text">{{ $notification->data['data'] ?? 'Notification' }}</div>
                            <div class="notif-time">{{ $createdAt->diffForHumans() }}</div>
                        </div>
                    </li>

                @endforeach
            </ul>

        @endif

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    function openPanel() {
        $('#notifPanel').addClass('open');
        $('#notifBackdrop').addClass('open');
        $('body').addClass('notif-open');
        markAllRead();
    }

    function closePanel() {
        $('#notifPanel').removeClass('open');
        $('#notifBackdrop').removeClass('open');
        $('body').removeClass('notif-open');
    }

    function markAllRead() {
        $.ajax({
            url: '/home/updateNotification',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (response) {
                updateBadge(response.notificationCount);
                $('.notif-item.unread').removeClass('unread');
            },
            error: function (xhr, status, error) {
                console.error('Error updating notification:', error);
            }
        });
    }

    function updateBadge(count) {
        $('.notif-badge').text(count > 99 ? '99+' : count);
    }

    $('#notifBellBtn').on('click', function (e) {
        e.preventDefault();
        openPanel();
    });

    $('#notifCloseBtn').on('click', function () { closePanel(); });
    $('#notifBackdrop').on('click', function () { closePanel(); });

    $('#markAllReadBtn').on('click', function () {
        markAllRead();
        closePanel();
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') closePanel();
    });

});
</script>