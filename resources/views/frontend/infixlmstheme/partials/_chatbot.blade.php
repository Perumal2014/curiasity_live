{{-- Curi-Assist chatbot widget. Renders nothing unless CHATBOT_URL is set in .env --}}
@if (config('chatbot.url'))
    <script src="{{ config('chatbot.url') }}/widget.js"
            data-api="{{ config('chatbot.url') }}"
            data-color="{{ config('chatbot.color') }}"
            data-title="{{ config('chatbot.title') }}"
            data-label="{{ config('chatbot.label') }}"
            defer></script>
@endif
