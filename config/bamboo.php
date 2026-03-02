<?php

return [
    'private_key' => env('BAMBOO_PRIVATE_KEY'),
    'public_key'  => env('BAMBOO_PUBLIC_KEY'),
    'environment' => env('BAMBOO_ENVIRONMENT', 'stage'),

    'base_url' => env('BAMBOO_ENVIRONMENT', 'stage') === 'production'
        ? 'https://api.bamboopayment.com'
        : 'https://api.stage.bamboopayment.com',

    'capture_script' => env('BAMBOO_ENVIRONMENT', 'stage') === 'production'
        ? 'https://capture.bamboopayment.com'
        : 'https://capture.stage.bamboopayment.com/',

    'capture_integrity' => env('BAMBOO_ENVIRONMENT', 'stage') === 'production'
        ? 'sha256-E4GdhdwL0BVk9Xq/21s9qxvjh+anbzbgDVtfH6iCu7E= sha384-vbOT5y4OCop26UzV2VOfg0IAK+ToPU+mR09IcQChnn5kfD5W7b3YQNWXkJob3WWs sha512-j9jTPXduda6zgSUmoTvy9xEOlJOnYS2fKO0dEMiSK5VSB4bGfmKWfSHFLe7pjsFj3lu0K426c9F+7lXf/810Kg=='
        : 'sha256-X8C6kzz5r0uxNzqtXB8q2tAkorRayzErzeIUm1f0xm4= sha384-Vt+ySpe3/DHlyv34xym7TY55eJ49WxXv6d2cHasu/GG/7h22MGUESq+3kQeHOaLC sha512-B132Bu3DX6JkgamkFER/g2rcsfdTzrwNWOY5O2Wsfe8lm/H7TD1uGdnEuPvudq4ZRdCQDrWk6PwCVkgP1o2Szg==',

    'target_country' => 'UY',
    'currency'       => 'UYU',
];
