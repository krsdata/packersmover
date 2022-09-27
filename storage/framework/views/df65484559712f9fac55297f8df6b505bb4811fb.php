
<?php $__env->startSection('dashcontent'); ?>

 

            <div class="row">
                <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Customer Details</div>
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-4">Name</dd>
								<dt class="col-sm-8"><?php echo e($order_data->name); ?></dt>
								<dd class="col-sm-4">Email</dd>
								<dt class="col-sm-8"><?php echo e($order_data->email); ?></dt>
							</dl>
							<dl class="row">
								
							</dl>
							<dl class="row">
								<dd class="col-sm-4">Contact</dd>
								<dt class="col-sm-8"><?php echo e($order_data->contact); ?></dt>
								<dd class="col-sm-4">Date</dd>
								<dt class="col-sm-8"><?php echo e($order_data->date); ?></dt>
							</dl>
							
							<hr>
							   
							</div>
						</div>
			        </div>

                    <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Enquiry Details</div>
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-4">Origin</dd>
								<dt class="col-sm-8"><?php echo e($order_data->origin); ?></dt>
								<dd class="col-sm-4">Origin Floor</dd>
								<dt class="col-sm-8"><?php echo e($order_data->origin_floor); ?></dt>
							</dl>
							<dl class="row">
								
							</dl>
							<dl class="row">
								<dd class="col-sm-4">Destination</dd>
								<dt class="col-sm-8"><?php echo e($order_data->destination); ?></dt>
								<dd class="col-sm-4">Destination Floor</dd>
								<dt class="col-sm-8"><?php echo e($order_data->destination_floor); ?></dt>
							</dl>
							
							<hr>
							   
							</div>
						</div>
			        </div>

            </div>
    
            
            <div class="row">
                <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Order Details</div>
							
						</div>
						<div class="card-body">
							
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ikey => $ival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							
						
							<dl class="row">
                               
                                <dd class="col-sm-4"><?php echo e($ival['name']); ?></dd>
								<dd class="col-sm-4">
								<?php echo e($ival['wraps']); ?>

								</dd>
								<dt class="col-sm-4"><?php echo e($ival['qty']); ?></dt>
								
							</dl>
							
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
							
							<hr>
							    
							</div>
						</div>
			        </div>

                    <div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="d-inline h4">Order Total</div>
							
						</div>
						<div class="card-body">
							<dl class="row">
								<dd class="col-sm-3">Packing</dd>
								<dt class="col-sm-3"><?php echo e($order_data->packing); ?></dt>
								<dd class="col-sm-3">Transport</dd>
								<dt class="col-sm-3"><?php echo e($order_data->transport); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Loading</dd>
								<dt class="col-sm-3"><?php echo e($order_data->loading); ?></dt>
								<dd class="col-sm-3">Unloading</dd>
								<dt class="col-sm-3"><?php echo e($order_data->unloading); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">UnPacking</dd>
								<dt class="col-sm-3"><?php echo e($order_data->unpacking); ?></dt>
								<dd class="col-sm-3">Ac</dd>
								<dt class="col-sm-3"><?php echo e($order_data->ac); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Local</dd>
								<dt class="col-sm-3"><?php echo e($order_data->local); ?></dt>
								<dd class="col-sm-3">Car Transport</dd>
								<dt class="col-sm-3"><?php echo e($order_data->car_transport); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Insurance</dd>
								<dt class="col-sm-3"><?php echo e($order_data->insurance); ?></dt>
								<dd class="col-sm-3">GST</dd>
								<dt class="col-sm-3"><?php echo e($order_data->gst); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Discount</dd>
								<dt class="col-sm-3"><?php echo e($order_data->discount); ?></dt>
								<dd class="col-sm-3">Transport gst</dd>
								<dt class="col-sm-3"><?php echo e($order_data->transport_gst); ?></dt>
							</dl>

                            <dl class="row">
								<dd class="col-sm-3">Sub Total</dd>
								<dt class="col-sm-3"><?php echo e($order_data->sub_total); ?></dt>
							</dl>
							
							<hr>
							    
							</div>
						</div>
			        </div>
                    
            </div>
				
			<!-- End of Order Details -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"> 
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/app/resources/views/pages/admin/order/order_detail.blade.php ENDPATH**/ ?>