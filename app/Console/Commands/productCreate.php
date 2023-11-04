<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class productCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($p = 1; $p <= 100000; $p++) {
            for ($i = 1; $i <= 10; $i++) {
                Product::create([
                    'kode' => 'k-' . $i,
                    'name' => 'nama barang ' . $i,
                    'price' => 200 . $i,
                    'description' => 'deskripsi' . $i,
                    'stock' => $i,
                    'category_id' => $i,
                    'image' =>  $i . '.jpg'
                ]);
            }
            $this->info('berhasil create product' . $p);
        }
    }
}
