<?php

return [
    'email_confirmation' => (bool) env('LOGIN_EMAIL_CONFIRMATION', true),
    'confirmation_expire' => (int) env('LOGIN_CONFIRMATION_EXPIRE', 15),
    'dormant_days' => (int) env('LOGIN_DORMANT_DAYS', 90),
    'lock_minutes' => (int) env('LOGIN_LOCK_MINUTES', 30),
    'trusted_device_days' => (int) env('LOGIN_TRUSTED_DEVICE_DAYS', 30),
];