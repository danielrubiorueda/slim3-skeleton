<?php
// Routes

$app->get('/', 'home:getHome');
    
$app->get('/voucher/{id}', 'voucher:getVoucher');

    