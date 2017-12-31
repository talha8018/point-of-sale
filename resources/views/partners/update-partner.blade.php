@extends('layouts.master')

@section('title')
Update Partner
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

                <form method="post" action="{{url('partner/update')}}">
            		{{ csrf_field() }}
            		<input type="hidden" name="id" value="{{$partner['id']}}" id="id">
            		<label>Name</label>
            		<input type="text" required="required" value="{{$partner['name']}}" name="name" id="name" class="form-control" placeholder="Name">
            		<br>
                    <label>Phone</label>
            		<input type="text" required="required" id="phone" name="phone" value="{{$partner['phone']}}" class="form-control" placeholder="Phone">
            		   <br>
                <label>Address</label>
                <textarea class="form-control" required="required" rows="4" name="address" >{{$partner['address']}}</textarea>
                    <br>
            		<label>Type</label>
            		<select name="type" id="type" class="form-control" required="required">
                        <option value="">Select</option>
                        <option value="dealer" <?php echo $partner['type']=='dealer'?'selected':''; ?>>Dealer</option>
                        <option value="customer" <?php echo $partner['type']=='customer'?'selected':''; ?> >Shopkeeper</option>
                    </select>
            		<br>
            		<input type="submit" name="" class="btn btn-block btn-outline btn-primary" value="Update">
            	</form>             
                </div>
                <div class="clearfix"></div>
            </div> 
        </div>
    </div>
</div>


<!--add model-->





@endsection

@section('css')
@endsection

@section('js')

@endsection