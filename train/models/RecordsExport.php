<?php namespace Samubra\Train\Models;
class RecordsExport extends \Backend\Models\ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $subscribers = Record::all();
        $subscribers->each(function($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }
}
