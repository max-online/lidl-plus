<div>
    <x-nav :links="['home']">
        Einstellungen
    </x-nav>

    <div class="flex my-4 space-x-8">
        <div class="flex-1">
            <x-input.select wire:model="selectedCategory" wire:change="updateTags">
                <option value="">Kategorie wählen..</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-input.select>

            @if ($selectedCategory)
                <div class="border-gray-400 border bg-gray-100 rounded-md p-2 mt-4">
                    <h1 class="text-lg text-gray-800 my-2 font-semibold">Neuer Tag</h1>

                    <form wire:submit.prevent="saveTag" class="p-2 m-0">
                        <label class="flex items-center mb-3">
                            <div class="w-32 text-gray-700">Name</div>
                            <x-input.text wire:model="name" placeholder="Tag eingeben" :error="$errors->has('name')"/>
                        </label>
                        @error('name')
                            <x-error>{{ $message }}</x-error>
                        @enderror
                        <label class="flex items-center mb-6">
                            <div class="w-32 text-gray-700">Kategorie</div>
                            <x-input.text class="opacity-30" value="{{ $categories->firstwhere('id', $selectedCategory)->name }}" disabled />
                        </label>
                        <div class="flex justify-end">
                            <button class="bg-blue-600 px-4 py-2 text-white rounded hover:bg-blue-700">Hinzufügen</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="flex-1">

            @if ($tags->isNotEmpty())
                <h3 class="text-lg mb-4">Einträge zu der Kategorie "{{ $categories->firstwhere('id', $selectedCategory)->name }}"</h3>

                <div class="mb-3" style="display:none;"
                    x-data="{ open: false, message: '' }"
                    x-show.transition.duration.2000ms="open" 
                    x-init="@this.on('tagsUpdated', (event) => { message = event.message; open = true; setTimeout(() => { open = false; }, 1500)})"
                >
                    <span class="px-3 py-2 bg-green-200 border border-green-400" x-text="message"></span>
                </div>

                <table>
                    @foreach($tags as $index => $tag)
                        <tr class="text-sm">
                            <td class="px-2 py-1 w-40">
                                @if ($editedTagId == $tag->id)
                                    <input type="text" 
                                        wire:model.defer="tags.{{ $index }}.name" 
                                        wire:keydown.enter="updateTag({{ $tag->id }})" 
                                        wire:keydown.escape.window="endEditMode" 
                                        class="form-input border border-gray-300"
                                    >
                                    @error('tags.' . $index . '.name')
                                        <x-error>{{ $message }}</x-error>
                                    @enderror
                                @else
                                    <span wire:click="editTag({{ $tag->id }})">{{ $tag->name }}</span>
                                @endif
                            </td>
                            <td class="px-2 py-1">
                                <button class="text-sm bg-red-500 px-2 py-1 tracking-wider text-white rounded hover:bg-red-600" wire:click="deleteTag({{ $tag->id }})">Löschen</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
</div>
