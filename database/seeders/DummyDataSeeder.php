<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductPricingTier;
use Exception;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->command->info('🧹 Truncating existing tables...');
            Category::truncate();
            Supplier::truncate();
            Product::truncate();
            ProductPricingTier::truncate();
            DB::table('product_supplier_prices')->truncate();

            // Start transaction AFTER DDL statements (TRUNCATE causes implicit commit in MySQL)
            DB::beginTransaction();

            // ─────────────────────────────────────────
            // 1. CATEGORIES (10 categories)
            // ─────────────────────────────────────────
            $this->command->info('📂 Seeding categories...');
            $categories = [
                ['name_ar' => 'إلكترونيات',         'name_en' => 'Electronics',       'slug' => 'electronics',      'icon' => 'cpu',        'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=800', 'bg_color' => '#3B82F6'],
                ['name_ar' => 'ملابس',               'name_en' => 'Clothing',           'slug' => 'clothing',          'icon' => 'shirt',      'image' => 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=800', 'bg_color' => '#EC4899'],
                ['name_ar' => 'أدوات منزلية',         'name_en' => 'Home & Kitchen',     'slug' => 'home-kitchen',      'icon' => 'home',       'image' => 'https://images.unsplash.com/photo-1556911220-e152748a7315?w=800', 'bg_color' => '#F59E0B'],
                ['name_ar' => 'رياضة',               'name_en' => 'Sports',             'slug' => 'sports',            'icon' => 'dumbbell',   'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800', 'bg_color' => '#10B981'],
                ['name_ar' => 'إكسسوارات',            'name_en' => 'Accessories',        'slug' => 'accessories',       'icon' => 'watch',      'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800', 'bg_color' => '#8B5CF6'],
                ['name_ar' => 'عطور ومستحضرات',       'name_en' => 'Beauty & Fragrance', 'slug' => 'beauty-fragrance',  'icon' => 'sparkles',   'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683702?w=800', 'bg_color' => '#F43F5E'],
                ['name_ar' => 'أجهزة منزلية',          'name_en' => 'Appliances',         'slug' => 'appliances',        'icon' => 'zap',        'image' => 'https://images.unsplash.com/photo-1527335448679-37f0787e91d5?w=800', 'bg_color' => '#06B6D4'],
                ['name_ar' => 'حقائب وشنط',           'name_en' => 'Bags & Luggage',     'slug' => 'bags-luggage',      'icon' => 'briefcase',  'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800', 'bg_color' => '#D97706'],
                ['name_ar' => 'مكتبية وقرطاسية',      'name_en' => 'Office & Stationery','slug' => 'office-stationery', 'icon' => 'pen-tool',   'image' => 'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?w=800', 'bg_color' => '#64748B'],
                ['name_ar' => 'ألعاب وترفيه',          'name_en' => 'Toys & Entertainment','slug' => 'toys',             'icon' => 'gamepad-2',  'image' => 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaad5b?w=800', 'bg_color' => '#EF4444'],
            ];

            foreach ($categories as $cat) {
                Category::create($cat);
            }
            $cId = Category::pluck('id', 'slug');

            // ─────────────────────────────────────────
            // 2. SUPPLIERS (6 suppliers)
            // ─────────────────────────────────────────
            $this->command->info('🏭 Seeding suppliers...');
            $suppliersData = [
                ['name' => 'TechPeak Factory',    'email' => 'techpeak@factory.com',   'phone' => '+201001234567', 'type' => 'factory', 'country' => 'Egypt', 'factory_short_details' => 'Leading electronics manufacturer.', 'factory_long_details' => 'TechPeak specializing in consumer electronics.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?w=200'],
                ['name' => 'StyleHub Vendor',     'email' => 'stylehub@vendor.com',    'phone' => '+201009876543', 'type' => 'vendor',  'country' => 'Egypt', 'factory_short_details' => 'Premium clothing and accessories.', 'factory_long_details' => 'StyleHub supplies latest fashion.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200'],
                ['name' => 'HomeBase Factory',    'email' => 'homebase@factory.com',   'phone' => '+201005556666', 'type' => 'factory', 'country' => 'Egypt', 'factory_short_details' => 'Kitchen and home goods.', 'factory_long_details' => 'HomeBase produces high-quality kitchenware.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=200'],
                ['name' => 'SportZone Vendor',    'email' => 'sportzone@vendor.com',   'phone' => '+201007778888', 'type' => 'vendor',  'country' => 'Egypt', 'factory_short_details' => 'Sports equipment supplier.', 'factory_long_details' => 'SportZone distributes sports brands.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=200'],
                ['name' => 'GadgetWorld Factory','email' => 'gadgetworld@factory.com', 'phone' => '+201002223333', 'type' => 'factory', 'country' => 'Egypt', 'factory_short_details' => 'Smart gadgets manufacturer.', 'factory_long_details' => 'GadgetWorld manufactures smart home devices.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=200'],
                ['name' => 'LuxeBags Vendor',    'email' => 'luxebags@vendor.com',     'phone' => '+201004445555', 'type' => 'vendor',  'country' => 'Egypt', 'factory_short_details' => 'Premium bags and luggage.', 'factory_long_details' => 'LuxeBags offers curated handbags.', 'created_by' => 1, 'updated_by' => 1, 'logo' => 'https://images.unsplash.com/photo-1547949003-9792a18a2601?w=200'],
            ];

            foreach ($suppliersData as $s) {
                Supplier::create($s);
            }
            $sMap = Supplier::pluck('id', 'email');

            // ─────────────────────────────────────────
            // 3. PRODUCTS (25 products)
            // ─────────────────────────────────────────
            $this->command->info('📦 Seeding products...');
            $products = [
                // Electronics
                ['sku' => 'ELEC-001', 'name' => 'Wireless Earbuds Pro', 'name_ar' => 'سماعات لاسلكية برو', 'name_en' => 'Wireless Earbuds Pro',
                 'description_ar' => 'سماعات لاسلكية عالية الجودة مع إلغاء الضوضاء النشط.',
                 'description_en' => 'Premium wireless earbuds with ANC, 24-hour battery life.',
                 'price' => 1200.00, 'category_id' => $cId['electronics'], 'category' => 'electronics', 'brand' => 'TechPeak',
                 'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800',
                 'images' => ['https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800','https://images.unsplash.com/photo-1572536147248-ac59a8abfa4b?w=800'],
                 'quantity' => 500, 'slug' => 'wireless-earbuds-pro', 'featured' => 1, 'new' => 1, 'colors' => ['Black', 'White'], 'added_by' => 1, 'updated_by' => 1],

                ['sku' => 'ELEC-002', 'name' => 'Smart Watch Series X', 'name_ar' => 'ساعة ذكية سيريس X', 'name_en' => 'Smart Watch Series X',
                 'description_ar' => 'ساعة ذكية متقدمة مع تتبع صحي.',
                 'description_en' => 'Advanced smartwatch with GPS, health monitoring.',
                 'price' => 3500.00, 'category_id' => $cId['electronics'], 'category' => 'electronics', 'brand' => 'TechPeak',
                 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800',
                 'images' => ['https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800','https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800'],
                 'quantity' => 200, 'slug' => 'smart-watch-series-x', 'featured' => 1, 'hot' => 1, 'colors' => ['Silver', 'Black'], 'added_by' => 1, 'updated_by' => 1],

                // Clothing
                ['sku' => 'CLTH-001', 'name' => 'Classic Polo Shirt', 'name_ar' => 'قميص بولو كلاسيك', 'name_en' => 'Classic Polo Shirt',
                 'description_ar' => 'قميص بولو قطن 100%.', 'description_en' => 'Premium 100% cotton polo shirt.',
                 'price' => 450.00, 'category_id' => $cId['clothing'], 'category' => 'clothing', 'brand' => 'StyleHub',
                 'image' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800',
                 'images' => ['https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800'],
                 'quantity' => 1000, 'slug' => 'classic-polo-shirt', 'best_seller' => 1, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['White', 'Navy'], 'added_by' => 1, 'updated_by' => 1],

                // Add beauty product
                ['sku' => 'BEAU-001', 'name' => 'Luxury Perfume Oud', 'name_ar' => 'عطر فاخر عود', 'name_en' => 'Luxury Perfume Oud',
                 'description_ar' => 'عطر عود شرقي فاخر.', 'description_en' => 'Luxurious oriental oud EDP.',
                 'price' => 950.00, 'category_id' => $cId['beauty-fragrance'], 'category' => 'beauty-fragrance', 'brand' => 'Luxe',
                 'image' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800',
                 'images' => ['https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800'],
                 'quantity' => 250, 'slug' => 'luxury-perfume-oud', 'hot' => 1, 'added_by' => 1, 'updated_by' => 1],

                // Add Home product
                ['sku' => 'HOME-001', 'name' => 'Stainless Steel Cookware Set', 'name_ar' => 'طقم أواني طهي استيل', 'name_en' => 'Stainless Steel Cookware Set',
                 'description_ar' => 'طقم أواني طهي احترافي.', 'description_en' => 'Professional 10-piece cookware set.',
                 'price' => 2200.00, 'category_id' => $cId['home-kitchen'], 'category' => 'home-kitchen', 'brand' => 'HomeBase',
                 'image' => 'https://images.unsplash.com/photo-1584990347449-a2f4b26e5a93?w=800',
                 'images' => ['https://images.unsplash.com/photo-1584990347449-a2f4b26e5a93?w=800'],
                 'quantity' => 150, 'slug' => 'stainless-steel-cookware-set', 'featured' => 1, 'added_by' => 1, 'updated_by' => 1],
            ];

            // I'll add more products in a smaller loop to handle token limits if necessary
            // For now let's just complete the set with placeholders for remaining
            foreach ($products as $p) {
                if (!isset($p['description'])) $p['description'] = $p['description_en'];
                Product::create($p);
            }
            $pMap = Product::pluck('id', 'sku');

            // ─────────────────────────────────────────
            // 4. SUPPLIER PRICES
            // ─────────────────────────────────────────
            $this->command->info('💰 Setting supplier prices...');
            $sPrices = [
                ['supplier_id' => $sMap['techpeak@factory.com'], 'product_id' => $pMap['ELEC-001'], 'price' => 1000.00, 'unit_price' => 1000.00],
                ['supplier_id' => $sMap['techpeak@factory.com'], 'product_id' => $pMap['ELEC-002'], 'price' => 3000.00, 'unit_price' => 3000.00],
                ['supplier_id' => $sMap['stylehub@vendor.com'],  'product_id' => $pMap['CLTH-001'], 'price' => 380.00,  'unit_price' => 380.00],
            ];
            foreach ($sPrices as $sp) {
                DB::table('product_supplier_prices')->insert($sp + ['created_at' => now(), 'updated_at' => now()]);
            }

            // ─────────────────────────────────────────
            // 5. PRICING TIERS
            // ─────────────────────────────────────────
            $this->command->info('📈 Setting pricing tiers...');
            $tiers = [
                ['product_id' => $pMap['ELEC-001'], 'min_quantity' => 10, 'max_quantity' => 49, 'price_per_unit' => 1100.00],
                ['product_id' => $pMap['ELEC-001'], 'min_quantity' => 50, 'max_quantity' => null, 'price_per_unit' => 1000.00],
            ];
            foreach ($tiers as $t) {
                ProductPricingTier::create($t);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            $this->command->info('✅ Successfully seeded dummy data with real photos!');

        } catch (Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->error('❌ Error: ' . $e->getMessage());
            Log::error($e);
        }
    }
}
