<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Utility;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   
        $arrPermissions = [
                [
                'name' => 'manage user',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

                [
                'name' => 'create user',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name' =>  'edit user',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

                [
                'name' =>  'delete user',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' =>    'manage language',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

                [
                'name'  =>   'create language',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            [
                'name'  =>    'manage account',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>    'edit account',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  =>     'change password account',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>     'manage system settings',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>     'manage role',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>     'create role',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'edit role',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>     'delete role',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

                [
                'name'  =>       'manage permission',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  =>      'create permission',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  =>      'edit permission',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'delete permission',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'manage company settings',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'manage stripe settings',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            [
                'name'  =>       'manage lead stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  =>       'create lead stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'  =>      'edit lead stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>       'delete lead stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'manage project stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
                [
                'name'  =>      'create project stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'edit project stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'delete project stage',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'manage lead source',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'create lead source',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'edit lead source',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'delete lead source',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage label',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
            
            [
                'name'  =>        'create label',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'edit label',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'delete label',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage product unit',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                
                [
                'name'  =>      'create product unit',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'edit product unit',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
            
            [
                'name'  =>      'delete product unit',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
            
            [
                'name'  =>       'manage expense',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'create expense',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'edit expense',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'delete expense',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage client',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'create client',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'edit client',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'delete client',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'manage lead',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'create lead',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit lead',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],

                [
                'name'  =>       'delete lead',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'create project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'edit project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'delete project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
            
            [
                'name'  =>       'client permission project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'invite user project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'manage product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'create product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'edit product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'delete product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'show project',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'manage tax',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'create tax',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit tax',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'delete tax',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'manage invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>     'create invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'delete invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'show invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'manage expense category',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'create expense category',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit expense category',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>       'delete expense category',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'create payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'delete payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'manage invoice product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'create invoice product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'edit invoice product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'delete invoice product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'manage invoice payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>      'create invoice payment',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'manage task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>       'create task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>       'edit task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>       'delete task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>        'move task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>        'show task',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],                    
                [
                'name'  =>        'create checklist',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>         'edit checklist',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>       'create milestone',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>         'edit milestone',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>        'delete milestone',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>         'view milestone',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],  
                [
                'name'  =>        'manage change password',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>         'manage plan',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>        'create plan',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>        'edit plan',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>        'buy plan',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>       'manage note',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>   'create note',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>        'edit note',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'delete note',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>       'manage order',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'manage bug status',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],                      
            
            [
                'name'  =>       'create bug status',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>     'edit bug status',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>     'delete bug status',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'move bug status',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'manage bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'create bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'edit bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'  =>        'delete bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'move bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'manage timesheet',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>      'edit bug report',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'create timesheet',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'  =>     'edit timesheet',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'delete timesheet',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'manage coupon',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'create coupon',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'edit coupon',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'delete coupon',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'payment reminder invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'send invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'custom mail send invoice',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'manage business settings',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'manage estimations',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'create estimation',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'edit estimation',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'delete estimation',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'view estimation',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       => 'estimation add product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'estimation edit product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>   'estimation delete product',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>   'manage email templates',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'create email template',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'edit email template',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'on-off email template',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ], 
                [
                'name'       =>  'edit email template lang',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],                  
                [
                'name'       =>  'manage contracts',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                'name'       =>  'edit contract',
                'guard_name' => 'web',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name'       =>  'create contract',
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name'       =>  'delete contract',
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name'       =>  'create attachment',
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name'       =>  'store comment',
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name'       =>  'store note',
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
        ];

         Permission::insert($arrPermissions);

        // Super admin

        $superAdminRole        = Role::create(
            [
                'name' => 'super admin',
                'created_by' => 0,
            ]
        );
        $superAdminPermissions = [
          ['name' => 'manage user'],
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'delete user'],
            ['name' => 'manage language'],
            ['name' => 'create language'],
            ['name' => 'manage account'],
            ['name' => 'edit account'],
            ['name' => 'change password account'],
            ['name' => 'manage system settings'],
            ['name' => 'manage stripe settings'],
            ['name' => 'manage role'],
            ['name' => 'create role'],
            ['name' => 'edit role'],
            ['name' => 'delete role'],
            ['name' => 'manage permission'],
            ['name' => 'create permission'],
            ['name' => 'edit permission'],
            ['name' => 'delete permission'],
            ['name' => 'manage change password'],
            ['name' => 'manage plan'],
            ['name' => 'create plan'],
            ['name' => 'edit plan'],
            ['name' => 'manage order'],
            ['name' => 'manage coupon'],
            ['name' => 'create coupon'],
            ['name' => 'edit coupon'],
            ['name' => 'delete coupon'],
            ['name' => 'manage email templates'],
            ['name' => 'create email template'],
            ['name' => 'edit email template'],
            ['name' => 'edit email template lang'],
        ];
     

     $superAdminRole->givePermissionTo($superAdminPermissions);

        $superAdmin = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('1234'),
                'type' => 'super admin',
                'lang' => 'en',
                'avatar' => '',
                'created_by' => 0,
            ]
        );
        $superAdmin->assignRole($superAdminRole);
        $superAdmin->defaultEmail();

        // client
        $clientRole       = Role::create(
            [
                'name' => 'client',
                'created_by' => 0,
            ]
        );
        $clientPermission = [
            ['name' =>'manage account'],
            ['name' =>'edit account'],
            ['name' =>'change password account'],
            ['name' =>'show project'],
            ['name' =>'manage project'],
            ['name' =>'manage task'],
            ['name' =>'create task'],
            ['name' =>'move task'],
            ['name' =>'show task'],
            ['name' =>'create checklist'],
            ['name' =>'edit checklist'],
            ['name' =>'create milestone'],
            ['name' =>'edit milestone'],
            ['name' =>'delete milestone'],
            ['name' =>'view milestone'],
            ['name' =>'manage change password'],
            ['name' =>'manage note'],
            ['name' =>'create note'],
            ['name' =>'edit note'],
            ['name' =>'delete note'],
            ['name' =>'manage bug status'],
            ['name' =>'create bug status'],
            ['name' =>'edit bug status'],
            ['name' =>'delete bug status'],
            ['name' =>'move bug status'],
            ['name' =>'manage bug report'],
            ['name' =>'create bug report'],
            ['name' =>'edit bug report'],
            ['name' =>'delete bug report'],
            ['name' =>'move bug report'],
            ['name' =>'manage timesheet'],
            ['name' =>'create timesheet'],
            ['name' =>'edit timesheet'],
            ['name' =>'delete timesheet'],
            ['name' =>'custom mail send invoice'],
            ['name' =>'manage estimations'],
            ['name' =>'view estimation'],
            ['name' =>'manage contracts'],
            ['name' =>'create attachment'],
            ['name' =>'store comment'],
            ['name' =>'store note'],
        ];
          
        $clientRole->givePermissionTo($clientPermission);
        
        // company
        $companyRole        = Role::create(
            [
                'name' => 'company',
                'created_by' => $superAdmin->id,
            ]
        );
         $companyPermissions = [
            ['name' =>"manage user"],
            ['name' =>"create user"],
            ['name' =>"edit user"],
            ['name' =>"delete user"],
            ['name' =>"manage language"],
            ['name' =>"manage account"],
            ['name' =>"edit account"],
            ['name' =>"change password account"],
            ['name' =>"manage role"],
            ['name' =>"create role"],
            ['name' =>"edit role"],
            ['name' =>"delete role"],
            ['name' =>"manage company settings"],
            ['name' =>"manage lead stage"],
            ['name' =>"create lead stage"],
            ['name' =>"edit lead stage"],
            ['name' =>"delete lead stage"],
            ['name' =>"manage project stage"],
            ['name' =>"create project stage"],
            ['name' =>"edit project stage"],
            ['name' =>"delete project stage"],
            ['name' =>"manage lead source"],
            ['name' =>"create lead source"],
            ['name' =>"edit lead source"],
            ['name' =>"delete lead source"],
            ['name' =>"manage label"],
            ['name' =>"create label"],
            ['name' =>"edit label"],
            ['name' =>"delete label"],
            ['name' =>"manage product unit"],
            ['name' =>"create product unit"],
            ['name' =>"edit product unit"],
            ['name' =>"delete product unit"],
            ['name' =>"manage expense"],
            ['name' =>"create expense"],
            ['name' =>"edit expense"],
            ['name' =>"delete expense"],
            ['name' =>"manage client"],
            ['name' =>"create client"],
            ['name' =>"edit client"],
            ['name' =>"delete client"],
            ['name' =>"manage lead"],
            ['name' =>"create lead"],
            ['name' =>"edit lead"],
            ['name' =>"delete lead"],
            ['name' =>"manage project"],
            ['name' =>"create project"],
            ['name' =>"edit project"],
            ['name' =>"delete project"],
            ['name' =>'client permission project'],
            ['name' =>'invite user project'],
            ['name' =>"manage product"],
            ['name' =>"create product"],
            ['name' =>"edit product"],
            ['name' =>"delete product"],
            ['name' =>"show project"],
            ['name' =>"manage tax"],
            ['name' =>"create tax"],
            ['name' =>"edit tax"],
            ['name' =>"delete tax"],
            ['name' =>"manage invoice"],
            ['name' =>"create invoice"],
            ['name' =>"edit invoice"],
            ['name' =>"delete invoice"],
            ['name' =>"show invoice"],
            ['name' =>"manage expense category"],
            ['name' =>"create expense category"],
            ['name' =>"edit expense category"],
            ['name' =>"delete expense category"],
            ['name' =>"manage payment"],
            ['name' =>"create payment"],
            ['name' =>"edit payment"],
            ['name' =>"delete payment"],
            ['name' =>"manage invoice product"],
            ['name' =>"create invoice product"],
            ['name' =>"edit invoice product"],
            ['name' =>"delete invoice product"],
            ['name' =>"manage invoice payment"],
            ['name' =>"create invoice payment"],
            ['name' =>"manage task"],
            ['name' =>"create task"],
            ['name' =>"edit task"],
            ['name' =>"delete task"],
            ['name' =>"move task"],
            ['name' =>'show task'],
            ['name' =>"create checklist"],
            ['name' =>"edit checklist"],
            ['name' =>'create milestone'],
            ['name' =>'edit milestone'],
            ['name' =>'delete milestone'],
            ['name' =>'view milestone'],
            ['name' =>'manage change password'],
            ['name' =>'manage plan'],
            ['name' =>'buy plan'],
            ['name' =>'manage note'],
            ['name' =>'create note'],
            ['name' =>'edit note'],
            ['name' =>'delete note'],
            ['name' =>'manage bug status'],
            ['name' =>'create bug status'],
            ['name' =>'edit bug status'],
            ['name' =>'delete bug status'],
            ['name' =>'move bug status'],
            ['name' =>'manage bug report'],
            ['name' =>'create bug report'],
            ['name' =>'edit bug report'],
            ['name' =>'delete bug report'],
            ['name' =>'move bug report'],
            ['name' =>'manage timesheet'],
            ['name' =>'create timesheet'],
            ['name' =>'edit timesheet'],
            ['name' =>'delete timesheet'],
            ['name' =>'payment reminder invoice'],
            ['name' =>'send invoice'],
            ['name' =>'manage order'],
            ['name' =>'manage business settings'],
            ['name' =>'manage estimations'],
            ['name' =>'create estimation'],
            ['name' =>'edit estimation'],
            ['name' =>'delete estimation'],
            ['name' =>'view estimation'],
            ['name' =>'estimation add product'],
            ['name' =>'estimation edit product'],
            ['name' =>'estimation delete product'],
            ['name' =>'manage email templates'],
            ['name' =>'on-off email template'],
            ['name' => 'manage contracts'],
            ['name' => 'create attachment'],
            ['name' => 'create contract'],
            ['name' => 'edit contract'],
            ['name' => 'delete contract'],
            ['name' => 'store comment'],
            ['name' => 'store note'],
        ];

        // foreach($companyPermissions as $ap)
        // {
        //     $permission = Permission::findByName($ap);
        //     $companyRole->givePermissionTo($permission);
        // }

         $companyRole->givePermissionTo($companyPermissions);
         
          $company = User::create(
            [
                'name' => 'company',
                'email' => 'company@example.com',
                'password' => Hash::make('1234'),
                'type' => 'company',
                'lang' => 'en',
                'avatar' => '',
                'plan' => 1,
                'created_by' => $superAdmin->id,
            ]
        );

        $company->assignRole($companyRole);
        $company->makeEmployeeRole($company->id);
        $company->userDefaultData();

        $data = [
            ['name'=>'local_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'local_storage_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()]
        ];
        DB::table('settings')->insert($data);
        
    }
}
