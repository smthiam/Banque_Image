<?php
    namespace App\Listeners;
    use App\Events\NameSaving as EventNameSaving;
    use Illuminate\Support\Str;
   

    class NameSaving
    
    {
        public function handle(EventNameSaving $event)
        {
            $event->model->slug = str::slug($event->model->name, '-');
        }
    }