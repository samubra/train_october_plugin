<?php namespace Samubra\Train\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required',
    ];

    const PARENT_ID = 'parent_id';
    const NEST_LEFT = '_lft';
    const NEST_RIGHT = '_rgt';
    const NEST_DEPTH = 'depth';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_train_category';
}