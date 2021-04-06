<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CRUD Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in CRUD operations throughout the
    | system.
    | Regardless where it is placed, a CRUD label can be listed here so it is easily
    | found in a intuitive way.
    |
    */

    'actions' => 'Actions',
    'permissions' => [
        'name' => 'Name',
        'permission' => 'Permission',
        'dependencies' => 'Dependencies',
        'roles' => 'Roles',
        'system' => 'System?',
        'total' => 'permission(s) total',
        'users' => 'Users',
        'group' => 'Group',
        'group-sort' => 'Group Sort',
        'groups' => [
            'name' => 'Group Name',
        ],
    ],
    'roles' => [
        'number_of_users' => '# Users',
        'permissions' => 'Permissions',
        'role' => 'Role',
        'login_destination' => 'Login Destination',
        'total' => 'role(s) total',
        'sort' => 'Sort',
    ],
    'users' => [
        'confirmed' => 'Confirmed',
        'created' => 'Created',
        'delete_permanently' => 'Delete Permanently',
        'email' => 'E-mail',
        'id' => 'ID',
        'last_updated' => 'Last Updated',
        'name' => 'Name',
        'no_banned_users' => 'No Banned Users',
        'no_deactivated_users' => 'No Deactivated Users',
        'no_deleted_users' => 'No Deleted Users',
        'other_permissions' => 'Other Permissions',
        'restore_user' => 'Restore User',
        'roles' => 'Roles',
        'total' => 'user(s) total',
        'photo' => 'Photo Profile',
        'username' => 'Username',
    ],

    /*
    |--------------------------------------------------------------------------
    | CRUD Language Lines outside view Files
    |--------------------------------------------------------------------------
    |
    | These lines are being marked as obsolete by the localization helper
    | because they will only be found outside view files.
    |
    */
    'activate_user_button' => 'Activate User',
    'ban_user_button' => 'Ban User',
    'change_password_button' => 'Change Password',
    'deactivate_user_button' => 'Deactivate User',
    'delete_button' => 'Delete',
    'edit_button' => 'Edit',
    'alert_delete' => 'Are you sure you want to delete this item?',
    'alert_batch_delete' => 'Are you sure you want to delete this item(s)?',
    'create_button' => 'Create',
    'add_new_button' => 'Add New',
    
    'menu' => [
        'title' => 'Title',
        'link' => 'Link',
        'icon' => 'Icon',
        'target' => 'Target',
        'group' => 'Group',
        'parent' => 'Parent Menu',
        'permission' => 'Permission',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'lang' => 'Language Key'
    ],
    
    'settings' => [
        'site_name' => 'Site Name',
        'default_per_page' => 'Default Per Page',
        'debug' => 'Show Debug?',
        'driver' => 'Driver',
        'host' => 'Host',
        'port' => 'Port',
        'email_address' => 'Email Address',
        'email_name' => 'Email Name',
        'username' => 'Username',
        'password' => 'Password',
        'default_per_page_desc' => 'When viewing records, how many items should be listed at a time?',
        'debug_desc' => 'Show debug when there are error from development mode?',
        'password_desc' => "Leave it blank, if you don't want to update password.",
        'profiler' => 'Show Profiler?',
    ],
    
    'languages' => [
        'code' => 'Code',
        'lang' => 'Lang',
        'name' => 'Name',
        'flag' => 'Flag',
        'create_group' => 'Create Group',
        'edit_group' => 'Edit Group',
        'group_created' => 'The group was successfully created.',
        'group_updated' => 'The group was successfully updated.',
        'group_deleted' => 'The group was successfully deleted.',
        'group_create_failed' => 'There was a problem creating group. Please try again.',
        'group_update_failed' => 'There was a problem updating this group. Please try again.',
        'group_delete_failed' => 'There was a problem deleting this group. Please try again.',
        'group_no_exist' => 'That group does not exist.',
        'image_invalid' => 'Image invalid.',
        
        'lang_name' => 'Name',
        'path' => 'Path',
        'type' => 'Type',
        'module_name' => 'Module Name',
        'create_lang' => 'Create Language',
        'edit_lang' => 'Edit Language',
        'lang_created' => 'The language was successfully created.',
        'lang_updated' => 'The language was successfully updated.',
        'lang_deleted' => 'The language was successfully deleted.',
        'lang_create_failed' => 'There was a problem creating language. Please try again.',
        'lang_update_failed' => 'There was a problem updating this language. Please try again.',
        'lang_delete_failed' => 'There was a problem deleting this language. Please try again.',
        'lang_no_exist' => 'That language does not exist.',
        
        'key' => 'Key',
    ],
];