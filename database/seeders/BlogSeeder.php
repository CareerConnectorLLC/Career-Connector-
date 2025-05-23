<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\{User,BlogCategory,Blog};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $user_ids = User::pluck('id')->toArray();

        $categories = [
            'How-To Guides',
            'Tips & Tricks',
            'News & Updates',
            'Product Reviews',
            'Interviews',
            'Case Studies',
            'Opinion Pieces',
            'Tutorials',
            'Behind the Scenes',
            'Industry Insights',
            'Lists (Listicles)',
            'Beginner Guides',
            'Comparisons',
            'FAQs',
            'Resources & Tools',
        ];

        foreach ($categories as $category) {
            BlogCategory::create([
                'name'   => $category,
                'slug'   => \Str::slug($category),
                'active' => $faker->numberBetween(0, 1),
            ]);
        }

        $blog_categories = BlogCategory::pluck('id')->toArray();

        foreach (range(1, 30) as $index) {
            $title = $faker->sentence(6);
            $slug = \Str::slug($title);

            Blog::create([
                'title' => $title,
                'slug' => $slug,
                'text_content' => $faker->paragraph(3, true),
                'active' => $faker->numberBetween(0, 1),
                'user_id' => $faker->randomElement($user_ids),
                'blog_category_id' => $faker->randomElement($blog_categories),
                'image_url' => null
            ]);
        }
    }
}
