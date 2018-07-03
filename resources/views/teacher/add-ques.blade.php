@extends('layouts.default')
@section('css')
<style>
body {
  padding-top: 5rem;
  background-color: #e9ecef;
}
.bg-purple {
    background-color: #6f42c1;
}
</style>
@stop
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 ">
        <!-- New Add-Question Form -->
        <form action="{{$id}}" method="POST" class="">
            {{ csrf_field() }}
            @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
            @endif
            <!-- Question -->
            <div class="form-group">
                <label for="question" class="col-sm-3 control-label">Question 1.</label>
                <select name="quetype" class="form-control form-control-sm col-sm-2 col-md-2 float-right" required>
                    <option selected value="0">Single Correct</option>
                    <option value="1">Multiple Correct</option>
                </select>
                @if ($errors->has('question'))
                    <div class="alert alert-danger">
                        @foreach ($errors->get('question') as $ques)
                            <strong>{{$ques}}</strong>
                        @endforeach
                    </div>
                    @endif
                <div>
                    <textarea class="form-control" rows="5" id="question" name="question"></textarea>
                </div>
            </div>

            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="1" id="option1" name="option1">
                <label for="opt1" class="col-sm-3 control-label">Option 1.</label>
                @if ($errors->has('opt1'))
                    <div class="alert alert-danger">
                        @foreach ($errors->get('opt1') as $opt)
                                <strong>{{$opt}}</strong>
                        @endforeach
                        </div>
                @endif

                <div>
                    <textarea class="form-control col-sm-6" rows="5" id="opt1" name="opt1"></textarea>
                </div>
            </div>

            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" value="1" id="option2" name="option2">
                <label for="opt2" class="col-sm-3 control-label">Option 2.</label>
                @if ($errors->has('opt2'))
                    <div class="alert alert-danger">
                        @foreach ($errors->get('opt2') as $opt)
                                <strong>{{$opt}}</strong>
                        @endforeach
                        </div>
                @endif

               <div>
                    <textarea class="form-control col-sm-6" rows="5" id="opt2" name="opt2"></textarea>
                </div>
            </div>

            <div class="form-group form-check" id="add">
                <input type="hidden" name="count" value="2">
                
            </div>
      
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <a class="anchor btn-outline-primary" href="#" id="btn1" style="text-decoration: none;"> <i class="fa fa-plus"></i> Add Option </a>
                </div>
            </div>
            <script>
            	var count = 3;
            	$("#btn1").click(function(){
            	$("#add").append('<input type="hidden" name="count" value="'+count+'"><input class="form-check-input" type="checkbox" value="1" id="option'+count+'" name="option'+count+'"><label for="opt" class="col-sm-3 control-label">Option '+count+'.</label> <div> <textarea class="form-control col-sm-6" rows="5" id="opt'+count+'" name="opt'+count+'"></textarea> </div>');
            	count++;
            	    });
            </script>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-plus"></i> Add Task
                    </button>
                </div>
            </div>
        </form>
       <div>
        <center>
                    <a class="btn btn-success " href="{{url('/')}}" id="btn2" style="text-decoration: none;"> Submit </a>
                </center>
        </div>
    </div>
    
    </div>
                    
</div>
@stop

