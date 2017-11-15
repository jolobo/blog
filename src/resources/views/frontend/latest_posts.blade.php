@if ($latest_posts)
    <div class="latest-posts">
        <h2>{{__('landing.news_title')}}</h2>
        <hr>
        <div class="articles">
            @foreach ($latest_posts as $post)

                <article class="post-article">
                    <a href="{{ url($post->route) }}">

                        <div>
                            @if ($post->image)
                                <div class="post_img_container"  style="background: url({{ $post->image }}) center center no-repeat; background-size: cover;">
                                    {{-- <img src="{{ $post->image }}" class="img-responsive post-image"> --}}
                                    <div class="post-info">
                                        <h2>
                                            {{ $post->title_translated }}
                                        </h2>
                                        <!--div class="description-gradient"></div-->

                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
@endif

