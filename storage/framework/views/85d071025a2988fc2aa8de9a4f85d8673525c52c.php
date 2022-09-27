<?php if($paginator->hasPages()): ?>
    <div class="col-md-12 d-inline-block p-0 my-3">
            <nav>
            <ul class="pagination flex-wrap float-right "  id="ajax_pagination">
                    <?php if($paginator->onFirstPage()): ?>
                    <li class="page-item disabled">
                            <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>">
                             <i class="mdi mdi-chevron-left"></i>
                            </a>
                         </li>
                    
                <?php else: ?>
                   
                    <li class="page-item prevs_enbl">
                            <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">
                             <i class="mdi mdi-chevron-left"></i>
                            </a>
                         </li>
                    <?php endif; ?>


              <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              
              <?php if(is_string($element)): ?>
                  <li class="page-item disabled"><a class="page-link"><?php echo e($element); ?></a></li>
              <?php endif; ?>


              
              <?php if(is_array($element)): ?>
                  <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($page == $paginator->currentPage()): ?>
                          <li class="page-item active"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                      <?php else: ?>

                          <li class="page-item"><a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                      <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


          <?php if($paginator->hasMorePages()): ?>
          <li class="page-item arrow_right"><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"><i class="mdi mdi-chevron-right"></i></a></li>
          
      <?php else: ?>
      <li class="page-item arrow_right disabled"><a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel=""><i class="mdi mdi-chevron-right"></i></a></li>
          
      <?php endif; ?>

            </ul>
            </nav>
            </div>
<?php endif; ?>
<?php /**PATH /home/saipacker/public_html/resources/views/ajax_pagination.blade.php ENDPATH**/ ?>