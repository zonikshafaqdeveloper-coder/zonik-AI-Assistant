<style>
.notification-center{display:flex;align-items:center;justify-content:center;gap:10px!important}
.notification-slot{display:flex;align-items:center}
.btn-notif{position:relative;width:36px;height:36px;padding:0;display:inline-flex;align-items:center;justify-content:center;background:#fff;border:1px solid #344054;border-radius:9px;color:#101828;font-size:1rem;line-height:1;box-shadow:0 2px 6px rgba(16,24,40,.08)}
.btn-notif .fa-bell{font-size:1.1rem}
.notif-badge{position:absolute;top:-5px;right:-5px;min-width:15px;height:15px;padding:0 4px;background:#e24b4a;color:#fff;font-size:10px;font-weight:600;line-height:15px;border-radius:8px;text-align:center;border:1px solid #1a1a2e}
.notif-badge.is-empty{display:none}
</style>

<div class="notification-center">
    @if(hasPermission('notification.admin'))
    <div class="notification-slot">
        <button class="btn btn-notif" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            <i class="fa-solid fa-bell @if($adminNotificationCount > 0) icon-left-right @endif"></i>
            <span class="notif-badge @if($adminNotificationCount <= 0) is-empty @endif">{{ $adminNotificationCount > 9 ? '9+' : $adminNotificationCount }}</span>
        </button>
    </div>
    @endif

    @if(hasPermission('notification.user'))
    <div class="notification-slot">
        <button class="btn btn-notif" type="button" data-bs-toggle="offcanvas" data-bs-target="#userOffcanvasRight" aria-controls="userOffcanvasRight">
            <i class="fa-solid fa-bell @if($NewUserCount > 0) icon-left-right @endif"></i>
            <span class="notif-badge @if($NewUserCount <= 0) is-empty @endif">{{ $NewUserCount > 9 ? '9+' : $NewUserCount }}</span>
        </button>
    </div>
    @endif

    @if(hasPermission('notification.order'))
    <div class="notification-slot">
        <button class="btn btn-notif" type="button" data-bs-toggle="offcanvas" data-bs-target="#orderOffcanvasRight" aria-controls="orderOffcanvasRight">
            <i class="fa-solid fa-bell @if($orderCount > 0) icon-left-right @endif"></i>
            <span class="notif-badge @if($orderCount <= 0) is-empty @endif">{{ $orderCount > 9 ? '9+' : $orderCount }}</span>
        </button>
    </div>
    @endif
</div>
