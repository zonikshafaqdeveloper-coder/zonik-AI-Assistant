@extends('mobile.mobile-app')

@section('title', 'AI Order Assistant')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script defer src="https://checkout.razorpay.com/v1/checkout.js"></script>
<style>
.ai-page {
    height: calc(100vh - 150px);
    padding: 0;
    color: #111827;
}
.ai-card {
    position: relative;
    height: 100%;
    min-height: 0;
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 24px;
    padding: 12px 12px 10px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.07);
    overflow: hidden;
}
.ai-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 16px;
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    border: 1px solid rgba(79, 70, 229, 0.12);
    margin-bottom: 10px;
    flex-shrink: 0;
}
.ai-avatar {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
    overflow: hidden;
    background: transparent;
}
.ai-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.ai-avatar svg {
    width: 24px;
    height: 24px;
    color: #ffffff;
}
.ai-header-text {
    display: grid;
    gap: 2px;
    min-width: 0;
    flex: 1;
}
.ai-title {
    font-size: 16px;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
}
.ai-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}
.ai-header-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border-radius: 999px;
    background: #ffffff;
    border: 1px solid rgba(79, 70, 229, 0.12);
    color: #4f46e5;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    cursor: pointer;
}
.ai-header-pill svg {
    width: 14px;
    height: 14px;
}
.ai-history-panel { position: absolute; inset: 0; z-index: 20; display: none; flex-direction: column; background: #fff; border-radius: 24px; overflow: hidden; }
.ai-history-panel.open { display: flex; }
.ai-history-header { display: flex; align-items: center; gap: 10px; padding: 16px; border-bottom: 1px solid #e2e8f0; }
.ai-history-back { width: 36px; height: 36px; border: 1px solid #e2e8f0; border-radius: 50%; background: #fff; color: #334155; display: grid; place-items: center; cursor: pointer; }
.ai-history-heading { flex: 1; min-width: 0; }
.ai-history-heading strong { display: block; color: #111827; font-size: 16px; }
.ai-history-heading span { color: #64748b; font-size: 11px; }
.ai-history-body { flex: 1; min-height: 0; overflow-y: auto; padding: 12px; background: #f8fafc; }
.ai-history-list, .ai-history-detail { display: grid; gap: 9px; }
.ai-history-actions { display: flex; align-items: center; gap: 7px; }
.ai-history-continue { display: none; border: 0; border-radius: 9px; padding: 8px 10px; background: #4f46e5; color: #fff; font-size: 11px; font-weight: 800; cursor: pointer; }
.ai-history-row { display: flex; gap: 8px; align-items: stretch; }
.ai-history-item { flex: 1; min-width: 0; text-align: left; border: 1px solid #e2e8f0; background: #fff; border-radius: 14px; padding: 12px; cursor: pointer; }
.ai-history-delete { width: 42px; flex: 0 0 42px; border: 1px solid #fecaca; border-radius: 14px; background: #fff; color: #dc2626; cursor: pointer; display: grid; place-items: center; }
.ai-history-delete svg { width: 17px; height: 17px; pointer-events: none; }
.ai-history-item-title { display: block; color: #111827; font-size: 13px; font-weight: 800; margin-bottom: 6px; }
.ai-history-item-meta { display: flex; flex-wrap: wrap; gap: 5px 10px; color: #64748b; font-size: 11px; }
.ai-history-empty { padding: 36px 16px; text-align: center; color: #64748b; }
.ai-history-badge { display: inline-flex; margin-top: 7px; padding: 4px 8px; border-radius: 999px; background: #dcfce7; color: #166534; font-size: 11px; font-weight: 800; }
.ai-cart-panel { position: absolute; inset: 0; z-index: 24; display: none; flex-direction: column; background: #f8fafc; border-radius: 24px; overflow: hidden; }
.ai-cart-panel,
.ai-cart-panel * { box-sizing: border-box; }
.ai-cart-panel.open { display: flex; }
.ai-cart-panel-head { display: flex; align-items: center; gap: 10px; width: 100%; min-width: 0; padding: 14px; background: #fff; border-bottom: 1px solid #e2e8f0; }
.ai-cart-panel-head svg { width: 22px; height: 22px; color: #1d4ed8; }
.ai-cart-panel-title { flex: 1; min-width: 0; overflow-wrap: anywhere; font-size: 15px; font-weight: 850; color: #111827; }
.ai-cart-panel-title small { color: #64748b; font-weight: 600; }
.ai-cart-clear { flex: 0 0 auto; white-space: nowrap; border: 0; background: transparent; color: #dc2626; font-size: 12px; font-weight: 750; cursor: pointer; }
.ai-cart-panel-body { width: 100%; max-width: 100%; flex: 1; min-height: 0; overflow-x: hidden; overflow-y: auto; padding: 12px; display: grid; grid-template-columns: minmax(0, 1fr); align-content: start; gap: 12px; }
.ai-cart-panel-item { width: 100%; max-width: 100%; min-width: 0; display: grid; grid-template-columns: 68px minmax(0, 1fr) auto; gap: 10px; align-items: center; padding: 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 15px; }
.ai-cart-panel-image { width: 68px; height: 68px; object-fit: contain; border-radius: 11px; background: #f8fafc; }
.ai-cart-panel-info { min-width: 0; display: grid; gap: 4px; }
.ai-cart-panel-name { max-width: 100%; overflow-wrap: anywhere; word-break: break-word; color: #111827; font-size: 13px; font-weight: 800; }
.ai-cart-panel-meta { max-width: 100%; overflow-wrap: anywhere; color: #64748b; font-size: 11px; }
.ai-cart-panel-price { max-width: 100%; overflow-wrap: anywhere; color: #111827; font-size: 13px; font-weight: 850; }
.ai-cart-remove { align-self: stretch; border: 0; background: transparent; color: #e11d48; padding: 5px; cursor: pointer; }
.ai-cart-remove svg { width: 17px; height: 17px; }
.ai-cart-add-more { border: 1px dashed #94a3b8; background: #fff; color: #1e3a8a; border-radius: 13px; padding: 12px; font-size: 12px; font-weight: 800; cursor: pointer; }
.ai-cart-bill { width: 100%; min-width: 0; display: grid; gap: 9px; padding: 14px; background: #fff; border: 1px solid #e2e8f0; border-radius: 15px; }
.ai-cart-bill-row { display: flex; justify-content: space-between; gap: 15px; color: #334155; font-size: 12px; }
.ai-cart-bill-row.total { padding-top: 10px; border-top: 1px solid #e2e8f0; color: #111827; font-size: 15px; font-weight: 850; }
.ai-cart-bill-row.total strong { color: #1d4ed8; font-size: 19px; }
.ai-cart-suggestions { width: 100%; min-width: 0; overflow: hidden; padding: 14px; background: linear-gradient(145deg, #eff6ff, #f5f3ff); border: 1px solid #dbeafe; border-radius: 15px; }
.ai-cart-suggestions strong { color: #1e3a8a; font-size: 13px; }
.ai-cart-chips { width: 100%; min-width: 0; display: flex; gap: 7px; overflow-x: auto; margin-top: 10px; overscroll-behavior-x: contain; }
.ai-cart-chip { white-space: nowrap; border: 1px solid #c7d2fe; background: #fff; color: #3730a3; border-radius: 999px; padding: 7px 10px; font-size: 11px; cursor: pointer; }
.ai-cart-panel-foot { padding: 10px 12px calc(10px + env(safe-area-inset-bottom, 0px)); background: #fff; border-top: 1px solid #e2e8f0; }
.ai-cart-review { width: 100%; border: 0; border-radius: 13px; padding: 13px; background: #075ff7; color: #fff; font-size: 14px; font-weight: 850; cursor: pointer; }
.ai-reorder-card { width: 100%; min-width: 0; overflow: hidden; padding: 0; }
.ai-reorder-head { display: flex; justify-content: space-between; gap: 10px; padding: 12px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
.ai-reorder-head span { color: #64748b; font-size: 11px; }
.ai-reorder-table-wrap { width: 100%; overflow-x: auto; }
.ai-reorder-table { width: 100%; min-width: 520px; border-collapse: collapse; table-layout: fixed; }
.ai-reorder-table th { padding: 8px; background: #eef2ff; color: #475569; font-size: 10px; text-align: left; }
.ai-reorder-table td { padding: 9px 8px; border-bottom: 1px solid #eef2f7; color: #334155; font-size: 11px; vertical-align: middle; }
.ai-reorder-product { display: flex; align-items: center; gap: 8px; min-width: 0; }
.ai-reorder-image { width: 42px; height: 42px; flex: 0 0 42px; object-fit: contain; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; }
.ai-reorder-name { min-width: 0; overflow-wrap: anywhere; font-weight: 750; color: #111827; }
.ai-reorder-total { display: flex; justify-content: space-between; align-items: center; padding: 11px 12px; background: #f8fafc; font-size: 13px; font-weight: 800; }
.ai-reorder-card .ai-product-actions { padding: 0 12px 12px; }
.ai-confirm-overlay { position: absolute; inset: 0; z-index: 50; display: none; align-items: center; justify-content: center; padding: 20px; background: rgba(15, 23, 42, .48); backdrop-filter: blur(2px); }
.ai-confirm-overlay.open { display: flex; }
.ai-confirm-box { width: min(100%, 330px); padding: 20px; border-radius: 18px; background: #fff; box-shadow: 0 22px 55px rgba(15, 23, 42, .25); text-align: center; }
.ai-confirm-icon { width: 48px; height: 48px; margin: 0 auto 12px; border-radius: 50%; display: grid; place-items: center; background: #fff1f2; color: #e11d48; }
.ai-confirm-icon svg { width: 23px; height: 23px; }
.ai-confirm-box h3 { margin: 0 0 7px; color: #111827; font-size: 17px; }
.ai-confirm-box p { margin: 0; color: #64748b; font-size: 12px; line-height: 1.5; }
.ai-confirm-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; margin-top: 18px; }
.ai-confirm-btn { border: 1px solid #e2e8f0; border-radius: 11px; padding: 10px; background: #fff; color: #334155; font-size: 12px; font-weight: 800; cursor: pointer; }
.ai-confirm-btn.delete { border-color: #e11d48; background: #e11d48; color: #fff; }
.ai-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.16);
}
.ai-caption {
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}
.ai-chat-shell {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    padding-right: 4px;
    margin-bottom: 10px;
}
.ai-chat-shell::-webkit-scrollbar {
    width: 4px;
}
.ai-chat-shell::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 999px;
}
.ai-chat {
    display: grid;
    gap: 10px;
    padding-bottom: 4px;
}
.ai-message {
    width: fit-content;
    max-width: 88%;
    padding: 12px 14px;
    border-radius: 16px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    line-height: 1.6;
    font-size: 14px;
}
.ai-message.assistant {
    border-color: #dbeafe;
}
.ai-message.user {
    background: #eef2ff;
    border-color: #c7d2fe;
    justify-self: end;
    text-align: right;
    max-width: 78%;
}
.ai-message strong {
    color: #111827;
}
.ai-product-card {
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 12px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
}
.ai-message-row { display: flex; align-items: flex-end; gap: 7px; max-width: 100%; }
.ai-message-row.user { justify-content: flex-end; }
.ai-reply-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; flex: 0 0 auto; border: 1px solid #dbeafe; }
.ai-message-time { display: block; margin-top: 5px; color: #64748b; font-size: 10px; line-height: 1; text-align: right; }
.ai-product-image { width: 58px; height: 58px; border-radius: 10px; object-fit: cover; flex: 0 0 auto; }
.ai-product-details { min-width: 0; flex: 1; display: grid; gap: 3px; }
.ai-product-actions, .ai-cart-actions { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 9px; }
.ai-product-btn, .ai-cart-btn { border: 1px solid #4f46e5; background: #fff; color: #3730a3; border-radius: 9px; padding: 7px 10px; font-size: 12px; font-weight: 700; cursor: pointer; }
.ai-product-btn.primary, .ai-cart-btn.primary { background: #4f46e5; color: #fff; }
.ai-checkout-choice-group { width:100%; display:grid; gap:7px; margin-top:7px; }
.ai-checkout-choice-title { color:#475467; font-size:11px; font-weight:800; letter-spacing:.04em; text-transform:uppercase; }
.ai-checkout-choice-list { display:flex; flex-wrap:wrap; gap:7px; }
.ai-qty-control { display: inline-flex; align-items: center; overflow: hidden; border: 1px solid #c7d2fe; border-radius: 9px; background: #fff; }
.ai-qty-btn { width: 32px; height: 32px; border: 0; background: #eef2ff; color: #3730a3; font-size: 18px; font-weight: 800; cursor: pointer; }
.ai-qty-value { min-width: 34px; text-align: center; color: #111827; font-size: 12px; font-weight: 800; }
.ai-product-btn:disabled { opacity: .65; cursor: wait; }
.ai-cart-summary { display: grid; gap: 6px; margin-top: 8px; }
.ai-cart-row { display: flex; justify-content: space-between; gap: 14px; font-size: 12px; color: #334155; }
.ai-cart-total { font-weight: 800; color: #111827; padding-top: 6px; border-top: 1px solid #e2e8f0; }
.ai-live-order-message { width: 100%; }
.ai-live-order-message .ai-message { width: 100%; max-width: 100%; }
.ai-live-order-item { display: grid; grid-template-columns: 52px minmax(0, 1fr) auto; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
.ai-live-order-item:last-child { border-bottom: 0; }
.ai-live-order-image { width: 52px; height: 52px; border-radius: 10px; object-fit: cover; background: #f1f5f9; }
.ai-live-order-name { color: #111827; font-size: 13px; font-weight: 750; line-height: 1.3; }
.ai-live-order-meta { color: #64748b; font-size: 12px; margin-top: 4px; }
.ai-live-order-price { color: #4f46e5; font-size: 13px; font-weight: 800; text-align: right; white-space: nowrap; }
.ai-typing { color: #64748b; }
.ai-product-title {
    font-weight: 700;
    color: #111827;
}
.ai-product-meta {
    color: #64748b;
    font-size: 12px;
}
.ai-product-price {
    color: #4f46e5;
    font-size: 14px;
    font-weight: 800;
}
.ai-suggestion-message { width: 100%; }
.ai-suggestion-message .ai-message { width: 100%; max-width: 100%; padding: 10px; }
.ai-suggestion-line {
    display: grid;
    grid-template-columns: repeat(3, minmax(88px, 1fr));
    gap: 8px;
    width: 100%;
}
.ai-suggestion-card {
    min-width: 0;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    color: #111827;
}
.ai-suggestion-card:hover { border-color: #a5b4fc; background: #f8faff; }
.ai-suggestion-card:focus-visible { outline: 3px solid rgba(79,70,229,.22); outline-offset: 1px; }
.ai-suggestion-card:disabled { opacity: .62; cursor: wait; }
.ai-suggestion-image { width: 54px; height: 54px; border-radius: 9px; object-fit: cover; background: #f1f5f9; }
.ai-suggestion-name { width: 100%; font-size: 11px; line-height: 1.25; font-weight: 700; text-align: center; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.ai-suggestion-add { color: #4f46e5; font-size: 10px; font-weight: 800; }
.ai-composer {
    flex-shrink: 0;
    border-top: 1px solid #e5e7eb;
    padding-top: 10px;
    padding-bottom: calc(env(safe-area-inset-bottom, 0px) + 2px);
    display: grid;
    gap: 10px;
    background: #ffffff;
    margin-top: auto;
}
.ai-actions {
    display: flex;
    gap: 7px;
    align-items: center;
}
.ai-action-btn {
    border: 1px solid #e5e7eb;
    color: #334155;
    background: #ffffff;
    border-radius: 10px;
    min-height: 34px;
    padding: 6px 9px;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.ai-action-btn svg { width: 15px; height: 15px; flex: 0 0 auto; }
.ai-action-btn.primary {
    background: #4f46e5;
    color: #fff;
    border-color: transparent;
}
.ai-input-row {
    display: flex;
    gap: 8px;
    align-items: center;
    position: relative;
}
.ai-mic-wrap { display: flex; align-items: center; gap: 7px; }
.ai-mic-status {
    min-width: 70px;
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    white-space: nowrap;
}
.ai-mic-status.listening { color: #dc2626; }
.ai-mic-status.processing { color: #4f46e5; }
.ai-mic-status.listening::before {
    content: '';
    display: inline-block;
    width: 7px;
    height: 7px;
    margin-right: 5px;
    border-radius: 50%;
    background: #dc2626;
    animation: aiStatusBlink .8s infinite alternate;
}
@keyframes aiStatusBlink { to { opacity: .25; } }
.ai-input {
    flex: 1;
    min-width: 0;
    border: 1px solid #d1d5db;
    border-radius: 999px;
    padding: 12px 14px;
    font-size: 14px;
    color: #111827;
    background: #ffffff;
    outline: none;
}
.ai-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
.ai-send-btn,
.ai-mic-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    background: #4f46e5;
    color: #fff;
    display: grid;
    place-items: center;
    cursor: pointer;
    flex-shrink: 0;
}
.ai-send-btn svg,
.ai-mic-btn svg {
    width: 18px;
    height: 18px;
}
.ai-action-btn.cart-shortcut { margin-left: auto; background: #16a34a; border-color: #16a34a; color: #fff; }
.ai-action-btn[hidden] { display: none; }
.ai-mic-btn.listening { background: #dc2626; animation: aiMicPulse 1s infinite; }
@keyframes aiMicPulse { 50% { box-shadow: 0 0 0 7px rgba(220, 38, 38, .16); } }
@media (min-width: 641px) {
    .ai-page {
        width: 100%;
        height: calc(100dvh - 138px);
        min-height: 560px;
        background: #fff;
    }
    .ai-card {
        width: 100%;
        max-width: none;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        padding: 18px max(28px, calc((100vw - 1180px) / 2));
    }
    .ai-header {
        padding: 10px 14px;
        margin-bottom: 14px;
        border-radius: 14px;
        background: #f7f9ff;
    }
    .ai-chat-shell {
        padding: 8px 12px 0;
        margin: 0;
    }
    .ai-chat { max-width: 980px; width: 100%; margin: 0 auto; }
    .ai-message { max-width: 68%; }
    .ai-message.user { max-width: 60%; }
    .ai-product-card { min-width: min(430px, 100%); }
    .ai-composer {
        max-width: 980px;
        width: 100%;
        margin: auto auto 0;
        padding: 12px 0 2px;
    }
    .ai-history-panel,
    .ai-cart-panel {
        left: max(28px, calc((100vw - 1180px) / 2));
        right: max(28px, calc((100vw - 1180px) / 2));
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        box-shadow: 0 18px 45px rgba(15,23,42,.12);
    }
}
@media (max-width: 640px) {
    .ai-page {
        height: calc(100dvh - 126px);
        min-height: 0;
        background: #fff;
    }
    .ai-card {
        padding: 8px 12px 6px;
        border: 0;
        border-radius: 0;
        box-shadow: none;
    }
    .ai-header {
        position: relative;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        padding: 14px 48px 12px;
        margin-bottom: 2px;
        border: 0;
        border-bottom: 1px solid #eef2f6;
        border-radius: 0;
        background: #fff;
        text-align: center;
    }
    .ai-avatar {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        position: relative;
        overflow: visible;
        background: #eef7ff;
        border: 1px solid #cfe5f7;
        box-shadow: 0 0 0 7px rgba(0,103,165,.055), 0 8px 22px rgba(0,103,165,.12);
        animation: aiAvatarBreathe 2.8s ease-in-out infinite;
    }
    .ai-avatar::before,
    .ai-avatar::after {
        content: '';
        position: absolute;
        inset: -7px;
        border: 1px solid rgba(0,103,165,.18);
        border-radius: 50%;
        pointer-events: none;
        animation: aiAvatarRing 2.8s ease-out infinite;
    }
    .ai-avatar::after { inset: -14px; animation-delay: 1.4s; }
    .ai-avatar img { border-radius: 50%; position: relative; z-index: 1; }
    @keyframes aiAvatarBreathe {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-2px) scale(1.035); }
    }
    @keyframes aiAvatarRing {
        0% { opacity: .65; transform: scale(.92); }
        75%, 100% { opacity: 0; transform: scale(1.12); }
    }
    .ai-header-text { justify-items: center; }
    .ai-title {
        font-size: 17px;
        letter-spacing: -.25px;
    }
    .ai-status { color: #159447; font-size: 10.5px; }
    .ai-status-dot { width: 7px; height: 7px; box-shadow: 0 0 0 3px rgba(34,197,94,.12); }
    .ai-header-pill { position: absolute; top: 10px; right: 2px; padding: 7px; border: 0; background: transparent; }
    .ai-header-pill span { display: none; }
    .ai-chat-shell { padding: 10px 2px 0; margin-bottom: 4px; }
    .ai-chat { gap: 8px; }
    .ai-message { max-width: 88%; padding: 10px 12px; font-size: 12.5px; border-radius: 13px 13px 13px 4px; background: #fff; }
    .ai-message.assistant { background: #f5f9ff; border-color: #dceafb; }
    .ai-message.user { max-width: 82%; border-radius: 13px 13px 4px 13px; background: #edf6ff; border-color: #d5e8f8; }
    .ai-reply-avatar { width: 25px; height: 25px; }
    .ai-composer { gap: 8px; padding-top: 8px; }
    .ai-actions { gap: 6px; }
    .ai-action-btn { min-height: 31px; padding: 5px 8px; border-radius: 9px; font-size: 10.5px; }
    .ai-action-btn.primary { background: #0067a5; }
    .ai-input { padding: 11px 13px; font-size: 13px; border-radius: 11px; }
    .ai-send-btn { width: 40px; height: 40px; border-radius: 11px; background: #0067a5; }
    .ai-mic-wrap { position: relative; }
    .ai-mic-wrap::before,
    .ai-mic-wrap::after {
        content: '';
        position: fixed;
        left: 50%;
        z-index: 6;
        pointer-events: none;
        opacity: 0;
        transition: opacity .2s ease;
    }
    .ai-mic-wrap::before {
        bottom: 196px;
        width: min(230px, 68vw);
        height: 58px;
        border-radius: 14px;
        background: repeating-linear-gradient(90deg, #0874bd 0 3px, transparent 3px 8px);
        clip-path: polygon(0 47%,4% 40%,8% 55%,12% 29%,16% 64%,20% 20%,24% 72%,28% 35%,32% 58%,36% 12%,40% 82%,44% 27%,48% 66%,52% 16%,56% 78%,60% 31%,64% 62%,68% 22%,72% 74%,76% 38%,80% 59%,84% 30%,88% 67%,92% 42%,96% 56%,100% 47%,100% 53%,96% 62%,92% 50%,88% 76%,84% 42%,80% 69%,76% 46%,72% 83%,68% 30%,64% 70%,60% 39%,56% 87%,52% 24%,48% 75%,44% 35%,40% 90%,36% 20%,32% 67%,28% 43%,24% 80%,20% 28%,16% 72%,12% 37%,8% 63%,4% 48%,0 55%);
        filter: drop-shadow(0 4px 8px rgba(0,103,165,.2));
        transform: translateX(-50%) scaleY(.2);
    }
    .ai-mic-wrap::after {
        bottom: 224px;
        width: min(250px, 74vw);
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,103,165,.2), transparent);
        transform: translateX(-50%);
    }
    .ai-mic-wrap:has(.ai-mic-btn.listening)::before {
        opacity: 1;
        animation: aiSoundWave 1.05s ease-in-out infinite alternate, aiWaveTravel 2s linear infinite;
    }
    .ai-mic-wrap:has(.ai-mic-btn.listening)::after { opacity: 1; }
    .ai-mic-btn {
        position: relative;
        width: 52px;
        height: 52px;
        margin: -6px 0;
        background: #0067a5;
        border: 4px solid #fff;
        box-shadow: 0 0 0 1px #cde2f4, 0 8px 20px rgba(0,103,165,.24);
    }
    .ai-mic-btn::before,
    .ai-mic-btn::after {
        content: '';
        position: absolute;
        width: 62px;
        height: 62px;
        border: 1px solid rgba(0,103,165,.22);
        border-radius: 50%;
        opacity: 0;
        pointer-events: none;
    }
    .ai-mic-btn.listening { background: #0067a5; animation: aiMobileMicGlow 1.15s ease-in-out infinite; }
    .ai-mic-btn.listening::before { animation: aiMobileVoiceRing 1.25s ease-out infinite; }
    .ai-mic-btn.listening::after { animation: aiMobileVoiceRing 1.25s .55s ease-out infinite; }
    @keyframes aiMobileMicGlow {
        50% { transform: scale(1.06); box-shadow: 0 0 0 7px rgba(0,103,165,.10), 0 10px 24px rgba(0,103,165,.28); }
    }
    @keyframes aiMobileVoiceRing {
        0% { opacity: .7; transform: scale(.85); }
        100% { opacity: 0; transform: scale(1.45); }
    }
    @keyframes aiSoundWave {
        0% { transform: translateX(-50%) scaleY(.42); filter: drop-shadow(0 3px 6px rgba(0,103,165,.14)); }
        28% { transform: translateX(-50%) scaleY(.82); }
        58% { transform: translateX(-50%) scaleY(.55); }
        100% { transform: translateX(-50%) scaleY(1); filter: drop-shadow(0 6px 12px rgba(0,103,165,.28)); }
    }
    @keyframes aiWaveTravel {
        from { background-position-x: 0; }
        to { background-position-x: 48px; }
    }
    .ai-mic-status { display: none; }
    .ai-product-card { padding: 9px; border-radius: 11px; background: #fff; }
    .ai-product-image { width: 52px; height: 52px; object-fit: contain; }
    .ai-product-title { font-size: 12px; }
    .ai-product-meta { font-size: 10.5px; }
    .ai-product-price { color: #0067a5; font-size: 12.5px; }
    .ai-product-btn.primary, .ai-cart-btn.primary { background: #0067a5; border-color: #0067a5; }
    .ai-action-btn.cart-shortcut {
        position: relative;
        margin-left: auto;
        padding-inline: 11px;
        background: #0067a5;
        border-color: #0067a5;
        color: #fff;
    }
    .ai-action-btn.cart-shortcut span { font-size: 0; }
    .ai-action-btn.cart-shortcut span::after { content: 'Live Order'; font-size: 10.5px; }
    .ai-cart-panel {
        position: fixed;
        inset: auto 6px calc(68px + env(safe-area-inset-bottom, 0px));
        z-index: 1050;
        width: auto;
        max-width: 520px;
        height: min(72dvh, 650px);
        margin: 0 auto;
        border: 1px solid #dce6ef;
        border-radius: 19px 19px 12px 12px;
        overflow-x: hidden;
        background: #fff;
        box-shadow: 0 -12px 38px rgba(16,43,67,.16), 0 8px 30px rgba(16,43,67,.10);
    }
    .ai-cart-panel::before {
        content: '';
        width: 38px;
        height: 4px;
        margin: 7px auto 0;
        border-radius: 99px;
        background: #d6dee8;
        flex: 0 0 auto;
    }
    .ai-cart-panel-head { padding: 7px 12px 10px; }
    .ai-cart-panel-head > svg { color: #0067a5; }
    .ai-cart-panel-title { text-transform: uppercase; letter-spacing: .035em; }
    .ai-cart-panel-body { padding: 10px; gap: 9px; overscroll-behavior: contain; }
    .ai-cart-panel-item { grid-template-columns: 54px minmax(0, 1fr) 32px; gap: 8px; padding: 9px; }
    .ai-cart-panel.open .ai-cart-panel-item { animation: aiOrderItemIn .24s ease-out both; }
    .ai-cart-panel.open .ai-cart-panel-item:nth-child(2) { animation-delay: .04s; }
    .ai-cart-panel.open .ai-cart-panel-item:nth-child(3) { animation-delay: .08s; }
    @keyframes aiOrderItemIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .ai-cart-panel-image { width: 54px; height: 54px; }
    .ai-cart-panel-name { font-size: 12px; overflow-wrap: anywhere; }
    .ai-cart-remove { width: 32px; min-height: 44px; padding: 4px; }
    .ai-cart-bill, .ai-cart-suggestions { padding: 11px; }
    .ai-cart-bill-row { gap: 8px; }
    .ai-cart-bill-row span:last-child { text-align: right; }
    .ai-cart-bill-row.total strong { font-size: 16px; }
    .ai-cart-panel-foot { padding: 9px 10px 10px; }
    .ai-cart-review { background: #0067a5; }
    .ai-cart-bill-row.total strong { color: #0067a5; }
    .ai-reorder-table { min-width: 0; table-layout: auto; }
    .ai-reorder-table th:nth-child(2), .ai-reorder-table td:nth-child(2) { display: none; }
    .ai-reorder-table th, .ai-reorder-table td { padding: 7px 5px; }
    .ai-reorder-image { width: 36px; height: 36px; flex-basis: 36px; }
    .ai-reorder-table .ai-qty-btn { width: 27px; height: 29px; }
    .ai-reorder-table .ai-qty-value { min-width: 25px; }
}
@media (prefers-reduced-motion: reduce) {
    .ai-avatar,
    .ai-avatar::before,
    .ai-avatar::after,
    .ai-mic-btn.listening,
    .ai-mic-btn.listening::before,
    .ai-mic-btn.listening::after { animation: none !important; }
    .ai-mic-wrap::before,
    .ai-mic-wrap::after,
    .ai-cart-panel.open .ai-cart-panel-item { animation: none !important; }
}

/* State-driven agent surface. The existing chat/cart elements remain the
   source of truth; this layer only presents their current state. */
.ai-agent-stage {
    display: grid;
    justify-items: center;
    align-content: center;
    gap: 8px;
    min-height: 128px;
    padding: 18px 16px 12px;
    text-align: center;
}
.ai-agent-character {
    position: relative;
    width: 138px;
    height: 132px;
    display: grid;
    place-items: end center;
    isolation: isolate;
    overflow: hidden;
    border-radius: 50%;
    background: #fff;
}
.ai-agent-identity {
    display: grid;
    justify-items: center;
    gap: 2px;
}
.ai-agent-name {
    color: #102a56;
    font-size: 17px;
    font-weight: 850;
    letter-spacing: -.02em;
}
.ai-agent-online {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #16834b;
    font-size: 11px;
    font-weight: 700;
}
.ai-agent-online::before {
    content: '';
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #22c55e;
    box-shadow: 0 0 0 3px rgba(34,197,94,.12);
}
.ai-hero-mic {
    display: grid;
    justify-items: center;
    gap: 8px;
    margin-top: 4px;
}
.ai-hero-mic .ai-mic-btn {
    width: 76px;
    height: 76px;
    margin: 0;
    border: 5px solid #fff;
    background: #075ff7;
    box-shadow: 0 0 0 1px #c9dcf5, 0 12px 28px rgba(7,95,247,.24);
    transition: transform .16s ease, box-shadow .16s ease, background .16s ease;
}
.ai-hero-mic .ai-mic-btn:hover { transform: translateY(-1px); }
.ai-hero-mic .ai-mic-btn:active { transform: scale(.94); }
.ai-hero-mic .ai-mic-btn svg { width: 27px; height: 27px; }
.ai-hero-mic .ai-mic-status {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
.ai-agent-character::before {
    content: '';
    position: absolute;
    inset: 20px 8px 2px;
    z-index: -1;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(49,87,213,.18), rgba(49,87,213,0) 68%);
    filter: blur(8px);
}
.ai-agent-character img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
    object-position: center bottom;
    filter: drop-shadow(0 12px 14px rgba(20,42,102,.16));
    transform-origin: center bottom;
    animation: aiCharacterIdle 3.4s ease-in-out infinite;
}
@keyframes aiCharacterIdle {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-3px) scale(1.012); }
}
.ai-agent-state {
    margin: 0;
    color: #101828;
    font-size: clamp(20px, 2vw, 28px);
    font-weight: 750;
    letter-spacing: -.025em;
}
.ai-agent-detail {
    max-width: 520px;
    min-height: 22px;
    margin: 0;
    color: #667085;
    font-size: 14px;
    line-height: 1.5;
}
.ai-agent-wave {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    height: 32px;
    opacity: 0;
    transition: opacity .2s ease;
}
.ai-agent-wave span {
    width: 3px;
    height: 8px;
    border-radius: 999px;
    background: #3157d5;
    transform-origin: center;
}
.ai-card[data-agent-state="listening"] .ai-agent-wave,
.ai-card[data-agent-state="understanding"] .ai-agent-wave,
.ai-card[data-agent-state="matching"] .ai-agent-wave,
.ai-card[data-agent-state="executing"] .ai-agent-wave,
.ai-card[data-agent-state="speaking"] .ai-agent-wave { opacity: 1; }
.ai-card[data-agent-state="listening"] .ai-agent-wave span { animation: aiStateWave .8s ease-in-out infinite alternate; }
.ai-card[data-agent-state="listening"] .ai-agent-character img {
    animation: aiCharacterListen 1.15s ease-in-out infinite;
}
.ai-card[data-agent-state="understanding"] .ai-agent-character img,
.ai-card[data-agent-state="matching"] .ai-agent-character img,
.ai-card[data-agent-state="executing"] .ai-agent-character img,
.ai-card[data-agent-state="speaking"] .ai-agent-character img {
    animation: aiCharacterThink 1.45s ease-in-out infinite;
}
.ai-card[data-agent-state="ready"] .ai-agent-character img {
    animation: aiCharacterDone .55s ease-out both;
}
@keyframes aiCharacterListen {
    0%, 100% { transform: translateY(0) rotate(-.35deg); }
    50% { transform: translateY(-4px) rotate(.45deg) scale(1.018); }
}
@keyframes aiCharacterThink {
    0%, 100% { transform: translateY(0); filter: drop-shadow(0 12px 14px rgba(20,42,102,.16)); }
    50% { transform: translateY(-2px) scale(.99); filter: drop-shadow(0 14px 18px rgba(98,70,234,.24)); }
}
@keyframes aiCharacterDone {
    0% { transform: scale(.94); }
    60% { transform: scale(1.035); }
    100% { transform: scale(1); }
}
.ai-card[data-agent-state="understanding"] .ai-agent-wave span,
.ai-card[data-agent-state="matching"] .ai-agent-wave span,
.ai-card[data-agent-state="executing"] .ai-agent-wave span,
.ai-card[data-agent-state="speaking"] .ai-agent-wave span { animation: aiStateWave 1.1s ease-in-out infinite alternate; }
.ai-agent-wave span:nth-child(2), .ai-agent-wave span:nth-child(8) { animation-delay: -.55s !important; }
.ai-agent-wave span:nth-child(3), .ai-agent-wave span:nth-child(7) { animation-delay: -.35s !important; }
.ai-agent-wave span:nth-child(4), .ai-agent-wave span:nth-child(6) { animation-delay: -.15s !important; }
.ai-agent-wave span:nth-child(5) { animation-delay: -.7s !important; }
@keyframes aiStateWave { to { height: 28px; background: #6246ea; } }
.ai-card[data-agent-state="ready"] .ai-agent-state { color: #087443; }
.ai-card[data-agent-state="error"] .ai-agent-state { color: #b54708; }
.ai-card[data-agent-state="listening"] .ai-avatar { animation-duration: 1.25s; }
.ai-card[data-agent-state="understanding"] .ai-avatar::after,
.ai-card[data-agent-state="matching"] .ai-avatar::after,
.ai-card[data-agent-state="executing"] .ai-avatar::after { animation-duration: 1.15s; }
.ai-order-dock {
    display: none;
    width: 100%;
    border: 1px solid #d9e2f2;
    border-radius: 16px;
    background: rgba(255,255,255,.96);
    box-shadow: 0 10px 28px rgba(16,24,40,.10);
    padding: 11px 14px;
    color: #101828;
    cursor: pointer;
    align-items: center;
    gap: 11px;
    text-align: left;
}
.ai-order-dock.visible { display: grid; grid-template-columns: 36px minmax(0, 1fr) auto; }
.ai-order-dock-icon { display:grid; place-items:center; width:36px; height:36px; flex:0 0 36px; border-radius:11px; color:#3157d5; background:#eef2ff; }
.ai-order-dock-icon svg { width:19px; height:19px; }
.ai-order-dock-copy { min-width:0; flex:1; display:flex; flex-direction:column; gap:2px; }
.ai-order-dock-copy strong { font-size:13px; }
.ai-order-dock-copy span { color:#667085; font-size:12px; }
.ai-order-dock-total { color:#101828; font-size:15px; font-weight:800; white-space:nowrap; }
.ai-live-preview { display:grid; grid-column:1 / -1; width:100%; max-height:154px; overflow-y:auto; overscroll-behavior:contain; border-top:1px solid #e8edf5; scrollbar-width:thin; }
.ai-live-preview-row { display:grid; grid-template-columns:minmax(0,1fr) auto auto; align-items:center; gap:9px; min-height:38px; padding:7px 2px; border-bottom:1px solid #f0f3f8; }
.ai-live-preview-row:last-child { border-bottom:0; }
.ai-live-preview-row.is-new { animation:aiLiveRowIn .32s ease-out both; }
.ai-live-preview-name { overflow:hidden; color:#172b4d; font-size:12px; font-weight:750; text-overflow:ellipsis; white-space:nowrap; }
.ai-live-preview-qty { color:#667085; font-size:11px; white-space:nowrap; }
.ai-live-preview-price { color:#102a56; font-size:12px; font-weight:800; white-space:nowrap; }
.ai-live-preview-empty { grid-column:1 / -1; padding:9px 2px 2px; color:#98a2b3; font-size:11px; text-align:center; }
@keyframes aiLiveRowIn { from { opacity:0; transform:translateY(8px); background:#eef6ff; } to { opacity:1; transform:translateY(0); background:transparent; } }
@media (max-width: 640px) {
    .ai-header {
        position: absolute;
        top: 4px;
        right: 8px;
        z-index: 8;
        display: block;
        width: 36px;
        height: 36px;
        padding: 0;
        margin: 0;
        border: 0;
        background: transparent;
    }
    .ai-header > .ai-avatar,
    .ai-header > .ai-header-text { display: none; }
    .ai-header-pill { position: static; width: 36px; height: 36px; padding: 8px; }
    .ai-agent-stage { min-height: clamp(280px, 50dvh, 330px); padding: 8px 12px 10px; gap: 6px; }
    .ai-card:has(.ai-live-preview-row) .ai-agent-stage { min-height: 210px; }
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-stage { min-height: 142px; }
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-character { width: 72px; height: 74px; }
    .ai-agent-character { width: 92px; height: 96px; margin: 0; }
    .ai-agent-character::before { inset: 16px 4px 0; }
    .ai-agent-name { font-size: 16px; }
    .ai-hero-mic { margin-top: 8px; gap: 7px; }
    .ai-hero-mic .ai-mic-btn { width: 78px; height: 78px; }
    .ai-agent-state { font-size: 21px; }
    .ai-agent-detail { max-width: 310px; font-size: 13px; }
    .ai-agent-wave { height: 25px; }
    .ai-mic-wrap::before,
    .ai-mic-wrap::after { display: none !important; }
    .ai-live-order-message { display: none !important; }
    .ai-action-btn.cart-shortcut { display: none !important; }
    .ai-order-dock { position: relative; z-index: 3; }
}
@media (prefers-reduced-motion: reduce) {
    .ai-agent-wave span,
    .ai-agent-character img { animation: none !important; }
}

/* Voice-ordering visual refresh. Keep all existing IDs and application hooks
   intact; these rules only restyle the current assistant surface. */
.ai-page {
    width: 100%;
    max-width: none;
    height: calc(100dvh - 105px);
    min-height: 0;
    margin: 0;
    background: #f3f7fa;
}
.mobile-main:has(.ai-page) {
    width: 100%;
    min-height: 0 !important;
    height: calc(100dvh - 105px);
    padding: 0 !important;
    margin: 0 !important;
    overflow: hidden;
    background: #f5fbff;
}
.ai-card {
    --shop-blue: #066fc2;
    --shop-ink: #11253e;
    padding: 0;
    border: 0;
    border-radius: 0;
    background: linear-gradient(155deg, #fff 0%, #f5fbff 66%, #e7f5fd 100%);
    box-shadow: 0 10px 45px rgba(23,49,74,.09);
    width: 100%;
    max-width: none;
    height: 100%;
    min-height: 0;
}
.ai-header {
    min-height: 66px;
    margin: 0;
    padding: 15px 19px;
    border: 0;
    border-radius: 0;
    background: transparent;
}
.ai-header-text { justify-items: center; }
.ai-title {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 15px;
    border-radius: 999px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(34,59,83,.07);
    color: #3b4d64;
    font-size: 11px;
    font-weight: 650;
}
.ai-title::before {
    content: '';
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: linear-gradient(135deg,#0a93d5,#6252d9);
}
.ai-status { display: none; }
.ai-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #f2f5f7;
}
.ai-avatar img { width: 24px; height: 28px; margin-top: 5px; }
.ai-header-pill {
    width: 32px;
    height: 32px;
    justify-content: center;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: #f2f5f7;
    color: #182c40;
}
.ai-header-pill span { display: none; }
.ai-home-back { position:absolute; top:14px; left:14px; z-index:10; width:32px; height:32px; display:grid; place-items:center; border-radius:50%; background:#f2f5f7; color:#607086; text-decoration:none; font-size:23px; line-height:1; }
.ai-agent-stage {
    flex: 0 0 auto;
    min-height: 380px;
    align-content: start;
    gap: 5px;
    padding: 4px 20px 10px;
}
.ai-agent-character {
    order: 3;
    width: 230px;
    height: 252px;
    margin-top: 2px;
    border-radius: 0;
    background: transparent;
    filter: drop-shadow(0 14px 12px rgba(13,119,173,.13));
}
.ai-agent-character::before { inset: 35px 20px 4px; }
.ai-agent-identity { order: 1; }
.ai-agent-name {
    font-size: 23px;
    line-height: 1.16;
    letter-spacing: -.6px;
    color: #2a3a51;
}
.ai-agent-kicker { font-weight: 500; }
.ai-agent-name strong { color: var(--shop-blue); font-weight: 750; }
.ai-agent-online { display: none; }
.ai-agent-state {
    order: 2;
    min-height: 28px;
    padding: 7px 16px;
    border: 1px solid #75b7ec;
    border-radius: 999px;
    background: #fff;
    box-shadow: 0 3px 12px rgba(47,135,197,.10);
    color: var(--shop-blue);
    font-size: 11px;
    font-weight: 700;
}
.ai-agent-detail { order: 5; min-height: 16px; font-size: 11px; }
.ai-agent-wave { order: 4; margin-top: -20px; }
.ai-hero-mic {
    order: 4;
    z-index: 2;
    margin-top: -47px;
}
.ai-hero-mic .ai-mic-btn {
    width: 62px;
    height: 62px;
    border: 5px solid #d5ecff;
    background: radial-gradient(circle at 35% 28%,#3cceff,#1679cc 56%,#005ea4);
    box-shadow: 0 7px 17px rgba(0,117,206,.34);
}
.ai-chat-shell {
    z-index: 3;
    padding: 0 20px;
    margin: -76px 0 8px;
}
.ai-chat { gap: 8px; }
.ai-message {
    max-width: 78%;
    padding: 10px 12px;
    border-color: #e7edf3;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 7px 18px rgba(23,54,76,.05);
    font-size: 12px;
    line-height: 1.4;
}
.ai-message.user { background: #e9f3ff; border-color: #dceafa; }
.ai-composer {
    z-index: 7;
    gap: 8px;
    padding: 8px 20px calc(14px + env(safe-area-inset-bottom, 0px));
    border-top: 0;
    border-radius: 21px 21px 0 0;
    background: rgba(255,255,255,.98);
    box-shadow: 0 -11px 34px rgba(19,61,89,.13);
}
.ai-composer::before {
    content: '';
    width: 28px;
    height: 4px;
    margin: 0 auto 2px;
    border-radius: 99px;
    background: #c5cbd1;
}
.ai-order-dock {
    padding: 8px 2px;
    border: 0;
    border-radius: 0;
    box-shadow: none;
    background: transparent;
}
.ai-order-dock-icon { color: var(--shop-blue); background: #edf7ff; }
.ai-order-checkout { display: none; grid-column: 1 / -1; width: 100%; padding-top: 8px; border-top: 1px solid #e8edf5; }
.ai-order-checkout.visible { display: grid; gap: 8px; }
.ai-order-checkout .ai-product-actions { margin: 0; }
.ai-order-checkout .ai-checkout-choice-list { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); }
.ai-order-checkout .ai-product-btn { min-height: 38px; white-space: normal; }
.ai-order-dock-copy strong { color: var(--shop-blue); font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
.ai-live-preview { max-height: 112px; }
.ai-actions { justify-content: center; }
.ai-action-btn { border-radius: 999px; font-size: 10px; }
.ai-input-row { border-color: #dce8f2; border-radius: 999px; background: #f8fbfd; }
.ai-send-btn { border-radius: 50%; background: var(--shop-blue); }
.ai-cart-panel, .ai-history-panel { border-radius: 21px 21px 0 0; }
.ai-cart-review { background: linear-gradient(90deg,#087bc7,#0062ac); }
.ai-product-btn.primary, .ai-cart-btn.primary { background: var(--shop-blue); border-color: var(--shop-blue); }

@media (min-width: 500px) {
    .mobile-main:has(.ai-page) { height: calc(100dvh - 105px); display: grid; place-items: center; }
    .ai-page { height: min(844px, 100%); max-width: 430px; margin: 0 auto; }
    .ai-card { border-radius: 28px; }
    .ai-composer { border-radius: 21px 21px 28px 28px; }
    .ai-cart-panel, .ai-history-panel { border-radius: 28px; }
}
@media (max-width: 640px) {
    body:has(.ai-page) { overflow: hidden; background: #edf5f9; }
    body:has(.ai-page) > .mobile-header,
    body:has(.ai-page) > .mobile-footer { display: none !important; }
    body:has(.ai-page) .mobile-main { width: 100vw; height: 100dvh; }
    .ai-page { width: 100vw; height: 100dvh; min-height: 0; padding: 7px; background: #edf5f9; }
    .ai-card { border-radius: 18px; }
    .ai-header { position: relative; inset: auto; display: flex; width: 100%; height: 60px; padding: 12px 14px; }
    .ai-header > .ai-avatar, .ai-header > .ai-header-text { display: grid; }
    .ai-header > .ai-avatar img { display: none; }
    .ai-header > .ai-avatar::after { content: '‹'; color: #607086; font-size: 24px; line-height: 1; }
    .ai-header-text { position: absolute; left: 50%; transform: translateX(-50%); }
    .ai-header-pill { position: static; margin-left: auto; }
    .ai-header-pill svg { display: none; }
    .ai-header-pill::after { content: '≡'; color: #607086; font-size: 16px; }
    .ai-agent-stage { position: relative; display: block; min-height: clamp(380px, 62dvh, 460px); padding: 0 12px; }
    .ai-card:has(.ai-live-preview-row) .ai-agent-stage,
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-stage { min-height: clamp(380px, 62dvh, 460px); }
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-character { width: 222px; height: 278px; }
    .ai-agent-character { position: absolute; left: 50%; bottom: 38px; width: 222px; height: 278px; margin: 0; transform: translateX(-50%); }
    .ai-agent-identity { display: block; }
    .ai-agent-name { font-size: 20px; }
    .ai-agent-state { width: fit-content; margin: 9px auto 0; }
    .ai-hero-mic { position: absolute; z-index: 4; left: 50%; bottom: 1px; margin: 0; transform: translateX(-50%); }
    .ai-hero-mic .ai-mic-btn { width: 58px; height: 58px; }
    .ai-agent-detail { position: absolute; left: 0; right: 0; bottom: -15px; }
    .ai-agent-wave { position: absolute; z-index: 3; left: 50%; bottom: 48px; width: 180px; margin: 0; transform: translateX(-50%); }
    .ai-chat-shell { position:absolute; z-index:6; top:auto; right:16px; bottom:var(--ai-composer-clearance, 205px); left:16px; width:auto; max-height:min(180px, 28dvh); padding:0; margin:0; overflow-x:hidden; overflow-y:auto; scrollbar-width:thin; transition:bottom .2s ease; }
    .ai-chat-shell:empty { pointer-events: none; }
    .ai-chat-shell:has(.ai-message-row) {
        padding: 9px;
        border: 1px solid #cfe1f3;
        border-radius: 13px;
        background: rgba(247,251,255,.97);
        box-shadow: 0 9px 24px rgba(31,79,119,.10);
    }
    .ai-chat .ai-message-row { width: 100%; }
    .ai-chat .ai-message { width: 100%; max-width: 100%; padding: 0; border: 0; background: transparent; box-shadow: none; }
    .ai-chat .ai-message.user { width: fit-content; max-width: 82%; margin-left: auto; padding: 8px 10px; background: #e9f3ff; }
    .ai-reply-avatar { display: none !important; }
    .ai-chat .ai-product-card { background: #fff; }
    .ai-chat .ai-product-actions { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:8px; }
    .ai-chat .ai-product-actions .ai-product-btn { min-height:40px; display:grid; place-items:center; padding:8px 10px; white-space:normal; text-align:center; }
    .ai-chat .ai-message-time { margin-top:7px; opacity:.7; }
    .ai-order-interactions {
        display: grid;
        gap: 8px;
        width: 100%;
        max-height: min(190px, 27dvh);
        overflow-x: hidden;
        overflow-y: auto;
        padding: 0;
        scrollbar-width: thin;
    }
    .ai-order-interactions:empty { display: none; }
    .ai-order-interactions .ai-message-row {
        padding: 9px;
        border: 1px solid #d9e7f4;
        border-radius: 12px;
        background: #f8fbff;
    }
    .ai-order-interactions .ai-suggestion-line {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
        overflow: visible;
    }
    .ai-composer { padding-inline: 14px; flex: 0 0 auto; max-height: 44%; overflow-y: auto; }
}
@media (max-height: 700px) {
    .ai-page { min-height: 520px; }
    .ai-agent-stage { min-height: 350px; }
    .ai-agent-character { width: 185px; height: 230px; }
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-stage { min-height: 350px; }
    .ai-card:has(.ai-chat > .ai-message-row) .ai-agent-character { width: 185px; height: 230px; }
    .ai-chat-shell { top:auto; max-height:150px; }
}

/* Exact responsive shell from the supplied UI reference. */
body:has(.ai-page){margin:0;overflow:hidden;background:#f3f7fa}
body:has(.ai-page)>.mobile-header,body:has(.ai-page)>.mobile-footer{display:none!important}
body:has(.ai-page) .mobile-main{width:100%;height:100dvh;padding:0!important}
.ai-page{width:min(100%,430px);height:100dvh;min-height:620px;margin:0 auto;padding:0;background:linear-gradient(155deg,#fff 0%,#f5fbff 66%,#e7f5fd 100%)}
.ai-card{width:100%;height:100%;min-height:0;padding:0;border:0;border-radius:0;background:transparent;box-shadow:0 10px 45px #17314a16;overflow:hidden}
.ai-header{position:absolute;z-index:20;inset:0 0 auto;height:66px;margin:0;padding:17px 19px;display:flex;align-items:center;justify-content:space-between;border:0;border-radius:0;background:transparent}
.ai-home-back,.ai-header-pill{position:static;width:30px;height:30px;min-width:30px;margin:0;padding:0;display:grid;place-items:center;border:0;border-radius:50%;background:#f2f5f7;color:#182c40;box-shadow:none}
.ai-home-back{font-size:24px}.ai-header-pill{margin-left:auto}.ai-header-pill svg,.ai-header-pill span{display:none!important}.ai-header-pill::after{content:'≡';color:#607086;font-size:16px}
.ai-header-text{position:absolute;left:50%;top:17px;width:auto;min-width:0;padding:7px 15px;display:block;transform:translateX(-50%);border-radius:99px;background:#fff;box-shadow:0 5px 18px #223b5310}
.ai-title{display:flex;align-items:center;gap:5px;color:#3b4d64;font-size:11px;font-weight:500;white-space:nowrap}.ai-title .ai-status-dot{width:11px;height:11px;margin:0;background:linear-gradient(135deg,#0a93d5,#6252d9);box-shadow:none}
.ai-agent-stage{position:absolute;inset:66px 0 128px;min-height:0!important;display:block;padding:0 20px}
.ai-agent-identity{position:absolute;z-index:5;top:6px;left:0;right:0;display:block;text-align:center}.ai-agent-name{margin:0;color:#2a3a51;font-size:23px;line-height:1.16;letter-spacing:-.6px;font-weight:500}.ai-agent-kicker{display:inline;font-weight:500}.ai-agent-kicker::after{content:'\A';white-space:pre}.ai-agent-name strong{color:#066fc2;font-weight:700}
.ai-agent-state{position:absolute;z-index:6;top:50px;left:50%;width:max-content;min-height:0;margin:0;padding:7px 16px;transform:translateX(-50%);border:1px solid #75b7ec;border-radius:99px;background:#fff;color:#066fc2;box-shadow:0 3px 12px #2f87c51a;font-size:11px;font-weight:650;line-height:1}.ai-agent-state::before{content:'♧\00a0'}
.ai-agent-character{position:absolute!important;z-index:2;left:50%!important;bottom:47px!important;width:240px!important;height:310px!important;margin:0!important;transform:translateX(-50%)!important;border:0;background:transparent;filter:drop-shadow(0 14px 12px #0d77ad22)}.ai-agent-character::before{display:none}.ai-agent-character img{width:100%;height:100%;object-fit:contain}
.ai-hero-mic{position:absolute;z-index:7;left:50%;bottom:9px;width:auto;margin:0;transform:translateX(-50%)}.ai-hero-mic .ai-mic-btn{width:57px;height:57px;margin:0 auto 3px;border:5px solid #d5ecff;border-radius:50%;background:radial-gradient(circle at 35% 28%,#3cceff,#1679cc 56%,#005ea4);box-shadow:0 7px 17px #0075ce55;color:#fff}.ai-hero-mic .ai-mic-btn svg{display:none}.ai-hero-mic .ai-mic-btn::before{content:'♪';font-size:27px;font-weight:700}.ai-mic-status{display:block;color:#607086;font-size:10px;white-space:nowrap}.ai-agent-detail{display:none!important}
.ai-agent-wave{position:absolute;z-index:3;left:50%;bottom:57px;width:240px;margin:0;transform:translateX(-50%);opacity:0}.ai-card[data-agent-state=listening] .ai-agent-wave,.ai-card[data-agent-state=speaking] .ai-agent-wave{opacity:.8}
.ai-composer{position:absolute;z-index:10;left:0;right:0;bottom:0;min-height:128px;max-height:67%;display:flex;gap:8px;padding:0 20px 16px;overflow-y:auto;border:0;border-radius:21px 21px 0 0;background:#fff;box-shadow:0 -11px 34px #133d5922}.ai-composer::before{width:28px;height:4px;flex:0 0 4px;margin:8px auto 3px;background:#c5cbd1}
.ai-order-dock{width:100%;padding:0;border:0;border-radius:0;background:transparent;box-shadow:none}.ai-order-dock-icon{display:none}.ai-order-dock-copy strong{color:#0068bf;font-size:11px;font-weight:800;letter-spacing:0;text-transform:uppercase}.ai-order-dock-copy strong::before{content:'♢\00a0'}.ai-order-dock-copy span{color:#6f7c8e;font-size:9px}.ai-order-dock-total{color:#11253e;font-size:12px}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total{color:#159450;font-size:0}.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total::after{content:'Building';font-size:9px}
.ai-live-preview{grid-column:1/-1;width:100%;max-height:112px;margin-top:4px}.ai-live-preview-empty{display:block;padding:15px 18px 1px;color:#657389;font-size:10px;line-height:1.55;text-align:center}.ai-order-interactions,.ai-order-checkout{grid-column:1/-1}.ai-actions,.ai-input-row{flex:0 0 auto}
.ai-card:not(:has(.ai-live-preview-row)):not(:has(.ai-order-interactions .ai-message-row)):not(:has(.ai-order-checkout.visible)) .ai-actions,.ai-card:not(:has(.ai-live-preview-row)):not(:has(.ai-order-interactions .ai-message-row)):not(:has(.ai-order-checkout.visible)) .ai-input-row{display:none}
@media(min-width:500px){body:has(.ai-page) .mobile-main{display:grid;place-items:center;padding:24px!important}.ai-page{height:min(844px,calc(100dvh - 48px));border-radius:28px;overflow:hidden}.ai-card{border-radius:28px}.ai-composer{border-radius:21px 21px 28px 28px}}
@media(max-height:730px){.ai-page{min-height:0}.ai-agent-stage{inset-bottom:115px}.ai-agent-character{bottom:35px!important;width:205px!important;height:265px!important}.ai-hero-mic{bottom:2px}.ai-composer{min-height:115px}}

/* Reference-screen alignment (342 x 712 source composition). */
body:has(.ai-page){background:#edf2f5}
.ai-page{height:min(674px,calc(100dvh - 8px));min-height:0;margin:4px auto 0;border-radius:0 0 20px 20px;overflow:hidden}
.ai-card{border-radius:0 0 20px 20px;background:linear-gradient(160deg,#fff 0%,#f9fcfe 58%,#e9f6fd 100%)}
.ai-header{height:57px;padding:14px 13px}
.ai-home-back,.ai-header-pill{width:25px;height:25px;min-width:25px;background:#f2f5f7}
.ai-home-back{font-size:0}
.ai-home-back::after{content:'\2039';font-size:22px;line-height:1;color:#18334b;transform:translateY(-1px)}
.ai-header-pill::after{content:'\2261';font-size:14px;line-height:1;color:#18334b;transform:scaleX(.72)}
.ai-header-text{top:17px;padding:5px 13px}
.ai-title{gap:6px;font-size:9px;color:#31465a}
.ai-title .ai-status-dot{width:8px;height:8px;background:#1579d4}
.ai-agent-stage{inset:57px 0 103px;padding:0}
.ai-agent-identity{top:7px}
.ai-agent-name{font-family:Georgia,'Times New Roman',serif;font-size:18px;line-height:1.08;letter-spacing:-.25px;color:#071d32}
.ai-agent-name strong{color:#0571c7;font-weight:700}
.ai-agent-state{top:56px;padding:6px 13px;border-color:#3fa6ef;font-size:9px;font-weight:700;box-shadow:0 2px 7px rgba(31,136,211,.12);cursor:pointer}
.ai-agent-state::before{content:'\2726\00a0';font-size:8px}
.ai-agent-character{bottom:50px!important;width:184px!important;height:233px!important;filter:drop-shadow(0 13px 14px rgba(13,119,173,.13))}
.ai-agent-character::after{content:'\2726';position:absolute;right:0;top:76px;color:#19a9e5;font-size:15px}
.ai-agent-character img{overflow:visible}
.ai-hero-mic{bottom:4px}
.ai-hero-mic .ai-mic-btn{width:48px;height:48px;margin-bottom:5px;border-width:4px;box-shadow:0 6px 14px rgba(0,117,206,.35)}
.ai-hero-mic .ai-mic-btn::before{display:none;content:none}
.ai-hero-mic .ai-mic-btn svg{display:block!important;width:21px;height:21px;stroke:#fff;stroke-width:2.35}
.ai-mic-status{font-size:8px;color:#758396;text-align:center}
.ai-composer{min-height:103px;max-height:64%;padding:0 14px 13px;border-radius:18px 18px 20px 20px;box-shadow:0 -11px 31px rgba(19,61,89,.11)}
.ai-composer::before{width:21px;height:3px;flex-basis:3px;margin:7px auto 5px;background:#b8c0c7}
.ai-order-dock{display:grid!important;grid-template-columns:minmax(0,1fr) auto;align-items:start;gap:0;padding:0}
.ai-order-dock-copy{display:flex;flex-direction:row;align-items:baseline;gap:7px}
.ai-order-dock-copy strong{font-size:9px;color:#0068bf}
.ai-order-dock-copy strong::before{content:'\2726\00a0';font-size:8px}
.ai-order-dock-copy span{font-size:7px;color:#566475}
.ai-order-dock-total{padding-top:1px}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total::after{font-size:7px}
.ai-live-preview{margin:0;max-height:92px}
.ai-live-preview-empty{padding:20px 10px 0;font-size:8px;line-height:1.7;color:#748195}
/* Filled live-order state: readable rows without disturbing the empty reference state. */
.ai-card:has(.ai-live-preview-row) .ai-composer{min-height:190px;max-height:min(58dvh,430px);padding-bottom:14px}
.ai-card:has(.ai-live-preview-row) .ai-order-dock{row-gap:8px}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-copy{min-height:24px;align-items:center}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-copy strong{font-size:10px}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-copy span{font-size:8px}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-total{display:grid;justify-items:end;gap:2px;color:#102a56;font-size:12px;line-height:1.1}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-total::before{content:'Subtotal';color:#7a8797;font-size:7px;font-weight:600;text-transform:uppercase;letter-spacing:.04em}
.ai-card:has(.ai-live-preview-row) .ai-live-preview{max-height:210px;padding:2px 0 4px;border-top:1px solid #e5edf4;scrollbar-color:#b8c9d8 transparent}
.ai-live-preview-row{grid-template-columns:minmax(0,1fr) 58px 72px;gap:8px;min-height:49px;padding:8px 2px;border-bottom:1px solid #edf2f6}
.ai-live-preview-name{display:-webkit-box;overflow:hidden;font-size:10px;line-height:1.35;font-weight:700;white-space:normal;text-overflow:clip;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow-wrap:anywhere}
.ai-live-preview-qty{font-size:9px;line-height:1.25;text-align:center;white-space:normal}
.ai-live-preview-price{font-size:10px;text-align:right}
.ai-live-preview-row:last-child{border-bottom:0}
.ai-card:has(.ai-live-preview-row) .ai-input-row{display:flex}
.ai-card:has(.ai-live-preview-row) .ai-input{min-height:38px;padding:9px 13px;font-size:12px}
.ai-card:has(.ai-live-preview-row) .ai-send-btn{width:38px;height:38px}
@media(min-width:500px){body:has(.ai-page) .mobile-main{padding:0!important}.ai-page{height:min(674px,calc(100dvh - 8px));border-radius:20px}.ai-card{border-radius:20px}.ai-composer{border-radius:18px 18px 20px 20px}}
@media(max-height:650px){.ai-page{height:calc(100dvh - 8px)}.ai-agent-character{width:160px!important;height:203px!important;bottom:45px!important}.ai-agent-stage{inset-bottom:96px}.ai-composer{min-height:96px}.ai-hero-mic{bottom:0}}

/* Final conflict reset: older assistant themes above must not rearrange this screen. */
.ai-page{position:relative!important;height:calc(100vh - 8px)!important;height:calc(100dvh - 8px)!important;max-height:none!important;margin:4px auto!important}
.ai-card{position:absolute!important;inset:0!important;width:auto!important;height:auto!important;min-height:0!important}
.ai-header{position:absolute!important;inset:0 0 auto 0!important;width:100%!important;height:57px!important;min-height:57px!important;display:flex!important;align-items:center!important;justify-content:space-between!important;padding:14px 13px!important}
.ai-home-back{position:absolute!important;top:14px!important;left:13px!important;width:25px!important;height:25px!important;display:grid!important;place-items:center!important;margin:0!important;background:#f2f5f7!important;text-decoration:none!important;transform:none!important}
.ai-header-pill{position:absolute!important;top:14px!important;right:13px!important;width:25px!important;height:25px!important;margin:0!important}
.ai-header-text{position:absolute!important;top:17px!important;left:50%!important;display:block!important;transform:translateX(-50%)!important}
.ai-title{padding:5px 13px!important;font-family:Arial,Helvetica,sans-serif!important;font-size:9px!important;line-height:1!important}
.ai-title::before{display:none!important;content:none!important}
.ai-agent-name{font-family:Arial,Helvetica,sans-serif!important;font-size:18px!important;line-height:1.12!important;font-weight:400!important;letter-spacing:-.35px!important}
.ai-agent-name strong{font-weight:700!important}
.ai-composer{position:absolute!important;z-index:10!important;right:0!important;bottom:0!important;left:0!important;display:flex!important;flex-direction:column!important;align-items:stretch!important;justify-content:flex-start!important;width:100%!important;gap:8px!important;overflow-x:hidden!important;background:#fff!important;border-radius:18px 18px 20px 20px!important;box-sizing:border-box!important}
.ai-composer::before{display:block!important;align-self:center!important}
.ai-order-dock{flex:0 0 auto!important;width:100%!important;min-width:0!important}
.ai-live-preview{width:100%!important;min-width:0!important;overflow-x:hidden!important;overflow-y:auto!important}
.ai-actions{width:100%!important;flex:0 0 auto!important}
.ai-input-row{width:100%!important;min-width:0!important;flex:0 0 auto!important;background:transparent!important}
.ai-input-row::before,.ai-input-row::after,.ai-composer::after{display:none!important;content:none!important}
.ai-card:has(.ai-live-preview-row) .ai-composer{height:auto!important;min-height:190px!important;max-height:min(58dvh,430px)!important}
.ai-card:has(.ai-live-preview-row) .ai-agent-character{bottom:50px!important;width:160px!important;height:203px!important}
.ai-card:has(.ai-live-preview-row) .ai-hero-mic{bottom:4px!important}
.ai-card:has(.ai-live-preview-row) .ai-agent-stage{inset-bottom:var(--ai-sheet-height,260px)!important}
.ai-card:has(.ai-live-preview-row) .ai-composer{
    height:max-content!important;
    min-height:0!important;
    max-height:calc(100dvh - 110px)!important;
    overflow-y:auto!important;
}
.ai-card:has(.ai-live-preview-row) .ai-live-preview{max-height:158px!important}
.ai-card:has(.ai-live-preview-row) .ai-order-interactions{display:block!important;max-height:none!important;overflow:visible!important}
.ai-card:has(.ai-live-preview-row) .ai-order-interactions:empty{display:none!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-message{padding:0!important;border:0!important;background:transparent!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-message .ai-message{padding:0!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-line{display:flex!important;gap:7px!important;overflow-x:auto!important;padding:2px 1px 5px!important;scrollbar-width:thin}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-card{flex:0 0 92px!important;min-width:92px!important;padding:6px!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-image{width:42px!important;height:42px!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-name{font-size:9px!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-message .ai-product-actions{display:flex!important;justify-content:center!important;margin:0!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-message .ai-product-btn{min-height:30px!important;padding:6px 10px!important;border-radius:999px!important;font-size:9px!important}
.ai-card:has(.ai-live-preview-row) .ai-suggestion-message .ai-message-time{display:none!important}
.ai-live-preview-row.has-controls{grid-template-columns:minmax(0,1fr) 82px 68px!important}
.ai-live-preview-row .ai-qty-control{height:27px;border-radius:8px}
.ai-live-preview-row .ai-qty-btn{width:26px;height:27px;font-size:15px}
.ai-live-preview-row .ai-qty-value{min-width:28px;font-size:9px}
.ai-checkout-stage-enter{animation:aiCheckoutStageIn .3s ease-out both}
.ai-checkout-stage-leave{animation:aiCheckoutStageOut .2s ease-in both}
@keyframes aiCheckoutStageIn{from{opacity:0;transform:translateX(18px)}to{opacity:1;transform:translateX(0)}}
@keyframes aiCheckoutStageOut{to{opacity:0;transform:translateX(-18px)}}
/* Readable mobile scale; this intentionally wins over the old short-screen shrink rule. */
.ai-agent-character,.ai-card:has(.ai-live-preview-row) .ai-agent-character{width:200px!important;height:253px!important;bottom:52px!important}
.ai-hero-mic,.ai-card:has(.ai-live-preview-row) .ai-hero-mic{bottom:3px!important}
.ai-hero-mic .ai-mic-btn{width:54px!important;height:54px!important}
.ai-hero-mic .ai-mic-btn svg{width:23px!important;height:23px!important}
.ai-mic-status{font-size:10px!important;line-height:1.25!important}
.ai-order-dock{grid-template-columns:minmax(0,1fr) auto!important;column-gap:12px!important}
.ai-order-dock-copy{grid-column:1!important;justify-self:start!important;gap:6px!important;min-width:0!important}
.ai-order-dock-copy strong{font-size:11px!important;line-height:1.2!important;white-space:nowrap!important}
.ai-order-dock-copy span{font-size:9px!important;line-height:1.2!important}
.ai-order-dock-total{grid-column:2!important;justify-self:end!important;align-self:start!important;min-width:74px!important;font-size:12px!important;line-height:1.2!important;text-align:right!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total::after{font-size:9px!important;line-height:1.2!important}
.ai-live-preview{grid-column:1/-1!important}
.ai-live-preview-empty{padding:18px 10px 2px!important;font-size:10px!important;line-height:1.55!important}
.ai-live-preview-name{font-size:11px!important;line-height:1.35!important}
.ai-live-preview-qty{font-size:10px!important}
.ai-live-preview-price{font-size:11px!important}
.ai-actions .ai-action-btn{font-size:10px!important}
.ai-input{font-size:12px!important}

/* Polished empty state based on the latest supplied reference. */
.ai-page{border-radius:28px!important;background:linear-gradient(155deg,#fff 0%,#f8fcff 58%,#eaf6ff 100%)!important}
.ai-card{border-radius:28px!important;box-shadow:0 14px 45px rgba(45,83,116,.12)!important}
.ai-header{height:70px!important;min-height:70px!important;padding:16px 16px!important}
.ai-home-back{top:16px!important;left:16px!important;width:36px!important;height:36px!important;background:#fff!important;box-shadow:0 7px 18px rgba(38,77,112,.13)!important}
.ai-home-back::after{font-size:29px!important;font-weight:700!important}
.ai-header-pill{top:16px!important;right:16px!important;width:36px!important;height:36px!important;background:rgba(255,255,255,.78)!important;border:2px solid #fff!important;box-shadow:0 6px 16px rgba(38,77,112,.11)!important}
.ai-header-pill::after{font-size:20px!important;font-weight:800!important;transform:scaleX(.78)!important}
.ai-header-text{top:17px!important;padding:0!important}
.ai-title{padding:10px 18px!important;font-size:14px!important;font-weight:750!important;color:#0c1a34!important;box-shadow:0 7px 20px rgba(38,77,112,.08)!important}
.ai-title .ai-status-dot{width:12px!important;height:12px!important;background:#0879ee!important}
.ai-agent-stage{top:70px!important}
.ai-agent-identity{top:22px!important}
.ai-agent-name{font-size:24px!important;line-height:1.25!important;font-weight:700!important;letter-spacing:-.65px!important;color:#081630!important}
.ai-agent-name strong{color:#0874ec!important;font-weight:800!important}
.ai-agent-state{top:104px!important;padding:10px 20px!important;border:1.5px solid #0879ee!important;font-size:14px!important;line-height:1!important;color:#0874ec!important;box-shadow:0 5px 14px rgba(8,121,238,.12)!important}
.ai-agent-state::before{font-size:12px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-agent-stage{inset-bottom:176px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-agent-character{width:280px!important;height:355px!important;bottom:130px!important;filter:drop-shadow(0 18px 18px rgba(14,114,184,.16))!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-agent-character::before{display:block!important;content:''!important;position:absolute!important;inset:34px 3px 0!important;border-radius:50%!important;background:radial-gradient(circle,rgba(44,154,242,.19),rgba(44,154,242,.06) 48%,transparent 70%)!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-hero-mic{z-index:12!important;bottom:70px!important}
.ai-hero-mic .ai-mic-btn{width:70px!important;height:70px!important;border:6px solid #fff!important;background:linear-gradient(145deg,#168cff,#0063e8)!important;box-shadow:0 0 0 8px rgba(43,157,255,.14),0 0 0 16px rgba(43,157,255,.08),0 12px 25px rgba(0,98,213,.30)!important}
.ai-hero-mic .ai-mic-btn svg{width:31px!important;height:31px!important;stroke-width:2!important}
.ai-mic-status{margin-top:8px!important;font-size:14px!important;font-weight:650!important;color:#647792!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-composer{min-height:176px!important;padding:0 20px 20px!important;border-radius:28px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-composer::before{width:35px!important;height:5px!important;flex-basis:5px!important;margin:10px auto 6px!important;background:#aebac7!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock{row-gap:12px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-copy{align-items:center!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-copy strong{font-size:14px!important;color:#0873dd!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-copy strong::before{content:'\2724\00a0'!important;font-size:13px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-copy span{font-size:12px!important;color:#78869a!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total{min-width:auto!important;padding:8px 13px!important;border-radius:999px!important;background:#e3f8eb!important;color:#0daa55!important;font-size:0!important;font-weight:750!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-order-dock-total::after{content:'\25CF\00a0 Building'!important;font-size:12px!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-live-preview{min-height:90px!important;display:grid!important;place-items:center!important;border:1px solid #dce6ef!important;border-radius:14px!important;background:rgba(255,255,255,.52)!important;overflow:hidden!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-live-preview-empty{display:grid!important;justify-items:center!important;gap:8px!important;padding:9px!important;font-size:12px!important;line-height:1.35!important;font-weight:600!important;color:#6e8099!important}
.ai-card:not(:has(.ai-live-preview-row)) .ai-live-preview-empty::before{content:'';width:38px;height:38px;border-radius:50%;background-color:#eaf1f8;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23889ab0' stroke-width='1.7'%3E%3Cpath d='M6 8h12l1 12H5L6 8Z'/%3E%3Cpath d='M9 9V6a3 3 0 0 1 6 0v3'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:center;background-size:23px}
@media(max-height:700px){
    .ai-agent-name{font-size:21px!important}
    .ai-agent-state{top:91px!important;padding:8px 17px!important;font-size:12px!important}
    .ai-card:not(:has(.ai-live-preview-row)) .ai-agent-character{width:235px!important;height:298px!important}
    .ai-card:not(:has(.ai-live-preview-row)) .ai-hero-mic .ai-mic-btn{width:60px!important;height:60px!important}
    .ai-card:not(:has(.ai-live-preview-row)) .ai-composer{min-height:164px!important}
    .ai-card:not(:has(.ai-live-preview-row)) .ai-agent-stage{inset-bottom:164px!important}
}
/* Product/checkout interaction state: size against the real sheet, not the
   empty-cart artwork, so growing content never covers the controls. */
.ai-card:has(.ai-order-interactions .ai-message-row) .ai-agent-stage,
.ai-card:has(.ai-order-checkout.visible) .ai-agent-stage{inset-bottom:var(--ai-sheet-height,300px)!important}
.ai-card:has(.ai-order-interactions .ai-message-row) .ai-agent-character,
.ai-card:has(.ai-order-checkout.visible) .ai-agent-character{width:178px!important;height:225px!important;bottom:48px!important}
.ai-card:has(.ai-order-interactions .ai-message-row) .ai-hero-mic,
.ai-card:has(.ai-order-checkout.visible) .ai-hero-mic{z-index:12!important;bottom:3px!important}
.ai-card:has(.ai-order-interactions .ai-message-row) .ai-composer,
.ai-card:has(.ai-order-checkout.visible) .ai-composer{max-height:62dvh!important;overflow-y:auto!important}
.ai-order-interactions .ai-product-card{display:grid!important;grid-template-columns:58px minmax(0,1fr)!important;gap:10px!important;padding:10px!important;border-radius:12px!important}
.ai-order-interactions .ai-product-image{width:58px!important;height:58px!important}
.ai-order-interactions .ai-product-title{font-size:12px!important;line-height:1.3!important}
.ai-order-interactions .ai-product-meta{font-size:10px!important;line-height:1.35!important}
.ai-order-interactions .ai-product-price{font-size:12px!important}
.ai-order-interactions .ai-product-actions{grid-template-columns:minmax(0,1fr) minmax(0,1fr)!important;gap:7px!important;margin-top:7px!important}
.ai-order-interactions .ai-product-btn{min-height:34px!important;font-size:10px!important}
/* Stable filled-cart composition. */
.ai-card:has(.ai-live-preview-row) .ai-agent-character{width:220px!important;height:280px!important;bottom:90px!important}
.ai-card:has(.ai-live-preview-row) .ai-hero-mic{z-index:12!important;bottom:48px!important;display:grid!important}
.ai-card:has(.ai-live-preview-row) .ai-mic-status{display:block!important;font-size:11px!important}
.ai-card:has(.ai-live-preview-row) .ai-composer{padding:0 18px 16px!important;border-radius:24px 24px 28px 28px!important}
.ai-card:has(.ai-live-preview-row) .ai-composer::before{width:30px!important;height:4px!important;flex-basis:4px!important;margin:8px auto 5px!important}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-copy strong{font-size:12px!important}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-copy span{font-size:10px!important}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-total{font-size:13px!important;min-width:88px!important}
.ai-card:has(.ai-live-preview-row) .ai-order-dock-total::before{font-size:9px!important}
.ai-card:has(.ai-live-preview-row) .ai-live-preview{max-height:174px!important;margin-top:2px!important}
.ai-card:has(.ai-live-preview-row) .ai-live-preview-row{min-height:48px!important;padding:9px 3px!important}
.ai-card:has(.ai-live-preview-row) .ai-live-preview-name{font-size:12px!important;line-height:1.35!important}
.ai-card:has(.ai-live-preview-row) .ai-live-preview-qty{font-size:10px!important}
.ai-card:has(.ai-live-preview-row) .ai-live-preview-price{font-size:12px!important}
/* The stage must clear the measured sheet height. `inset-bottom` is not a CSS
   property; use the real bottom inset so character and mic cannot be covered. */
.ai-agent-stage{bottom:var(--ai-sheet-height,176px)!important}
.ai-card:has(.ai-live-preview-row) .ai-agent-character{width:250px!important;height:318px!important;bottom:45px!important}
.ai-card:has(.ai-live-preview-row) .ai-hero-mic{z-index:14!important;bottom:10px!important}
.ai-suggestion-message.ai-stage-dismiss{pointer-events:none;animation:aiSuggestionStageOut .22s ease-in both!important}
@keyframes aiSuggestionStageOut{to{opacity:0;transform:translateY(12px) scale(.97)}}
</style>

<div class="ai-page">
    <div class="ai-card" data-agent-state="idle">
        <div class="ai-header">
            <a class="ai-home-back" href="{{ route('web.home') }}" aria-label="Back to home">‹</a>
            <div class="ai-header-text">
                <div class="ai-title"><span class="ai-status-dot"></span> Shop AI</div>
            </div>
            <button class="ai-header-pill" type="button" id="aiHistoryButton">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/><path d="M12 7v5l3 2"/></svg>
                <span>History</span>
            </button>
        </div>

        <section class="ai-agent-stage" aria-live="polite" aria-atomic="true">
            <div class="ai-agent-character" aria-hidden="true">
                <img id="aiAgentCharacterImage"
                     src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     data-ready-src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     data-listening-src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     data-searching-src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     data-success-src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     data-clarifying-src="{{ asset('assets/zonik-shop-ai-chef-3d.png') }}"
                     width="1024" height="1024" alt="">
            </div>
            <div class="ai-agent-identity">
                <div class="ai-agent-name"><span class="ai-agent-kicker">Shop effortlessly with</span> <strong>smart AI..</strong></div>
                <span class="ai-agent-online">Online</span>
            </div>
            <div class="ai-mic-wrap ai-hero-mic">
                <button class="ai-mic-btn" type="button" title="Tap to speak" id="aiMicBtn" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5a2.5 2.5 0 0 1 2.5 2.5v4a2.5 2.5 0 0 1-5 0v-4A2.5 2.5 0 0 1 12 5Z"/><path d="M19 11.5a7 7 0 0 1-14 0"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </button>
                <span class="ai-mic-status" id="aiMicStatus" role="status" aria-live="polite">Tap to speak</span>
            </div>
            <h1 class="ai-agent-state" id="aiAgentState" role="button" tabindex="0">Start talking!</h1>
            <p class="ai-agent-detail" id="aiAgentDetail">Tap to speak</p>
            <div class="ai-agent-wave" aria-hidden="true">
                <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
        </section>

        <section class="ai-history-panel" id="aiHistoryPanel" aria-hidden="true">
            <div class="ai-history-header">
                <button class="ai-history-back" type="button" id="aiHistoryBack" aria-label="Back">←</button>
                <div class="ai-history-heading"><strong id="aiHistoryTitle">Chat history</strong><span id="aiHistorySubtitle">Your saved conversations</span></div>
                <div class="ai-history-actions"><button class="ai-history-continue" type="button" id="aiHistoryContinue">Continue chat</button></div>
            </div>
            <div class="ai-history-body"><div class="ai-history-list" id="aiHistoryContent"></div></div>
        </section>

        <section class="ai-cart-panel" id="aiCartPanel" aria-hidden="true">
            <div class="ai-cart-panel-head">
                <button class="ai-history-back" type="button" id="aiCartBack" aria-label="Back">←</button>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="20" r="1"/><circle cx="19" cy="20" r="1"/><path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/></svg>
                <div class="ai-cart-panel-title">Confirmed Order <small id="aiCartCount"></small></div>
                <button class="ai-cart-clear" type="button" id="aiCartClear">Clear All</button>
            </div>
            <div class="ai-cart-panel-body" id="aiCartPanelBody"></div>
            <div class="ai-cart-panel-foot"><button class="ai-cart-review" type="button" id="aiCartReview">Review Order →</button></div>
        </section>

        <div class="ai-confirm-overlay" id="aiDeleteConfirm" role="dialog" aria-modal="true" aria-labelledby="aiDeleteConfirmTitle" aria-hidden="true">
            <div class="ai-confirm-box">
                <div class="ai-confirm-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"/></svg></div>
                <h3 id="aiDeleteConfirmTitle">Delete this chat?</h3>
                <p>This conversation will be permanently removed from your chat history.</p>
                <div class="ai-confirm-actions">
                    <button class="ai-confirm-btn" type="button" id="aiDeleteCancel">Cancel</button>
                    <button class="ai-confirm-btn delete" type="button" id="aiDeleteConfirmButton">Confirm Delete</button>
                </div>
            </div>
        </div>

        <div class="ai-composer" id="aiComposer">
            <div class="ai-order-dock visible" role="button" tabindex="0" id="aiOrderDock" aria-label="Open live order">
                <span class="ai-order-dock-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="20" r="1"/><circle cx="19" cy="20" r="1"/><path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/></svg></span>
                <span class="ai-order-dock-copy"><strong>Live Order</strong><span id="aiOrderDockCount">(0)</span></span>
                <strong class="ai-order-dock-total" id="aiOrderDockTotal">₹0.00</strong>
                <span class="ai-live-preview" id="aiLivePreview"><span class="ai-live-preview-empty">Your order will appear here<br>as you speak.</span></span>
                <div class="ai-chat ai-order-interactions" id="aiChat" aria-live="polite"></div>
                <div class="ai-order-checkout" id="aiOrderCheckout"></div>
            </div>
            <div class="ai-actions">
                <button class="ai-action-btn" type="button" data-action="repeat" title="Repeat last response" aria-label="Repeat last response">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg><span>Repeat</span>
                </button>
                <button class="ai-action-btn primary" type="button" data-action="fresh" title="Start a new chat" aria-label="Start a new chat">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg><span>New</span>
                </button>
                <button class="ai-action-btn cart-shortcut" type="button" id="aiCartShortcut" title="Open cart" aria-label="Open cart" hidden>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1"/><circle cx="19" cy="20" r="1"/><path d="M3 4h2l2.4 10.4a2 2 0 0 0 2 1.6h7.7a2 2 0 0 0 2-1.6L21 8H6"/></svg><span>Cart</span>
                </button>
            </div>

            <div class="ai-input-row">
                <input type="text" class="ai-input" id="aiInput" placeholder="Type your order..." aria-label="Assistant input">
                <button class="ai-send-btn" type="button" title="Send message" id="aiSendBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-20 9 7.5 2.5L15 21l2-9 5-10Z"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chat = document.getElementById('aiChat');
        const chatShell = document.getElementById('aiChatShell');
        const composer = document.getElementById('aiComposer');
        const input = document.getElementById('aiInput');
        const sendBtn = document.getElementById('aiSendBtn');
        const micBtn = document.getElementById('aiMicBtn');
        const micStatus = document.getElementById('aiMicStatus');
        const aiCard = document.querySelector('.ai-card');
        const agentStateLabel = document.getElementById('aiAgentState');
        const agentStateDetail = document.getElementById('aiAgentDetail');
        const agentCharacterImage = document.getElementById('aiAgentCharacterImage');
        const orderDock = document.getElementById('aiOrderDock');
        const orderCheckout = document.getElementById('aiOrderCheckout');
        const orderDockCount = document.getElementById('aiOrderDockCount');
        const orderDockTotal = document.getElementById('aiOrderDockTotal');
        const livePreview = document.getElementById('aiLivePreview');
        function syncChatClearance() {
            if (!composer || window.innerWidth > 640) return;
            const clearance = Math.ceil(composer.getBoundingClientRect().height + 10);
            chatShell?.style.setProperty('--ai-composer-clearance', clearance + 'px');
            aiCard?.style.setProperty('--ai-sheet-height', Math.ceil(composer.getBoundingClientRect().height) + 'px');
        }
        if (window.ResizeObserver && composer) {
            new ResizeObserver(syncChatClearance).observe(composer);
        }
        window.addEventListener('resize', syncChatClearance, {passive: true});
        requestAnimationFrame(syncChatClearance);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const chatUrl = '{{ route('assistant.chat') }}';
        const productsUrl = '{{ route('assistant.products') }}';
        const cartUrl = '{{ route('assistant.cart') }}';
        const assistantCartBaseUrl = '{{ url('/assistant/cart') }}';
        const assistantCartQuantityBaseUrl = '{{ url('/assistant/cart') }}';
        const assistantCartSnapshotUrl = '{{ route('assistant.cart.snapshot') }}';
        const historyUrl = '{{ route('assistant.history') }}';
        const selectionUrl = '{{ route('assistant.selection') }}';
        const transcribeUrl = '{{ route('assistant.transcribe') }}';
        const speakUrl = '{{ route('assistant.speak') }}';
        const welcomeUrl = '{{ route('assistant.welcome') }}';
        const onboardingIntentUrl = '{{ route('assistant.onboarding-intent') }}';
        const previousOrdersUrl = '{{ route('assistant.previous-orders') }}';
        const reorderUrl = '{{ route('assistant.reorder') }}';
        const catalogueEnquiryUrl = '{{ route('assistant.catalogue-enquiry') }}';
        const assistantCheckoutDataUrl = '{{ route('assistant.checkout-data') }}';
        const checkoutUrl = '{{ route('web.chekout') }}';
        const historyButton = document.getElementById('aiHistoryButton');
        const historyPanel = document.getElementById('aiHistoryPanel');
        const historyBack = document.getElementById('aiHistoryBack');
        const historyContent = document.getElementById('aiHistoryContent');
        const historyTitle = document.getElementById('aiHistoryTitle');
        const historySubtitle = document.getElementById('aiHistorySubtitle');
        const historyContinue = document.getElementById('aiHistoryContinue');
        const cartShortcut = document.getElementById('aiCartShortcut');
        const cartPanel = document.getElementById('aiCartPanel');
        const cartPanelBody = document.getElementById('aiCartPanelBody');
        const cartPanelCount = document.getElementById('aiCartCount');
        const cartBack = document.getElementById('aiCartBack');
        const cartClear = document.getElementById('aiCartClear');
        const cartReview = document.getElementById('aiCartReview');
        const deleteConfirm = document.getElementById('aiDeleteConfirm');
        const deleteCancel = document.getElementById('aiDeleteCancel');
        const deleteConfirmButton = document.getElementById('aiDeleteConfirmButton');
        function openAccessiblePanel(panel) {
            if (!panel) return;
            panel.inert = false;
            panel.removeAttribute('inert');
            panel.setAttribute('aria-hidden', 'false');
            panel.classList.add('open');
        }
        function closeAccessiblePanel(panel, returnFocus) {
            if (!panel) return;
            if (panel.contains(document.activeElement) && returnFocus) {
                try { returnFocus.focus({preventScroll: true}); } catch (error) { returnFocus.focus(); }
            }
            panel.classList.remove('open');
            panel.setAttribute('aria-hidden', 'true');
            panel.inert = true;
        }
        historyPanel.inert = true;
        cartPanel.inert = true;
        deleteConfirm.inert = true;
        let pendingDeleteConversation = null;
        let historyDetailOpen = false;
        let openedHistoryConversation = null;
        let mediaRecorder = null;
        let accurateVoiceMode = false;
        let voiceCaptureStarting = false;
        let voiceSilenceTimer = null;
        let speechRecognition = null;
        let speechRecognitionRestartTimer = null;
        let speechRecognitionStartPending = false;
        let continuousTalkMode = false;
        // Default to Indian English for voice input. Detected Hindi, Hinglish
        // and every other supported language replace this after a message.
        let conversationLanguage = 'en-IN';
        let conversationReplyLanguage = 'hinglish';
        let audioChunks = [];
        let recordingTimer = null;
        let responseReminderTimer = null;
        let responseReminderCount = 0;
        const maximumResponseReminders = 3;
        let activeOrderingStage = null;
        let activeOrderingProductId = null;
        let activeClarificationOptions = [];
        let activeCandidateSetId = '';
        let previousOrdersVisible = false;
        let awaitingNewOrderReady = false;
        let liveOrderMessage = null;
        let clarificationMessage = null;
        let liveOrderEditable = false;
        let previousLiveOrderRows = new Map();
        let selectedDeliveryDetails = '';
        let deliveryOptionRequestVersion = 0;
        let assistantOrderSubmitting = false;
        let assistantOrderCompleted = false;
        let cartPanelRequestVersion = 0;
        let lastUserMessage = '';
        let welcomeGreetingFinished = false;
        let onboardingStage = 'choose_order';
        let onboardingIntentRequestVersion = 0;
        let customerHasPreviousOrder = false;
        // The server supplies this with a customer-care workflow. Keeping the
        // latest safe `tel:` URL lets an affirmative spoken/typed response
        // open the phone app in the same user interaction, rather than waiting
        // for the AJAX response (which mobile browsers can treat as a popup).
        const customerCareFallbackPhone = @json(config('services.customer_care.phone', '+918850268043'));
        let customerCareDialUrl = '';
        let lastCustomerCareDialAttemptAt = 0;
        let lastCustomerCareDialAttemptUrl = '';

        function aiDebug(event, details) {
            console.log('[AI Assistant][' + new Date().toISOString() + '] ' + event, details === undefined ? '' : details);
        }

        function customerCareDialUrlFrom(value) {
            let phone = String(value || '').trim();
            if (!phone) return '';
            phone = phone.replace(/^tel:\s*/i, '');
            const hasPlus = phone.charAt(0) === '+';
            const digits = phone.replace(/\D/g, '');
            // A telephone URI must be a phone number, never an arbitrary URL.
            if (digits.length < 7 || digits.length > 15) return '';
            return 'tel:' + (hasPlus ? '+' : '') + digits;
        }

        function rememberCustomerCareDialer(workflow) {
            const data = workflow || {};
            const dialUrl = customerCareDialUrlFrom(data.dial_url)
                || customerCareDialUrlFrom(data.phone)
                || customerCareDialUrlFrom(customerCareFallbackPhone);
            if (dialUrl) customerCareDialUrl = dialUrl;
            return dialUrl;
        }

        function isCustomerCareDecline(value) {
            const message = String(value || '');
            return /\b(?:no|nahi|nahin|nai|nako|cancel|rehne\s+do|mat|nahi\s+chahiye)\b/iu.test(message)
                || /(?:\u0928\u0939\u0940\u0902|\u0928\u0939\u093f|\u0928\u0939\u0940|\u092e\u0924|\u0930\u0939\u0928\u0947\s*\u0926\u094b|\u0928\u0915\u094b)/u.test(message);
        }

        function isCustomerCareProductCommand(value) {
            const message = String(value || '');
            return /\b(?:add|remove|select|choose|show|search|find|replace|quantity|qty|product|item|cart)\b/iu.test(message)
                || /(?:\u0910\u0921|\u091c\u094b\u0921|\u0939\u091f\u093e\u0913|\u091a\u0941\u0928|\u0926\u093f\u0916\u093e\u0913|\u0922\u0942\u0902\u0922|\u092c\u0926\u0932|\u092e\u093e\u0924\u094d\u0930\u093e|\u092a\u094d\u0930\u094b\u0921\u0915\u094d\u091f|\u0906\u0907\u091f\u092e|\u0915\u093e\u0930\u094d\u091f)/u.test(message);
        }

        function isCustomerCareAffirmative(value) {
            const message = String(value || '');
            if (isCustomerCareDecline(message)
                || (isCustomerCareProductCommand(message) && !isExplicitCustomerCareCallRequest(message))) return false;
            return /\b(?:yes|yeah|yep|haan|han|haa|ha|ji|ok|okay|call|dial|ring|lagao|laga\s+do|connect|milao|mila\s+do|baat\s+(?:karo|karao|karwao))\b/iu.test(message)
                || /(?:\u0939\u093e\u0901|\u0939\u093e\u0902|\u0939\u093e\u0901\s*\u091c\u0940|\u091c\u0940|\u0915\u0949\u0932|\u0921\u093e\u092f\u0932|\u0932\u0917(?:\u093e\u0913|\u093e\s*\u0926\u094b)|\u0915\u0928\u0947\u0915\u094d\u091f|\u092e\u093f\u0932(?:\u093e\u0913|\u093e\s*\u0926\u094b)|\u092c\u093e\u0924\s*(?:\u0915\u0930\u094b|\u0915\u0930\u093e\u0913|\u0915\u0930\u0935\u093e(?:\u0913|\u0926\u094b)))/u.test(message);
        }

        function isExplicitCustomerCareCallRequest(value) {
            const message = String(value || '');
            if (isCustomerCareDecline(message)) return false;
            const mentionsCustomerCare = /\b(?:customer\s*care|support|helpline)\b/iu.test(message)
                || /(?:\u0915\u0938\u094d\u091f\u092e\u0930\s*\u0915\u0947\u092f\u0930|\u0917\u094d\u0930\u093e\u0939\u0915\s*\u0938\u0947\u0935\u093e|\u0938\u092a\u094b\u0930\u094d\u091f|\u0939\u0947\u0932\u094d\u092a\u0932\u093e\u0908\u0928)/u.test(message);
            const asksToDial = /\b(?:call|phone|dial|ring|connect|lagao|laga\s+do|milao|mila\s+do|baat\s+(?:karo|karao|karwao|karwa\s+do))\b/iu.test(message)
                || /(?:\u0915\u0949\u0932|\u0921\u093e\u092f\u0932|\u0915\u0928\u0947\u0915\u094d\u091f|\u0932\u0917(?:\u093e\u0913|\u093e\s*\u0926\u094b)|\u092e\u093f\u0932(?:\u093e\u0913|\u093e\s*\u0926\u094b)|\u092c\u093e\u0924\s*(?:\u0915\u0930\u094b|\u0915\u0930\u093e\u0913|\u0915\u0930\u0935\u093e(?:\u0913|\u0926\u094b)))/u.test(message);
            return mentionsCustomerCare && asksToDial;
        }

        function isStandaloneCustomerCareCallRequest(value) {
            const message = String(value || '').trim();
            if (isCustomerCareDecline(message) || isCustomerCareProductCommand(message)) return false;
            // A short imperative such as "call karo" has only one sensible
            // Zonik meaning: connect the customer to support. Keeping this
            // intentionally narrow prevents ordinary questions containing the
            // word "call" from opening the phone app.
            return /^(?:(?:please|pls|mujhe|mujhko|mere\s+liye|ek)\s+)*(?:call|dial|phone)\s*(?:karo|karao|kar\s+do|karwao|karwa\s+do|lagao|laga\s+do|milao|mila\s+do|connect\s+karo)?\s*[.!?]*$/iu.test(message)
                || /^(?:\u092e\u0941\u091d\u0947\s+)?(?:\u0915\u0949\u0932|\u0921\u093e\u092f\u0932|\u092b\u094b\u0928)\s*(?:\u0915\u0930\u094b|\u0915\u0930\u093e\u0913|\u0915\u0930\s*\u0926\u094b|\u0915\u0930\u0935\u093e\u0913|\u0915\u0930\u0935\u093e\s*\u0926\u094b|\u0932\u0917\u093e\u0913|\u0932\u0917\u093e\s*\u0926\u094b|\u092e\u093f\u0932\u093e\u0913|\u092e\u093f\u0932\u093e\s*\u0926\u094b)?\s*[.!?]*$/u.test(message);
        }

        function openCustomerCareDialer(value, source, preferUserActivation) {
            const dialUrl = customerCareDialUrlFrom(value)
                || customerCareDialUrl
                || customerCareDialUrlFrom(customerCareFallbackPhone);
            if (!dialUrl) {
                aiDebug('Customer-care dialer skipped: invalid phone', {source: source});
                return false;
            }

            const now = Date.now();
            // The local fast path and the confirmed server response can both
            // arrive within a moment. One attempt is enough; do not present
            // two dialer prompts for the same consent.
            if (lastCustomerCareDialAttemptAt && now - lastCustomerCareDialAttemptAt < 12000) {
                aiDebug('Customer-care duplicate dialer prevented', {
                    source: source,
                    dialUrl: dialUrl,
                    previousDialUrl: lastCustomerCareDialAttemptUrl
                });
                return false;
            }

            customerCareDialUrl = dialUrl;
            lastCustomerCareDialAttemptAt = now;
            lastCustomerCareDialAttemptUrl = dialUrl;
            aiDebug('Opening customer-care dialer', {source: source, dialUrl: dialUrl, userActivated: Boolean(preferUserActivation)});

            if (preferUserActivation) {
                try {
                    // This runs inside the tap/Enter event and is the most
                    // reliable path on iOS/Android browsers.
                    window.location.assign(dialUrl);
                    return true;
                } catch (error) {
                    aiDebug('Direct customer-care dialer navigation failed', {source: source, error: String(error)});
                }
            }

            // A programmatic anchor is the best available fallback for voice
            // recognition and asynchronous server confirmations.
            const dialLink = document.createElement('a');
            dialLink.href = dialUrl;
            dialLink.style.position = 'fixed';
            dialLink.style.left = '-9999px';
            dialLink.style.width = '1px';
            dialLink.style.height = '1px';
            dialLink.setAttribute('aria-hidden', 'true');
            document.body.appendChild(dialLink);
            try {
                dialLink.click();
                return true;
            } catch (error) {
                aiDebug('Customer-care dialer anchor failed', {source: source, error: String(error)});
                try {
                    window.location.assign(dialUrl);
                    return true;
                } catch (fallbackError) {
                    aiDebug('Customer-care dialer fallback failed', {source: source, error: String(fallbackError)});
                    return false;
                }
            } finally {
                window.setTimeout(function () { dialLink.remove(); }, 0);
            }
        }

        function setMicStatus(label, state) {
            if (!micStatus) return;
            micStatus.textContent = label;
            micStatus.classList.toggle('listening', state === 'listening');
            micStatus.classList.toggle('processing', state === 'processing');
            micBtn?.setAttribute('aria-pressed', state === 'listening' ? 'true' : 'false');
            if (state === 'listening') setAgentUiState('listening');
            else if (state === 'processing') setAgentUiState('understanding');
        }

        const agentUiCopy = {
            idle: ['Start talking!', 'Tap to speak'],
            listening: ['Listening…', 'Speak naturally — I’m listening.'],
            understanding: ['Understanding your order…', 'Checking product, quantity, pack and context.'],
            matching: ['Checking approved products…', 'Matching your request with your approved price list.'],
            clarifying: ['Choose an approved option', 'I found more than one suitable match.'],
            executing: ['Updating your live order…', 'Applying the confirmed change.'],
            speaking: ['Zonik is responding...', 'You can interrupt me at any time.'],
            ready: ['Done', 'Your live order is up to date.'],
            checkout: ['Order ready', 'Confirm delivery and payment to place the order.'],
            error: ['Let’s try that again', 'I could not complete that request.']
        };
        const agentPoseByState = {
            idle: 'ready',
            listening: 'listening',
            understanding: 'searching',
            matching: 'searching',
            clarifying: 'clarifying',
            executing: 'searching',
            speaking: 'listening',
            ready: 'success',
            checkout: 'success',
            error: 'clarifying'
        };
        const agentPoseSources = agentCharacterImage ? {
            ready: agentCharacterImage.dataset.readySrc,
            listening: agentCharacterImage.dataset.listeningSrc,
            searching: agentCharacterImage.dataset.searchingSrc,
            success: agentCharacterImage.dataset.successSrc,
            clarifying: agentCharacterImage.dataset.clarifyingSrc
        } : {};
        Object.values(agentPoseSources).forEach(function (source) {
            if (!source) return;
            const preload = new Image();
            preload.src = source;
        });
        let agentUiResetTimer = null;
        function setAgentUiState(state, detail) {
            const normalized = Object.prototype.hasOwnProperty.call(agentUiCopy, state) ? state : 'idle';
            const copy = agentUiCopy[normalized];
            if (agentUiResetTimer) window.clearTimeout(agentUiResetTimer);
            aiCard?.setAttribute('data-agent-state', normalized);
            const poseSource = agentPoseSources[agentPoseByState[normalized]];
            if (agentCharacterImage && poseSource && agentCharacterImage.src !== poseSource) {
                agentCharacterImage.src = poseSource;
            }
            if (agentStateLabel) agentStateLabel.textContent = copy[0];
            if (agentStateDetail) agentStateDetail.textContent = detail || copy[1];
            if (normalized === 'ready') {
                agentUiResetTimer = window.setTimeout(function () { setAgentUiState('idle'); }, 2600);
            }
        }
        function setAgentUiFromWorkflow(workflow, autoAdded) {
            const stage = String(workflow?.stage || '');
            if (stage === 'clarify_product' || ['confirm_product', 'await_quantity', 'confirm_quantity'].includes(stage)) return setAgentUiState('clarifying');
            if (['delivery_details', 'payment_method', 'checkout_ready', 'confirm_order'].includes(stage)) return setAgentUiState('checkout');
            if (autoAdded || ['added', 'cart_updated', 'cart_removed', 'anything_else'].includes(stage)) return setAgentUiState('ready');
            if (stage === 'customer_care_offer') return setAgentUiState('clarifying', 'Would you like me to send this enquiry to customer care?');
            setAgentUiState('matching');
        }

        function cancelSpeechRecognitionRestart() {
            if (speechRecognitionRestartTimer) window.clearTimeout(speechRecognitionRestartTimer);
            speechRecognitionRestartTimer = null;
            speechRecognitionStartPending = false;
        }

        function scheduleSpeechRecognitionRestart(delay) {
            if (accurateVoiceMode || !continuousTalkMode || speechRecognition || speechRecognitionStartPending) return;
            speechRecognitionStartPending = true;
            setMicStatus('Listening…', 'listening');
            micBtn?.classList.add('listening');
            speechRecognitionRestartTimer = window.setTimeout(function () {
                speechRecognitionRestartTimer = null;
                speechRecognitionStartPending = false;
                if (continuousTalkMode && !speechRecognition && !activeAssistantAudio
                    && !assistantAudioQueue.length && !window.speechSynthesis?.speaking) {
                    startBrowserSpeechRecognition(true);
                }
            }, Math.max(150, Number(delay) || 250));
        }

        function startAutoListening() {
            setMicStatus('Starting…', 'processing');
            window.setTimeout(function () {
                if (speechRecognition) return;
                if (activeAssistantAudio || window.speechSynthesis?.speaking) {
                    window.setTimeout(startAutoListening, 500);
                    return;
                }
                if (!startBrowserSpeechRecognition()) setMicStatus('Tap mic', 'idle');
            // Wait until the post-speech echo guard has cleared.
            }, 1200);
        }

        function finishWelcomeAndListen() {
            if (welcomeGreetingFinished) return;
            welcomeGreetingFinished = true;
            startAutoListening();
        }
        const assistantLanguageLocales = {
            english: 'en-IN', 'indian english': 'en-IN', hindi: 'hi-IN', hinglish: 'en-IN', marathi: 'mr-IN',
            bengali: 'bn-IN', bangla: 'bn-IN', tamil: 'ta-IN', telugu: 'te-IN',
            gujarati: 'gu-IN', punjabi: 'pa-IN', kannada: 'kn-IN', malayalam: 'ml-IN',
            odia: 'or-IN', urdu: 'ur-IN', arabic: 'ar-SA', spanish: 'es-ES',
            french: 'fr-FR', german: 'de-DE', portuguese: 'pt-BR', italian: 'it-IT',
            russian: 'ru-RU', japanese: 'ja-JP', korean: 'ko-KR', chinese: 'zh-CN',
            mandarin: 'zh-CN', indonesian: 'id-ID', turkish: 'tr-TR', thai: 'th-TH',
            vietnamese: 'vi-VN', nepali: 'ne-NP', persian: 'fa-IR', farsi: 'fa-IR',
            hebrew: 'he-IL', sinhala: 'si-LK', swahili: 'sw-KE', khmer: 'km-KH',
            lao: 'lo-LA', burmese: 'my-MM', myanmar: 'my-MM', armenian: 'hy-AM',
            georgian: 'ka-GE'
        };

        function localeForAssistantLanguage(language) {
            const value = String(language || '').trim();
            if (/^[a-z]{2,3}(?:-[a-z0-9]{2,8})+$/i.test(value)) {
                const parts = value.split('-');
                return parts.map(function (part, index) {
                    return index === 0 ? part.toLowerCase() : (part.length === 2 ? part.toUpperCase() : part);
                }).join('-');
            }
            const normalized = value.toLowerCase();
            const match = Object.keys(assistantLanguageLocales).find(function (name) { return normalized.includes(name); });
            return match ? assistantLanguageLocales[match] : null;
        }

        function inferredAssistantLanguage(value) {
            const text = String(value || '');
            // Marathi uses Devanagari too, so it must be identified before
            // the generic Hindi-script rule below. This also keeps browser
            // voice recognition on mr-IN for longer Marathi requests.
            if (/(?:मला|माझ|तुम्ह|आम्ह|पाहिजे|द्या|आहे|आहेत|नको|किती|काय|कसा|कशी|कोणत|हवे|हवं|कार्टमध्ये|आणखी|झाले|झालं|एवढेच|इतकेच|निश्चित करा)/u.test(text)) return 'marathi';
            if (/[஀-௿]/.test(text)) return 'tamil';
            if (/[ঀ-৿]/.test(text)) return 'bengali';
            if (/[઀-૿]/.test(text)) return 'gujarati';
            if (/[਀-੿]/.test(text)) return 'punjabi';
            if (/[ఀ-౿]/.test(text)) return 'telugu';
            if (/[ಀ-೿]/.test(text)) return 'kannada';
            if (/[ഀ-ൿ]/.test(text)) return 'malayalam';
            if (/[଀-୿]/.test(text)) return 'odia';
            if (/[؀-ۿ]/.test(text)) return 'arabic';
            if (/[぀-ヿ]/.test(text)) return 'japanese';
            if (/[一-鿿]/.test(text)) return 'chinese';
            if (/[가-힯]/.test(text)) return 'korean';
            if (/[Ѐ-ӿ]/.test(text)) return 'russian';
            if (/[฀-๿]/.test(text)) return 'thai';
            if (/[ऀ-ॿ]/.test(text)) return 'hindi';
            if (/[\u0590-\u05FF]/.test(text)) return 'hebrew';
            if (/[\u0D80-\u0DFF]/.test(text)) return 'sinhala';
            if (/[\u1780-\u17FF]/.test(text)) return 'khmer';
            if (/[\u0E80-\u0EFF]/.test(text)) return 'lao';
            if (/[\u1000-\u109F]/.test(text)) return 'burmese';
            if (/[\u0530-\u058F]/.test(text)) return 'armenian';
            if (/[\u10A0-\u10FF]/.test(text)) return 'georgian';
            const marathiWords = text.match(/\b(?:mala|pahije|dya|nako|kiti|aahe|ahe|ahet|hava|havi|majha|maza|majhi|tumcha|tumhi|madhe|madhye|kay|kaay|konata|konate|dakhva|dakhawa|milta|milte|pahila|udya|aamhi|aamcha|kasa|kashi|baram|bara|nakki|evdhech|itkech|zale|zhalay)\b/gi) || [];
            if (new Set(marathiWords.map(function (word) { return word.toLowerCase(); })).size >= 2) return 'marathi';
            if (/\b(?:mai|main|aaj|mujhe|muje|usme|isme|kya|hoga|banaunga|banaungi|chahiye|haan|nahi|purana|pichla|naya|karo|batao)\b/i.test(text)) return 'hinglish';
            if (/\b(?:the|please|need|want|show|hello|thanks|order|product)\b/i.test(text)) return 'english';
            return '';
        }

        function applyDetectedLanguage(language, sampleText) {
            const explicit = String(language || '').trim();
            const inferred = inferredAssistantLanguage(sampleText);
            const locale = localeForAssistantLanguage(explicit) || localeForAssistantLanguage(inferred);
            if (locale) conversationLanguage = locale;
            if (explicit) conversationReplyLanguage = explicit.toLowerCase();
            else if (inferred) conversationReplyLanguage = inferred;
            return locale;
        }
        function typeAssistantText(node, text) {
            if (!node) return;
            const value = String(text || '');
            node.textContent = '';
            let index = 0;
            const interval = window.setInterval(function () {
                index = Math.min(value.length, index + 1);
                node.textContent = value.slice(0, index);
                chat.parentElement.scrollTop = chat.parentElement.scrollHeight;
                if (index >= value.length) window.clearInterval(interval);
            }, 45);
        }
        let conversationId = window.crypto?.randomUUID ? window.crypto.randomUUID() : ('chat-' + Date.now() + '-' + Math.random().toString(36).slice(2));

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function (character) {
                return {'&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'}[character];
            });
        }

        // Voice recognition produces many variants: "main new order karunga",
        // "naya order karu ga", and even reversed wording such as "order new
        // karna hai". Keep this local so the customer is never dependent on a
        // remote intent request merely to start a fresh order.
        function isNewOrderIntent(value) {
            const message = String(value || '');
            const latinPhrase = /\b(?:(?:new|fresh|naya|nayaa|nayi)(?:\s+(?:wala|ka))?(?:\s+order)?|order\s+(?:new|fresh|naya|nayaa|nayi)|(?:mai|main|me|hum|ham)\s+(?:new|fresh|naya|nayaa|nayi))\b/i;
            // Browser speech recognition uses hi-IN here, so it can return
            // Devanagari even when the customer speaks Hinglish. `\b` only
            // understands Latin word characters, hence the separate pattern.
            const devanagariPhrase = /(?:\u0928\u092f\u093e|\u0928\u0908|\u0928\u092f\u0940|\u0928\u094d\u092f\u0942|\u092b\u094d\u0930\u0947\u0936)(?:\s+(?:\u0935\u093e\u0932\u093e|\u0915\u093e))?(?:\s+(?:\u0911\u0930\u094d\u0921\u0930|\u0906\u0930\u094d\u0921\u0930))?|(?:\u092e\u0948\u0902|\u092e\u0948|\u092e\u0941\u091d\u0947|\u0939\u092e)\s+(?:\u0928\u092f\u093e|\u0928\u0908|\u0928\u092f\u0940|\u0928\u094d\u092f\u0942|\u092b\u094d\u0930\u0947\u0936)/u;
            return latinPhrase.test(message) || devanagariPhrase.test(message);
        }

        function renderOnboardingHandledReply(data) {
            const response = data || {};
            const workflow = response.workflow || {};
            const workflowStage = String(workflow.stage || response.workflow_stage || '');
            const reply = String(response.reply || response.message || workflow.reply || 'Main Zonik se related ismein madad kar sakta hoon.');
            const supportedStages = ['confirm_product', 'await_quantity', 'confirm_quantity', 'anything_else', 'clarify_product', 'await_remove_quantity', 'confirm_order', 'order_suggestions', 'delivery_details', 'payment_method', 'checkout_ready', 'customer_care_offer'];

            onboardingStage = null;
            activeOrderingStage = supportedStages.includes(workflowStage) ? workflowStage : null;
            appendMessage('assistant', escapeHtml(reply));

            if (workflowStage === 'customer_care_offer') {
                const dialUrl = rememberCustomerCareDialer(workflow);
                const dialUrlAttribute = dialUrl ? ' data-customer-care-dial-url="' + escapeHtml(dialUrl) + '"' : '';
                appendMessage('assistant', '<div class="ai-product-actions"><button type="button" class="ai-product-btn primary" data-customer-care-call="yes"' + dialUrlAttribute + '>Call Customer Care</button><button type="button" class="ai-product-btn" data-customer-care-call="no">Continue Here</button></div>');
            } else if (workflowStage === 'call_customer_care') {
                const dialUrl = rememberCustomerCareDialer(workflow);
                if (dialUrl) {
                    appendMessage('assistant', '<div class="ai-product-actions"><a class="ai-product-btn primary" href="' + escapeHtml(dialUrl) + '">Opening Customer Care call…</a></div>');
                    openCustomerCareDialer(dialUrl, 'onboarding-customer-care-response', false);
                }
                activeOrderingStage = null;
            }

            if (response.voice_base64) playVoice(response.voice_base64, response.voice_mime, scheduleResponseReminder);
            else loadVoiceAsync(reply, scheduleResponseReminder);
        }

        function forwardOnboardingMessageToChat(message, selectedProductId, options) {
            // The user message is already visible. Re-use the normal chat
            // pipeline so product commands, support requests, and Zonik FAQs
            // get their actual answer without duplicating the message bubble.
            onboardingStage = null;
            awaitingNewOrderReady = false;
            onboardingIntentRequestVersion++;
            const forwardOptions = Object.assign({}, options || {}, {
                skipOrderChoice: true,
                alreadyRenderedUserMessage: true
            });
            sendMessage(message, selectedProductId, forwardOptions);
        }

        function beginNewOrder() {
            // Ignore an older onboarding response that may arrive after the
            // customer has clearly selected a fresh order.
            onboardingIntentRequestVersion++;
            onboardingStage = null;
            awaitingNewOrderReady = false;
            previousOrdersVisible = false;
            // Only clear the idle state. We never discard the cart or an
            // in-progress checkout merely because the customer said "new".
            if (!activeOrderingStage || activeOrderingStage === 'anything_else') activeOrderingStage = null;
            const reply = 'Theek hai, product ka naam aur quantity saath mein bataiye.';
            appendMessage('assistant', escapeHtml(reply));
            loadVoiceAsync(reply);
            input.focus();
        }

        function normalizeCatalogLanguage(text) {
            const aliases = {'रियल':'real','जूस':'juice','रस':'juice','टमाटर':'tomato','टोमॅटो':'tomato','प्याज':'onion','कांदा':'onion','आलू':'potato','बटाटा':'potato','चावल':'rice','तांदूळ':'rice','दूध':'milk','तेल':'oil','चीनी':'sugar','साखर':'sugar','आटा':'flour','पीठ':'flour','नमक':'salt','मीठ':'salt','बॉक्स':'box','कार्टन':'carton','पॅकेट':'packet','पैकेट':'packet','एक':'1','दो':'2','तीन':'3','डालें':'add','डालो':'add','दें':'add','द्या':'add','टाका':'add','मुझे':'','चाहिए':''};
            return Object.keys(aliases).reduce(function (result, word) { return result.split(word).join(' ' + aliases[word] + ' '); }, text);
        }

        function getOrderDetails(text) {
            const normalizedText = normalizeCatalogLanguage(text);
            const quantity = normalizedText.match(/\d+(?:\.\d+)?/);
            const unit = normalizedText.match(/kg|kgs|kilo|kilogram|gram|litre|liter|ltr|carton|box|packet|pack|pcs?|pieces?|dozen/i);
            const name = normalizedText.toLowerCase()
                .replace(/\d+(?:\.\d+)?/g, ' ')
                .replace(/\b(add|also|please|mujhe|chahiye|do|de|dena|karo|cart|order|kg|kgs|kilo|kilogram|gram|litre|liter|ltr|carton|box|packet|pack|pcs?|pieces?|dozen)\b/gi, ' ')
                .replace(/[^\p{L}\p{N}\s]/gu, ' ')
                .replace(/\s+/g, ' ').trim();
            return { name: name, quantity: Math.max(1, Math.round(Number(quantity?.[0] || 1))), unit: unit?.[0] || 'unit' };
        }

        function normalizeSpokenQuantity(text) {
            let result = String(text || '')
                .replace(/(?:सोनिक|ज़ोनिक|झोनिक)/g, 'zonik')
                .replace(/\b(?:sonic|zonic|jonik|zone\s*ik|zo\s*nik)\b/gi, 'zonik')
                .replace(/\b(?:fire|file|fife)\s*box(?:es)?\b/gi, '5 box')
                .replace(/\b(?:fire|file|fife)\s*(packet|pack|carton|piece|pieces|pcs)\b/gi, '5 $1')
                .replace(/\b(?:search|surge|church|turn|term|then|den|tan|tin)\s*box(?:es)?\b/gi, '10 box')
                .replace(/\b(?:turn|term|then|den|tan|tin)\s*(packet|pack|carton|piece|pieces|pcs)\b/gi, '10 $1');
            const numbers = {zero:0, one:1, won:1, ek:1, two:2, to:2, too:2, do:2, three:3, tree:3, teen:3, four:4, for:4, char:4, chaar:4, five:5, fife:5, panch:5, paanch:5, six:6, che:6, chhe:6, seven:7, saat:7, eight:8, aath:8, nine:9, nau:9, ten:10, das:10};
            const units = '(?=\\s*(?:boxes?|packet|pack|carton|kg|kgs|kilo|gram|litre|liter|ltr|pcs?|pieces?|dozen|unit)\\b)';
            Object.keys(numbers).forEach(function (word) {
                result = result.replace(new RegExp('\\b' + word + '\\b' + units, 'gi'), String(numbers[word]));
                result = result.replace(new RegExp('^(\\s*(?:add|give|order|please\\s+add)?\\s*)' + word + '\\b', 'i'), '$1' + numbers[word]);
                result = result.replace(new RegExp('\\b(mujhe|muje|mala|add|give|order)\\s+' + word + '\\b', 'gi'), '$1 ' + numbers[word]);
                if (new RegExp('^\\s*' + word + '\\s*$', 'i').test(result)) result = String(numbers[word]);
            });
            return result.replace(/\s+/g, ' ').trim();
        }

        function productCard(product, quantity, unit, label, requiresQuantity, workflowStage) {
            const image = product.image ? '<img class="ai-product-image" src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.name) + '">' : '';
            const brand = product.brand ? '<div class="ai-product-meta">Brand: ' + escapeHtml(product.brand) + '</div>' : '';
            const available = product.available_in_outlet !== false;
            const price = '<div class="ai-product-price">₹' + Number(product.price || 0).toFixed(2) + ' / ' + escapeHtml(product.unit || 'unit') + '</div>' + (!available ? '<div class="ai-product-meta"><strong>Catalogue product</strong></div>' : '');
            const safeQty = Math.max(1, Math.round(Number(quantity) || 1));
            const action = !available
                ? (product.enquiry_sent
                    ? '<button type="button" class="ai-product-btn primary" disabled>Enquiry Sent ✓</button>'
                    : '<button type="button" class="ai-product-btn primary" data-catalogue-enquiry="' + product.id + '">Send Price Enquiry</button>')
                : requiresQuantity
                ? '<button type="button" class="ai-product-btn primary" data-choose-product="' + escapeHtml(product.name) + '" data-choose-product-id="' + product.id + '">Select</button>'
                : '<span class="ai-qty-control"><button type="button" class="ai-qty-btn" data-qty-change="-1">−</button><span class="ai-qty-value">' + safeQty + '</span><button type="button" class="ai-qty-btn" data-qty-change="1">+</button></span><button type="button" class="ai-product-btn primary" data-add-product="' + product.id + '" data-qty="' + safeQty + '" data-price="' + product.price + '" data-workflow-stage="' + escapeHtml(workflowStage || '') + '">' + escapeHtml(label || 'Add') + '</button>';
            return '<div class="ai-product-card">' + image + '<div class="ai-product-details"><div class="ai-product-title">' + escapeHtml(product.name) + '</div>' + brand + '<div class="ai-product-meta">Carton size: ' + escapeHtml(product.carton_size || 'N/A') + '</div>' + price + '</div></div><div class="ai-product-actions">' + action + '<button type="button" class="ai-product-btn" data-action="change-item">Change item</button></div>';
        }

        function suggestionCard(product) {
            const image = product.image
                ? '<img class="ai-suggestion-image" src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.name) + '">'
                : '<div class="ai-suggestion-image"></div>';
            return '<button type="button" class="ai-suggestion-card" data-add-product="' + escapeHtml(product.id) + '" data-qty="1" data-price="' + escapeHtml(product.price || 0) + '" data-workflow-stage="order_suggestions" data-suggestion-source="order" aria-label="Add ' + escapeHtml(product.name) + ' to order">' + image + '<span class="ai-suggestion-name">' + escapeHtml(product.name) + '</span><span class="ai-suggestion-add">+ Add</span></button>';
        }

        function historyProductCard(product) {
            const image = product.image ? '<img class="ai-product-image" src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.name) + '">' : '';
            const selected = product.selected ? '<span class="ai-history-badge">✓ Selected × ' + escapeHtml(product.selected_quantity || 1) + '</span>' : '';
            const price = product.available_in_outlet === false ? '<div class="ai-product-meta"><strong>Not available in selected outlet</strong></div>' : '<div class="ai-product-price">₹' + Number(product.price || 0).toFixed(2) + ' / ' + escapeHtml(product.unit || 'unit') + '</div>';
            return '<div class="ai-product-card">' + image + '<div class="ai-product-details"><div class="ai-product-title">' + escapeHtml(product.name) + '</div><div class="ai-product-meta">Carton size: ' + escapeHtml(product.carton_size || 'N/A') + '</div>' + price + selected + '</div></div>';
        }

        function savedMessageHtml(message) {
            if (message.message === 'Live Order List') {
                let list = '<strong>Live Order List</strong><div class="ai-cart-summary">';
                (message.products || []).forEach(function (product) {
                    const image = product.image ? '<img class="ai-live-order-image" src="' + escapeHtml(product.image) + '" alt="' + escapeHtml(product.name) + '">' : '<div class="ai-live-order-image"></div>';
                    list += '<div class="ai-live-order-item">' + image + '<div><div class="ai-live-order-name">' + escapeHtml(product.name) + '</div><div class="ai-live-order-meta">Quantity: ' + escapeHtml(product.selected_quantity || 1) + ' ' + escapeHtml(product.unit || 'unit') + '</div></div><div class="ai-live-order-price">' + money(product.line_total || 0) + '</div></div>';
                });
                return list + '</div>';
            }
            let html = '';
            (message.products || []).forEach(function (product) {
                html += historyProductCard(product);
            });
            return html;
        }

        function openHistoryList() {
            historyDetailOpen = false;
            openedHistoryConversation = null;
            historyContinue.style.display = 'none';
            openAccessiblePanel(historyPanel);
            historyTitle.textContent = 'Chat history';
            historySubtitle.textContent = 'Your saved conversations';
            historyContent.innerHTML = '<div class="ai-history-empty">Loading chats…</div>';
            fetch(historyUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json()).then(function (data) {
                    const conversations = data.conversations || [];
                    if (!conversations.length) { historyContent.innerHTML = '<div class="ai-history-empty">No saved chat history yet.</div>'; return; }
                    historyContent.innerHTML = conversations.map(function (item) {
                        return '<div class="ai-history-row"><button type="button" class="ai-history-item" data-history-conversation="' + escapeHtml(item.id) + '"><span class="ai-history-item-title">' + escapeHtml(item.title) + '</span><span class="ai-history-item-meta"><span>👤 ' + escapeHtml(item.customer_name) + '</span>' + (item.outlet_name ? '<span>🏪 ' + escapeHtml(item.outlet_name) + '</span>' : '') + '<span>📅 ' + escapeHtml(item.date) + '</span><span>🕒 ' + escapeHtml(item.time) + '</span></span></button><button type="button" class="ai-history-delete" data-delete-conversation="' + escapeHtml(item.id) + '" aria-label="Delete conversation" title="Delete chat"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/></svg></button></div>';
                    }).join('');
                }).catch(function () { historyContent.innerHTML = '<div class="ai-history-empty">Could not load chat history.</div>'; });
        }

        function openHistoryDetail(id) {
            historyDetailOpen = true;
            openedHistoryConversation = id;
            historyContinue.style.display = 'inline-flex';
            historyTitle.textContent = 'Conversation';
            historySubtitle.textContent = 'Messages and selected products';
            historyContent.innerHTML = '<div class="ai-history-empty">Loading conversation…</div>';
            fetch(historyUrl + '?conversation_id=' + encodeURIComponent(id), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json()).then(function (data) {
                    const messages = data.messages || [];
                    if (!messages.length) { historyContent.innerHTML = '<div class="ai-history-empty">No messages found.</div>'; return; }
                    historyContent.innerHTML = '<div class="ai-history-detail">' + messages.map(function (message) {
                        const content = savedMessageHtml(message);
                        if (!content.trim()) return '';
                        const bubble = '<div class="ai-message ' + escapeHtml(message.role) + '">' + content + '<span class="ai-message-time">' + escapeHtml(message.time) + '</span></div>';
                        return '<div class="ai-message-row ' + escapeHtml(message.role) + '">' + bubble + '</div>';
                    }).join('') + '</div>';
                }).catch(function () { historyContent.innerHTML = '<div class="ai-history-empty">Could not load this conversation.</div>'; });
        }

        historyButton?.addEventListener('click', openHistoryList);
        historyBack?.addEventListener('click', function () {
            if (historyDetailOpen) openHistoryList();
            else closeAccessiblePanel(historyPanel, historyButton);
        });
        historyContent?.addEventListener('click', function (event) {
            const deleteButton = event.target.closest('[data-delete-conversation]');
            if (deleteButton) {
                pendingDeleteConversation = deleteButton.dataset.deleteConversation;
                openAccessiblePanel(deleteConfirm);
                return;
            }
            const item = event.target.closest('[data-history-conversation]');
            if (item) openHistoryDetail(item.dataset.historyConversation);
        });
        function closeDeleteConfirm() {
            pendingDeleteConversation = null;
            deleteConfirmButton.disabled = false;
            deleteConfirmButton.textContent = 'Confirm Delete';
            closeAccessiblePanel(deleteConfirm, historyButton);
        }
        deleteCancel?.addEventListener('click', closeDeleteConfirm);
        deleteConfirm?.addEventListener('click', function (event) { if (event.target === deleteConfirm) closeDeleteConfirm(); });
        deleteConfirmButton?.addEventListener('click', function () {
            if (!pendingDeleteConversation) return;
            const id = pendingDeleteConversation;
            deleteConfirmButton.disabled = true;
            deleteConfirmButton.textContent = 'Deleting…';
            fetch(historyUrl + '/' + encodeURIComponent(id), {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}})
                .then(function (response) { if (!response.ok) throw new Error('Delete failed'); return response.json(); })
                .then(function () { closeDeleteConfirm(); openHistoryList(); })
                .catch(function () { deleteConfirmButton.textContent = 'Try Again'; })
                .finally(function () { deleteConfirmButton.disabled = false; if (deleteConfirmButton.textContent !== 'Try Again') deleteConfirmButton.textContent = 'Confirm Delete'; });
        });
        historyContinue?.addEventListener('click', function () {
            if (!openedHistoryConversation) return;
            conversationId = openedHistoryConversation;
            fetch(historyUrl + '?conversation_id=' + encodeURIComponent(conversationId), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json()).then(function (data) {
                    chat.innerHTML = '';
                    (data.messages || []).forEach(function (message) {
                        let html = escapeHtml(message.message).replace(/\n/g, '<br>');
                        (message.products || []).forEach(function (product) { html += historyProductCard(product); });
                        appendMessage(message.role, html, message.time);
                    });
                    closeAccessiblePanel(historyPanel, input);
                    input.focus();
                });
        });

        function appendMessage(role, html, time) {
            const wrap = document.createElement('div');
            const timestamp = time || new Date().toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
            wrap.className = 'ai-message-row ' + role;
            const bubble = '<div class="ai-message ' + role + '">' + html + '<span class="ai-message-time">' + escapeHtml(timestamp) + '</span></div>';
            wrap.innerHTML = bubble;

            // Voice-only chat: keep plain conversational text off-screen and
            // render only actionable product/order UI.
            const hasVisualContent = role === 'assistant'
                && /<(?:div|button|table|img|strong|ul|ol)\b/i.test(String(html || ''));
            if (!hasVisualContent) return wrap;

            chat.appendChild(wrap);
            chat.parentElement.scrollTop = chat.parentElement.scrollHeight;
            return wrap;
        }

        // The hero state already communicates Understanding / Checking. This
        // detached sentinel keeps the existing async cleanup API intact.
function appendTyping() {
    const wrap = document.createElement('div');
    wrap.className = 'ai-message assistant';
    return wrap;
}

        function removeTyping(node) {
            if (node && node.parentNode) node.parentNode.removeChild(node);
        }

        let queuedWelcomeAudio = null;
        let activeAssistantAudio = null;
        let assistantAudioQueue = [];
        let speechRequestGeneration = 0;
        let assistantSpeechEndedAt = 0;
        let lastAssistantSpokenText = '';
        // Once ElevenLabs fails, use one stable browser voice for the rest of
        // a short recovery window instead of alternating every reply.
        let voiceProviderMode = 'auto';
        let elevenLabsRetryAt = 0;
        function useBrowserVoiceTemporarily() {
            voiceProviderMode = 'browser';
            elevenLabsRetryAt = Date.now() + 15000;
        }
        function resumeListeningAfterReply() {
            if (!continuousTalkMode || speechRecognition) return;
            if (accurateVoiceMode) {
                window.setTimeout(startAccurateVoiceCapture, 700);
                return;
            }
            scheduleSpeechRecognitionRestart(1100);
        }

        function playNextAssistantAudio() {
            if (activeAssistantAudio || !assistantAudioQueue.length) return;
            const item = assistantAudioQueue.shift();
            const audio = new Audio('data:' + (item.mime || 'audio/mpeg') + ';base64,' + item.base64);
            // ElevenLabs already controls pacing. Do not slow the generated
            // clip a second time in the browser.
            audio.playbackRate = 1.0;
            audio.preservesPitch = true;
            activeAssistantAudio = audio;
            const finish = function () {
                if (activeAssistantAudio === audio) activeAssistantAudio = null;
                assistantSpeechEndedAt = Date.now();
                if (typeof item.onEnded === 'function') item.onEnded();
                playNextAssistantAudio();
                // Restart the microphone only after every queued assistant clip
                // has finished, otherwise it can transcribe the next clip.
                if (!activeAssistantAudio && !assistantAudioQueue.length) {
                    setAgentUiState('idle');
                    resumeListeningAfterReply();
                }
            };
            audio.addEventListener('ended', finish, {once: true});
            audio.addEventListener('error', finish, {once: true});
            audio.play().then(function () {
                setAgentUiState('speaking');
                if (typeof item.onStart === 'function') item.onStart();
            }).catch(function () {
                // Keep only the first autoplay-blocked welcome clip. Normal
                // replies are always serialized and never overlap.
                if (typeof item.onStart === 'function') item.onStart();
                if (!queuedWelcomeAudio) queuedWelcomeAudio = audio;
                activeAssistantAudio = null;
                if (typeof item.onEnded === 'function') item.onEnded();
                else resumeListeningAfterReply();
            });
        }

        function playVoice(base64, mime, onEnded, onStart) {
            if (!base64) return;
            assistantAudioQueue.push({base64: base64, mime: mime, onEnded: onEnded, onStart: onStart});
            playNextAssistantAudio();
        }

        function speakWithBrowser(text, onEnded, onStart) {
            if (!window.speechSynthesis || !text) {
                if (typeof onEnded === 'function') onEnded();
                return;
            }
            window.speechSynthesis.cancel();
            const cleanText = String(text).replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
            const utterance = new SpeechSynthesisUtterance(cleanText);
            // A server/transcriber detected locale is more accurate than a
            // broad Unicode-script guess for mixed text or Kanji-only text.
            const knownLocale = localeForAssistantLanguage(conversationReplyLanguage);
            if (knownLocale && conversationReplyLanguage !== 'hinglish') {
                utterance.lang = knownLocale;
            } else {
            if (/(झाले|आणखी|किती|हवे|कोणता|पर्याय|मध्ये)/u.test(cleanText)) utterance.lang = 'mr-IN';
            else if (/[\u0900-\u097F]/.test(cleanText)) utterance.lang = 'hi-IN';
            else if (/[\u0B80-\u0BFF]/.test(cleanText)) utterance.lang = 'ta-IN';
            else if (/[\u0980-\u09FF]/.test(cleanText)) utterance.lang = 'bn-IN';
            else if (/[\u0A80-\u0AFF]/.test(cleanText)) utterance.lang = 'gu-IN';
            else if (/[\u0A00-\u0A7F]/.test(cleanText)) utterance.lang = 'pa-IN';
            else if (/[\u0C00-\u0C7F]/.test(cleanText)) utterance.lang = 'te-IN';
            else if (/[\u0C80-\u0CFF]/.test(cleanText)) utterance.lang = 'kn-IN';
            else if (/[\u0D00-\u0D7F]/.test(cleanText)) utterance.lang = 'ml-IN';
            else if (/[\u0B00-\u0B7F]/.test(cleanText)) utterance.lang = 'or-IN';
            else if (/[\u0600-\u06FF]/.test(cleanText)) utterance.lang = conversationLanguage.startsWith('ur') ? 'ur-IN' : 'ar-SA';
            else if (/[\u4E00-\u9FFF]/.test(cleanText)) utterance.lang = 'zh-CN';
            else if (/[\u3040-\u30FF]/.test(cleanText)) utterance.lang = 'ja-JP';
            else if (/[\uAC00-\uD7AF]/.test(cleanText)) utterance.lang = 'ko-KR';
            else if (/[\u0400-\u04FF]/.test(cleanText)) utterance.lang = 'ru-RU';
            else if (/[\u0E00-\u0E7F]/.test(cleanText)) utterance.lang = 'th-TH';
            else utterance.lang = conversationLanguage || navigator.language || 'en-IN';
            }
            // Prefer a natural male system voice in the detected language.
            // Voice inventories differ by Android/iOS/desktop, so first use
            // known male names and then fall back to the best locale match.
            const voices = window.speechSynthesis.getVoices() || [];
            const requestedLanguage = String(utterance.lang || 'en-IN').toLowerCase();
            const maleName = /\b(?:ravi|madhur|hemant|rishi|david|daniel|mark|alex|aaron|guy|male)\b/i;
            utterance.voice = voices.find(function (voice) {
                return maleName.test(voice.name) && String(voice.lang || '').toLowerCase() === requestedLanguage;
            }) || voices.find(function (voice) {
                return maleName.test(voice.name) && String(voice.lang || '').toLowerCase().startsWith(requestedLanguage.split('-')[0]);
            }) || voices.find(function (voice) {
                return maleName.test(voice.name);
            }) || voices.find(function (voice) {
                return String(voice.lang || '').toLowerCase() === requestedLanguage;
            }) || null;
            utterance.rate = 0.92;
            utterance.pitch = 0.84;
            utterance.volume = 0.96;
            const finishBrowserSpeech = function () {
                assistantSpeechEndedAt = Date.now();
                setAgentUiState('idle');
                resumeListeningAfterReply();
                if (typeof onEnded === 'function') onEnded();
            };
            utterance.onend = finishBrowserSpeech;
            utterance.onerror = finishBrowserSpeech;
            utterance.onstart = function () {
                setAgentUiState('speaking');
                if (typeof onStart === 'function') onStart();
            };
            window.speechSynthesis.speak(utterance);
        }

        function speechFriendlyText(text) {
            const units = {
                l: ['litre', 'litres'], lt: ['litre', 'litres'], ltr: ['litre', 'litres'], ltrs: ['litre', 'litres'], lit: ['litre', 'litres'],
                liter: ['litre', 'litres'], litre: ['litre', 'litres'], ml: ['millilitre', 'millilitres'],
                kg: ['kilogram', 'kilograms'], kgs: ['kilogram', 'kilograms'], g: ['gram', 'grams'],
                gm: ['gram', 'grams'], gms: ['gram', 'grams'], pc: ['piece', 'pieces'], pcs: ['piece', 'pieces'],
                pkt: ['packet', 'packets'], pkts: ['packet', 'packets'], ctn: ['carton', 'cartons'], ctns: ['carton', 'cartons'],
                doz: ['dozen', 'dozen']
            };
            return String(text || '')
                .replace(/<[^>]*>/g, ' ')
                .replace(/\b(\d+(?:\.\d+)?)\s*(l(?:t(?:rs?)?)?|lit(?:er|re)?s?|ml|kgs?|gms?|g|pcs?|pkts?|ctns?|doz)\b/giu, function (_, quantity, rawUnit) {
                    let unit = String(rawUnit).toLowerCase().replace(/s$/, '');
                    if (unit === 'liter') unit = 'litre';
                    const forms = units[unit] || [rawUnit, rawUnit];
                    return quantity + ' ' + forms[Math.abs(Number(quantity) - 1) < .00001 ? 0 : 1];
                })
                .replace(/₹\s*([\d,]+(?:\.\d+)?)/gu, '$1 rupees')
                .replace(/\b([\d.]+)\s*%/gu, '$1 percent')
                .replace(/\s*[×x]\s*/gu, ' times ')
                .replace(/&/g, ' and ')
                .replace(/\bZonik\b/giu, 'Zo-nik')
                .replace(/\bAI\b/gu, 'A I')
                .replace(/\bMRP\b/gu, 'M R P')
                .replace(/\bGST\b/gu, 'G S T')
                .replace(/\bUPI\b/gu, 'U P I')
                .replace(/\bCOD\b/gu, 'C O D')
                .replace(/\bSKU\b/gu, 'S K U')
                .replace(/\bN\/?A\b/gu, 'not available')
                .replace(/([.!?])(?=[^\s])/g, '$1 ')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function loadVoiceAsync(text, onEnded, onStart) {
            lastAssistantSpokenText = speechFriendlyText(text);
            if (voiceProviderMode === 'browser' && Date.now() < elevenLabsRetryAt) {
                speakWithBrowser(lastAssistantSpokenText || text, onEnded, onStart);
                return;
            }
            if (voiceProviderMode === 'browser') voiceProviderMode = 'auto';
            const requestGeneration = speechRequestGeneration;
            const controller = window.AbortController ? new AbortController() : null;
            let completed = false;
            const requestTimeout = window.setTimeout(function () {
                if (completed || requestGeneration !== speechRequestGeneration) return;
                completed = true;
                if (controller) controller.abort();
                useBrowserVoiceTemporarily();
                console.info('ElevenLabs audio timed out; using browser voice temporarily.');
                speakWithBrowser(lastAssistantSpokenText || text, onEnded, onStart);
            }, 18000);
            fetch(speakUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                // The backend rewrites speech to the customer's latest
                // detected language/script before ElevenLabs synthesis.
                body: JSON.stringify({
                    text: text,
                    match_language_to: lastUserMessage || conversationReplyLanguage || conversationLanguage
                }),
                signal: controller ? controller.signal : undefined
            }).then(response => response.json()).then(function (data) {
                if (completed || requestGeneration !== speechRequestGeneration) return;
                completed = true;
                window.clearTimeout(requestTimeout);
                const localizedText = data.speech_text || speechFriendlyText(data.text || text);
                lastAssistantSpokenText = speechFriendlyText(localizedText);
                if (data.voice_base64 && voiceProviderMode !== 'browser') {
                    voiceProviderMode = 'elevenlabs';
                    playVoice(data.voice_base64, data.voice_mime, onEnded, onStart);
                }
                else {
                    useBrowserVoiceTemporarily();
                    console.info('ElevenLabs unavailable; using browser voice temporarily.');
                    speakWithBrowser(localizedText, onEnded, onStart);
                }
            }).catch(function () {
                if (completed || requestGeneration !== speechRequestGeneration) return;
                completed = true;
                window.clearTimeout(requestTimeout);
                useBrowserVoiceTemporarily();
                console.info('ElevenLabs request failed; using browser voice temporarily.');
                speakWithBrowser(lastAssistantSpokenText || text, onEnded, onStart);
            });
        }

        function cancelResponseReminder() {
            if (responseReminderTimer) window.clearTimeout(responseReminderTimer);
            responseReminderTimer = null;
        }

        function resetResponseReminders() {
            cancelResponseReminder();
            responseReminderCount = 0;
        }

        function stopAssistantAudio(clearQueue) {
            speechRequestGeneration++;
            if (activeAssistantAudio) {
                activeAssistantAudio.pause();
                activeAssistantAudio.currentTime = 0;
                activeAssistantAudio = null;
            }
            if (clearQueue !== false) assistantAudioQueue = [];
        }

        function responseReminderText() {
            if (responseReminderCount === 2) {
                const finalReminderByStage = {
                    confirm_product: 'Main is product par ruki hoon. Jab free ho, haan ya nahi bol dena.',
                    clarify_product: 'Options screen par hain. Product ka naam bolkar cart mein add ya enquiry bata dijiye.',
                    await_quantity: 'Main quantity ka wait kar raha hoon. Free hoke sirf number bol dena.',
                    confirm_quantity: 'Quantity pending hai. Jab ready ho, confirm ya change bol dena.',
                    anything_else: 'Aap busy ho toh koi problem nahi. Baad mein yahin se order continue ho jayega.',
                    confirm_order: 'Order summary safe hai. Free hone par confirm karke delivery continue kar lena.',
                    delivery_details: 'Cart safe hai. Jab free ho, delivery location aur slot select kar lena.',
                    payment_method: 'Order details safe hain. Free hone par payment option choose kar lena.',
                    checkout_ready: 'Order abhi submit nahi hua hai. Ready hone par Place Order button dabana.',
                    customer_care_offer: 'Main yahin ruk rahi hoon. Free hone par call ya continue bol dena.'
                };
                return finalReminderByStage[activeOrderingStage] || 'Aap busy ho toh koi problem nahi. Free hone par yahin se continue kar lena.';
            }
            const remindersByStage = {
                confirm_product: ['Jab ready ho, bas haan ya nahi bol dijiye—main isi product ke saath aage badh jaungi.', 'Koi jaldi nahi hai. Yeh product rakhna hai ya koi aur option dekhna hai?'],
                clarify_product: ['Product ka naam ya brand bolkar saath mein cart mein add ya enquiry bol dijiye.', 'Kaunsa flavour chahiye aur usko cart mein add karna hai ya price enquiry bhejni hai?'],
                await_quantity: ['Bas quantity bata dijiye, jaise 1, 2 ya 3—phir main add kar dungi.', 'Is product ki kitni quantity rakhni hai? Aap araam se bata dijiye.'],
                confirm_quantity: ['Quantity confirm kar dijiye, phir main order list update kar dungi.', 'Jo quantity batayi thi, wahi rakhni hai ya change karni hai?'],
                anything_else: ['Aur kuch chahiye ho toh bata dijiye. Nahi toh order confirm bol dijiye, main summary dikha dungi.', 'Main yahin hoon—kuch add karna hai, ya order summary ke liye confirm bolna hai?'],
                confirm_order: ['Summary check kar lijiye. Sab sahi ho toh confirm bol dijiye, phir address aur delivery slot le lungi.', 'Order ready hai. Aapki haan milte hi next delivery details poochungi.'],
                delivery_details: ['Delivery ke liye address ya location aur convenient slot bata dijiye.', 'Bas delivery details pending hain. Address aur time slot share kar dijiye, phir payment par aate hain.'],
                payment_method: ['Payment ka option select kar dijiye, phir place order ka final button aa jayega.', 'Ab sirf payment method choose karna hai—online, delivery par, ya jo option dikh raha ho.'],
                checkout_ready: ['Order ready hai. Sab details sahi ho toh neeche Place Order button dabaiye.', 'Main order place karne ke liye aapki confirmation ka wait kar raha hoon—Place Order button dabaiye.'],
                customer_care_offer: ['Aap chahen toh customer care se baat kar sakte hain. Haan boliye ya call lagao bol dijiye; warna yahin continue karte hain.', 'Koi doubt ho toh main customer care ko call laga sakti hoon. Aap jo comfortable ho, woh bol dijiye.']
            };
            const options = remindersByStage[activeOrderingStage] || ['Main yahin hoon. Jab ready ho, bata dijiye aage kya karna hai.'];
            return options[responseReminderCount % options.length];
        }

        function loadResponseReminder() {
            if (!activeOrderingStage || responseReminderCount >= maximumResponseReminders) return;
            const reminder = responseReminderText();
            responseReminderCount++;
            const requestGeneration = speechRequestGeneration;
            // A reminder must still be useful when the speech provider is
            // out of quota or the customer is not using microphone mode.
            // It is deliberately display-only state: no workflow is changed.
            appendMessage('assistant', escapeHtml(reminder));
            fetch(speakUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({text: reminder, match_language_to: lastUserMessage || 'Hinglish mein jawab dijiye.'})
            }).then(function (response) { return response.json(); }).then(function (data) {
                if (requestGeneration !== speechRequestGeneration) return;
                const localizedText = data.text || reminder;
                if (speechRecognition) {
                    try { speechRecognition.abort(); } catch (error) {}
                    speechRecognition = null;
                }
                if (data.voice_base64 && voiceProviderMode !== 'browser') playVoice(data.voice_base64, data.voice_mime, scheduleResponseReminder);
                else {
                    useBrowserVoiceTemporarily();
                    speakWithBrowser(localizedText, scheduleResponseReminder);
                }
            }).catch(function () {
                if (requestGeneration === speechRequestGeneration) {
                    useBrowserVoiceTemporarily();
                    speakWithBrowser(reminder, scheduleResponseReminder);
                }
            });
        }

        function rememberUserLanguage(text) {
            // Do not turn an unfamiliar or short reply into English. Keep the
            // last reliable language until a transcript/model identifies it.
            applyDetectedLanguage('', text);
        }

        function scheduleResponseReminder() {
            cancelResponseReminder();
            if (!activeOrderingStage || responseReminderCount >= maximumResponseReminders) return;
            responseReminderTimer = window.setTimeout(function () {
                responseReminderTimer = null;
                if (!activeOrderingStage) return;
                loadResponseReminder();
            }, [12000, 25000, 45000][responseReminderCount] || 45000);
        }

        document.addEventListener('click', function playQueuedWelcome() {
            if (!queuedWelcomeAudio) return;
            queuedWelcomeAudio.play().then(function () { queuedWelcomeAudio = null; }).catch(function () {});
        });

        // Render the first sentence immediately; network/TTS work continues in
        // parallel so the customer never sees a silent blank assistant.
        const instantWelcomeText = {!! json_encode('Namaste ' . (auth()->user()->name ?? 'there') . ' ji. Aap voice se ya text se order kar sakte hain. Aap naya order karna chahenge ya purana order?') !!};
        appendMessage('assistant', escapeHtml(instantWelcomeText));
        // Most returning users have history. Do not start welcome DB + TTS
        // requests until history confirms this is a new conversation.
        let welcomePromise = null;
        function loadWelcome() {
            if (welcomePromise) return welcomePromise;
            welcomePromise = fetch(welcomeUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(function (response) { return response.json(); })
                .then(function (welcome) {
                    customerHasPreviousOrder = !!welcome.has_previous_order;
                    return welcome;
                });
            return welcomePromise;
        }
        function playWelcome() {
            return loadWelcome().then(function (welcome) {
                return new Promise(function (resolve) {
                    loadVoiceAsync(welcome.text || instantWelcomeText, function () {
                        finishWelcomeAndListen();
                        resolve();
                    });
                });
            });
        }
        fetch(historyUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
            .then(response => response.json())
            .then(data => {
                if (!data.messages || !data.messages.length) {
                    playWelcome()
                        .catch(finishWelcomeAndListen);
                    return;
                }
                if (data.active_conversation_id) conversationId = data.active_conversation_id;
                const restoredState = data.active_workflow_state || {};
                const restoredStage = String(restoredState.stage || '');
                activeCandidateSetId = String(restoredState.candidate_set_id || '');
                if (restoredStage) {
                    onboardingStage = null;
                    activeOrderingStage = restoredStage;
                    activeOrderingProductId = Number(restoredState.product?.id) || null;
                    activeClarificationOptions = (restoredState.products || []).map(function (product) {
                        return {id: Number(product.id), requested_quantity: Number(product.requested_quantity) || 0, requested_unit: String(product.requested_unit || '')};
                    }).filter(function (product) { return product.id > 0; });
                    aiDebug('Conversation workflow restored', {conversationId: conversationId, state: restoredState});
                } else {
                    onboardingStage = null;
                    activeOrderingStage = 'anything_else';
                    aiDebug('Conversation restored with cart fallback', {conversationId: conversationId});
                }
                chat.innerHTML = '';
                data.messages.forEach(function (message) {
                    const html = savedMessageHtml(message);
                    if (html.trim()) appendMessage(message.role, html, message.time);
                });
                renderLiveOrderList();
                finishWelcomeAndListen();
            })
            .catch(finishWelcomeAndListen);
        // Safety attempt for browsers that block welcome audio autoplay and
        // therefore never fire the audio-ended callback.
        window.setTimeout(function () {
            if (welcomeGreetingFinished && !speechRecognition && !continuousTalkMode) startAutoListening();
        }, 6000);

        function sendMessage(message, selectedProductId, options) {
            const text = (message || '').trim();
            if (!text) return;
            const sendOptions = options || {};
            const alreadyRenderedUserMessage = Boolean(sendOptions.alreadyRenderedUserMessage);
            aiDebug('Command received', {
                text: text,
                conversationId: conversationId,
                activeStage: activeOrderingStage,
                selectedProductId: selectedProductId || activeOrderingProductId || null,
                clarificationOptions: activeClarificationOptions,
                replayingOnboardingMessage: alreadyRenderedUserMessage
            });
            if (/^\s*(?:stop|ruko|ruk jao|bas chup|band karo|pause)\s*[.!?]*$/iu.test(text)) {
                stopAssistantAudio(true);
                continuousTalkMode = false;
                cancelSpeechRecognitionRestart();
                setMicStatus('Mic ready', 'idle');
                return;
            }
            if (/^\s*(?:repeat|dobara|phir se|wapas bolo|kya kaha)\s*[.!?]*$/iu.test(text) && lastAssistantSpokenText) {
                stopAssistantAudio(true);
                loadVoiceAsync(lastAssistantSpokenText);
                return;
            }
            if (!alreadyRenderedUserMessage) {
                resetResponseReminders();
                stopAssistantAudio(true);
                rememberUserLanguage(text);
                lastUserMessage = text;
                appendMessage('user', escapeHtml(text));
            }

            const intent = text.toLowerCase();
            const directCustomerCareCallRequest = isExplicitCustomerCareCallRequest(text)
                || isStandaloneCustomerCareCallRequest(text);
            // Starting a fresh order is a safe local decision. Do this before
            // the onboarding request so a slow/failed request can never put a
            // recognised phrase such as "main new order karunga" back into the
            // previous/new clarification loop.
            if (!sendOptions.skipOrderChoice && (onboardingStage === 'choose_order' || onboardingStage === 'resolving_order') && isNewOrderIntent(intent)) {
                input.value = '';
                beginNewOrder();
                return;
            }
            if (!sendOptions.skipOrderChoice
                && (onboardingStage === 'choose_order' || onboardingStage === 'resolving_order')
                && directCustomerCareCallRequest) {
                // Do not put a direct call behind the onboarding classifier:
                // mobile browsers allow the tel: launch only in the original
                // tap/Enter event.
                onboardingIntentRequestVersion++;
                onboardingStage = null;
            }
            if (!sendOptions.skipOrderChoice && onboardingStage === 'choose_order') {
                input.value = '';
                onboardingStage = 'resolving_order';
                const requestVersion = ++onboardingIntentRequestVersion;
                setMicStatus('Processing…', 'processing');
                fetch(onboardingIntentUrl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                    body: JSON.stringify({message: text})
                }).then(function (response) {
                    if (!response.ok) throw new Error('Could not understand order choice');
                    return response.json();
                }).then(function (data) {
                    if (requestVersion !== onboardingIntentRequestVersion) return;
                    if (data.choice === 'previous') {
                        onboardingStage = null;
                        showPreviousOrders();
                        return;
                    }
                    if (data.choice === 'new') {
                        beginNewOrder();
                        return;
                    }
                    if (data.choice === 'handled') {
                        renderOnboardingHandledReply(data);
                        return;
                    }
                    if (data.choice === 'forward_to_chat' || data.forward_to_chat) {
                        forwardOnboardingMessageToChat(text, selectedProductId, sendOptions);
                        return;
                    }
                    onboardingStage = 'choose_order';
                    const clarifyReply = String(data.fallback_reply || 'Aap Zonik ke product, order, delivery, payment ya customer care ke baare mein pooch sakte hain. Naya ya previous order shuru karna ho to woh bhi bol dijiye.');
                    appendMessage('assistant', escapeHtml(clarifyReply));
                    loadVoiceAsync(clarifyReply);
                }).catch(function () {
                    if (requestVersion !== onboardingIntentRequestVersion) return;
                    // Keep the known local intent usable even if the request
                    // failed after the user spoke it.
                    if (isNewOrderIntent(intent)) {
                        beginNewOrder();
                        return;
                    }
                    // An onboarding classifier failure must never swallow a
                    // real support question, a customer-care request, or a
                    // product command. The regular assistant has the full
                    // conversation and is the safe fallback.
                    forwardOnboardingMessageToChat(text, selectedProductId, sendOptions);
                });
                return;
            }
            if (!sendOptions.skipOrderChoice && onboardingStage === 'resolving_order') {
                // A newer sentence should win over an old, slow onboarding
                // classifier response. Route it to the normal assistant
                // instead of silently dropping it.
                onboardingIntentRequestVersion++;
                onboardingStage = null;
            }

            if (awaitingNewOrderReady) {
                input.value = '';
                awaitingNewOrderReady = false;
                onboardingStage = 'resolving_ready';
                const requestVersion = ++onboardingIntentRequestVersion;
                setMicStatus('Processing…', 'processing');
                fetch(onboardingIntentUrl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                    body: JSON.stringify({message: text, stage: 'readiness'})
                }).then(function (response) {
                    if (!response.ok) throw new Error('Could not understand readiness');
                    return response.json();
                }).then(function (data) {
                    if (requestVersion !== onboardingIntentRequestVersion) return;
                    onboardingStage = null;
                    if (data.choice === 'handled') {
                        renderOnboardingHandledReply(data);
                        return;
                    }
                    if (data.choice === 'forward_to_chat' || data.forward_to_chat) {
                        forwardOnboardingMessageToChat(text, selectedProductId, sendOptions);
                        return;
                    }
                    let readyReply = '';
                    if (data.choice === 'yes') {
                        readyReply = 'Okay, ab product ka naam aur quantity saath mein bataiye.';
                    } else if (data.choice === 'no') {
                        readyReply = 'Theek hai, jab ready hon tab new order bol dijiye.';
                    } else {
                        awaitingNewOrderReady = true;
                        readyReply = 'Main samajh nahi paaya; please ek baar phir bataiye.';
                    }
                    appendMessage('assistant', escapeHtml(readyReply));
                    loadVoiceAsync(readyReply);
                }).catch(function () {
                    if (requestVersion !== onboardingIntentRequestVersion) return;
                    onboardingStage = null;
                    // A readiness-check outage must not turn an ordinary
                    // Zonik question into a dead end.
                    forwardOnboardingMessageToChat(text, selectedProductId, sendOptions);
                });
                return;
            }
            if (!sendOptions.skipOrderChoice && onboardingStage === 'resolving_ready') {
                // Do not drop a newer request while a readiness reply is
                // still being classified; the newest message owns the chat.
                onboardingIntentRequestVersion++;
                onboardingStage = null;
                awaitingNewOrderReady = false;
            }
            if (previousOrdersVisible && !isNewOrderIntent(intent) && /(?:\b(?:same|yahi|yehi|confirm|direct|first|second|third|pehla|dusra|doosra|teesra|last)\b|\border\b|\u0915\u0902\u092b\u0930\u094d\u092e|\u0911\u0930\u094d\u0921\u0930|\u092a\u0939\u0932|\u0926\u0942\u0938\u0930|\u0924\u0940\u0938\u0930|\u0906\u0916\u093f\u0930)/i.test(intent)) {
                input.value = '';
                const cards = Array.from(chat.querySelectorAll('[data-reorder-card]'));
                let selectedCard = null;
                if (/(?:\b(?:first|1st|pehla|pehli)\b|\u092a\u0939\u0932)/i.test(intent)) selectedCard = cards[0] || null;
                else if (/(?:\b(?:second|2nd|dusra|doosra|dusri|doosri)\b|\u0926\u0942\u0938\u0930)/i.test(intent)) selectedCard = cards[1] || null;
                else if (/(?:\b(?:third|3rd|teesra|tisra|last|aakhri)\b|\u0924\u0940\u0938\u0930|\u0906\u0916\u093f\u0930)/i.test(intent)) selectedCard = cards[2] || cards[cards.length - 1] || null;
                if (!selectedCard) {
                    const namedCards = cards.filter(function (card) {
                        const orderNumber = String(card.querySelector('.ai-reorder-head strong')?.textContent || '').toLowerCase();
                        return orderNumber && intent.includes(orderNumber);
                    });
                    if (namedCards.length === 1) selectedCard = namedCards[0];
                }
                if (selectedCard) {
                    selectedCard.querySelector('[data-reorder-order]')?.click();
                } else {
                    const chooseReply = 'Kaunsa previous order chahiye? First, second, third, ya order number boliye.';
                    appendMessage('assistant', escapeHtml(chooseReply));
                    loadVoiceAsync(chooseReply);
                }
                return;
            }
            if (!activeOrderingStage && /\b(previous|last|old|repeat|reorder|purana|pichla|pehle wala)\s*(?:order)?\b/i.test(intent)) {
                input.value = '';
                showPreviousOrders();
                return;
            }
            if ((!activeOrderingStage || activeOrderingStage === 'anything_else') && isNewOrderIntent(intent)) {
                input.value = '';
                beginNewOrder();
                return;
            }
            if (false && !activeOrderingStage && /\b(checkout|place order)\b/.test(intent)) {
                input.value = '';
                appendMessage('assistant', 'Order complete karne ke liye pehle delivery location aur slot confirm kijiye. Main yahin se payment aur order placement complete karunga.');
                return;
            }
            if (false && !activeOrderingStage && /\b(delivery|slot)\b/.test(intent)) {
                input.value = '';
                appendMessage('assistant', 'Choose a delivery slot:<div class="ai-product-actions"><button class="ai-product-btn primary">Morning (8 AM–12 PM)</button><button class="ai-product-btn primary">Afternoon (12 PM–4 PM)</button><button class="ai-product-btn primary">Evening (4 PM–8 PM)</button></div>');
                return;
            }
            if (false && !activeOrderingStage && /\b(payment|prepaid|cod|net 30)\b/.test(intent)) {
                input.value = '';
                const paymentReply = 'Checkout par Pay Online, Cash on Delivery, ya eligible ho to Pay on Credit choose kijiye.';
                appendMessage('assistant', escapeHtml(paymentReply));
                loadVoiceAsync(paymentReply);
                return;
            }
            const typing = appendTyping();
            setAgentUiState('matching', '“' + text.slice(0, 110) + (text.length > 110 ? '…' : '') + '”');
            input.value = '';
            const requestStage = activeOrderingStage;
            const customerCareConsent = (requestStage === 'customer_care_offer' && isCustomerCareAffirmative(text))
                || directCustomerCareCallRequest;
            const customerCareDialerUrl = customerCareConsent
                ? (customerCareDialUrlFrom(sendOptions.customerCareDialUrl) || customerCareDialUrl)
                : '';

            // Start the state/history request before handing control to the
            // dialer. On a real tap or Enter press, launch it in this same
            // synchronous event so mobile browser popup rules cannot force a
            // second tap on the phone number.
            const chatRequest = fetch(chatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    message: text,
                    conversation_id: conversationId,
                    selected_product_id: selectedProductId || activeOrderingProductId || null,
                    workflow_stage: activeOrderingStage || null,
                    clarification_options: activeOrderingStage === 'clarify_product' ? activeClarificationOptions : [],
                    candidate_set_id: activeOrderingStage === 'clarify_product' ? activeCandidateSetId : null,
                    delivery_details: selectedDeliveryDetails || null
                })
            });
            if (customerCareConsent) {
                openCustomerCareDialer(
                    customerCareDialerUrl,
                    requestStage === 'customer_care_offer' ? 'customer-care-consent' : 'customer-care-direct-request',
                    Boolean(sendOptions.customerCareUserGesture || navigator.userActivation?.isActive)
                );
            }
            chatRequest
            .then(function (response) {
                if (!response.ok) {
                    return response.json().catch(function () { return {}; }).then(function (error) {
                        throw new Error(error.message || 'Assistant request failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                aiDebug('Chat API response', {
                    reply: data.reply,
                    intent: data.intent,
                    workflow: data.workflow,
                    products: (data.products || []).map(function (product) {
                        return {id: product.id, name: product.name, requestedQuantity: product.requested_quantity};
                    }),
                    autoAdded: data.auto_added
                });
                removeTyping(typing);
                const reply = data.reply || 'I can help with that.';
                const order = getOrderDetails(text);
                const products = data.products || [];
                const aiIntent = data.intent || {};
                applyDetectedLanguage(aiIntent.language, text);
                const quantity = aiIntent.quantity || order.quantity;
                const unit = aiIntent.unit || order.unit;
                const workflow = data.workflow || {};
                activeCandidateSetId = workflow.stage === 'clarify_product'
                    ? String(workflow.candidate_set_id || activeCandidateSetId || '')
                    : '';
                setAgentUiFromWorkflow(workflow, Boolean(data.auto_added));
                // Only the latest checkout payload is valid. This is also
                // required when the user changes payment method while already
                // on the Place Order step; an older button contains the old
                // payment method in its data attribute.
                document.querySelectorAll('[data-place-ai-order]').forEach(function (button) {
                    button.closest('.ai-message-row')?.remove();
                });
                if (requestStage === 'delivery_details' && workflow.stage === 'payment_method') {
                    selectedDeliveryDetails = text;
                }
                if (workflow.stage === 'payment_method' && workflow.delivery_details) {
                    selectedDeliveryDetails = String(workflow.delivery_details);
                }
                // A completed delivery choice must retire the old location /
                // slot controls. Keeping those controls active lets a second
                // tap submit an outdated choice against the next workflow
                // state and makes the assistant appear to repeat itself.
                if (requestStage === 'delivery_details' && workflow.stage !== 'delivery_details') {
                    clearAssistantDeliveryOptions();
                }
                liveOrderEditable = workflow.stage === 'confirm_order';
                activeOrderingStage = ['confirm_product', 'await_quantity', 'confirm_quantity', 'anything_else', 'clarify_product', 'await_remove_quantity', 'confirm_order', 'order_suggestions', 'delivery_details', 'payment_method', 'checkout_ready', 'customer_care_offer'].includes(workflow.stage)
                    ? workflow.stage
                    : null;
                aiDebug('Workflow state updated', {previousStage: requestStage, currentStage: activeOrderingStage, workflow: workflow});
                if (workflow.stage === 'confirm_product' && products.length === 1) {
                    activeOrderingProductId = Number(products[0].id) || null;
                } else if (!activeOrderingStage || ['anything_else', 'delivery_details', 'payment_method'].includes(workflow.stage)) {
                    activeOrderingProductId = null;
                }
                if (workflow.stage === 'clarify_product') {
                    activeClarificationOptions = products.map(function (product) {
                        return {
                            id: Number(product.id),
                            requested_quantity: Number(product.requested_quantity) || 0,
                            requested_unit: String(product.requested_unit || '')
                        };
                    }).filter(function (product) { return product.id > 0; });
                } else if (workflow.stage !== 'clarify_product') {
                    activeClarificationOptions = [];
                }
                if (aiIntent.intent === 'cart') {
                    removeTyping(typing);
                    showCart();
                    return;
                }
                appendMessage('assistant', escapeHtml(reply));
                if (data.auto_added || ['added', 'cart_updated'].includes(workflow.stage)) cartShortcut.hidden = false;
                const cartMutationConfirmed = Boolean(data.auto_added)
                    || ['added', 'cart_updated', 'cart_removed'].includes(workflow.stage);
                if (workflow.show_cart || cartMutationConfirmed) window.setTimeout(renderLiveOrderList, 100);
                if (workflow.stage === 'cart_removed') cartShortcut.hidden = !(data.cart || []).length;
                if (data.auto_added && workflow.stage === 'anything_else') {
                    clarificationMessage?.remove();
                    clarificationMessage = null;
                    loadVoiceAsync(reply);
                    return;
                }
                if (products.length) {
                    let html = '';
                    if (workflow.stage === 'order_suggestions') {
                        html = '<div class="ai-suggestion-line" data-order-suggestions="true">' + products.slice(0, 3).map(suggestionCard).join('') + '</div>'
                            + '<div class="ai-product-actions"><button type="button" class="ai-product-btn" data-skip-order-suggestions="true">No thanks, continue delivery</button></div>';
                    } else if (!['added', 'cart_updated', 'cart_removed', 'await_quantity'].includes(workflow.stage)) products.forEach(function (product) {
                        const productQuantity = Number(product.requested_quantity || quantity || 1);
                        const label = workflow.stage === 'choose_cart_item' ? ('Set ' + productQuantity) : (workflow.stage === 'choose_cart_remove' ? 'Remove' : 'Add to Cart');
                        const needsSpokenQuantity = ['choose_product', 'choose_brand', 'confirm_product'].includes(workflow.stage) && !Number(product.requested_quantity);
                        let card = productCard(product, productQuantity, product.requested_unit || unit, label, needsSpokenQuantity, workflow.stage);
                        if (workflow.stage === 'choose_cart_remove') {
                            card = card.replace('data-add-product="' + product.id + '"', 'data-remove-product="' + escapeHtml(product.name) + '"');
                        }
                        html += card;
                    });
                    if (html.trim()) {
                        const renderedMessage = appendMessage('assistant', html);
                        if (workflow.stage === 'order_suggestions') renderedMessage.classList.add('ai-suggestion-message');
                        if (workflow.stage === 'clarify_product') clarificationMessage = renderedMessage;
                    }
                }
                if (workflow.stage === 'delivery_details') {
                    dismissOrderSuggestionMessages();
                    window.setTimeout(function () { renderAssistantDeliveryOptions(workflow); }, 230);
                    if (data.voice_base64) playVoice(data.voice_base64, data.voice_mime, scheduleResponseReminder);
                    else loadVoiceAsync(reply, scheduleResponseReminder);
                    return;
                }
                if (workflow.stage === 'payment_method') {
                    let paymentHtml = '';
                    Object.keys(workflow.payment_options || {}).forEach(function (method) {
                        paymentHtml += '<button type="button" class="ai-product-btn primary" data-payment-option="' + escapeHtml(method) + '">' + escapeHtml(workflow.payment_options[method]) + '</button>';
                    });
                    if (paymentHtml && orderCheckout) {
                        replaceOrderCheckout('<div class="ai-checkout-choice-group"><span class="ai-checkout-choice-title">Payment method</span><div class="ai-checkout-choice-list">' + paymentHtml + '</div></div>');
                    }
                }
                if (workflow.stage === 'customer_care_offer') {
                    const dialUrl = rememberCustomerCareDialer(workflow);
                    const dialUrlAttribute = dialUrl ? ' data-customer-care-dial-url="' + escapeHtml(dialUrl) + '"' : '';
                    appendMessage('assistant', '<div class="ai-product-actions"><button type="button" class="ai-product-btn primary" data-customer-care-call="yes"' + dialUrlAttribute + '>Call Customer Care</button><button type="button" class="ai-product-btn" data-customer-care-call="no">Continue Here</button></div>');
                }
                if (workflow.stage === 'call_customer_care') {
                    const dialUrl = rememberCustomerCareDialer(workflow);
                    if (dialUrl) {
                        // Keep a normal tel link only as an accessibility
                        // fallback. The dialer itself is launched immediately.
                        appendMessage('assistant', '<div class="ai-product-actions"><a class="ai-product-btn primary" href="' + escapeHtml(dialUrl) + '">Opening Customer Care call…</a></div>');
                        openCustomerCareDialer(dialUrl, 'customer-care-workflow-response', false);
                    }
                    activeOrderingStage = ['confirm_product', 'await_quantity', 'confirm_quantity', 'anything_else', 'clarify_product', 'await_remove_quantity', 'confirm_order', 'order_suggestions', 'delivery_details', 'payment_method', 'checkout_ready'].includes(workflow.resume_stage)
                        ? workflow.resume_stage
                        : 'anything_else';
                }
                if (data.voice_base64) {
                    playVoice(data.voice_base64, data.voice_mime, scheduleResponseReminder);
                } else {
                    loadVoiceAsync(reply, scheduleResponseReminder);
                }
                if (workflow.stage === 'checkout_ready') {
                    const checkoutData = encodeURIComponent(JSON.stringify({payment_method: workflow.payment_method, delivery_details: workflow.delivery_details || ''}));
                    if (orderCheckout) {
                        replaceOrderCheckout('<div class="ai-checkout-choice-group"><span class="ai-checkout-choice-title">Payment confirmed: ' + escapeHtml(workflow.payment_method || '') + '</span><div class="ai-checkout-choice-list"><button type="button" class="ai-cart-btn primary" data-place-ai-order="' + checkoutData + '">Place Order</button></div></div>');
                    }
                }
            })
            .catch((error) => {
                aiDebug('Chat API failed', {message: String(error), text: text, stage: requestStage});
                removeTyping(typing);
                setAgentUiState('error');
                const failureReply = 'Sorry, abhi reply connect nahi hua. Ek baar phir boliye; main sun raha hoon.';
                appendMessage('assistant', escapeHtml(failureReply));
                // Keep hands-free mode alive after a failed network request.
                // Previously this branch produced no audio, so the normal
                // speech-ended hook never restarted recognition.
                loadVoiceAsync(failureReply, function () {
                    if (continuousTalkMode) resumeListeningAfterReply();
                });
            });
        }

        function dismissOrderSuggestionMessages() {
            chat?.querySelectorAll('.ai-suggestion-message').forEach(function (message) {
                if (message.classList.contains('ai-stage-dismiss')) return;
                message.classList.add('ai-stage-dismiss');
                window.setTimeout(function () { message.remove(); syncChatClearance(); }, 220);
            });
        }

        let checkoutStageVersion = 0;
        function replaceOrderCheckout(html) {
            if (!orderCheckout) return;
            const version = ++checkoutStageVersion;
            const commit = function () {
                if (version !== checkoutStageVersion) return;
                orderCheckout.innerHTML = '<div class="ai-checkout-stage-enter">' + html + '</div>';
                orderCheckout.classList.add('visible');
                syncChatClearance();
            };
            const current = orderCheckout.firstElementChild;
            if (!current) { commit(); return; }
            current.classList.add('ai-checkout-stage-leave');
            window.setTimeout(commit, 190);
        }

        function renderAssistantDeliveryOptions(workflow) {
            // There is only one active delivery-choice screen. Replace an
            // older copy rather than leaving stale location/slot buttons in
            // the chat for the customer to tap again.
            clearAssistantDeliveryOptions();
            let locationHtml = '';
            let slotHtml = '';
            const locations = Array.isArray(workflow.locations) ? workflow.locations : Object.values(workflow.locations || {});
            const slots = Array.isArray(workflow.slots) ? workflow.slots : Object.values(workflow.slots || {});
            const selectedLocation = workflow.selected_location || null;

            locations.forEach(function (location) {
                const isSelected = selectedLocation && Number(selectedLocation.outlet_id) === Number(location.outlet_id);
                const command = location.outlet_name || location.label || '';
                locationHtml += '<button type="button" class="ai-product-btn ' + (isSelected ? 'primary' : '') + '" data-delivery-option="' + escapeHtml(command) + '">&#128205; ' + escapeHtml(location.label || command) + '</button>';
            });
            slots.forEach(function (slot) {
                const locationLabel = selectedLocation ? String(selectedLocation.label || '') : '';
                const command = (locationLabel ? locationLabel + ', ' : '') + String(slot.label || '');
                slotHtml += '<button type="button" class="ai-product-btn primary" data-delivery-option="' + escapeHtml(command) + '">&#128336; ' + escapeHtml(slot.label || '') + '</button>';
            });

            let html = '';
            // Checkout is intentionally progressive: location first, then
            // replace it with slots only after the server confirms location.
            if (!selectedLocation && locationHtml) {
                html = '<div class="ai-checkout-choice-group"><span class="ai-checkout-choice-title">Select delivery location</span><div class="ai-checkout-choice-list">' + locationHtml + '</div></div>';
            } else if (selectedLocation && slotHtml) {
                html = '<div class="ai-checkout-choice-group"><span class="ai-checkout-choice-title">Select delivery slot</span><div class="ai-checkout-choice-list">' + slotHtml + '</div></div>';
            }
            if (!html) html = '<div class="ai-product-meta"><strong>Delivery options load nahi hue. Please location ka naam boliye ya dobara confirm kijiye.</strong></div>';
            replaceOrderCheckout('<div class="ai-product-actions" data-assistant-delivery-options="true">' + html + '</div>');
            orderDock?.scrollIntoView({block: 'nearest', behavior: 'smooth'});
        }

        function clearAssistantDeliveryOptions() {
            // Invalidate the retry timer created by an earlier click before
            // replacing this screen with fresh server-verified choices.
            deliveryOptionRequestVersion++;
            chat?.querySelectorAll('[data-assistant-delivery-options]').forEach(function (options) {
                const message = options.closest('.ai-message-row');
                if (message) message.remove();
                else options.remove();
            });
        }

        function showPreviousOrders() {
            const typing = appendTyping();
            fetch(previousOrdersUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}}).then(function (response) {
                if (!response.ok) throw new Error('Previous orders failed');
                return response.json();
            }).then(function (data) {
                removeTyping(typing);
                const orders = data.orders || [];
                if (!orders.length) {
                    const emptyReply = conversationReplyLanguage === 'english' ? 'I could not find a previous order. Shall we start a new one?' : 'Aapka koi previous order nahi mila. Naya order shuru karein?';
                    appendMessage('assistant', emptyReply); loadVoiceAsync(emptyReply); return;
                }
                previousOrdersVisible = true;
                let html = '<strong>Aapke previous orders:</strong><div class="ai-cart-summary">';
                orders.forEach(function (order) {
                    html += '<div class="ai-cart-bill ai-reorder-card" data-reorder-card="' + order.id + '"><div class="ai-reorder-head"><strong>' + escapeHtml(order.order_no) + '</strong><span>' + escapeHtml(order.date) + '</span></div><div class="ai-reorder-table-wrap"><table class="ai-reorder-table"><thead><tr><th style="width:42%">Product</th><th>Price</th><th>Quantity</th><th>Amount</th></tr></thead><tbody>';
                    order.items.forEach(function (item) {
                        const image = item.image ? '<img class="ai-reorder-image" src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '">' : '<span class="ai-reorder-image"></span>';
                        html += '<tr data-reorder-item data-product-id="' + item.product_id + '" data-price="' + Number(item.price || 0) + '"><td><div class="ai-reorder-product">' + image + '<span class="ai-reorder-name">' + escapeHtml(item.name) + '<small class="ai-product-meta">' + escapeHtml(item.unit || 'unit') + '</small></span></div></td><td>' + money(item.price) + '</td><td><span class="ai-qty-control"><button type="button" class="ai-qty-btn" data-reorder-qty="-1">−</button><span class="ai-qty-value">' + item.quantity + '</span><button type="button" class="ai-qty-btn" data-reorder-qty="1">+</button></span></td><td><strong data-line-total>' + money(item.line_total) + '</strong></td></tr>';
                    });
                    html += '</tbody></table></div><div class="ai-reorder-total"><span>Order Total</span><strong data-reorder-total>' + money(order.items.reduce(function (sum, item) { return sum + Number(item.line_total || 0); }, 0)) + '</strong></div><div class="ai-product-actions"><button type="button" class="ai-product-btn primary" data-reorder-order="' + order.id + '">Order Same / Updated Qty</button></div></div>';
                });
                html += '</div>';
                appendMessage('assistant', html);
                const listReply = conversationReplyLanguage === 'english'
                    ? 'Here are your previous orders. You can adjust the quantities, or confirm the same order.'
                    : (conversationReplyLanguage === 'marathi' ? 'हे तुमचे मागील ऑर्डर आहेत. प्रमाण कमी-जास्त करा किंवा हाच ऑर्डर निश्चित करा.' : 'Ye aapke previous orders hain. Quantity kam ya zyada kar sakte hain, ya same order confirm kar dijiye.');
                loadVoiceAsync(listReply);
            }).catch(function () { removeTyping(typing); appendMessage('assistant', 'Previous orders abhi load nahi ho paaye. Dobara try karein.'); });
        }

        function placeOrderInsideAssistant(encoded) {
            if (assistantOrderSubmitting || assistantOrderCompleted) return;
            assistantOrderSubmitting = true;
            document.querySelectorAll('[data-place-ai-order]').forEach(function (button) { button.disabled = true; });
            let selection = {};
            try { selection = JSON.parse(decodeURIComponent(encoded)); } catch (error) {}
            appendTyping();
            fetch(assistantCheckoutDataUrl, {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify({delivery_details: selection.delivery_details || ''})})
                .then(function (response) { if (!response.ok) return response.json().then(function (data) { throw new Error(data.message || 'Checkout data unavailable'); }); return response.json(); })
                .then(function (data) {
                    const payload = data.payload;
                    const method = String(selection.payment_method || '').toLowerCase();
                    if (method.includes('online') || method.includes('upi') || method.includes('card') || method.includes('wallet')) {
                        payload.payment_status = 'paid';
                        return fetch('/create-order', {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || ''}, body: JSON.stringify(payload)})
                            .then(function (response) { return response.json(); }).then(function (razorpayOrder) {
                                if (razorpayOrder.error) throw new Error(razorpayOrder.error);
                                new Razorpay({key: razorpayOrder.razorpay_key || '{{ env('RAZORPAY_KEY') }}', amount: razorpayOrder.amount, currency: 'INR', name: 'Zonik', description: 'Order payment', order_id: razorpayOrder.order_id,
                                    handler: function (payment) {
                                        fetch('{{ route('razorpay.payment.success') }}', {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify(payment)})
                                            .then(function (response) { if (!response.ok) throw new Error('Payment verification failed'); return response.json(); })
                                            .then(showAssistantOrderSuccess).catch(function () { appendMessage('assistant', 'Payment verify nahi ho paya. Support se contact karein.'); });
                                    }}).open();
                            });
                    }
                    payload.payment_status = method.includes('credit') ? 'credit' : 'pay_on_delivery';
                    const body = new URLSearchParams();
                    payload.assistant_order_token = conversationId;
                    Object.keys(payload).forEach(function (key) { if (key === 'cart') payload.cart.forEach(function (item, index) { Object.keys(item).forEach(function (field) { body.append('cart[' + index + '][' + field + ']', item[field] ?? ''); }); }); else body.append(key, payload[key] ?? ''); });
                    return fetch('/insert-order', {method: 'POST', headers: {'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}, body: body}).then(function (response) { if (!response.ok) throw new Error('Order failed'); return response.json(); }).then(showAssistantOrderSuccess);
                }).catch(function (error) { assistantOrderSubmitting = false; document.querySelectorAll('[data-place-ai-order]').forEach(function (button) { button.disabled = false; }); chat.querySelector('.ai-message.ai-typing')?.remove(); appendMessage('assistant', escapeHtml(error.message || 'Order place nahi ho paya.')); });
        }

        function showAssistantOrderSuccess(data) {
            assistantOrderSubmitting = false;
            assistantOrderCompleted = true;
            document.querySelectorAll('[data-place-ai-order]').forEach(function (button) { button.disabled = true; button.remove(); });
            chat.querySelector('.ai-message.ai-typing')?.remove();
            cartShortcut.hidden = true;
            data = data || {};
            const orderNumber = data.order_id || '';
            const trackingCode = data.tracking_code || '';
            const trackerUrl = @json(route('web.order.tracker'));
            const customerCarePhone = '+918850268043';
            const message = 'Thank you. Aapka order successfully place ho gaya. Ab tracker page par order status dekh sakte hain; query ke liye customer care call kijiye.';
            const tracking = (orderNumber ? '<div class="ai-product-meta"><strong>Order Number: ' + escapeHtml(orderNumber) + '</strong></div>' : '')
                + (trackingCode ? '<div class="ai-product-meta">Tracking Code: ' + escapeHtml(trackingCode) + '</div>' : '');
            appendMessage('assistant', '<strong>✓ Order Placed! Thank you.</strong>' + tracking + '<div class="ai-cart-actions"><a class="ai-cart-btn primary" href="' + escapeHtml(trackerUrl) + '">Track Order</a><a class="ai-cart-btn" href="tel:' + customerCarePhone + '">Customer Care</a></div>');
            let redirected = false;
            const openTracker = function () {
                if (redirected) return;
                redirected = true;
                window.location.assign(trackerUrl);
            };
            loadVoiceAsync(message, openTracker);
            window.setTimeout(openTracker, 9000);
        }

        sendBtn?.addEventListener('click', function () {
            sendMessage(input.value);
        });

        input?.addEventListener('keydown', function (event) {
            cancelResponseReminder();
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage(input.value);
            }
        });

        function startBrowserSpeechRecognition(fromContinuousMode) {
            const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!Recognition) return false;
            if (speechRecognition || speechRecognitionStartPending) return true;
            accurateVoiceMode = false;
            if (!fromContinuousMode) continuousTalkMode = true;
            if (activeAssistantAudio || assistantAudioQueue.length || window.speechSynthesis?.speaking || Date.now() - assistantSpeechEndedAt < 1000) {
                const retryDelay = Math.max(250, 1050 - (Date.now() - assistantSpeechEndedAt));
                scheduleSpeechRecognitionRestart(retryDelay);
                return true;
            }
            cancelSpeechRecognitionRestart();
            const recognition = new Recognition();
            speechRecognition = recognition;
            recognition.lang = conversationLanguage;
            // Collect the complete sentence instead of submitting Chrome's
            // first short fragment as the customer's whole command.
            recognition.interimResults = true;
            recognition.continuous = true;
            recognition.maxAlternatives = 3;
            let receivedSpeech = false;
            let speechStartedAt = 0;
            let finalTranscript = '';
            let latestInterimTranscript = '';
            let finishListeningTimer = null;
            let bestConfidence = 0;
            const finishCurrentUtterance = function (delay) {
                if (finishListeningTimer) window.clearTimeout(finishListeningTimer);
                finishListeningTimer = window.setTimeout(function () {
                    try { recognition.stop(); } catch (error) {}
                }, delay);
            };
            recognition.onspeechstart = function () { speechStartedAt = Date.now(); };
            recognition.onstart = function () {
                micBtn.classList.add('listening');
                setMicStatus('Listening…', 'listening');
                scheduleResponseReminder();
            };
            recognition.addEventListener('start', cancelResponseReminder, {once: true});
            recognition.onresult = function (event) {
                if (activeAssistantAudio || window.speechSynthesis?.speaking || Date.now() - assistantSpeechEndedAt < 900) return;
                finalTranscript = '';
                latestInterimTranscript = '';
                for (let index = 0; index < event.results.length; index++) {
                    const result = event.results[index];
                    const alternatives = Array.from(result);
                    const ranked = alternatives.map(function (alternative) {
                        const normalized = normalizeSpokenQuantity(alternative.transcript);
                        let score = (alternative.confidence || 0) * 10;
                        if (/\d+/.test(normalized)) score += 20;
                        if (/\b(box(?:es)?|packet|pack|carton|kg|kgs|kilo|gram|litre|liter|ltr|pcs?|pieces?|dozen|unit)\b/i.test(normalized)) score += 10;
                        return {text: normalized, score: score, confidence: Number(alternative.confidence || 0)};
                    }).sort(function (a, b) { return b.score - a.score; });
                    const bestMatch = ranked[0];
                    if (!bestMatch?.text) continue;
                    bestConfidence = Math.max(bestConfidence, bestMatch.confidence);
                    if (result.isFinal) finalTranscript += (finalTranscript ? ' ' : '') + bestMatch.text;
                    else latestInterimTranscript += (latestInterimTranscript ? ' ' : '') + bestMatch.text;
                }
                const heardText = normalizeSpokenQuantity((finalTranscript + ' ' + latestInterimTranscript).trim());
                if (!heardText) return;
                receivedSpeech = true;
                setMicStatus('Listening…', 'listening');
                aiDebug('Voice utterance updated', {final: finalTranscript, interim: latestInterimTranscript});
                finishCurrentUtterance(finalTranscript ? 850 : 1400);
            };
            recognition.onerror = function (event) {
                if (['not-allowed', 'service-not-allowed'].includes(event.error)) {
                    continuousTalkMode = false;
                    cancelSpeechRecognitionRestart();
                    setMicStatus('Tap mic to allow', 'idle');
                } else if (['audio-capture'].includes(event.error)) {
                    continuousTalkMode = false;
                    cancelSpeechRecognitionRestart();
                    setMicStatus('Mic unavailable', 'idle');
                } else if (event.error !== 'aborted') {
                    setMicStatus(event.error === 'no-speech' ? 'Listening…' : 'Try again', event.error === 'no-speech' ? 'listening' : 'idle');
                }
            };
            recognition.onend = function () {
                if (finishListeningTimer) window.clearTimeout(finishListeningTimer);
                speechRecognition = null;
                if (accurateVoiceMode) return;
                const transcript = normalizeSpokenQuantity((finalTranscript || latestInterimTranscript).trim());
                const speechDuration = speechStartedAt ? Date.now() - speechStartedAt : 0;
                if (receivedSpeech && transcript && !isLikelyBackgroundSpeech(transcript, bestConfidence, speechDuration, !!fromContinuousMode)) {
                    micBtn.classList.remove('listening');
                    setMicStatus('Processing…', 'processing');
                    aiDebug('Complete voice command', {transcript: transcript, speechDuration: speechDuration});
                    sendMessage(transcript);
                } else if (continuousTalkMode && !receivedSpeech) {
                    // Chrome ends a recognition session after silence. Keep
                    // the UI and continuous mode active while one controlled
                    // restart bridges that browser-imposed boundary.
                    scheduleSpeechRecognitionRestart(250);
                } else if (!receivedSpeech) {
                    micBtn.classList.remove('listening');
                    setMicStatus('Mic ready', 'idle');
                }
            };
            try {
                recognition.start();
                return true;
            } catch (error) {
                speechRecognition = null;
                cancelSpeechRecognitionRestart();
                micBtn.classList.remove('listening');
                setMicStatus('Tap mic', 'idle');
                aiDebug('Voice recognition could not start', {error: String(error)});
                return false;
            }
        }

        function isLikelyBackgroundSpeech(transcript, confidence, speechDuration, fromContinuousMode) {
            const text = String(transcript || '').trim().toLowerCase();
            if (!text || text.length < 2) return true;

            // Web Speech confidence is inconsistent on mobile: Chrome often
            // returns 0 for a perfectly valid phrase. Product-only answers
            // such as "Basmati rice" also contain no command keyword. Trust
            // every real transcript and reject only an explicitly reported,
            // extremely-low-confidence fragment. Echo is already blocked by
            // the assistant-audio and post-speech guards in onresult.
            return confidence > 0 && confidence < 0.08;
        }

        function browserSpeechFallback() {
            if (!startBrowserSpeechRecognition()) {
                appendMessage('assistant', 'Voice input is not supported here. Please use HTTPS or type your order.');
            }
        }

        function uploadRecordedAudio(blob) {
            const form = new FormData();
            form.append('audio', blob, 'voice-order.webm');
            const typing = appendTyping();
            fetch(transcribeUrl, {method: 'POST', headers: {'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}, body: form})
                .then(function (response) { if (!response.ok) throw new Error('Transcription failed'); return response.json(); })
                .then(function (data) {
                    removeTyping(typing);
                    if (!data.transcript) throw new Error('Empty transcript');
                    applyDetectedLanguage(data.language, data.transcript);
                    sendMessage(data.transcript);
                })
                .catch(function () {
                    removeTyping(typing);
                    const retryReply = 'Sorry, kya aap ek baar phir clearly bol sakte hain? Aap text bhi type kar sakte hain.';
                    appendMessage('assistant', escapeHtml(retryReply));
                    loadVoiceAsync(retryReply);
                });
        }

        async function startAccurateVoiceCapture() {
            if (voiceCaptureStarting || mediaRecorder?.state === 'recording' || activeAssistantAudio || window.speechSynthesis?.speaking) return true;
            if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia || !window.MediaRecorder) return false;
            voiceCaptureStarting = true;
            try {
                const stream = await navigator.mediaDevices.getUserMedia({audio: {echoCancellation: true, noiseSuppression: true, autoGainControl: true}});
                const preferredMime = MediaRecorder.isTypeSupported('audio/webm;codecs=opus') ? 'audio/webm;codecs=opus' : '';
                mediaRecorder = new MediaRecorder(stream, preferredMime ? {mimeType: preferredMime} : undefined);
                audioChunks = [];
                let speechDetected = false;
                let lastVoiceAt = Date.now();
                const startedAt = Date.now();
                let audioContext = null;
                let analyser = null;
                let animationFrame = null;

                const closeAudioAnalysis = function () {
                    if (animationFrame) cancelAnimationFrame(animationFrame);
                    if (voiceSilenceTimer) window.clearTimeout(voiceSilenceTimer);
                    voiceSilenceTimer = null;
                    if (audioContext) audioContext.close().catch(function () {});
                };
                const monitorSilence = function () {
                    if (!analyser || mediaRecorder?.state !== 'recording') return;
                    const levels = new Uint8Array(analyser.fftSize);
                    analyser.getByteTimeDomainData(levels);
                    let energy = 0;
                    for (let index = 0; index < levels.length; index++) energy += Math.abs(levels[index] - 128);
                    const average = energy / levels.length;
                    if (average > 3.2) {
                        speechDetected = true;
                        lastVoiceAt = Date.now();
                    }
                    if (speechDetected && Date.now() - lastVoiceAt > 1050 && Date.now() - startedAt > 700) {
                        mediaRecorder.stop();
                        return;
                    }
                    animationFrame = requestAnimationFrame(monitorSilence);
                };

                try {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    analyser = audioContext.createAnalyser();
                    analyser.fftSize = 512;
                    audioContext.createMediaStreamSource(stream).connect(analyser);
                } catch (error) {
                    // Without an analyser, still submit the recorded clip at
                    // the hard timeout instead of discarding valid speech.
                    speechDetected = true;
                }

                mediaRecorder.ondataavailable = function (event) { if (event.data.size) audioChunks.push(event.data); };
                mediaRecorder.onstop = function () {
                    closeAudioAnalysis();
                    micBtn.classList.remove('listening');
                    setMicStatus('Processing…', 'processing');
                    stream.getTracks().forEach(function (track) { track.stop(); });
                    const recordedMime = mediaRecorder.mimeType || 'audio/webm';
                    mediaRecorder = null;
                    if (audioChunks.length && speechDetected) uploadRecordedAudio(new Blob(audioChunks, {type: recordedMime}));
                    else if (continuousTalkMode) window.setTimeout(startAccurateVoiceCapture, 350);
                };
                mediaRecorder.start(200);
                accurateVoiceMode = true;
                continuousTalkMode = true;
                micBtn.classList.add('listening');
                setMicStatus('Listening…', 'listening');
                if (analyser) monitorSilence();
                voiceSilenceTimer = window.setTimeout(function () {
                    if (mediaRecorder?.state === 'recording') mediaRecorder.stop();
                }, 12000);
                return true;
            } catch (error) {
                accurateVoiceMode = false;
                setMicStatus('Tap mic to allow', 'idle');
                return false;
            } finally {
                voiceCaptureStarting = false;
            }
        }

        micBtn?.addEventListener('click', async function () {
            // Barge-in: tapping the microphone while the assistant is speaking
            // immediately stops playback and hands control to the customer.
            if (activeAssistantAudio || assistantAudioQueue.length || window.speechSynthesis?.speaking) {
                stopAssistantAudio(true);
                assistantSpeechEndedAt = 0;
                continuousTalkMode = true;
                window.setTimeout(function () {
                    if (!startBrowserSpeechRecognition() && !voiceCaptureStarting) startAccurateVoiceCapture();
                }, 120);
                return;
            }
            if (speechRecognition || speechRecognitionStartPending) {
                accurateVoiceMode = true;
                cancelSpeechRecognitionRestart();
                if (speechRecognition) {
                    try { speechRecognition.abort(); } catch (error) {}
                    speechRecognition = null;
                }
            }
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                clearTimeout(recordingTimer);
                mediaRecorder.stop();
                return;
            }
            // Use the browser's streaming speech recognizer first. It provides
            // an immediate transcript even when the remote audio transcription
            // service is slow or temporarily unreachable.
            if (startBrowserSpeechRecognition()) return;
            // Fall back to recorded-audio transcription on browsers that do
            // not expose SpeechRecognition.
            if (await startAccurateVoiceCapture()) return;
            if (!window.isSecureContext || !navigator.mediaDevices?.getUserMedia || !window.MediaRecorder) {
                browserSpeechFallback();
                return;
            }
            try {
                const stream = await navigator.mediaDevices.getUserMedia({audio: true});
                const preferredMime = MediaRecorder.isTypeSupported('audio/webm;codecs=opus') ? 'audio/webm;codecs=opus' : '';
                mediaRecorder = new MediaRecorder(stream, preferredMime ? {mimeType: preferredMime} : undefined);
                audioChunks = [];
                mediaRecorder.ondataavailable = function (event) { if (event.data.size) audioChunks.push(event.data); };
                mediaRecorder.onstop = function () {
                    micBtn.classList.remove('listening');
                    setMicStatus('Processing…', 'processing');
                    stream.getTracks().forEach(function (track) { track.stop(); });
                    if (audioChunks.length) uploadRecordedAudio(new Blob(audioChunks, {type: mediaRecorder.mimeType || 'audio/webm'}));
                };
                mediaRecorder.start();
                micBtn.classList.add('listening');
                setMicStatus('Listening…', 'listening');
                recordingTimer = setTimeout(function () { if (mediaRecorder?.state === 'recording') mediaRecorder.stop(); }, 12000);
            } catch (error) {
                setMicStatus('Tap mic to allow', 'idle');
            }
        });

        agentStateLabel?.addEventListener('click', function () { micBtn?.click(); });
        agentStateLabel?.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                micBtn?.click();
            }
        });

        function showCart() {
            const typing = appendTyping();
            fetch(cartUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json())
                .then(data => {
                    removeTyping(typing);
                    if (!data.items || !data.items.length) return appendMessage('assistant', 'Your order is empty. Tell me an item and quantity to begin.');
                    let html = '<strong>Your current order</strong><div class="ai-cart-summary">';
                    data.items.forEach(function (item) { html += '<div class="ai-cart-row"><span>' + escapeHtml(item.name) + ' × ' + escapeHtml(item.qty) + '</span><span>₹' + Number(item.total).toFixed(2) + '</span></div>'; });
                    html += '<div class="ai-cart-row ai-cart-total"><span>Total</span><span>₹' + Number(data.total).toFixed(2) + '</span></div></div><div class="ai-cart-actions"><button type="button" class="ai-cart-btn primary" data-action="checkout">Checkout</button></div>';
                    appendMessage('assistant', html);
                })
                .catch(function () { removeTyping(typing); appendMessage('assistant', 'I could not load your order. Please try again.'); });
        }

        function money(value) { return '₹' + Number(value || 0).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}); }

        function renderLiveOrderList() {
            aiDebug('Loading live order list', {conversationId: conversationId, stage: activeOrderingStage});
            return fetch(cartUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json()).then(function (data) {
                    aiDebug('Live order list loaded', {items: data.items, total: data.total, count: data.count});
                    const items = data.items || [];
                    if (!items.length) {
                        liveOrderMessage?.remove();
                        liveOrderMessage = null;
                        orderDock?.classList.add('visible');
                        if (orderDock) orderDock.hidden = false;
                        if (orderDockCount) orderDockCount.textContent = '(0)';
                        if (orderDockTotal) orderDockTotal.textContent = money(0);
                        if (livePreview) livePreview.innerHTML = '<span class="ai-live-preview-empty">Your order will appear here<br>as you speak.</span>';
                        previousLiveOrderRows = new Map();
                        return;
                    }
                    const itemCount = Number(data.count) || items.reduce(function (sum, item) { return sum + Number(item.qty || 0); }, 0);
                    if (orderDock) {
                        orderDock.hidden = false;
                        orderDock.classList.add('visible');
                    }
                    if (orderDockCount) orderDockCount.textContent = '(' + itemCount + ')';
                    if (orderDockTotal) orderDockTotal.textContent = money(data.total);
                    if (livePreview) {
                        let newestChangedKey = '';
                        const nextRows = new Map();
                        livePreview.innerHTML = items.map(function (item) {
                            const rowKey = String(item.cart_id || item.product_id || item.name);
                            const signature = [item.qty, item.unit, item.total].join('|');
                            const changed = previousLiveOrderRows.get(rowKey) !== signature;
                            if (changed) newestChangedKey = rowKey;
                            nextRows.set(rowKey, signature);
                            const quantityControl = liveOrderEditable
                                ? '<span class="ai-qty-control"><button type="button" class="ai-qty-btn" data-live-qty="-1" data-cart-id="' + escapeHtml(item.cart_id) + '" data-current-qty="' + escapeHtml(item.qty) + '" aria-label="Decrease quantity">&minus;</button><span class="ai-qty-value">' + escapeHtml(item.qty) + '</span><button type="button" class="ai-qty-btn" data-live-qty="1" data-cart-id="' + escapeHtml(item.cart_id) + '" data-current-qty="' + escapeHtml(item.qty) + '" aria-label="Increase quantity">+</button></span>'
                                : '<span class="ai-live-preview-qty">' + escapeHtml(item.qty) + ' &times; ' + escapeHtml(item.unit || 'unit') + '</span>';
                            return '<span class="ai-live-preview-row' + (changed ? ' is-new' : '') + (liveOrderEditable ? ' has-controls' : '') + '" data-live-preview-key="' + escapeHtml(rowKey) + '">'
                                + '<span class="ai-live-preview-name">' + escapeHtml(item.name) + '</span>'
                                + quantityControl
                                + '<span class="ai-live-preview-price">' + money(item.total) + '</span></span>';
                        }).join('');
                        previousLiveOrderRows = nextRows;
                        if (newestChangedKey) {
                            const changedRow = Array.from(livePreview.querySelectorAll('[data-live-preview-key]')).find(function (row) {
                                return row.dataset.livePreviewKey === newestChangedKey;
                            });
                            if (changedRow) {
                                livePreview.scrollTo({
                                    top: Math.max(0, changedRow.offsetTop - livePreview.clientHeight + changedRow.offsetHeight),
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                    // Bottom Live Order is the single cart summary. Clean up
                    // any duplicate produced by an older cached script, then
                    // stop before the legacy chat-card renderer.
                    liveOrderMessage?.remove();
                    liveOrderMessage = null;
                    chat.querySelectorAll('.ai-live-order-message').forEach(function (message) { message.remove(); });
                    fetch(assistantCartSnapshotUrl, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                        body: JSON.stringify({conversation_id: conversationId})
                    }).catch(function () {});
                    return;

                    let html = '<strong>Live Order List</strong><div class="ai-cart-summary">';
                    items.forEach(function (item) {
                        const image = item.image
                            ? '<img class="ai-live-order-image" src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '">'
                            : '<div class="ai-live-order-image"></div>';
                        const quantityDisplay = liveOrderEditable
                            ? '<div class="ai-qty-control"><button type="button" class="ai-qty-btn" data-live-qty="-1" data-cart-id="' + escapeHtml(item.cart_id) + '" data-current-qty="' + escapeHtml(item.qty) + '">−</button><span class="ai-qty-value">' + escapeHtml(item.qty) + '</span><button type="button" class="ai-qty-btn" data-live-qty="1" data-cart-id="' + escapeHtml(item.cart_id) + '" data-current-qty="' + escapeHtml(item.qty) + '">+</button></div>'
                            : '<div class="ai-live-order-meta">Quantity: ' + escapeHtml(item.qty) + ' ' + escapeHtml(item.unit || 'unit') + '</div>';
                        html += '<div class="ai-live-order-item">' + image
                            + '<div><div class="ai-live-order-name">' + escapeHtml(item.name) + '</div>' + quantityDisplay + '</div>'
                            + '<div class="ai-live-order-price">' + money(item.total) + '</div></div>';
                    });
                    html += '<div class="ai-cart-row ai-cart-total"><span>Total</span><span>' + money(data.total) + '</span></div></div>';
                    if (!liveOrderMessage || !liveOrderMessage.isConnected) {
                        liveOrderMessage = appendMessage('assistant', html);
                        liveOrderMessage.classList.add('ai-live-order-message');
                    } else {
                        liveOrderMessage.querySelector('.ai-message').innerHTML = html;
                        chat.appendChild(liveOrderMessage);
                    }
                    chat.parentElement.scrollTop = chat.parentElement.scrollHeight;
                    fetch(assistantCartSnapshotUrl, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                        body: JSON.stringify({conversation_id: conversationId})
                    }).catch(function () {});
                }).catch(function () {});
        }

        function cartSuggestionChip(product) {
            const productId = Number(product.id || 0);
            const price = Number(product.price || 0);
            if (!Number.isInteger(productId) || productId <= 0 || !Number.isFinite(price) || price <= 0) return '';
            const name = String(product.name || 'Suggested product');
            return '<button type="button" class="ai-cart-chip" data-cart-suggestion-add="true" data-add-product="' + escapeHtml(productId) + '" data-qty="1" data-price="' + escapeHtml(price) + '" data-workflow-stage="anything_else" data-suggestion-source="cart" aria-label="Add ' + escapeHtml(name) + ' to order">+ Add ' + escapeHtml(name) + '</button>';
        }

        function openCartPanel() {
            const requestVersion = ++cartPanelRequestVersion;
            openAccessiblePanel(cartPanel);
            cartPanelBody.innerHTML = '<div class="ai-history-empty">Loading your order…</div>';
            fetch(cartUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                .then(response => response.json()).then(function (data) {
                    if (requestVersion !== cartPanelRequestVersion || !cartPanel.classList.contains('open')) return;
                    const items = data.items || [];
                    cartPanelCount.textContent = '(' + items.length + ' ' + (items.length === 1 ? 'item' : 'items') + ')';
                    cartClear.hidden = !items.length;
                    cartReview.disabled = !items.length;
                    if (!items.length) {
                        cartPanelBody.innerHTML = '<div class="ai-history-empty"><strong>Your cart is empty</strong><br>Ask the AI assistant to add a product.<br><br><button type="button" class="ai-cart-add-more" data-cart-close>＋ Add items via AI</button></div>';
                        cartShortcut.hidden = true;
                        return;
                    }
                    let html = items.map(function (item) {
                        const image = item.image ? '<img class="ai-cart-panel-image" src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '">' : '<div class="ai-cart-panel-image"></div>';
                        return '<div class="ai-cart-panel-item">' + image + '<div class="ai-cart-panel-info"><div class="ai-cart-panel-name">' + escapeHtml(item.name) + '</div><div class="ai-cart-panel-meta">' + escapeHtml(item.qty) + ' × ' + escapeHtml(item.unit || 'unit') + '</div><div class="ai-cart-panel-meta">' + money(item.price) + ' / ' + escapeHtml(item.unit || 'unit') + '</div><div class="ai-cart-panel-price">' + money(item.total) + '</div></div><button class="ai-cart-remove" type="button" data-remove-cart="' + escapeHtml(item.cart_id) + '" aria-label="Remove ' + escapeHtml(item.name) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"/></svg></button></div>';
                    }).join('');
                    html += '<button type="button" class="ai-cart-add-more" data-cart-close>＋ Add more items via AI</button>';
                    html += '<div class="ai-cart-bill"><div class="ai-cart-bill-row"><span>Items (' + items.length + ')</span><span>' + money(data.subtotal) + '</span></div><div class="ai-cart-bill-row"><span>Estimated GST</span><span>' + money(data.gst) + '</span></div><div class="ai-cart-bill-row"><span>Delivery charges</span><span>Calculated at checkout</span></div><div class="ai-cart-bill-row total"><span>Estimated Total</span><strong>' + money(data.total) + '</strong></div></div>';
                    // Adding a product while address, slot, or payment is
                    // pending must not overwrite that state. Show quick-add
                    // suggestions only in the normal shopping stage.
                    const showQuickSuggestions = !activeOrderingStage || activeOrderingStage === 'anything_else';
                    if (showQuickSuggestions) {
                        html += '<div class="ai-cart-suggestions"><strong>✦ AI Smart Suggestions</strong><div class="ai-cart-chips" id="aiCartSuggestions"><span class="ai-product-meta">Loading suggestions…</span></div></div>';
                    }
                    cartPanelBody.innerHTML = html;
                    if (!showQuickSuggestions) return;
                    fetch(productsUrl, {headers: {'X-Requested-With': 'XMLHttpRequest'}}).then(response => response.json()).then(function (result) {
                        if (requestVersion !== cartPanelRequestVersion || !cartPanel.classList.contains('open')) return;
                        const existing = new Set(items.map(item => Number(item.product_id)));
                        const suggestions = (result.products || []).filter(function (item) {
                            return !existing.has(Number(item.id)) && Number(item.id) > 0 && Number(item.price) > 0;
                        }).slice(0, 5);
                        const area = cartPanelBody.querySelector('#aiCartSuggestions');
                        const chips = suggestions.map(cartSuggestionChip).filter(Boolean);
                        if (area) area.innerHTML = chips.length ? chips.join('') : '<span class="ai-product-meta">Ask AI for more products</span>';
                    }).catch(function () {
                        if (requestVersion !== cartPanelRequestVersion) return;
                        const area = cartPanelBody.querySelector('#aiCartSuggestions');
                        if (area) area.innerHTML = '<span class="ai-product-meta">Suggestions could not load right now.</span>';
                    });
                }).catch(function () { cartPanelBody.innerHTML = '<div class="ai-history-empty">Could not load your cart.</div>'; });
        }

        orderDock?.addEventListener('click', function (event) {
            // Agent actions are part of the Live Order dock. Their own click
            // handlers must run without opening the separate cart panel.
            if (event.target.closest('.ai-live-preview button, .ai-order-interactions button, .ai-order-interactions a, [data-delivery-option], [data-payment-option], [data-place-ai-order]')) return;
            openCartPanel();
        });
        livePreview?.addEventListener('click', function (event) {
            const button = event.target.closest('[data-live-qty]');
            if (!button || button.disabled) return;
            const current = Number(button.dataset.currentQty || 1);
            const quantity = Math.max(1, current + Number(button.dataset.liveQty));
            livePreview.querySelectorAll('[data-cart-id]').forEach(function (control) {
                if (String(control.dataset.cartId) === String(button.dataset.cartId)) control.disabled = true;
            });
            fetch(assistantCartQuantityBaseUrl + '/' + encodeURIComponent(button.dataset.cartId) + '/quantity', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({quantity: quantity})
            }).then(function (response) {
                if (!response.ok) throw new Error('Quantity update failed');
                return response.json();
            }).then(renderLiveOrderList).catch(function () {
                livePreview.querySelectorAll('[data-cart-id]').forEach(function (control) { control.disabled = false; });
            });
        });
        orderDock?.addEventListener('keydown', function (event) {
            if ((event.key === 'Enter' || event.key === ' ') && !event.target.closest('button')) {
                event.preventDefault();
                openCartPanel();
            }
        });

        orderCheckout?.addEventListener('click', function (event) {
            const delivery = event.target.closest('[data-delivery-option]');
            if (delivery) {
                if (delivery.disabled || delivery.dataset.deliverySelectionPending === 'true') return;
                const choices = Array.from(orderCheckout.querySelectorAll('[data-delivery-option]'));
                choices.forEach(function (choice) {
                    choice.disabled = true;
                    choice.dataset.deliverySelectionPending = 'true';
                    choice.setAttribute('aria-busy', 'true');
                });
                sendMessage(delivery.dataset.deliveryOption);
                return;
            }
            const payment = event.target.closest('[data-payment-option]');
            if (payment) {
                const labels = {online: 'Pay Online', pay_on_delivery: 'Pay on Delivery', credit: 'Pay on Credit'};
                sendMessage(labels[payment.dataset.paymentOption] || payment.dataset.paymentOption);
                return;
            }
            const placeOrder = event.target.closest('[data-place-ai-order]');
            if (placeOrder) {
                continuousTalkMode = false;
                cancelSpeechRecognitionRestart();
                if (speechRecognition) {
                    try { speechRecognition.abort(); } catch (error) {}
                    speechRecognition = null;
                }
                micBtn?.classList.remove('listening');
                setMicStatus('Mic off', 'idle');
                placeOrder.disabled = true;
                placeOrderInsideAssistant(placeOrder.dataset.placeAiOrder);
            }
        });

        cartBack?.addEventListener('click', function () { cartPanelRequestVersion++; closeAccessiblePanel(cartPanel, orderDock); });
        cartReview?.addEventListener('click', function () {
            if (cartReview.disabled) return;
            cartPanelRequestVersion++;
            closeAccessiblePanel(cartPanel, input);
            appendMessage('assistant', 'Order complete karne ke liye voice ya message mein boliye: bas itna hi. Phir main location, slot aur payment yahin confirm karunga.');
            input.focus();
        });
        cartClear?.addEventListener('click', function () {
            if (!window.confirm('Remove all items from this order?')) return;
            setAgentUiState('executing', 'Clearing your live order…');
            fetch(assistantCartBaseUrl, {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({confirmed: true})
            }).then(function (response) {
                return assistantJsonResponse(response, 'Could not clear your order.');
            }).then(function () {
                setAgentUiState('ready', 'Your live order is empty.');
                renderLiveOrderList();
                openCartPanel();
            }).catch(function (error) {
                setAgentUiState('error', error.message || 'Your live order was not changed.');
            });
            if (orderCheckout) {
                orderCheckout.innerHTML = '';
                orderCheckout.classList.remove('visible');
            }
        });
        cartPanelBody?.addEventListener('click', function (event) {
            const remove = event.target.closest('[data-remove-cart]');
            if (remove) {
                remove.disabled = true;
                fetch(assistantCartBaseUrl + '/' + encodeURIComponent(remove.dataset.removeCart), {method: 'DELETE', headers: {'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}}).then(openCartPanel);
                return;
            }
            const suggestion = event.target.closest('[data-cart-suggestion-add]');
            if (suggestion) {
                addAssistantProductCard(suggestion);
                return;
            }
            if (event.target.closest('[data-cart-close]')) {
                cartPanelRequestVersion++;
                closeAccessiblePanel(cartPanel, input);
            }
        });

        function showReorderDelivery(workflow) {
            if (!workflow || workflow.stage !== 'delivery_details') {
                aiDebug('Reorder delivery workflow missing', {workflow: workflow});
                return;
            }
            activeOrderingStage = 'delivery_details';
            previousOrdersVisible = false;
            cartShortcut.hidden = false;
            selectedDeliveryDetails = '';
            activeOrderingProductId = null;
            activeClarificationOptions = [];
            activeCandidateSetId = '';
            renderLiveOrderList();
            const reply = workflow.reply || 'Saved address aur delivery slot confirm kijiye.';
            appendMessage('assistant', escapeHtml(reply));
            renderAssistantDeliveryOptions(workflow);
            loadVoiceAsync(reply);
            aiDebug('Reorder delivery shown', {locations: workflow.locations || [], slots: workflow.slots || []});
        }

        function assistantJsonResponse(response, fallbackMessage) {
            return response.json().catch(function () { return {}; }).then(function (data) {
                data = data && typeof data === 'object' ? data : {};
                if (!response.ok) throw new Error(data.message || fallbackMessage);
                return data;
            });
        }

        // Product cards carry their own originating stage.  Using only the
        // mutable global stage made a delayed tap on a recommendation look
        // like a normal product add, so the server never advanced from the
        // suggestion/review flow.
        function addAssistantProductCard(button) {
            const productId = Number(button.dataset.addProduct || 0);
            const quantity = Math.max(1, Math.round(Number(button.dataset.qty) || 1));
            const selectionStage = String(button.dataset.workflowStage || activeOrderingStage || '');
            const isOrderSuggestion = button.dataset.suggestionSource === 'order' || selectionStage === 'order_suggestions';
            const keepCartOpen = button.dataset.suggestionSource === 'cart';
            const sourceMessage = keepCartOpen ? null : button.closest('.ai-message-row');
            const compactCard = button.classList.contains('ai-suggestion-card');
            const originalLabel = button.textContent;
            let cartAdded = false;

            if (!Number.isInteger(productId) || productId <= 0) {
                appendMessage('assistant', '<strong>This suggested product is unavailable. Please choose another item.</strong>');
                return;
            }
            if (button.disabled || button.dataset.addPending === 'true') return;

            aiDebug('Product card action', {productId: productId, quantity: quantity, stage: selectionStage, suggestion: button.dataset.suggestionSource || null});
            button.disabled = true;
            button.dataset.addPending = 'true';
            button.setAttribute('aria-busy', 'true');
            if (!compactCard) button.textContent = 'Adding…';

            setAgentUiState('executing');
            fetch(selectionUrl, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({conversation_id: conversationId, product_id: productId, quantity: quantity, success: true, workflow_stage: selectionStage || null, candidate_set_id: selectionStage === 'clarify_product' ? activeCandidateSetId : null})
            })
                .then(function (response) { return assistantJsonResponse(response, 'Could not add this item. Please try again.'); })
                .then(function (selection) {
                    if (!selection.saved || !selection.cart_result) throw new Error(selection.message || 'Could not add this item. Please try again.');
                    cartAdded = true;
                    return selection;
                })
                .then(function (selection) {
                    const next = selection.workflow || {};
                    const nextStage = String(next.stage || '');
                    button.disabled = false;
                    delete button.dataset.addPending;
                    button.removeAttribute('aria-busy');
                    if (!compactCard) button.textContent = originalLabel;

                    cartShortcut.hidden = false;
                    activeOrderingProductId = null;
                    clarificationMessage = null;
                    if (nextStage === 'delivery_details') {
                        activeOrderingStage = 'delivery_details';
                        liveOrderEditable = false;
                    } else if (nextStage === 'confirm_order' || isOrderSuggestion) {
                        activeOrderingStage = 'confirm_order';
                        liveOrderEditable = true;
                    } else {
                        activeOrderingStage = 'anything_else';
                        liveOrderEditable = false;
                    }

                    // Retire the source cards only after both cart mutation and
                    // workflow persistence succeeded. This avoids a tap that
                    // looks accepted but leaves the customer with no retry UI.
                    sourceMessage?.remove();
                    if (isOrderSuggestion) {
                        chat?.querySelectorAll('[data-order-suggestions]').forEach(function (suggestions) {
                            suggestions.closest('.ai-message-row')?.remove();
                        });
                    }
                    renderLiveOrderList();
                    setAgentUiState('ready');

                    if (nextStage === 'delivery_details') {
                        const reply = next.reply || 'Saved address aur delivery slot confirm kijiye.';
                        renderAssistantDeliveryOptions(next);
                        loadVoiceAsync(reply);
                    } else {
                        loadVoiceAsync(next.reply || selection.message || (isOrderSuggestion
                            ? 'Suggested product add ho gaya. Updated order summary confirm kijiye.'
                            : 'Product add ho gaya. Aur kuch chahiye?'));
                    }
                    if (keepCartOpen) openCartPanel();
                })
                .catch(function (error) {
                    aiDebug('Product card add failed', {productId: productId, cartAdded: cartAdded, error: String(error)});
                    button.disabled = false;
                    delete button.dataset.addPending;
                    button.removeAttribute('aria-busy');
                    if (!compactCard) button.textContent = originalLabel;
                    setAgentUiState('error', error.message || 'Your live order was not changed.');

                    // The cart route can succeed even if the bookkeeping
                    // request times out. Keep the item visible instead of
                    // falsely reporting that it was not added.
                    if (cartAdded) {
                        cartShortcut.hidden = false;
                        activeOrderingStage = isOrderSuggestion ? 'confirm_order' : 'anything_else';
                        liveOrderEditable = isOrderSuggestion;
                        renderLiveOrderList();
                        if (keepCartOpen) openCartPanel();
                        appendMessage('assistant', '<strong>✓ Product added to your order.</strong><div class="ai-product-meta">Order screen refresh nahi hui; please continue from the updated list.</div>');
                        return;
                    }
                    appendMessage('assistant', '<strong>' + escapeHtml(error.message || 'Could not add this item. Please try again.') + '</strong>');
                });
        }

        chat?.addEventListener('click', function (event) {
            const button = event.target.closest('button');
            if (!button) return;
            if (button.dataset.liveQty) {
                const current = Number(button.dataset.currentQty || 1);
                const quantity = Math.max(1, current + Number(button.dataset.liveQty));
                aiDebug('Cart quantity action', {cartId: button.dataset.cartId, from: current, to: quantity});
                button.disabled = true;
                fetch(assistantCartQuantityBaseUrl + '/' + encodeURIComponent(button.dataset.cartId) + '/quantity', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                    body: JSON.stringify({quantity: quantity})
                }).then(function (response) { if (!response.ok) throw new Error('Quantity update failed'); return response.json(); })
                    .then(renderLiveOrderList).catch(function () { button.disabled = false; });
            } else if (button.dataset.qtyChange) {
                const actions = button.closest('.ai-product-actions');
                const value = actions?.querySelector('.ai-qty-value');
                const add = actions?.querySelector('[data-add-product]');
                if (!value || !add) return;
                const next = Math.max(1, Math.min(99999, Number(value.textContent || 1) + Number(button.dataset.qtyChange)));
                value.textContent = next;
                add.dataset.qty = next;
            } else if (button.dataset.reorderQty) {
                const row = button.closest('[data-reorder-item]');
                const value = row?.querySelector('.ai-qty-value');
                if (value) {
                    value.textContent = Math.max(1, Number(value.textContent || 1) + Number(button.dataset.reorderQty));
                    const card = row.closest('[data-reorder-card]');
                    const lineTotal = Number(row.dataset.price || 0) * Number(value.textContent);
                    const lineNode = row.querySelector('[data-line-total]');
                    if (lineNode) lineNode.textContent = money(lineTotal);
                    const total = Array.from(card.querySelectorAll('[data-reorder-item]')).reduce(function (sum, itemRow) { return sum + Number(itemRow.dataset.price || 0) * Number(itemRow.querySelector('.ai-qty-value')?.textContent || 1); }, 0);
                    const totalNode = card.querySelector('[data-reorder-total]');
                    if (totalNode) totalNode.textContent = money(total);
                }
            } else if (button.dataset.reorderOrder) {
                const card = button.closest('[data-reorder-card]');
                const items = Array.from(card.querySelectorAll('[data-reorder-item]')).map(function (row) {
                    return {product_id: Number(row.dataset.productId), quantity: Number(row.querySelector('.ai-qty-value')?.textContent || 1)};
                });
                button.disabled = true;
                fetch(reorderUrl, {method: 'POST', headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'}, body: JSON.stringify({order_id: Number(button.dataset.reorderOrder), items: items, conversation_id: conversationId})})
                    .then(function (response) { if (!response.ok) throw new Error('Reorder failed'); return response.json(); })
                    .then(function (data) {
                        button.disabled = false;
                        aiDebug('Reorder API response', data);
                        if (!data.success) throw new Error(data.message || 'Reorder failed');
                        showReorderDelivery(data.workflow);
                    })
                    .catch(function (error) { button.disabled = false; aiDebug('Reorder failed', {error: String(error)}); appendMessage('assistant', 'Order cart mein add nahi ho paya. Dobara try karein.'); });
            } else if (button.hasAttribute('data-start-previous')) {
                showPreviousOrders();
            } else if (button.hasAttribute('data-start-new')) {
                beginNewOrder();
            } else if (button.dataset.skipOrderSuggestions) {
                const suggestionRow = button.closest('.ai-message-row');
                suggestionRow?.querySelectorAll('button').forEach(function (choice) { choice.disabled = true; });
                dismissOrderSuggestionMessages();
                sendMessage('No, continue delivery');
            } else if (button.dataset.removeProduct) {
                sendMessage(button.dataset.removeProduct + ' remove kar do');
            } else if (button.dataset.chooseProduct) {
                sendMessage(button.dataset.chooseProduct, Number(button.dataset.chooseProductId));
            } else if (button.dataset.catalogueEnquiry) {
                const enquiryActions = button.closest('.ai-product-actions');
                const enquiryCard = enquiryActions?.previousElementSibling?.classList.contains('ai-product-card')
                    ? enquiryActions.previousElementSibling
                    : null;
                const enquiryMessage = button.closest('.ai-message-row');
                button.disabled = true;
                fetch(catalogueEnquiryUrl, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf || '', 'X-Requested-With': 'XMLHttpRequest'},
                    body: JSON.stringify({product_id: Number(button.dataset.catalogueEnquiry)})
                }).then(function (response) {
                    return response.json().then(function (data) { if (!response.ok) throw new Error(data.message || 'Enquiry failed'); return data; });
                }).then(function (data) {
                    if (enquiryCard) enquiryCard.remove();
                    if (enquiryActions) enquiryActions.remove();
                    if (enquiryMessage && !enquiryMessage.querySelector('.ai-product-card, [data-catalogue-enquiry], [data-add-product], [data-choose-product]')) {
                        enquiryMessage.remove();
                    }
                    appendMessage('assistant', escapeHtml(data.message || 'Price-list enquiry customer care ko bhej di hai.'));
                    loadVoiceAsync(data.message || 'Price-list enquiry customer care ko bhej di hai. Ab doosra product bataiye.');
                    activeOrderingStage = 'anything_else';
                }).catch(function (error) {
                    button.disabled = false;
                    appendMessage('assistant', escapeHtml(error.message || 'Enquiry nahi bhej paaya. Dobara try karein.'));
                });
            } else if (button.dataset.addProduct) {
                addAssistantProductCard(button);
            } else if (button.dataset.action === 'change-item') appendMessage('assistant', 'Sure—tell me the item you want instead.');
            else if (button.dataset.deliveryOption) {
                // A slot/location choice is a state-changing request. Lock
                // the complete current choice set until it returns so a
                // double tap cannot submit the same choice twice and race
                // the server-side flow state.
                if (button.disabled || button.dataset.deliverySelectionPending === 'true') return;
                const requestVersion = ++deliveryOptionRequestVersion;
                const choiceButtons = Array.from(chat.querySelectorAll('[data-delivery-option]'));
                choiceButtons.forEach(function (choice) {
                    choice.disabled = true;
                    choice.dataset.deliverySelectionPending = 'true';
                    choice.setAttribute('aria-busy', 'true');
                });
                window.setTimeout(function () {
                    if (requestVersion !== deliveryOptionRequestVersion) return;
                    choiceButtons.forEach(function (choice) {
                        if (!chat.contains(choice)) return;
                        choice.disabled = false;
                        delete choice.dataset.deliverySelectionPending;
                        choice.removeAttribute('aria-busy');
                    });
                }, 12000);
                sendMessage(button.dataset.deliveryOption);
            }
            else if (button.dataset.paymentOption) {
                const labels = {online: 'Pay Online', pay_on_delivery: 'Pay on Delivery', credit: 'Pay on Credit'};
                sendMessage(labels[button.dataset.paymentOption] || button.dataset.paymentOption);
            }
            else if (button.dataset.customerCareCall) {
                const wantsCall = button.dataset.customerCareCall === 'yes';
                if (wantsCall) {
                    // Stop rapid duplicate taps before the request returns.
                    const actions = button.closest('.ai-product-actions');
                    actions?.querySelectorAll('[data-customer-care-call]').forEach(function (choice) {
                        choice.disabled = true;
                        choice.setAttribute('aria-busy', 'true');
                    });
                }
                sendMessage(
                    wantsCall ? 'haan call laga do' : 'nahi yahin continue karo',
                    null,
                    {
                        customerCareDialUrl: button.dataset.customerCareDialUrl || '',
                        customerCareUserGesture: wantsCall
                    }
                );
            }
            else if (button.dataset.placeAiOrder) {
                continuousTalkMode = false;
                cancelSpeechRecognitionRestart();
                if (speechRecognition) {
                    try { speechRecognition.abort(); } catch (error) {}
                    speechRecognition = null;
                }
                if (mediaRecorder?.state === 'recording') {
                    clearTimeout(recordingTimer);
                    audioChunks = [];
                    try { mediaRecorder.stop(); } catch (error) {}
                }
                micBtn?.classList.remove('listening');
                setMicStatus('Mic off', 'idle');
                button.disabled = true;
                placeOrderInsideAssistant(button.dataset.placeAiOrder);
            }
            else if (button.dataset.action === 'checkout') appendMessage('assistant', 'Delivery location aur slot confirm karke payment yahin complete karte hain.');
            else if (button.dataset.conversation) {
                fetch(historyUrl + '?conversation_id=' + encodeURIComponent(button.dataset.conversation), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    .then(response => response.json()).then(function (data) {
                        if (!data.messages) return;
                        chat.innerHTML = '';
                        data.messages.forEach(function (message) { appendMessage(message.role, savedMessageHtml(message), message.time); });
                    });
            }
        });

        document.querySelectorAll('.ai-action-btn[data-action]').forEach(function (button) {
            button.addEventListener('click', function () {
                const action = button.getAttribute('data-action');
                if (action === 'fresh') {
                    conversationId = window.crypto?.randomUUID ? window.crypto.randomUUID() : ('chat-' + Date.now() + '-' + Math.random().toString(36).slice(2));
                    chat.innerHTML = '';
                    liveOrderMessage = null;
                    clarificationMessage = null;
                    activeOrderingStage = null;
                    activeOrderingProductId = null;
                    activeClarificationOptions = [];
                    activeCandidateSetId = '';
                    previousOrdersVisible = false;
                    awaitingNewOrderReady = false;
                    liveOrderEditable = false;
                    selectedDeliveryDetails = '';
                    assistantOrderSubmitting = false;
                    assistantOrderCompleted = false;
                    customerCareDialUrl = '';
                    lastCustomerCareDialAttemptAt = 0;
                    lastCustomerCareDialAttemptUrl = '';
                    setAgentUiState('idle');
                    const freshReply = @json('Hi ' . (auth()->user()->name ?? 'there') . '! I am ready for a new order. Tell me the first item.');
                    loadVoiceAsync(freshReply, startAutoListening);
                    input.focus();
                } else if (action === 'history') {
                    openHistoryList();
                } else {
                    showCart();
                }
            });
        });
        cartShortcut?.addEventListener('click', openCartPanel);
        renderLiveOrderList().then(function () {
            cartShortcut.hidden = orderDock?.hidden !== false;
        });
    });
</script>
@endsection
