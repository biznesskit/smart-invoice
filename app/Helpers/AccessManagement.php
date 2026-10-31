<?php

namespace App\Helpers;

use App\Models\User;
use CreatePermissionTables;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccessManagement
{

  /**
   * Generate Roles
   */
  public static function generateRoles()
  {
    self::generateAllPermissions();

    $roles = [
      ['name' => 'super-admin', 'guard_name' => 'api', 'description' => 'Has access to the entire system', 'category' => ''],
      ['name' => 'admin', 'guard_name' => 'api', 'description' => 'can perform all functions in the system except delete account ', 'category' => ''],
      ['name' => 'supervisor', 'guard_name' => 'api', 'description' => 'can sell, and manage stock', 'category' => ''],
      ['name' => 'cashier', 'guard_name' => 'api', 'description' => 'can only sell and view orders', 'category' => ''],
    ];

    foreach ($roles as $role) {
      self::createRole($role);
    }
  }

  public static function createRole($role)
  {
    $newrole =  Role::updateOrCreate(['name' => $role['name']], $role);
    return $newrole;
  }

  public static function createRoleAssignUser($name, User $user)
  {
    // self::generatePermissions();
    $role = Role::where('name', $name)->first();

    if (!$role) $role = self::createRole($name);

    $user->assignRole($role);

    self::assignPermissionToUser($role, $user);
  }

  public static function userSyncRoles(User $user, String $name)
  {

    $role = Role::firstOrCreate(['name' => $name], ['name'=>$name, 'guard_name'=>'api']);

    $user->syncRoles([$role->name]);

    self::assignPermissionToUser($role, $user);
  }



  /**
   * Assign permission to roles
   */
  public static function assignPermissionToUser(Role $role, User $user)
  {
    switch ($role->name) {
      case 'super-admin':
        self::giveSuperAdminPermission($user);
        break;
      case 'admin':
        self::giveAdminPermission($user);
        break;
      case 'supervisor':
        self::giveSupervisorPermission($user);
        break;
      case 'casheir':
        self::giveCasheirPermission($user);
        break;
      case 'staff':
        self::giveStaffPermission($user);
        break;
     

      default:
        return 'Unknown role name. Permissions not granted';
        break;
    }
  }

  /**
   * Give Owner permissions
   */
  public static function giveSuperAdminPermission(User $user)
  {
    $permissions = [];

    foreach (self::getSuperAdminPermissions() as $item) $permissions[] = $item['name'];

    $user->syncPermissions($permissions);
  }

  /**
   * Give Manager permissions
   */
  public static function giveAdminPermission(User $user)
  {
    $permissions = [];
    $permissionsArr = [];

    foreach (self::getAdminPermissions() as $item) $permissions[] = $item['name'];

    foreach ($permissions as $name) {
      $permsn = Permission::firstOrCreate(['name'=>$name], ['name'=>$name, 'guard_name'=>'api']);
      $permissionsArr[]=$permsn->id;
    }

    $user->syncPermissions($permissions);
  }

  /**
   * Give Supervisor permissions
   */
  public static function giveSupervisorPermission(User $user)
  {

    $permissions = [];
    $permissionsArr = [];

    foreach (self::getSupervisorPermissions() as $item) $permissions[] = $item['name'];

    foreach ($permissions as $name) {
      $permsn = Permission::firstOrCreate(['name'=>$name], ['name'=>$name, 'guard_name'=>'api']);
      $permissionsArr[]=$permsn->id;
    }

    $user->syncPermissions($permissions);
  }

  /**
   * Give OtherStaff permissions
   */
  public static function giveCasheirPermission(User $user)
  {

    $permissions = [];
    $permissionsArr = [];

    foreach (self::getCashierPermissions() as $item) $permissions[] = $item['name'];

    foreach ($permissions as $name) {
      $permsn = Permission::firstOrCreate(['name'=>$name], ['name'=>$name, 'guard_name'=>'api']);
      $permissionsArr[]=$permsn->id;
    }

    $user->syncPermissions($permissions);

  }

  public static function giveStaffPermission(User $user)
  {
    $user->syncPermissions([]);
  }

  /**
   * Give Customer permissions
   */
  public static function giveCustomerPermission(User $user)
  {
    $user->syncPermissions([]);
  }


  /**
   * Genereate role permissions
   */
  public static function generateAllPermissions()
  {
    $allPermissions = self::getAllPermissions() ;

    foreach ($allPermissions as $permission) :
      Permission::updateOrCreate(['name' => $permission['name']], $permission);
    endforeach;
  }


  public static function getPosibleRoles(User $user)
  {
    if (empty($user)) return [];
    $userRoles = $user->roles()->pluck('name');
    $userRoles = count($userRoles) ? array_values($userRoles->toArray()) : [];

    if (in_array('super-admin', $userRoles)) return ['super-admin', 'admin', 'supervisor', 'cashier'];
    if (in_array('admin', $userRoles)) return ['admin', 'supervisor', 'cashier'];
    if (in_array('supervisor', $userRoles)) return ['supervisor', 'cashier'];
    return ['cashier'];
  }

  public static function canAccessAllBranchesData(User $user)
  {
    if ($user->hasRole(['super-admin', 'admin']))  return true;
    else return false;
  }

  public static function getAllPermissions()
  {
    return self::getSuperAdminPermissions();
  }


  public static function getSuperAdminPermissions()
  {

    return [
      ...self::getAdminPermissions(),
      ...self::getSupervisorPermissions(),
      ...self::getCashierPermissions(),
      ['name' => 'delete_account', 'guard_name' => 'api', 'description' => 'delete account', 'category' => 'account'],

      // add other permissions specifi to this role here.....
    ];
  }


  /**
   * Permissions assigned to a user with role admin by default
   */
  public static function getAdminPermissions()
  {

    return [
      ...self::getCashierPermissions(),
      ...self::getSupervisorPermissions(),
      ['name' => 'manage_integrations', 'guard_name' => 'api', 'description' => '', 'category' => 'integrations'],
      ['name' => 'create_user', 'guard_name' => 'api', 'description' => '', 'category' => 'users'],
      ['name' => 'view_users', 'guard_name' => 'api', 'description' => '', 'category' => 'users'],
      ['name' => 'edit_user', 'guard_name' => 'api', 'description' => '', 'category' => 'users'],
      ['name' =>  'delete_user', 'guard_name' => 'api', 'description' => '', 'category' => 'users'],
      ['name' => 'create_staff', 'guard_name' => 'api', 'description' => '', 'category' => 'staff'],
      ['name' => 'view_staffs', 'guard_name' => 'api', 'description' => '', 'category' => 'staff'],
      ['name' => 'edit_staff', 'guard_name' => 'api', 'description' => '', 'category' => 'staff'],

      ['name' =>  'delete_staff', 'guard_name' => 'api', 'description' => '', 'category' => 'staff'],
      ['name' =>  'delete_supplier', 'guard_name' => 'api', 'description' => '', 'category' => 'suppliers'],

      ['name' => 'create_product', 'guard_name' => 'api', 'description' => 'save new product', 'category' => 'stock'],

      //Accounting
      ['name' => 'view_statements', 'guard_name' => 'api', 'description' => '', 'category' => 'accounting'],
      ['name' => 'view_accounts', 'guard_name' => 'api', 'description' => '', 'category' => 'accounting'],
      ['name' => 'manage_accounts', 'guard_name' => 'api', 'description' => '', 'category' => 'accounting'],
      //Reports
      ['name' => 'view_reports', 'guard_name' => 'api', 'description' => '', 'category' => 'reports'],

      ['name' => 'clear_data', 'guard_name' => 'api', 'description' => '', 'category' => 'settings'],
      ['name' => 'edit_product', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],
      ['name' => 'delete_product', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],

      ['name' => 'view_settings', 'guard_name' => 'api', 'description' => '', 'category' => 'settings'],
      ['name' => 'edit_settings', 'guard_name' => 'api', 'description' => '', 'category' => 'settings'],
      ['name' => 'view_integrations', 'guard_name' => 'api', 'description' => '', 'category' => 'integrations'],
      ['name' => 'manage_integrations', 'guard_name' => 'api', 'description' => '', 'category' => 'integrations'],

      // add other permissions specific to this role here.....
      
    ];
  }


   /**
   * Permissions assigned to a user with role supervisor by default
   */
  public static function getSupervisorPermissions()
  {
    return [
      ['name' => 'manage_integrations', 'guard_name' => 'api', 'description' => '', 'category' => 'integrations'],
      ['name' => 'delete_customer', 'guard_name' => 'api', 'description' => '', 'category' => 'customers'],
      ['name' => 'create_supplier', 'guard_name' => 'api', 'description' => '', 'category' => 'suppliers'],
      ['name' => 'view_suppliers', 'guard_name' => 'api', 'description' => '', 'category' => 'suppliers'],
      ['name' => 'edit_supplier', 'guard_name' => 'api', 'description' => '', 'category' => 'suppliers'],
      ['name' =>  'delete_sale', 'guard_name' => 'api', 'description' => '', 'category' => 'sales'],
      ['name' => 'receive_stock', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],
      ['name' =>  'recount_stock', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],
      ['name' =>  'transfer_stock', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],
      ['name' => 'delete_quotation', 'guard_name' => 'api', 'description' => '', 'category' => 'quotations'],
      ['name' =>  'delete_invoice', 'guard_name' => 'api', 'description' => '', 'category' => 'invoices'],

      ...self::getCashierPermissions(),
      // add other permissions specific to this role here.....

    ];
  }


   /**
   * Permissions assigned to a user with role cashier by default
   */
  public static function getCashierPermissions()
  {
    return [
      ['name' => 'create_customer', 'guard_name' => 'api', 'description' => '', 'category' => 'customers'],
      ['name' => 'view_customers', 'guard_name' => 'api', 'description' => '', 'category' => 'customers'],
      ['name' => 'edit_customer', 'guard_name' => 'api', 'description' => '', 'category' => 'customers'],
      ['name' =>  'create_sale', 'guard_name' => 'api', 'description' => '', 'category' => 'checkout'],
      ['name' =>  'give_discount', 'guard_name' => 'api', 'description' => '', 'category' => 'checkout'],

      ['name' =>  'view_sales', 'guard_name' => 'api', 'description' => '', 'category' => 'sales'],
      ['name' =>  'edit_sale', 'guard_name' => 'api', 'description' => '', 'category' => 'sales'],
      ['name' =>  'issue_refund', 'guard_name' => 'api', 'description' => '', 'category' => 'sales'],
      ['name' => 'view_products', 'guard_name' => 'api', 'description' => '', 'category' => 'stock'],

      ['name' => 'create_quotation', 'guard_name' => 'api', 'description' => '', 'category' => 'quotations'],
      ['name' => 'view_quotations', 'guard_name' => 'api', 'description' => '', 'category' => 'quotations'],
      ['name' => 'edit_quotation', 'guard_name' => 'api', 'description' => '', 'category' => 'quotations'],

      ['name' =>  'create_invoice', 'guard_name' => 'api', 'description' => '', 'category' => 'invoices'],
      ['name' =>  'view_invoices', 'guard_name' => 'api', 'description' => '', 'category' => 'invoices'],
      ['name' => 'edit_invoice', 'guard_name' => 'api', 'description' => '', 'category' => 'invoices'],

      ['name' =>  'create_delivery_note', 'guard_name' => 'api', 'description' => '', 'category' => 'delivery notes'],
      ['name' =>  'view_delivery_notes', 'guard_name' => 'api', 'description' => '', 'category' => 'delivery notes'],
      ['name' => 'edit_delivery_note', 'guard_name' => 'api', 'description' => '', 'category' => 'delivery notes'],
      ['name' => 'delete_delivery_note', 'guard_name' => 'api', 'description' => '', 'category' => 'delivery notes'],
      ...self::getCommonPermissions()

      // add other permissions specific to this role here.....

    ];
  }


   /**
   * Permissions assigned to all users by default
   */
  public static function getCommonPermissions()
  {
    return [
      ['name' => 'reset_password', 'guard_name' => 'api', 'description' => '', 'category' => 'auth'],
      ['name' =>  'change_password', 'guard_name' => 'api', 'description' => '', 'category' => 'auth'],
      ['name' => 'edit_profile', 'guard_name' => 'api', 'description' => '', 'category' => 'profile'],
      // add other common permissions specific  here.....

    ];
  }
}
