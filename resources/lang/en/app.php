<?php

return [
    // Navigation
    'nav.catalog'  => 'CATALOG PRODUCTS',
    'nav.about'    => 'ABOUT US',
    'nav.contacts' => 'CONTACTS',
    'nav.privacy'  => 'Privacy Policy',

    // Breadcrumbs
    'breadcrumbs' => [
        'aria'    => 'Breadcrumbs',
        'home'    => 'Home',
        'catalog' => 'Product catalog',
        'cart'    => 'Cart',
    ],

    // Forms
    'form' => [
        'name'        => 'Name',
        'name.ph'     => 'Enter your name',
        'phone'       => 'Phone',
        'phone.ph'    => '+7 747 123 45 67',
        'address'     => 'Delivery address',
        'address.ph'  => 'Abay Saina 34',
        'comment.ph'  => 'Comments',
        'message'     => 'Message',
        'send'        => 'Send',
        'submit'      => 'Submit request',
    ],

    // Product
    'product' => [
        'sku'              => 'SKU:',
        'in_stock'         => 'In stock',
        'out_of_stock'     => 'Out of stock',
        'delivery_info'    => 'Delivery & payment information',
        'tabs' => [
            'description'  => 'Description',
            'composition'  => 'Composition',
            'usage'        => 'Usage',
            'certificates' => 'Certificates',
        ],
        'description_soon'  => 'Description coming soon.',
        'certificate' => [
            'alt'   => 'Certificate',
            'empty' => 'No certificates available.',
        ],
        'related'     => 'Frequently bought together',
        'qty' => [
            'plus'  => 'Plus',
            'minus' => 'Minus',
        ],
        'item'         => 'Product',
        'badge' => [
            'best_seller' => 'Best seller',
        ],
    ],

    // Home blocks
    'advantages' => [
        'title' => 'Advantages',
        'items' => [
            ['title' => 'Naturalness'],
            ['title' => 'Certification'],
            ['title' => 'Effectiveness'],
        ],
    ],
    'reviews' => [
        'title' => 'Customer reviews',
        'guest' => 'Guest',
    ],
    'popular' => [
        'title' => 'Popular products',
    ],
    'banners' => [
        'title' => 'Promotions',
    ],

    'contacts.title'        => 'Contacts',
    'contacts.address.title'=> 'Address',


    // Footer
    'footer' => [
        'contacts' => 'Contacts',
        'socials'  => 'Social media',
    ],

    // Common
    'privacy' => [
        'title' => 'Privacy Policy',
        'empty' => 'The policy content has not been added yet.',
    ],
    'common' => [
        'na' => '—',
    ],

    // Buttons (если где-то используешь отдельно)
    'buttons' => [
        'add_to_cart' => 'Add to cart',
        'in_cart'     => 'In cart',
    ],

    // Cart
    'cart' => [
        'title'      => 'Cart',
        'add'        => 'Add to cart',
        'add_short'  => 'Add',
        'in_cart'    => 'In cart',
        'empty'      => 'Your cart is empty',
        'code'       => 'Product code',
        'currency'   => 'T',
        'delete'     => 'Remove item',
        'minus_aria' => 'Minus',
        'plus_aria'  => 'Plus',
    ],

    // Toasts
    'toast' => [
        'added'         => 'Item added to cart',
        'cart_empty'    => 'Cart is empty',
        'parse_error'   => 'Failed to parse items',
        'fill_required' => 'Please fill name, phone and address',
        'order_error'   => 'Order submission error',
        'order_ok'      => '✅ Order received! № :number',
        'network'       => 'Network error',

        // lead form
        'lead_ok'       => '✅ Request sent! Our manager will contact you.',
        'lead_error'    => 'Failed to submit the request',
    ],

    'catalog' => [
        'title' => 'Product catalog',

        'products_count' => ':count items',
        'filter'         => 'Filter',
        'brand'          => 'Brand',
        'price'          => 'Price',
        'price_from'     => 'From',
        'price_to'       => 'To',
        'show'           => 'Show',
        'reset'          => 'Reset',
        'not_found'      => 'No products found',
    ],
];
