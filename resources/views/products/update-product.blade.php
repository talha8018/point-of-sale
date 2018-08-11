
@extends('layouts.master')

@section('title')
Products
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
			<div class="white-box whit-box-custom">
             <div class="col-md-12">
            @if(isset($error))
	            <div class="alert alert-danger alert-dismissable"><b> Error! </b> {{ $error }}</div>                    
            @endif
        </div>
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
               
				<form method="post" id="add-product" action="{{url('product/update')}}">
            		{{ csrf_field() }}
                    <label>Companies</label>
                    <select name="company_id"  required class="company_id form-control" required="required">
                        <option value="">Select</option>
                        @foreach($companies as $c)
                            <option value="{{$c['id']}}" <?php if(isset($input)){ echo $input['company_id']==$c['id']?'selected':''; } else{echo $products['company_id']==$c['id']?'selected':''; } ?> >{{$c['name']}}</option>
                        @endforeach
                    </select>
                    <br>
                    <input type="hidden" name="id" value="<?php if(isset($input)){echo $input['id'];} else {echo $products['id'];} ?>" id="id">
            		<label>Name</label>
            		<input type="text" name="name" value="<?php if(isset($input)){echo $input['name'];} else {echo $products['name'];} ?>" class="form-control" required="required" placeholder="Name">
            		<br>
                    <label>Barcode</label>
            		<input type="text" name="barcode" value="<?php if(isset($input)){echo $input['barcode'];} else {echo $products['barcode'];} ?>" required="required" id="barcode" class="form-control"  placeholder="Barcode">
            		<br>
            		<label>Description</label>
            		<textarea class="form-control"  name="description" required="required" rows="4"><?php if(isset($input)){echo $input['description'];} else {echo $products['description'];} ?></textarea>
            		<br>
            		<input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Update">
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