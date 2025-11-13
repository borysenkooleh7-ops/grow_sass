<?php

/**
 * @fileoverview Settings & Permissions Controller
 * @description Handles Settings & Permissions functionality including module permissions
 * and role-based access control
 */

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Permissions\IndexResponse;
use App\Http\Responses\Settings\Permissions\ModulesResponse;
use App\Http\Responses\Settings\Permissions\RolesResponse;
use App\Repositories\RoleRepository;
use App\Repositories\Modules\ModuleRolesRespository;
use Illuminate\Http\Request;
use Validator;

class Permissions extends Controller
{
    /**
     * The roles repository instance.
     */
    protected $rolesrepo;

    /**
     * The module roles repository instance.
     */
    protected $modulerepo;

    public function __construct(RoleRepository $rolesrepo, ModuleRolesRespository $modulerepo)
    {
        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

        $this->rolesrepo = $rolesrepo;
        $this->modulerepo = $modulerepo;
    }

    /**
     * Display the main Settings & Permissions page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //crumbs, page data & stats
        $page = $this->pageSettings();

        //get all team roles
        request()->merge([
            'filter_role_type' => 'team',
        ]);
        $roles = $this->rolesrepo->search();

        //get available modules
        $modules = $this->getAvailableModules();

        //response payload
        $payload = [
            'page' => $page,
            'roles' => $roles,
            'modules' => $modules,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Display the Available Modules page
     *
     * @return \Illuminate\Http\Response
     */
    public function modules()
    {
        //crumbs, page data & stats
        $page = $this->pageSettings('modules');

        //get all team roles
        request()->merge([
            'filter_role_type' => 'team',
        ]);
        $roles = $this->rolesrepo->search();

        //get available modules
        $modules = $this->getAvailableModules();

        //response payload
        $payload = [
            'page' => $page,
            'roles' => $roles,
            'modules' => $modules,
        ];

        //show the view
        return new ModulesResponse($payload);
    }

    /**
     * Display the Role Permissions page
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {
        //crumbs, page data & stats
        $page = $this->pageSettings('roles');

        //get all team roles
        request()->merge([
            'filter_role_type' => 'team',
        ]);
        $roles = $this->rolesrepo->search();

        //get available modules
        $modules = $this->getAvailableModules();

        //response payload
        $payload = [
            'page' => $page,
            'roles' => $roles,
            'modules' => $modules,
        ];

        //show the view
        return new RolesResponse($payload);
    }

    /**
     * Get available modules with their permissions
     *
     * @return array
     */
    private function getAvailableModules()
    {
        return [
            [
                'name' => 'Inbox',
                'alias' => 'inbox',
                'icon' => 'sl-icon-envelope',
                'description' => 'Message inbox and communication center',
                'permissions' => [
                    'access' => 'Can access inbox',
                    'manage' => 'Can manage all messages',
                    'reply' => 'Can reply to messages',
                    'view' => 'Read-only access'
                ]
            ]
        ];
    }

    /**
     * Basic page setting for this section of the app
     *
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = [])
    {
        $page = [
            'crumbs' => [
                __('lang.settings'),
                __('lang.settings_permissions'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => ' - ' . __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_permissions' => 'active',
        ];

        //modules section
        if ($section == 'modules') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.settings_permissions'),
                __('lang.available_modules'),
            ];
            $page['submenu_permissions_modules'] = 'active';
        }

        //roles section
        if ($section == 'roles') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.settings_permissions'),
                __('lang.role_permissions'),
            ];
            $page['submenu_permissions_roles'] = 'active';
        }

        return $page;
    }
}
