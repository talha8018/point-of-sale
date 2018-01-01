<?php

       $role = Auth::user()->role_id;
        $status = Auth::user()->status;

        if($role == '1' || $role == '2' )
        {
            
        }
        else
        {
die('Access Denied');
        }
    

?>
@extends('layouts.master')

@section('title')
Purchase Edit 
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
			
            <form action="/purchase/edit" method="post">
                {{csrf_field()}}
                <label for="">Edit Bill</label>
                <input type="number" name="bill_id" class="form-control" placeholder="Bill #">
                <br>
                <input type="submit" value="Edit Bill" class="btn btn-success">
            </form>
        </div>

        <div class="col-md-6">
			
            <form action="/purchase/delete" method="post">
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
