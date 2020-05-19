<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankTransactions extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_ledger';
}
