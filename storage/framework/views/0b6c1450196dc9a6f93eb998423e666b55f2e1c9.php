    <div class="row">
        <div class="col-md-12">
            <?php $__currentLoopData = $timeSheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $logs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h5 class="my-3"><?php echo e($dateFormat[$date]); ?></h5>
                <table class="table align-middle mb-0 bg-white">
                  <thead class="bg-light">
                    <tr>
                      <th><?php echo e(__('Project')); ?></th>
                      <th><?php echo e(__('Who')); ?></th>
                      <th><?php echo e(__('Description')); ?></th>
                      <th><?php echo e(__('Task List')); ?></th>
                      <th><?php echo e(__('Start')); ?></th>
                      <th><?php echo e(__('End')); ?></th>
                      <th><?php echo e(__('Billable')); ?></th>
                      <th><?php echo e(__('Time')); ?></th>
                      <th><?php echo e(__('Action')); ?></th>
                    </tr>
                  </thead>
                  <tbody id="entriesList">
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="ms-3">
                            <p><?php echo e($timeSheet['project_name'] ?? ''); ?></p>
                        </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="http://localhost/teamwork/storage/productimages/<?php echo e($timeSheet['avatar']); ?>" alt="" style="width: 45px; height: 45px" class="rounded-circle"
                          />
                      </div>
                      <div>
                        <p class="fw-bold mb-1"><?php echo e($timeSheet['user_name'] ?? ''); ?></p>
                      </div>
                  </td>
                  <td>
                    <p class="fw-normal mb-1">Task : <?php echo e($timeSheet['task_title'] ?? ''); ?></p>
                    <p class="text-muted mb-0"><?php echo e($timeSheet['remark'] ?? ''); ?></p>
                  </td>
                  <td>
                    <p class="fw-normal mb-1"><?php echo e($timeSheet['group_name'] ?? ''); ?></p>
                  </td>
                  <!-- <td>
                    <span class="badge badge-success rounded-pill d-inline">Active</span>
                  </td> -->
                  <td><?php echo e($timeSheet['start_time'] ?? ''); ?></td>
                  <td><?php echo e($timeSheet['end_time'] ?? ''); ?></td>
                  <td>
                    <?php if($timeSheet['billable'] == 'Yes'): ?>
                      <img src="<?php echo e(URL::asset('public/assets/images/icons/accept.png')); ?>" alt="Yes" height="20" width="20" style="margin-left: 20px;">
                    <?php else: ?>
                      <img src="<?php echo e(URL::asset('public/assets/images/icons/reject.png')); ?>" alt="No" height="20" width="20" style="margin-left: 20px;">
                    <?php endif; ?>
                  </td>
                  <td><?php echo e($timeSheet['total_hrs_mins'] ?? ''); ?></td>
                    <?php if(\Auth::user()->type!='client'): ?>
                        <td class="Action">
                            <div class="action-btn bg-info ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"  data-url="<?php echo e(route('task.timesheet.edit',[$timeSheet['id']])); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Time Sheet')); ?>" title="<?php echo e(__('Edit')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-size="md"><span class="text-white"><i class="ti ti-edit"></i></span></a>
                            </div>
                            <div class="action-btn bg-danger ms-2">
                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="<?php echo e(__('Are You Sure?')); ?>" data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="delete-form-<?php echo e($timeSheet['id']); ?>" title="<?php echo e(__('Delete')); ?>" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-trash"></i></span></a>
                            </div>                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['task.timesheet.destroy', $timeSheet['id']],'id'=>'delete-form-'.$timeSheet['id']]); ?>

                            <?php echo Form::close(); ?>

                        </td>
                    <?php else: ?>
                        <td><?php echo e($timeSheet['user_name'] ?? ''); ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
            <?php if( !empty($finalTime) ): ?>
              <p class="my-3" style="text-align: right"><strong>Total: </strong> <?php echo e((!empty($finalTime[$date]) ) ? floor($finalTime[$date] / 60).'h ' : ''); ?> <?php echo e((!empty($finalTime[$date])) ? (($finalTime[$date] - floor($finalTime[$date] / 60) * 60) > 0) ? ($finalTime[$date] - floor($finalTime[$date] / 60) * 60) . 'm' : '' : ''); ?> <strong>  Billable Time: </strong> <?php echo e((!empty($totalBillableTimeSum[$date])) ? floor($totalBillableTimeSum[$date] / 60).'h ' : '0h'); ?> <?php echo e((isset($totalBillableTimeSum[$date])) ? (($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) > 0) ? ($totalBillableTimeSum[$date] - floor($totalBillableTimeSum[$date] / 60) * 60) . 'm' : '' : ''); ?></p>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php /**PATH D:\wamp64\www\teamwork\resources\views/projects/timeSheetShowMore.blade.php ENDPATH**/ ?>