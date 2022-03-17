<?php

use App\Models\Order;
use App\Traits\Accounts;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Account\Entities\Transaction;
use Modules\Account\Repositories\TransactionRepository;

class PreviousOrderAmountFix extends Migration
{
    use Accounts;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('orders')){
            $transected_orders = Transaction::where('morphable_type', Order::class)->where('come_from', 'sales_income')->where('come_from', 'sales_expense')->pluck('morphable_id')->toArray();
            $orders = Order::whereNotIn('id', $transected_orders)->where('is_confirmed', 1)->where('is_cancelled',0)->where('is_completed', 0)->get();
            $defaultIncomeAccount = $this->defaultIncomeAccount();
            $defaultGSTAccount = $this->defaultGSTAccount();
            $defaultSellerAccount = $this->defaultSellerAccount();
            $defaultProductTaxAccount = $this->defaultProductTaxAccount();
            $defaultSellerCommisionAccount = $this->defaultSellerCommisionAccount();
            $transactionRepo = new TransactionRepository(new Transaction());
            $total_package_amount = 0;
            foreach($orders as $order){

                $package = $order->packages->first();
                $revenue_amount = $package->products->sum('total_price') + $package->shipping_cost;
                $total_gst_amount = $package->gst_taxes->sum('amount');
                $total_product_tax_amount = $package->tax_amount;

                $transactionRepo->makeTransaction("Earning from Sales", "in", $order->GatewayName, "sales_income", $defaultIncomeAccount, "Product Sale", $order, $revenue_amount, Carbon::now()->format('Y-m-d'), 1, null, null);
                if ($total_product_tax_amount > 0) {
                    $transactionRepo->makeTransaction("Product Tax on Sale", "in", $order->GatewayName, "product_tax", $defaultProductTaxAccount, "ProductWise Tax Inhouse", $order, $total_product_tax_amount, Carbon::now()->format('Y-m-d'), 1, null, null);
                }
            }

            $orders = Order::where('is_confirmed', 1)->where('is_cancelled',0)->where('is_completed', 0)->get();
            foreach($orders as $order){
                Transaction::where('morphable_type', Order::class)->whereIn('come_from', ['sales_income','gst_tsx','product_tax','sales_expense'])->where('morphable_id', $order->id)->delete();
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
