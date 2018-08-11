
@extends('layouts.master')

@section('title')
Sale
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @if(isset($error))
	            <div class="alert alert-danger alert-dismissable"><b> Error! </b> {{ $error }}</div>                    
            @endif
            @if ( session()->has('message') )
	                <div class="alert alert-success alert-dismissable"><b> Success! </b> {{ session()->get('message') }}</div>
	            @endif
        </div>
        <div class="col-md-6">
			
            <form action="/sale/edit" method="post">
                {{csrf_field()}}
                <label for="">Edit Bill</label>
                <input type="number" name="bill_id" class="form-control" placeholder="Bill #">
                <br>
                <input type="submit" value="Edit Bill" class="btn btn-success">
            </form>
        </div>

        <div class="col-md-6">
			
            <form action="/sale/delete" method="post">
                {{csrf_field()}}
                <label for="">Delete Bill</label>
                <input type="number" name="bill_id" class="form-control" placeholder="Bill #">
                <br>
                <input type="submit" value="Delete Bill" class="btn btn-success">
            </form>
        </div>

    </div>
</div>






@endsection

@section('css')
@endsection

@section('js')
@endsection