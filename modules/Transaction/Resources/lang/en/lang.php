<?php

return [
    'title' => 'Order Transactions',
    'menus' => [
        'index' => 'Order Transactions',
        'view' => 'View Order Transactions'
    ],
    'crud' => [
        'id' => 'ID',
        'buyer_name' => 'Buyer Name',
        'address' => 'Address',
        'total_amount' => 'Total Amount',
        'order_date' => 'Order Date',

        'product_name' => 'Product Name',
        'qty' => 'QTY',
        'price' => 'Price',
        'total_price' => 'Total Price',
    ],
    'alerts' => [
        'no_exist' => 'That Order Transactions does not exist.',
        'not_selected' => 'You have not selected any Order Transactions to delete.'
    ],
];