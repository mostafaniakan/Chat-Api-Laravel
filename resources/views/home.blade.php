@extends('layout.master')

@section('title','home')

@section('content')
    <section class="container">
        {{--        @if($errors->all())--}}
        {{--            <div>--}}
        {{--                @foreach($errors->all() as $error)--}}
        {{--                     {{print_r($error)}}--}}
        {{--                @endforeach--}}
        {{--            </div>--}}
        {{--        @endif--}}

        <div class="register">
            <div class="logo">
                <img src="img/lotus.png" alt="logo">
                <span class="title">Register</span>
            </div>

            <form method="POST" action="{{route('Register.User')}}">
                @csrf
                <label>
                    @error('name')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    <input type="text" @error('name') style="border-color: red" @enderror name="name"
                           placeholder="name">
                </label>
                <label>
                    @error('email')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    <input type="text" @error('email') style="border-color: red" @enderror name="email"
                           placeholder="email">
                </label>
                <label>
                    @error('phone')
                    <span style="color: red">{{$message}}</span>
                    @enderror
                    <input type="number" @error('phone') style="border-color: red" @enderror name="phone" placeholder="phone">
                </label>

                <label class="submit btn">
                    <input type="submit" name="Login" value="Login">
                </label>

            </form>
        </div>

        <div class="login">
            <div class="logo">
                <span class="title">Log in</span>
            </div>
            <form method="POST" action="{{route('LoginUser')}}">
               @csrf
                <label>
                    @error('phoneLogin')
                    <span style="color: #7a0000">{{$message}}</span>
                    @enderror
                    <input type="number" @error('phoneLogin') style="border-color: red" @enderror name="phoneLogin" placeholder="phone">
                </label>

                <label class="submit btn">
                    <input type="submit" name="Login" value="Login">
                </label>

            </form>
        </div>
    </section>
@endsection
