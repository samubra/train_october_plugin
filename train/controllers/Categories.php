<?php namespace Samubra\Train\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Categories extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController'];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'category.manage' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Samubra.Train', 'main-menu-item', 'side-menu-item2');
    }
}