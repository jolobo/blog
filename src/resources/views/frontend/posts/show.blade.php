<!DOCTYPE html>

{{--<!--[if lt IE 7]>      <html class="no-js lt-ie9 ltº-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"><!--<![endif]-->--}}
<head>
    <title>SOMA | {{  $post->meta_title_translated }}</title>
    <meta name="title" content="{{ $post->meta_title_translated }}" />
    <meta name="description" content="{{ $post->meta_description_translated }}" />
    <meta property="og:locale" content="{{ app()->getLocale() }}" />
    <meta property="og:title" content="{{ $post->meta_title_translated }}" />
    <meta property="og:description" content="{{ $post->meta_description_translated }}" />
    <meta property="og:url" content="{{ request()->url() }}" />
    <meta property="og:image" content="{{ url($post->image) }}"/>
    @include("landing.links");
</head>

<body data-spy="scroll" data-target="#navigationBar" data-offset="120">

@include("landing.navbar");

<div class="main_part ">

    <div id="intro" class="clearfix">
        <div class="item" style="background: url({{ $post->image }}) center center no-repeat; background-size: cover;">
            <div class="container">
                <div class="row">
                    <h1 data-animate="fadeInDown">{{ $post->title_translated }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section post-section">

        <div class="col-md-12">
            <div class="container col-md-6 col-md-offset-3">

                <div class="row">
                    @include('blog::frontend.posts.post_data')
                    <span class="post-data" style="padding-left: 1em">{{__('landing.by')}}</span>
                    <span style="padding-left: 1em"> {{ $user_name }} </span>
                </div>

                <div class="row post-body text-large">
                    {!! $post->description_translated !!}
                </div>

            </div>
        </div>

    </div>

    <div class="section text-gray">
        <div class="col-md-6 col-md-offset-3">
            <div class="row ">
                <h2>{{__('landing.other_posts')}}</h2>
                @include('blog::frontend.latest_posts')
            </div>
        </div>
    </div>

    @include("landing.bottombar")

</div>
</body>
