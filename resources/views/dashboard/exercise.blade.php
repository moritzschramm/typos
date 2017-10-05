@extends('layouts.main')

@section('title')
  @if(isset($edit))
    @lang('exercise.edit.title')
  @else
    @lang('exercise.create.title')
  @endif
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="box-container" style="position: relative; padding: 20px 40px;">

    <a href="{{ url('/dashboard?view=exercises') }}" class="back unselectable" style="position: absolute; top: -8px; left: 8px;" title="@lang('info.back')">&times;</a>

    <h3>
      @if(isset($edit))
        @lang('exercise.edit.title')
      @else
        @lang('exercise.create.title')
      @endif
    </h3>

    <form role="form" method="POST" action="{{ isset($edit) ? url("/exercise/$id/edit") : url('/exercise') }}">
      {{ csrf_field() }}

      <div class="form-group">
        <input type="text" class="form-control" id="title" name="title" placeholder="@lang('exercise.title')" value="{{ isset($edit) ? $title : old('title') }}">
      </div>
      @if($errors->has('title'))
        <div class="alert alert-danger">@lang($errors->first('title'))</div>
      @endif

      <div class="form-group">
       <textarea class="form-control" rows="16" id="content" name="content" placeholder="@lang('exercise.content')" style="resize:vertical">{{ isset($edit) ? $content : old('content') }}</textarea>
      </div>
      @if($errors->has('content'))
        <div class="alert alert-danger">@lang($errors->first('content'))</div>
      @endif

      <div style="text-align: right;">
        <button type="submit" class="btn btn-default btn-main btn-submit" style="width:150px;"><span>@lang('info.save')</span></button>
      </div>

    </form>

  </div>

</div>

@endsection
