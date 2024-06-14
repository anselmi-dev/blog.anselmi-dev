@section('title', 'The title of the page')

@section('description', 'Description of the page')

<div class="flex flex-col gap-40">
    <x-containers.content>
        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 px-2 lg:p-0">
            @foreach ($main_posts as $post)
                <x-blog.card-main :post="$post" class="w-full md:w-2/3 h-[24em]"></x-blog.card-main>
            @endforeach
        </div>

        <x-blog.related-posts />

    </x-containers.content>
</div>
