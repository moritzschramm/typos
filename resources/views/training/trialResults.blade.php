@extends('layouts.main')

@section('title')
@lang('training.results.title')
@endsection

@section('header')
<link href="/res/css/results.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="box-container text-center" style="padding: 30px 40px;">

    <h1>@lang('training.results.title')</h1>

    <div class="info">

      @if($cheated)
        <h2>@lang('training.results.cheated')</h2>
      @endif

      <form class="form-horizontal" role="form" method="POST" action="{{ url('/trial/publish') }}">
        {{ csrf_field() }}

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.amountCorrects'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $keystrokes }}</p>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.amountErrors'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $error_amount }}</p>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.avgSpeed'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $velocity }} @lang('training.velocityUnit')</p>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.score'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $score }} </p>
          </div>
        </div>

        @unless(session('is_public') || session('cheated'))
        <div class="form-group">
          <label class="control-label col-sm-6">@lang('training.results.nickname'):</label>
          <div class="col-sm-3 text-left">
            <input type="text" class="form-control" name="nickname" value="{{ session('nickname') }}"
                  id="nickname" placeholder="@lang('training.results.nickname')">
          </div>
        </div>
        @endunless

        @if($errors->has('nickname'))
          <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
              <div class="alert alert-danger">@lang($errors->first('nickname'), ['max' => 30])</div>
            </div>
          </div>
        @endif


        <div class="text-right">
          @unless(session('is_public') || session('cheated'))
          <button type="submit" class="btn btn-default btn-main btn-continue">
            <span>@lang('training.results.publish')</span>
          </button>
          @endunless

          <a href="{{ url('/trial') }}" class="btn btn-default btn-main btn-continue">
            <span>@lang('info.repeat')</span>
          </a>
        </div>

      </form>


      <hr style="border-color: #d6d6d6;">

      <h1>@lang('training.results.ranking')</h1>

      @if(count($results) == 0)
        <p class="text-center">@lang('training.results.noData')</p>
      @else
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>@lang('training.results.place')</th>
            <th>@lang('training.results.nickname')</th>
            <th>@lang('training.results.avgSpeed') (@lang('training.velocityUnit'))</th>
            <th>@lang('training.results.amountCorrects')</th>
            <th>@lang('training.results.amountErrors')</th>
            <th>@lang('training.results.score')</th>
          </tr>
        </thead>
        <tbody>
          @foreach($results as $result)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $result->nickname }}</td>
            <td>{{ $result->velocity }}</td>
            <td>{{ $result->keystrokes }}</td>
            <td>{{ $result->errors }}</td>
            <td>{{ $result->score }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif

    </div>

  </div>
</div>

@endsection
