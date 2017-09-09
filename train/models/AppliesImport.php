<?php namespace Samubra\Train\Models;

class AppliesImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];


    public function importData($results, $sessionKey = null)
    {
      //var_dump($results);
        foreach ($results as $row => $data) {

            try {
                //Record::create($data);
                //var_dump($this->record_type);
                $subscriber = new Record;
                if(!isset($data['type_id']))
		{
			$data['type_id'] = $this->record_type;
		}
		if(!isset($data['is_valid'])){
                	$data['is_valid'] = $this->valid;
		}
		if(isset($data['remark']) && !is_array($data['remark']))
		{
			$data['remark'] = [$data['remark']];
		}
                $subscriber->fill($data);
                //$subscriber->type_id =
                $subscriber->save();
                trace_sql();

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }

    public function getRecordTypeOptions()
    {
      return Category::lists('name','id');

    }
}
