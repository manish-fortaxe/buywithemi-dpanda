<div class="col-12 col-md-3 col-lg-3 mb-4">
    <a href="{{ route('single-blog', $blog->slug) }}">
        <div class="card h-100">
            <div class="img-container" style="overflow: hidden;">
                <img class="img-fluid w-100" src="{{ getStorageImages(path: $blog->media_full_url , type: 'backend-blog') }}" alt="Card image cap" loading="lazy" style="object-fit: contain; height: 200px;">
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="fw-bold">{{ $blog->title }}</h5>
                <div class="flex-grow-1">
                    {!! strlen(strip_tags($blog->description)) > 100 ? substr(strip_tags($blog->description), 0, 100) . '...' : strip_tags($blog->description) !!}
                </div>
                <a href="{{ route('single-blog', $blog->slug) }}" class="mt-3 d-block">Read more..</a>
            </div>
        </div>
    </a>
</div>
