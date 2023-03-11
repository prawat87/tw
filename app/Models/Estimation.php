<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    protected $fillable = [
        'estimation_id',
        'client_id',
        'status',
        'issue_date',
        'discount',
        'tax_id',
        'terms',
        'created_by',
    ];

    public static $statues = [
        'Open',
        'Not Paid',
        'Partialy Paid',
        'Paid',
        'Cancelled',
    ];

    public function client()
    {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }

    public function tax()
    {
        return $this->hasOne('App\Models\Tax', 'id', 'tax_id');
    }

    public function getProducts()
    {
        return $this->hasMany('App\Models\EstimationProduct', 'estimation_id', 'id');
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        foreach($this->getProducts as $product)
        {
            $subTotal += $product->price * $product->quantity;
        }

        return $subTotal;
    }

    public function getTax()
    {
        if($this->getSubTotal() > 0)
        {
            $tax = (($this->getSubTotal() - $this->discount) * $this->tax->rate) / 100.00;
        }
        else
        {
            $tax = 0;
        }

        return $tax;
    }

    public function getTotal()
    {
        return $this->getSubTotal() - $this->discount + $this->getTax();
    }

    public function getDue()
    {
        $due = 0;
        foreach($this->payments as $payment)
        {
            $due += $payment->amount;
        }

        return $this->getTotal() - $due;
    }

    public static function getEstimationSummary($estimates)
    {
        $total = 0;

        foreach($estimates as $estimate)
        {
            $total += $estimate->getTotal();
        }

        return \Auth::user()->priceFormat($total);
    }



        public static function estimation_nm($estimation_name)
    {
        $taxArr  = explode(',', $estimation_name);
        $lead = 0;
        foreach($taxArr as $tax)
        {
            $tax     = Leads::find($tax);

            $lead = $tax->name;
        }

        return $lead;
    }





}
