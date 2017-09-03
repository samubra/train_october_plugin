<?php namespace Samubra\Train\Models;

class RecordsImport extends \Backend\Models\ImportModel
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
                $data['type_id'] = $this->record_type;;
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
