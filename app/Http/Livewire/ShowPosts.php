<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class ShowPosts extends Component
{
    public $message = "heeklo";
    public $posts;

    public function mount() {
        $this->posts = Category::all();
    }

    public function render()
    {
        return view('livewire.show-posts');
    }
}
