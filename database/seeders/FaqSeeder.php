<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Example data to insert into the faq_models table
        $faqs = [
            [
                'question' => 'What is Laravel?',
                'slug' => 'what-is-laravel',
                'answer' => 'Laravel is a PHP framework for building web applications.',
            ],
            [
                'question' => 'How do I install Laravel?',
                'slug' => 'how-to-install-laravel',
                'answer' => 'You can install Laravel by running the command `composer create-project --prefer-dist laravel/laravel project-name`.',
            ],
            [
                'question' => 'What is Eloquent?',
                'slug' => 'what-is-eloquent',
                'answer' => 'Eloquent is Laravel\'s built-in ORM (Object-Relational Mapper), which provides an easy and expressive syntax for working with the database.',
            ],
            [
                'question' => 'What is Laravel?',
                'slug' => 'what-is-laravel',
                'answer' => 'Laravel is a PHP framework for building web applications.',
            ],
            [
                'question' => 'How do I install Laravel?',
                'slug' => 'how-to-install-laravel',
                'answer' => 'You can install Laravel by running the command `composer create-project --prefer-dist laravel/laravel project-name`.',
            ],
            [
                'question' => 'What is Eloquent?',
                'slug' => 'what-is-eloquent',
                'answer' => 'Eloquent is Laravel\'s built-in ORM (Object-Relational Mapper), which provides an easy and expressive syntax for working with the database.',
            ],
            [
                'question' => 'What is Laravel?',
                'slug' => 'what-is-laravel',
                'answer' => 'Laravel is a PHP framework for building web applications.',
            ],
            [
                'question' => 'How do I install Laravel?',
                'slug' => 'how-to-install-laravel',
                'answer' => 'You can install Laravel by running the command `composer create-project --prefer-dist laravel/laravel project-name`.',
            ],
            [
                'question' => 'What is Eloquent?',
                'slug' => 'what-is-eloquent',
                'answer' => 'Eloquent is Laravel\'s built-in ORM (Object-Relational Mapper), which provides an easy and expressive syntax for working with the database.',
            ],
            [
                'question' => 'What is Laravel?',
                'slug' => 'what-is-laravel',
                'answer' => 'Laravel is a PHP framework for building web applications.',
            ],
            [
                'question' => 'How do I install Laravel?',
                'slug' => 'how-to-install-laravel',
                'answer' => 'You can install Laravel by running the command `composer create-project --prefer-dist laravel/laravel project-name`.',
            ],
            [
                'question' => 'What is Eloquent?',
                'slug' => 'what-is-eloquent',
                'answer' => 'Eloquent is Laravel\'s built-in ORM (Object-Relational Mapper), which provides an easy and expressive syntax for working with the database.',
            ],
            [
                'question' => 'What is Laravel?',
                'slug' => 'what-is-laravel',
                'answer' => 'Laravel is a PHP framework for building web applications.',
            ],
            [
                'question' => 'How do I install Laravel?',
                'slug' => 'how-to-install-laravel',
                'answer' => 'You can install Laravel by running the command `composer create-project --prefer-dist laravel/laravel project-name`.',
            ],
            [
                'question' => 'What is Eloquent?',
                'slug' => 'what-is-eloquent',
                'answer' => 'Eloquent is Laravel\'s built-in ORM (Object-Relational Mapper), which provides an easy and expressive syntax for working with the database.',
            ],
        ];

        // Insert the data using Eloquent
        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
