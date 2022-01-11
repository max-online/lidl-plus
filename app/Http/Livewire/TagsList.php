<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Tag;

class TagsList extends Component
{
    public $categories;

    public $selectedCategory;
    
    public $name = '';

    public $tags;

    public $editedTagId;

    protected $queryString = [
        'selectedCategory' => ['except' => '']
    ];
    
    protected $validationAttributes = [
        'tags.*.name' => 'Name'
    ];
    
    protected function rules()
    {
        return [
            'tags.*.name' => 'required|min:2'
        ];   
    }

    public function mount()
    {
        $this->tags = collect();
        $this->categories = Category::get();

        if ($this->selectedCategory) {
            $this->updateTags();
        }
    }

    public function saveTag()
    {
        $this->validate([
            'name' => 'required|min:2|unique:tags'
        ]);

        Tag::create([
            'name' => \Str::lower($this->name),
            'category_id' => $this->selectedCategory
        ]);
        
        $this->reset('name');

        $this->updateTags();

        $this->emit('tagsUpdated', ['message' => 'Erfolgreich angelegt.']);
    }

    public function updateTags()
    {
        $this->tags = Tag::where('category_id', $this->selectedCategory)
                        ->orderBy('name')
                        ->get();
    }

    public function deleteTag($id)
    {
        Tag::destroy($id);

        $this->updateTags();

        $this->emit('tagsUpdated', ['message' => 'Erfolgreich gelöscht.']);
    }

    public function editTag($id)
    {
        $this->editedTagId = $id;
    }

    public function endEditMode()
    {
        $this->updateTags();

        $this->editedTagId = '';
    }

    public function updateTag($id)
    {
        $this->validate();

        Tag::where('id', $id)
            ->update([
                'name' => $this->tags->firstWhere('id', $id)->name
            ]);

        $this->emit('tagsUpdated', ['message' => 'Erfolgreich bearbeitet.']);

        $this->editedTagId = null;
    }

    public function render()
    {
        return view('livewire.tags-list', [
            'tags' => $this->tags
        ]);
    }
}
