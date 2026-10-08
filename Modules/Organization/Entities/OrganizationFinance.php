<?php

namespace Modules\Organization\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrganizationFinance extends Model
{
    protected $guarded = ["id"];

    public static $debit = 'debit';
    public static $credit = 'credit';

 // description
    public static $course_sale_description = 'income course sell';
    public static $payout_description = 'payout amount';
    public static $deposit_description = 'deposit amount';

//payment status
    public static $payment_completed = 'completed';
    public static $payment_pending = 'pending';
    public static $payment_canceled = 'canceled';

 //data type
    public static $type_income = 'sell_income';
    public static $type_payout = 'payout';
    public static $type_deposit = 'deposit';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withDefault();
    }


    /**
     * this function responsible for total course sell income
     */
    public static function totalIncome()
    {
       return OrganizationFinance::where('user_id',Auth::id())->where('data_type',self::$type_income)->where('type',self::$credit)->sum('amount');

    }

    /**
     * this function responsible for ready to payout amount
     */
    public static function readyToPayout()
    {
        return OrganizationFinance::where('user_id',Auth::id())
            ->where('data_type',self::$type_income)
            ->where('type',self::$credit)
            ->where('status',true)
            ->where('payment_type',self::$payment_pending)
            ->sum('amount');

    }

    /**
     * this function responsible for request payout amount
     */
    public static function requestPayout()
    {
        return OrganizationFinance::where('user_id',Auth::id())
            ->where('data_type',self::$type_payout)
            ->where('payment_type',self::$payment_pending)
            ->sum('amount');
    }


    /**
     * this function responsible for total payout amount
     */
    public static function totalPayout()
    {
        return OrganizationFinance::where('user_id',Auth::id())
            ->where('data_type',self::$type_payout)
            ->where('payment_type',self::$payment_completed)
            ->sum('amount');
    }


}
