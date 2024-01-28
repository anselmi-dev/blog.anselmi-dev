<div
    class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:mx-0 lg:max-w-none lg:grid-cols-2">
    @foreach (\App\Models\Post::all()->take(4) as $post)
        <x-blog.card :post="$post"
            @class([
                // "col-span-full" => $loop->first,
                "mt-0" => $loop->index == 1,
            ])
        ></x-blog.card>
    @endforeach
</div>
