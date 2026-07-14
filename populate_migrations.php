<?php

$migrationsPath = __DIR__ . '/database/migrations';

$schemas = [
    'shops' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->string('name');
            \$table->string('logo')->nullable();
            \$table->text('address')->nullable();
            \$table->decimal('rating', 3, 2)->default(0);",
            
    'seller_subscriptions' => "
            \$table->string('name');
            \$table->decimal('price', 10, 2);
            \$table->integer('duration_days');
            \$table->integer('max_products');",

    'seller_subscription_payments' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('subscription_id')->constrained('seller_subscriptions')->onDelete('cascade');
            \$table->decimal('amount', 10, 2);
            \$table->string('status'); // e.g. paid, pending
            \$table->timestamp('valid_until')->nullable();",

    'categories' => "
            \$table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->string('icon')->nullable();",

    'brands' => "
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->string('logo')->nullable();",

    'attributes' => "
            \$table->string('name');",

    'attribute_values' => "
            \$table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            \$table->string('value');",

    'products' => "
            \$table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            \$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            \$table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description')->nullable();
            \$table->enum('product_type', ['physical', 'digital'])->default('physical');
            \$table->boolean('b2b_wholesale')->default(false);
            \$table->boolean('is_featured')->default(false);
            \$table->boolean('todays_deal')->default(false);
            \$table->string('status')->default('active');",

    'product_variants' => "
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->string('variant_string'); // e.g., Red-16GB
            \$table->string('sku')->nullable();
            \$table->decimal('price', 10, 2);
            \$table->integer('stock')->default(0);
            \$table->string('image')->nullable();",

    'digital_assets' => "
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->string('file_path');
            \$table->integer('download_limit')->nullable();",

    'flash_deals' => "
            \$table->string('title');
            \$table->timestamp('start_date')->nullable();
            \$table->timestamp('end_date')->nullable();
            \$table->string('banner')->nullable();
            \$table->boolean('status')->default(true);",

    'flash_deal_products' => "
            \$table->foreignId('flash_deal_id')->constrained('flash_deals')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->decimal('discount', 10, 2);
            \$table->enum('discount_type', ['percent', 'flat'])->default('flat');",

    'wholesale_prices' => "
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->integer('min_qty');
            \$table->integer('max_qty')->nullable();
            \$table->decimal('price', 10, 2);",

    'auctions' => "
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->timestamp('start_date')->nullable();
            \$table->timestamp('end_date')->nullable();
            \$table->decimal('starting_bid', 10, 2);",

    'bids' => "
            \$table->foreignId('auction_id')->constrained('auctions')->onDelete('cascade');
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->decimal('amount', 10, 2);
            \$table->string('status')->default('pending');",

    'classified_products' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            \$table->string('title');
            \$table->text('description')->nullable();
            \$table->decimal('price', 10, 2);
            \$table->string('condition');
            \$table->string('status')->default('active');",

    'orders' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('shipping_address_id')->nullable()->constrained('user_addresses')->onDelete('set null');
            \$table->decimal('grand_total', 10, 2);
            \$table->string('payment_status')->default('unpaid');
            \$table->string('payment_method')->nullable();
            \$table->string('source')->default('web'); // web, app, pos",

    'sub_orders' => "
            \$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            \$table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            \$table->decimal('sub_total', 10, 2);
            \$table->decimal('shipping_cost', 10, 2)->default(0);
            \$table->string('order_status')->default('pending');",

    'order_items' => "
            \$table->foreignId('sub_order_id')->constrained('sub_orders')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('set null');
            \$table->integer('qty');
            \$table->decimal('price', 10, 2);",

    'shipping_methods' => "
            \$table->string('title');
            \$table->decimal('cost', 10, 2)->default(0);
            \$table->boolean('is_free')->default(false);
            \$table->boolean('is_flat_rate')->default(false);",

    'pickup_points' => "
            \$table->string('name');
            \$table->text('address');
            \$table->string('manager_name');
            \$table->string('phone');",

    'refund_requests' => "
            \$table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->text('reason');
            \$table->decimal('refund_amount', 10, 2);
            \$table->string('status')->default('pending');",

    'order_trackings' => "
            \$table->foreignId('sub_order_id')->constrained('sub_orders')->onDelete('cascade');
            \$table->string('status');
            \$table->text('description')->nullable();",

    'wishlists' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');",

    'compares' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');",

    'conversations' => "
            \$table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            \$table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            \$table->string('title')->nullable();",

    'messages' => "
            \$table->foreignId('conversation_id')->constrained('conversations')->onDelete('cascade');
            \$table->text('message');
            \$table->boolean('is_read')->default(false);",

    'support_tickets' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->string('subject');
            \$table->string('priority')->default('normal');
            \$table->string('status')->default('open');",

    'ticket_replies' => "
            \$table->foreignId('support_ticket_id')->constrained('support_tickets')->onDelete('cascade');
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->text('reply');
            \$table->string('attachments')->nullable();",

    'affiliate_users' => "
            \$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            \$table->string('referral_code')->unique();
            \$table->decimal('balance', 10, 2)->default(0);",

    'affiliate_links' => "
            \$table->foreignId('affiliate_user_id')->constrained('affiliate_users')->onDelete('cascade');
            \$table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            \$table->string('url_hash')->unique();",

    'affiliate_earnings' => "
            \$table->foreignId('affiliate_user_id')->constrained('affiliate_users')->onDelete('cascade');
            \$table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            \$table->decimal('amount', 10, 2);
            \$table->string('status')->default('pending');",

    'languages' => "
            \$table->string('name');
            \$table->string('code')->unique();
            \$table->boolean('is_rtl')->default(false);
            \$table->boolean('is_default')->default(false);",

    'translations' => "
            \$table->string('translatable_type');
            \$table->unsignedBigInteger('translatable_id');
            \$table->string('language_code');
            \$table->string('column_name');
            \$table->text('translation');",

    'currencies' => "
            \$table->string('name');
            \$table->string('symbol');
            \$table->decimal('exchange_rate', 10, 4);
            \$table->boolean('is_default')->default(false);",

    'pages' => "
            \$table->string('slug')->unique();
            \$table->string('title');
            \$table->longText('content');",

    'theme_settings' => "
            \$table->string('key')->unique();
            \$table->text('value')->nullable();"
];

$files = scandir($migrationsPath);

$targetBlock = "            \$table->id();\n            \$table->timestamps();";

$processedCount = 0;

foreach ($files as $file) {
    if (strpos($file, '.php') !== false) {
        // Extract table name from filename, e.g., 2023_01_01_000000_create_shops_table.php -> shops
        preg_match('/_create_(.*)_table\.php/', $file, $matches);
        if (isset($matches[1]) && array_key_exists($matches[1], $schemas)) {
            $tableName = $matches[1];
            $filePath = $migrationsPath . '/' . $file;
            $content = file_get_contents($filePath);
            
            // Only inject if the file still has the default structure (hasn't been modified yet)
            if (strpos($content, $targetBlock) !== false) {
                $replacement = "            \$table->id();\n" . $schemas[$tableName] . "\n            \$table->timestamps();";
                $newContent = str_replace($targetBlock, $replacement, $content);
                file_put_contents($filePath, $newContent);
                echo "Injected schema for table: {$tableName}\n";
                $processedCount++;
            }
        }
    }
}

echo "Finished! Injected schema into {$processedCount} migration files.\n";
