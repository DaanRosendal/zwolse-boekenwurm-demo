<?php

use App\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Fake books seeder
         */

        //$faker = Faker\Factory::create('nl_NL');
//        for ($i = 1; $i <= 500; $i++) {
//            DB::table('books')->insert([
//                'title' => $faker->words(3, true),
//                'author' => $faker->name,
//                'category_id' => $faker->randomElement([1, 2, 3]),
//                'location' => 'Doos ' . rand(1, 50),
//                'bol_link' => 'https://www.bol.com/nl/l/boeken/N/' . $i,
//                'sold' => $faker->boolean(20)
//            ]);
//        }

        /**
         * Real books seeder
         */
        $filesWithBooksWithBoxNumber = ['nederlands.romans.txt', 'buitenlands.romans.txt', 'buitenlands.thrillers.txt'];

        $category = ''; // Give category a global scope

        // output all files and directories except for '.' and '..'
        foreach (new DirectoryIterator(__DIR__.'/books/') as $file) {
            if($file->isDot()) continue;
            $fileName = $file->getFilename();
            $file = fopen(__DIR__.'/books/'.$fileName, "r") or die("Unable to open file!");

            // Output one line until end-of-file
            $lineCount = 0;
            while(!feof($file)) {
                // First line is the category
                if($lineCount == 0){
                    $category = ucfirst(trim(fgets($file)));

                    DB::table('categories')->insert([
                        'name' => $category
                    ]);

                    $category = DB::table('categories')->where('name', '=', $category)->first();
                // Lines after the first line are books
                } else {
                    $line = fgets($file);
                    if (in_array($fileName, $filesWithBooksWithBoxNumber)){
                        $boekGegevens = explode(" ", $line, 2);
                        if (is_numeric(mb_substr($boekGegevens[0], 0,1))){
                            $boxNumber = $boekGegevens[0];

                            $titelEnAuteur = explode("–", $boekGegevens[1]);
                            $titel = trim($titelEnAuteur[0]);
                            $auteur = isset($titelEnAuteur[1]) ? trim($titelEnAuteur[1]) : 'Geen auteur';

                            DB::table('books')->insert([
                                'title' => $titel,
                                'author' => $auteur != '' ? $auteur : 'Onbekende of Geen Auteur',
                                'category_id' => $category->id,
                                'location' => 'Doos '.$boxNumber,
                                'bol_link' => '',
                                'sold' => false,
                                'created_at' => Carbon::now(),
                                'updated_at' => null
                            ]);
                        } else {
                            $boekGegevens = explode("–", $line);
                            error_log(print_r($boekGegevens));
                            $titel = trim($boekGegevens[0]);
                            $auteur = trim($boekGegevens[1]);

                            DB::table('books')->insert([
                                'title' => $titel,
                                'author' => $auteur != '' ? $auteur : 'Onbekende of Geen Auteur',
                                'category_id' => $category->id,
                                'location' => '',
                                'bol_link' => '',
                                'sold' => false,
                                'created_at' => Carbon::now(),
                                'updated_at' => null
                            ]);
                        }
                    } else {
                        $boekGegevens = explode("–", $line);
                        error_log(print_r($boekGegevens));
                        $titel = trim($boekGegevens[0]);
                        $auteur = trim($boekGegevens[1]);

                        DB::table('books')->insert([
                            'title' => $titel,
                            'author' => $auteur != '' ? $auteur : 'Onbekende of Geen Auteur',
                            'category_id' => $category->id,
                            'location' => '',
                            'bol_link' => '',
                            'sold' => false,
                            'created_at' => Carbon::now(),
                            'updated_at' => null
                        ]);
                    }
                }
                $lineCount++;
            }
            fclose($file);
        }
    }
}
