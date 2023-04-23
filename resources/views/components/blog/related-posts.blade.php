<div
    class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
    @foreach (\App\Models\Post::all()->take(3) as $post)
        <x-blog.card-related :post="$post" class="py-12"></x-blog.card-related>
    @endforeach
</div>
