<div class="mobile-footer">
    <a href="{{ route('web.home') }}" class="footer-item {{ request()->routeIs('web.home') ? 'active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Home</span>
    </a>
    <a href="{{ route('web.order.tracker') }}" class="footer-item {{ request()->routeIs('web.order.tracker') ? 'active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        <span>Orders</span>
    </a>
    <a href="{{ route('web.price.list') }}" class="footer-item {{ request()->routeIs('offers.*') ? 'active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L13.42 20.58a2 2 0 0 1-2.83 0L2.59 12.6a2 2 0 0 1-.59-1.41V4a2 2 0 0 1 2-2h7.17a2 2 0 0 1 1.41.59l8.01 8.01a2 2 0 0 1 0 2.81z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
        <span>Offers</span>
    </a>
  <a href="{{ route('web.price.list') }}" class="footer-item {{ request()->routeIs('web.price.list') ? 'active' : '' }}">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        <span>Price List</span>
    </a>
    <a href="{{ route('web.ai.assistant') }}" class="footer-item footer-ai-open footer-ai-btn">
        <span class="footer-ai-icon">
            <img src="{{ asset('assets/ai-assistant-loop.gif') }}" alt="AI assistant" class="footer-ai-gif">
        </span>
        <span class="footer-ai-label">AI Assist</span>
    </a>
</div>

@php
    $assistantUserName = optional(auth()->user())->name ?? 'Customer';
    $assistantOutletName = optional(\App\Models\User::find(optional(auth()->user())->selected_outlet_id))->outlet_name ?? 'your selected outlet';
@endphp
<div id="mobileAiMetaData"
     data-user-name="{{ $assistantUserName }}"
     data-outlet-name="{{ $assistantOutletName }}"></div>

<style>
.mobile-footer {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: #fff; border-top: 1px solid #eef0f3;
    display: flex; justify-content: space-around; align-items: center;
    padding: 10px 0 max(10px, env(safe-area-inset-bottom));
    z-index: 500;
}
.footer-item {
    display: flex; flex-direction: column; align-items: center; gap: 3px;
    color: #98a2b3; text-decoration: none; font-size: 11px; font-weight: 600;
}
.footer-item.active { color: #4f5fff; }
    .footer-ai-btn {
        padding: 0;
        min-width: auto;
        background: transparent;
        color: #6C2BD9;
        border: none;
        box-shadow: none;
        gap: 4px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: transform 160ms ease, opacity 160ms ease;
        animation: footer-ai-button-pulse 4s ease-in-out infinite;
    }
    .footer-ai-btn:hover {
        transform: translateY(-1px) scale(1.02);
        opacity: 0.95;
    }
    .footer-ai-icon {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    .footer-ai-icon img.footer-ai-gif {
        width: 28px;
        height: 28px;
        display: block;
        object-fit: contain;
    }
    .footer-ai-label {
        display: block;
        margin-top: 2px;
        font-size: 8.5px;
        font-weight: 600;
        color: #6C2BD9;
        line-height: 1.1;
    }
    .footer-ai-icon::before,
    .footer-ai-icon::after,
    .ai-ring,
    .ai-star,
    .ai-label-ai,
    .ai-orbit-dot,
    .ai-floating-particle {
        display: none;
    }
    .ai-ring {
        transform-origin: center center;
        animation: ai-ring-scale 4s ease-in-out infinite;
    }
    .ai-star {
        transform-origin: center center;
        animation: ai-twinkle 4s ease-in-out infinite;
    }
    .ai-star-1 {
        animation-delay: 0s;
    }
    .ai-star-2 {
        animation-delay: 1.1s;
    }
    .ai-label-ai {
        animation: ai-text-pulse 4s ease-in-out infinite;
        filter: drop-shadow(0 0 0 rgba(255,255,255,0));
    }
    .ai-orbit-dot {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(167, 85, 247, 0.95);
        box-shadow: 0 0 12px rgba(167, 85, 247, 0.35);
        transform: translate(-50%, -50%) translateX(20px);
        z-index: 4;
        animation: ai-orbit 4s linear infinite;
        pointer-events: none;
    }
    .ai-floating-particle {
        position: absolute;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: rgba(167, 85, 247, 0.85);
        box-shadow: 0 0 12px rgba(167, 85, 247, 0.24);
        opacity: 0.16;
        pointer-events: none;
        animation: ai-floating 4s ease-in-out infinite;
    }
    .particle-1 { top: -4px; left: 50%; transform: translateX(-50%); animation-delay: 0s; }
    .particle-2 { top: 50%; right: -6px; transform: translateY(-50%); animation-delay: 1.2s; }
    .particle-3 { bottom: -4px; left: 34%; animation-delay: 2.4s; }
    .footer-ai-label {
        margin-top: 0;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
        color: #6C2BD9;
    }
    .footer-ai-btn .footer-ai-label {
        display: block;
    }
    @keyframes ai-ring-scale {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.03); }
    }
    @keyframes ai-twinkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        40%, 60% { opacity: 0.82; transform: scale(1.12); }
    }
    @keyframes ai-text-pulse {
        0%, 100% { filter: drop-shadow(0 0 0 rgba(255,255,255,0)); }
        45%, 55% { filter: drop-shadow(0 0 12px rgba(167, 85, 247, 0.28)); }
    }
    @keyframes ai-orbit {
        0% { transform: translate(-50%, -50%) translateX(20px) rotate(0deg); }
        100% { transform: translate(-50%, -50%) translateX(20px) rotate(360deg); }
    }
    @keyframes ai-floating {
        0%, 100% { opacity: 0.16; transform: translateY(0) scale(1); }
        50% { opacity: 0.08; transform: translateY(-2px) scale(1.14); }
    }
    @keyframes ai-shimmer {
        0%, 18%, 100% { opacity: 0; transform: translateX(-18%) scale(0.92); }
        45%, 55% { opacity: 0.6; transform: translateX(16%) scale(1.05); }
        80% { opacity: 0; transform: translateX(40%) scale(1.08); }
    }
    @keyframes footer-ai-button-pulse {
        0%, 100% {
            transform: translateY(0) scale(1);
        }
        50% {
            transform: translateY(-1px) scale(1.02);
        }
    }
    .mobile-ai-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .22s ease, visibility .22s ease;
        z-index: 900;
    }
    .mobile-ai-overlay.open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        display: block;
        backdrop-filter: blur(2px);
    }
    .mobile-ai-panel {
        position: fixed;
        inset: 0;
        display: none;
        justify-content: center;
        align-items: flex-end;
        z-index: 910;
        padding: 16px;
        font-family: 'Inter', sans-serif;
    }
    .mobile-ai-panel.open {
        display: flex;
    }
    .mobile-ai-shell {
        width: min(100%, 560px);
        max-height: min(100vh, 840px);
        margin: auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 28px 80px rgba(15, 23, 42, 0.2);
        overflow: hidden;
        transform: translateY(110%);
        transition: transform .32s cubic-bezier(0.22, 1, 0.36, 1);
        display: flex;
        flex-direction: column;
    }
    .mobile-ai-panel.open .mobile-ai-shell {
        transform: translateY(0);
    }
    .mobile-ai-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 20px 16px;
        border-bottom: 1px solid #eef2ff;
        background: linear-gradient(180deg, #f8faff 0%, #ffffff 100%);
    }
    .mobile-ai-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .mobile-ai-avatar {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: linear-gradient(180deg, #4750ff 0%, #7b93ff 100%);
        display: grid;
        place-items: center;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }
    .mobile-ai-title {
        font-size: 16px;
        font-weight: 800;
        color: #101828;
    }
    .mobile-ai-status {
        font-size: 12px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mobile-ai-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
    }
    .mobile-ai-close {
        width: 40px;
        height: 40px;
        border: none;
        background: #eef4ff;
        color: #334155;
        border-radius: 14px;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: transform .2s ease, background .2s ease;
    }
    .mobile-ai-close:hover {
        background: #dbe9ff;
        transform: scale(1.05);
    }
    .mobile-ai-intro {
        padding: 16px 20px 0;
    }
    .mobile-ai-subtitle {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
    }
    .mobile-ai-caption {
        margin-top: 6px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .mobile-ai-chat {
        flex: 1;
        overflow-y: auto;
        padding: 0 20px 12px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .mobile-ai-message {
        max-width: 100%;
        display: inline-flex;
        flex-direction: column;
        gap: 8px;
        animation: fadeIn .24s ease;
    }
    .mobile-ai-message.assistant {
        align-self: flex-start;
        background: #f8fafc;
        padding: 16px;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 0 0 1px rgba(238, 249, 255, 0.8);
    }
    .mobile-ai-message.user {
        align-self: flex-end;
        background: linear-gradient(135deg, #4f5fff 0%, #7c88ff 100%);
        color: #fff;
        padding: 16px;
        border-radius: 24px;
        box-shadow: 0 8px 24px rgba(79, 95, 255, 0.18);
    }
    .mobile-ai-message strong {
        font-size: 13px;
        font-weight: 700;
        color: inherit;
    }
    .mobile-ai-message strong {
        display: block;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .mobile-ai-typing {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mobile-ai-dot {
        width: 8px;
        height: 8px;
        background: #475569;
        border-radius: 50%;
        opacity: 0;
        animation: blink 1.4s infinite ease-in-out;
    }
    .mobile-ai-dot:nth-child(2) { animation-delay: 0.2s; }
    .mobile-ai-dot:nth-child(3) { animation-delay: 0.4s; }
    .mobile-ai-actions {
        display: flex;
        gap: 8px;
        padding: 0 20px 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scroll-snap-type: x mandatory;
        white-space: nowrap;
    }
    .mobile-ai-actions::-webkit-scrollbar {
        height: 8px;
    }
    .mobile-ai-actions::-webkit-scrollbar-thumb {
        background: rgba(79, 95, 255, 0.24);
        border-radius: 999px;
    }
    .mobile-ai-quick-btn {
        border: 1px solid #e5e7f0;
        background: #ffffff;
        color: #102a43;
        padding: 10px 14px;
        min-width: 92px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
        text-align: center;
        flex-shrink: 0;
        scroll-snap-align: start;
        cursor: pointer;
        transition: transform .2s ease, border-color .2s ease, background .2s ease;
    }
    .mobile-ai-quick-btn:hover {
        border-color: #4f5fff;
        background: #f8faff;
        transform: translateY(-1px);
        color: #4f5fff;
    }
    .mobile-ai-composer {
        padding: 14px 20px 20px;
        border-top: 1px solid #eef2ff;
        background: #fbfdff;
    }
    .mobile-ai-input-row {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .mobile-ai-input {
        flex: 1;
        min-width: 0;
        border: 1px solid #d9e2ec;
        border-radius: 16px;
        padding: 14px 16px;
        font-size: 14px;
        color: #0f172a;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .mobile-ai-input:focus {
        outline: none;
        border-color: #4f5fff;
        box-shadow: 0 0 0 3px rgba(79, 95, 255, 0.12);
    }
    .mobile-ai-send-btn,
    .mobile-ai-icon-btn {
        border: none;
        background: #4f5fff;
        color: #fff;
        border-radius: 16px;
        padding: 14px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform .2s ease, background .2s ease;
    }
    .mobile-ai-send-btn:hover,
    .mobile-ai-icon-btn:hover {
        transform: translateY(-1px);
        background: #3b4dff;
    }
    .mobile-ai-icon-btn {
        background: #eef4ff;
        color: #4f5fff;
        width: 50px;
        height: 50px;
        padding: 0;
    }
    .mobile-ai-support-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 10px;
        font-size: 12px;
        color: #64748b;
    }
    .mobile-ai-language-select {
        border: 1px solid #d9e2ec;
        border-radius: 14px;
        padding: 10px 12px;
        background: #fff;
        color: #0f172a;
        font-size: 12px;
        transition: border-color .2s ease;
    }
    .mobile-ai-language-select:focus {
        outline: none;
        border-color: #4f5fff;
    }
    .mobile-ai-product-card,
    .mobile-ai-product-option {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 12px;
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .mobile-ai-product-image {
        width: 58px;
        height: 58px;
        object-fit: cover;
        border-radius: 14px;
    }
    .mobile-ai-product-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: #101828;
    }
    .mobile-ai-product-meta {
        font-size: 12px;
        color: #667085;
    }
    .mobile-ai-product-actions,
    .mobile-ai-cart-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .mobile-ai-product-btn,
    .mobile-ai-cart-btn {
        border: 1px solid #e4e7ec;
        background: #fff;
        color: #101828;
        border-radius: 14px;
        padding: 10px 14px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
    }
    .mobile-ai-product-btn-primary,
    .mobile-ai-cart-btn-primary {
        background: #4f5fff;
        color: #fff;
        border-color: transparent;
    }
    .mobile-ai-cart-summary {
        margin-top: 12px;
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
        display: grid;
        gap: 10px;
    }
    .mobile-ai-cart-item,
    .mobile-ai-cart-total {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
    }
    .mobile-ai-cart-total {
        font-weight: 700;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px);} to { opacity: 1; transform: translateY(0);} }
    @keyframes blink { 0%, 80%, 100% { opacity: 0; } 40% { opacity: 1; } }
    @media (min-width: 768px) {
        .mobile-ai-panel { align-items: center; }
        .mobile-ai-shell { border-radius: 24px; width: 520px; max-height: 90vh; }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openButton = document.querySelector('.footer-ai-open');
        const overlay = document.getElementById('mobileAiOverlay');
        const panel = document.getElementById('mobileAiPanel');
        const closeButton = document.getElementById('mobileAiClose');
        const sendButton = document.getElementById('mobileAiSend');
        const inputField = document.getElementById('mobileAiInput');
        const micButton = document.getElementById('mobileAiMic');
        const chatContainer = document.getElementById('mobileAiChat');
        const quickActions = document.getElementById('mobileAiQuickActions');
        const languageSelect = document.getElementById('mobileAiLanguage');
        const assistantProductsUrl = '{{ route('assistant.products') }}';
        const assistantCartUrl = '{{ route('assistant.cart') }}';
        const cartAddUrl = '{{ route('cart.add') }}';
        const checkoutUrl = '{{ route('web.chekout') }}';
        const assistantMeta = document.getElementById('mobileAiMetaData');
        const assistantUserName = assistantMeta?.dataset.userName || 'Customer';
        const assistantOutletName = assistantMeta?.dataset.outletName || 'your selected outlet';
        let assistantOpened = false;
        let assistantState = 'idle';
        let assistantVoice = null;
        let recognition = null;
        let listening = false;

        const commonCommands = {
            cart: ['cart', 'my cart', "what's in my cart", 'what is in my cart', 'cart details'],
            checkout: ['checkout', 'go to cart', 'go to checkout', 'place order', 'ready to checkout'],
            offers: ['offers', 'show offers', 'today offers', 'aaj ke offers', 'आज के offers'],
            delivery: ['delivery', 'slot', 'time slot', 'delivery slot', 'delivery window'],
            payment: ['payment', 'terms', 'due', 'credit', 'net 30', 'prepaid'],
            newOrder: ['new order', 'fresh order', 'start order', 'place new order', 'order now']
        };

        function openAssistant() {
            overlay.classList.add('open');
            overlay.style.display = 'block';
            panel.classList.add('open');
            panel.style.display = 'flex';
            panel.setAttribute('aria-hidden', 'false');
            if (!assistantOpened) {
                assistantOpened = true;
                assistantState = 'ready';
                appendAssistantMessage('Hello ' + escapeHtml(assistantUserName) + '! 👋<br>I’m your AI grocery assistant for <strong>' + escapeHtml(assistantOutletName) + '</strong>.');
                appendAssistantMessage('Tell me what you want to order, or ask for delivery slots, payment terms, or your current cart.');
            }
        }

        function closeAssistant() {
            overlay.classList.remove('open');
            overlay.style.display = 'none';
            panel.classList.remove('open');
            panel.style.display = 'none';
            panel.setAttribute('aria-hidden', 'true');
            stopVoice();
        }

        function appendMessage(role, html) {
            const message = document.createElement('div');
            message.className = 'mobile-ai-message ' + role;
            message.innerHTML = html;
            chatContainer.appendChild(message);
            scrollChat();
            return message;
        }

        function appendAssistantMessage(html, speak = true) {
            appendMessage('assistant', html);
            if (speak) {
                speakText(stripHtml(html));
            }
        }

        function appendUserMessage(text) {
            appendMessage('user', escapeHtml(text));
        }

        function appendTypingIndicator() {
            const wrapper = document.createElement('div');
            wrapper.className = 'mobile-ai-message assistant';
            wrapper.innerHTML = '<div class="mobile-ai-typing"><span class="mobile-ai-dot"></span><span class="mobile-ai-dot"></span><span class="mobile-ai-dot"></span></div>';
            chatContainer.appendChild(wrapper);
            scrollChat();
            return wrapper;
        }

        function scrollChat() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function escapeHtml(text) {
            return String(text).replace(/[&<>\"]+/g, function(match) {
                return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[match];
            });
        }

        function stripHtml(html) {
            const container = document.createElement('div');
            container.innerHTML = html;
            return container.textContent || container.innerText || '';
        }

        function speakText(text) {
            if (!text || !('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            if (assistantVoice) {
                utterance.voice = assistantVoice;
            }
            utterance.rate = 1;
            utterance.pitch = 1.05;
            utterance.volume = 1;
            window.speechSynthesis.speak(utterance);
        }

        function selectAssistantVoice() {
            if (!('speechSynthesis' in window)) return;
            const voices = window.speechSynthesis.getVoices();
            const preferred = voices.find(v => /female|woman|zira|amy|samantha|alloy|ines|kajal|ayanami|google.*en-?in/i.test(v.name))
                || voices.find(v => /en-?in|en-?gb|en-?us/i.test(v.lang) && /female|woman|zira|amy|samantha|alloy|ines|kajal/i.test(v.name))
                || voices.find(v => /en-?in|en-?gb|en-?us/i.test(v.lang));
            assistantVoice = preferred || voices[0] || null;
        }

        function buildProductCard(product, quantity) {
            const imageHtml = product.image ? '<img class="mobile-ai-product-image" src="' + product.image + '" alt="' + escapeHtml(product.name) + '">' : '';
            return '<div class="mobile-ai-product-card">' + imageHtml + '<div class="mobile-ai-product-details"><strong>' + escapeHtml(product.name) + '</strong>' + '<div class="mobile-ai-product-meta">₹' + parseFloat(product.price).toFixed(2) + ' / ' + escapeHtml(product.unit) + '</div>' + '<div class="mobile-ai-product-meta">Carton: ' + escapeHtml(product.carton_size || 'N/A') + '</div>' + '</div></div>';
        }

        function performAssistantQuery(text) {
            const command = text.trim();
            if (!command) {
                appendAssistantMessage('Please enter a product or command.');
                return;
            }
            appendUserMessage(command);
            const indicator = appendTypingIndicator();

            const normalized = command.toLowerCase();
            if (checkSimpleIntent(normalized, commonCommands.cart)) {
                fetchCartSummary(indicator);
                return;
            }
            if (checkSimpleIntent(normalized, commonCommands.checkout)) {
                removeIndicator(indicator);
                appendAssistantMessage('Taking you to checkout...');
                window.location.href = checkoutUrl;
                return;
            }
            if (checkSimpleIntent(normalized, commonCommands.offers)) {
                removeIndicator(indicator);
                showOfferSuggestions();
                return;
            }
            if (checkSimpleIntent(normalized, commonCommands.delivery)) {
                removeIndicator(indicator);
                suggestDeliverySlots();
                return;
            }
            if (checkSimpleIntent(normalized, commonCommands.payment)) {
                removeIndicator(indicator);
                explainPaymentTerms();
                return;
            }
            if (checkSimpleIntent(normalized, commonCommands.newOrder) || assistantState === 'greeting') {
                removeIndicator(indicator);
                startNewOrder();
                return;
            }

            const parsed = parseOrderText(normalized);
            if (parsed.productName) {
                fetchProductMatches(parsed, indicator);
                return;
            }

            removeIndicator(indicator);
            appendAssistantMessage('I could not understand that. Try "5 kg tomato" or "10 kilo onion".');
        }

        function checkSimpleIntent(text, phrases) {
            return phrases.some(phrase => text.includes(phrase));
        }

        function removeIndicator(node) {
            if (node && node.parentNode) {
                node.parentNode.removeChild(node);
            }
        }

        function fetchProductMatches(parsed, indicator) {
            const query = parsed.productName;
            fetch(assistantProductsUrl + '?q=' + encodeURIComponent(query), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(res => res.json())
                .then(data => {
                    removeIndicator(indicator);
                    if (!data.products || !data.products.length) {
                        appendAssistantMessage('I could not find that product. Try a different name like "potato" or "onion".');
                        return;
                    }
                    if (data.products.length === 1) {
                        showProductConfirmation(data.products[0], parsed.quantity, parsed.unit);
                        return;
                    }
                    showProductOptions(data.products, parsed.quantity, parsed.unit);
                })
                .catch(() => {
                    removeIndicator(indicator);
                    appendAssistantMessage('Unable to search products right now. Please try again.');
                });
        }

        function parseOrderText(text) {
            const digitMatch = text.match(/(\d+(?:\.\d+)?)/);
            const qty = digitMatch ? parseFloat(digitMatch[1]) : 1;
            const unitMatch = text.match(/(kg|kilo|kilogram|kilograms|kgs|g|gram|grams|ltr|litre|litres|pc|pcs|piece|pieces|carton|box|packet|pack|dozen)/i);
            const unit = unitMatch ? unitMatch[1] : 'unit';
            const productName = text
                .replace(/\b(add|also|please|mujhe|chahiye|do|de|dena|mai|main|bhai|karo|karru|carton|box|packet|pack|kg|kilo|kilogram|kilograms|kgs|g|gram|grams|ltr|litre|litres|pc|pcs|piece|pieces|dozen)\b/gi, '')
                .replace(digitMatch ? digitMatch[1] : '', '')
                .replace(unitMatch ? unitMatch[0] : '', '')
                .replace(/[^a-zA-Z0-9\s]/g, ' ')
                .trim();
            return { productName, quantity: qty || 1, unit };
        }

        function showProductConfirmation(product, quantity, unit) {
            appendAssistantMessage('<strong>Found: ' + escapeHtml(product.name) + '</strong>' + buildProductCard(product, quantity) + '<div class="mobile-ai-product-meta">Quantity: ' + quantity + ' ' + escapeHtml(unit) + '</div><div class="mobile-ai-product-actions"><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-add="' + product.id + '" data-qty="' + quantity + '" data-price="' + parseFloat(product.price).toFixed(2) + '">Yes, Add ' + quantity + ' ' + escapeHtml(unit) + '</button><button type="button" class="mobile-ai-product-btn" data-action="change_item">Change Item</button></div>');
        }

        function showProductOptions(products, quantity, unit) {
            let html = '<strong>I found multiple options. Please choose one:</strong>';
            products.forEach(product => {
                html += '<div class="mobile-ai-product-option">' + buildProductCard(product, quantity) + '<div class="mobile-ai-product-actions"><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-select="' + product.id + '" data-qty="' + quantity + '" data-price="' + parseFloat(product.price).toFixed(2) + '">Select</button></div></div>';
            });
            appendAssistantMessage(html);
        }

        function showOfferSuggestions() {
            appendAssistantMessage('Showing special offers from available products.');
            fetch(assistantProductsUrl + '?q=offer', { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(res => res.json())
                .then(data => {
                    if (!data.products.length) {
                        appendAssistantMessage('No offers found at the moment.');
                        return;
                    }
                    data.products.slice(0, 3).forEach(product => {
                        appendAssistantMessage(buildProductCard(product) + '<div class="mobile-ai-product-actions"><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-add="' + product.id + '" data-qty="1" data-price="' + parseFloat(product.price).toFixed(2) + '">Add 1 ' + escapeHtml(product.unit) + '</button></div>');
                    });
                })
                .catch(() => appendAssistantMessage('Unable to fetch offers right now.'));
        }

        function suggestDeliverySlots() {
            appendAssistantMessage('Here are the common delivery windows we can confirm for your order:');
            appendAssistantMessage('<ul><li>Today morning (8 AM - 12 PM)</li><li>Today afternoon (12 PM - 4 PM)</li><li>Today evening (4 PM - 8 PM)</li></ul><div class="mobile-ai-product-actions"><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="confirm_slot" data-slot="morning">Choose Morning</button><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="confirm_slot" data-slot="afternoon">Choose Afternoon</button><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="confirm_slot" data-slot="evening">Choose Evening</button></div>', true);
        }

        function explainPaymentTerms() {
            appendAssistantMessage('You can place this order on the following payment terms:');
            appendAssistantMessage('<ul><li>Prepaid</li><li>Cash on delivery</li><li>Net 30 (if approved)</li></ul><div class="mobile-ai-product-actions"><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="payment_prepaid">Prepaid</button><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="payment_cod">COD</button><button type="button" class="mobile-ai-product-btn mobile-ai-product-btn-primary" data-action="payment_net30">Net 30</button></div>', true);
        }

        function startNewOrder() {
            assistantState = 'ordering';
            appendAssistantMessage('Great! I’m ready to take your order for <strong>' + escapeHtml(assistantOutletName) + '</strong>. What would you like to add first? For example: 5 kg tomato or 2 boxes of biscuits.');
        }

        function fetchCartSummary(indicator) {
            fetch(assistantCartUrl, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(res => res.json())
                .then(data => {
                    removeIndicator(indicator);
                    if (!data.items.length) {
                        appendAssistantMessage('Your cart is empty. Add products like 5 kg tomato to begin.');
                        return;
                    }
                    let html = '<strong>Your current order:</strong><div class="mobile-ai-cart-summary">';
                    data.items.forEach(item => {
                        html += '<div class="mobile-ai-cart-item"><span>' + escapeHtml(item.name) + ' × ' + item.qty + '</span><span>₹' + parseFloat(item.total).toFixed(2) + '</span></div>';
                    });
                    html += '<div class="mobile-ai-cart-total"><span>Total</span><span>₹' + parseFloat(data.total).toFixed(2) + '</span></div></div>';
                    html += '<div class="mobile-ai-cart-actions"><button type="button" class="mobile-ai-cart-btn" data-action="continue_shopping">Continue Shopping</button><button type="button" class="mobile-ai-cart-btn mobile-ai-cart-btn-primary" data-action="go_to_cart">Go to Cart</button></div>';
                    appendAssistantMessage(html);
                })
                .catch(() => {
                    removeIndicator(indicator);
                    appendAssistantMessage('Could not load cart summary. Try again in a moment.');
                });
        }

        function addProductToCart(productId, qty, price, button) {
            if (button) button.disabled = true;
            fetch(cartAddUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: qty, price: price })
            })
                .then(res => res.json())
                .then(data => {
                    if (button) button.disabled = false;
                    if (data.success) {
                        appendAssistantMessage('✓ Added to your cart');
                        refreshCartCounts();
                    } else {
                        appendAssistantMessage('Could not add item to cart.');
                    }
                })
                .catch(() => {
                    if (button) button.disabled = false;
                    appendAssistantMessage('Could not add item due to a network error.');
                });
        }

        function refreshCartCounts() {
            fetch(assistantCartUrl, { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(res => res.json())
                .then(data => {
                    const headerCount = document.getElementById('headerCartCount');
                    const cartCount = document.getElementById('cartCount');
                    const cartItemsCount = document.getElementById('cartItemsCount');
                    if (headerCount) headerCount.textContent = data.count;
                    if (cartCount) cartCount.textContent = data.count;
                    if (cartItemsCount) cartItemsCount.textContent = data.count;
                });
        }

        function initializeSpeech() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                micButton.style.opacity = '0.4';
                micButton.disabled = true;
            } else {
                recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;
                recognition.lang = 'en-IN';

                recognition.addEventListener('result', event => {
                    const transcript = event.results[0][0].transcript;
                    inputField.value = transcript;
                    listening = false;
                    micButton.classList.remove('listening');
                    performAssistantQuery(transcript);
                    inputField.value = '';
                });
                recognition.addEventListener('end', () => {
                    if (listening) {
                        stopVoice();
                    }
                });
                recognition.addEventListener('error', () => {
                    listening = false;
                    micButton.classList.remove('listening');
                });
            }

            selectAssistantVoice();
            if ('speechSynthesis' in window) {
                window.speechSynthesis.onvoiceschanged = selectAssistantVoice;
            }
        }

        function toggleVoice() {
            if (!recognition) return;
            if (listening) {
                stopVoice();
                return;
            }
            recognition.lang = languageSelect.value === 'hindi' ? 'hi-IN' : languageSelect.value === 'marathi' ? 'mr-IN' : 'en-IN';
            recognition.start();
            listening = true;
            micButton.classList.add('listening');
        }

        function stopVoice() {
            if (recognition && listening) {
                recognition.stop();
            }
            listening = false;
            micButton.classList.remove('listening');
        }

        closeButton?.addEventListener('click', closeAssistant);
        overlay?.addEventListener('click', closeAssistant);
        sendButton?.addEventListener('click', function () {
            performAssistantQuery(inputField.value);
            inputField.value = '';
        });
        inputField?.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                performAssistantQuery(inputField.value);
                inputField.value = '';
            }
        });
        micButton?.addEventListener('click', toggleVoice);
        quickActions?.addEventListener('click', function (event) {
            const cmd = event.target.closest('button[data-action]');
            if (!cmd) return;
            const action = cmd.dataset.action;
            if (action === 'start_order') {
                chatContainer.innerHTML = '';
                assistantOpened = true;
                startNewOrder();
                return;
            }
            if (action === 'repeat_last_order') {
                appendAssistantMessage('Repeating your cart items.');
                fetchCartSummary(appendTypingIndicator());
                return;
            }
            if (action === 'delivery_slots') {
                suggestDeliverySlots();
                return;
            }
            if (action === 'payment_terms') {
                explainPaymentTerms();
                return;
            }
            if (action === 'weekly_purchase') {
                showOfferSuggestions();
            }
        });
        chatContainer?.addEventListener('click', function (event) {
            const selectBtn = event.target.closest('button[data-select]');
            const addBtn = event.target.closest('button[data-add]');
            const actionBtn = event.target.closest('button[data-action]');
            if (selectBtn) {
                addProductToCart(selectBtn.dataset.select, parseFloat(selectBtn.dataset.qty) || 1, parseFloat(selectBtn.dataset.price) || 0, selectBtn);
                return;
            }
            if (addBtn) {
                addProductToCart(addBtn.dataset.add, parseFloat(addBtn.dataset.qty) || 1, parseFloat(addBtn.dataset.price) || 0, addBtn);
                return;
            }
            if (actionBtn) {
                const action = actionBtn.dataset.action;
                if (action === 'change_item') {
                    appendAssistantMessage('Sure, tell me what you want instead.');
                }
                if (action === 'continue_shopping') {
                    closeAssistant();
                }
                if (action === 'go_to_cart') {
                    window.location.href = checkoutUrl;
                }
                if (action === 'confirm_slot') {
                    const slot = actionBtn.dataset.slot || 'selected slot';
                    appendAssistantMessage('Delivery slot confirmed: ' + escapeHtml(slot) + '. You can continue shopping or checkout when ready.');
                }
                if (action === 'payment_prepaid') {
                    appendAssistantMessage('Prepaid selected. I will prepare your cart and proceed to checkout whenever you are ready.');
                }
                if (action === 'payment_cod') {
                    appendAssistantMessage('Cash on delivery selected. Your order will be placed with COD instructions.');
                }
                if (action === 'payment_net30') {
                    appendAssistantMessage('Net 30 selected (subject to approval). I will hold this order for your approval flow.');
                }
            }
        });
        initializeSpeech();
    });
</script>
