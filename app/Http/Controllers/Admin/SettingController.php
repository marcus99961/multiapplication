<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Issue;
use App\Models\Issueitem;
use App\Models\Issueno;
use App\Models\Receive;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (! $settings) {
            return config('settings.default');
        }

        return $settings;
    }

    public function update()
    {
        $settings = request()->validate([
            'app_name' => ['required', 'string'],
            'date_format' => ['required', 'string'],
            'pagination_limit' => ['required', 'int', 'min:1', 'max:100'],
            'location_input'=>'',
            'clear_data'=>'',
            // 'dailyemail'=>'',
            // 'to'=> '',
            // 'update_mail'=> ''
        ]);
       
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
       
        
        Cache::flush('settings');
        if(setting('clear_data')=='true'){
            Receive::query()->truncate();
            Issue::query()->truncate();
            Issueitem::query()->truncate();
            Issueno::query()->truncate();
            Stock::query()->truncate();
            Transaction::query()->truncate();
            Invoice::query()->truncate();
        }
       

        return response()->json(['success' => true]);
    }
    public function truncate()
    {
       Receive::query()->truncate();
       Issue::query()->truncate();
       Issueitem::query()->truncate();
       Issueno::query()->truncate();
       Stock::query()->truncate();
       Transaction::query()->truncate();
       Invoice::query()->truncate();

        return response()->json(['success' => true]);
    }
}
