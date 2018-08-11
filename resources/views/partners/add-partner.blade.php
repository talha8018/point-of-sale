
@extends('layouts.master')

@section('title')
Add Partner
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
			<div class="col-md-6 col-xs-12">
            <?php if(count($errors)>0): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php  foreach($errors ->all() as $e): ?>
                            <li>{{$e}}</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="post" action="{{url('partner/add')}}">
                {{ csrf_field() }}
                <label>Name</label>
                <input type="text" required="required" name="name" class="form-control" placeholder="Name">
                
                <br>
                <label>Phone</label>
                <input type="text" required="required" name="phone" class="form-control" placeholder="Phone">
                
                <br>
                <label>Address</label>
                <textarea class="form-control" required="required" rows="4" name="address" ></textarea>
                <br>
                <label>Type</label>
                <select name="type" id="" class="form-control" required="required">
                    <option value="">Select</option>
                    <option value="dealer">Dealer</option>
                    <option value="customer">Shopkeeper</option>
                </select>
                <br>
                <input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Add">
            </form>              
                </div>
                <div class="clearfix"></div>
            </div> 
        </div>
    </div>
</div>




@endsection

@section('css')
@endsection

@section('js')

@endsection