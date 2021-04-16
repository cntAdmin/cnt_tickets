<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App as AppEnv;
use Illuminate\Support\Facades\Storage;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(AppEnv::environment('local')){
            // CREAR EL DIRECTORIO SI NO EXISTE
            if(!is_dir(storage_path() . '/app/public/media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT))){
                Storage::disk('public')->makeDirectory('media/' . now()->year . '/' . str_pad(now()->month, 2, '0', STR_PAD_LEFT));
            }
            
            factory(App\Models\Attachment::class, 50)->create()->each(function($attachment) {
                $attachment->tickets()->save(App\Models\Ticket::withoutGlobalScopes()->inRandomOrder()->first());
            });
        }
    }
}
