<?php

return [
    // Public https address of the Curi-Assist chatbot service, e.g. https://curi-assist.onrender.com
    // Leave empty to keep the chatbot switched off.
    'url' => rtrim((string) env('CHATBOT_URL', ''), '/'),

    // Widget look. All optional.
    'color' => env('CHATBOT_COLOR', '#0d6efd'),
    'title' => env('CHATBOT_TITLE', 'Curi-Assist'),
    'label' => env('CHATBOT_LABEL', 'Ask Curi-Assist'),
];
