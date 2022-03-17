<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Modules\Bkash\Http\Controllers\BkashController;
use Modules\MercadoPago\Http\Controllers\MercadoPagoController;
use Modules\PaymentGateway\Http\Controllers\StripeController;
use Modules\PaymentGateway\Http\Controllers\RazorpayController;
use Modules\PaymentGateway\Http\Controllers\PayPalController;
use Modules\PaymentGateway\Http\Controllers\PaystackController;
use Modules\PaymentGateway\Http\Controllers\PaytmController;
use Modules\PaymentGateway\Http\Controllers\InstamojoController;
use Modules\PaymentGateway\Http\Controllers\BankPaymentController;
use Modules\PaymentGateway\Http\Controllers\MidtransController;
use Modules\PaymentGateway\Http\Controllers\JazzCashController;
use Modules\PaymentGateway\Http\Controllers\PayUmoneyController;
use Modules\PaymentGateway\Http\Controllers\FlutterwaveController;
use Modules\PaymentGateway\Services\PaymentGatewayService;
use Modules\Account\Repositories\TransactionRepository;
use Modules\Account\Entities\Transaction;
use Modules\SslCommerz\Http\Controllers\SslCommerzPaymentController;
use Modules\SslCommerz\Library\SslCommerz\SslCommerzNotification;
use Unicodeveloper\Paystack\Paystack;
use Modules\Wallet\Services\WalletService;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\Accounts;
use App\Traits\Notification;
use Modules\UserActivityLog\Traits\LogActivity;

class WalletController extends Controller
{
    use Accounts, Notification;
    protected $walletService;

    public function __construct(WalletService  $walletService)
    {
        $this->middleware('maintenance_mode');
        $this->walletService = $walletService;
    }


    public function index()
    {
        $data['users'] = $this->walletService->getAllUsers();
        return view('wallet::backend.admin.recharge_request_index', $data);
    }

    public function rechargeRequestGetData()
    {
        $transaction = $this->walletService->getAllRequests()->whereIn('payment_method', ['3','4','5','6','8','9','10','11','12','13','14']);
        return DataTables::of($transaction)
            ->addIndexColumn()
            ->addColumn('date', function ($transaction) {
                return date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at));
            })
            ->addColumn('email', function ($transaction) {
                return @$transaction->user->email;
            })
            ->addColumn('txn_id', function ($transaction) {
                return view('wallet::backend.admin.components._txn_id_td', compact('transaction'));
            })
            ->addColumn('amount', function ($transaction) {
                return single_price($transaction->amount);
            })
            ->addColumn('GatewayName', function ($transaction) {
                return $transaction->GatewayName;
            })
            ->addColumn('approval', function ($transaction) {
                return view('wallet::backend.admin.components._approval_td', compact('transaction'));
            })
            ->rawColumns(['txn_id', 'approval'])
            ->toJson();
    }

    public function BankRechargeIndex(){
        return view('wallet::backend.admin.bank_request_index');
    }

    public function bankRechargeRequestGetData()
    {
        $transaction = $this->walletService->getAllRequests()->where('payment_method', 'BankPayment');
        return DataTables::of($transaction)
            ->addIndexColumn()
            ->addColumn('date', function ($transaction) {
                return date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at));
            })
            ->addColumn('email', function ($transaction) {
                return @$transaction->user->email;
            })
            ->addColumn('txn_id', function ($transaction) {
                return view('wallet::backend.admin.components._txn_id_td', compact('transaction'));
            })
            ->addColumn('amount', function ($transaction) {
                return single_price($transaction->amount);
            })
            ->addColumn('GatewayName', function ($transaction) {
                return $transaction->GatewayName;
            })
            ->addColumn('approval', function ($transaction) {
                return view('wallet::backend.admin.components._approval_td', compact('transaction'));
            })
            ->rawColumns(['txn_id', 'approval'])
            ->toJson();
    }

    public function offline_index()
    {
        $data['users'] = $this->walletService->getAllUsers()->where('role_id', 4)->where('is_active', 1);

        return view('wallet::backend.admin.offline_index', $data);
    }

    public function offline_index_get_data()
    {
        $transaction = $this->walletService->getAllOfflineRecharge();
        return DataTables::of($transaction)
            ->addIndexColumn()
            ->addColumn('date', function ($transaction) {
                return date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at));
            })
            ->addColumn('email', function ($transaction) {
                return @$transaction->user->email;
            })
            ->addColumn('txn_id', function ($transaction) {
                return view('wallet::backend.admin.components._offline_txn_id_td', compact('transaction'));
            })
            ->addColumn('amount', function ($transaction) {
                return single_price($transaction->amount);
            })
            ->addColumn('payment_method', function ($transaction) {
                return $transaction->GatewayName;
            })
            ->addColumn('approval', function ($transaction) {
                return view('wallet::backend.admin.components._offline_approval_td', compact('transaction'));
            })
            ->addColumn('action', function ($transaction) {
                return view('wallet::backend.admin.components._offline_action_td', compact('transaction'));
            })
            ->rawColumns(['txn_id', 'approval', 'action'])
            ->toJson();
    }

    public function getUserByRole(Request $request){
        return $this->walletService->getAllUsers()->where('role_id', $request->role_id)->where('is_active', 1);
    }


    public function my_index()
    {
        $data['transactions'] = $this->walletService->getAll();
        if (auth()->user()->role->type != "customer") {
            return view('wallet::backend.seller.my_wallet_index');
        } else {
            return view(theme('pages.profile.wallets.my_wallet_index'), $data);
        }
    }

    public function my_index_get_data()
    {

        $transaction = $this->walletService->getAll();

        return DataTables::of($transaction)
            ->addIndexColumn()
            ->addColumn('date', function ($transaction) {
                return date(app('general_setting')->dateFormat->format, strtotime($transaction->created_at));
            })

            ->addColumn('amount', function ($transaction) {
                return single_price($transaction->amount);
            })
            ->addColumn('payment_method', function ($transaction) {
                return $transaction->GatewayName;
            })
            ->addColumn('approval', function ($transaction) {
                return view('wallet::backend.seller.components._approval_td', compact('transaction'));
            })
            ->rawColumns(['approval'])
            ->toJson();
    }


    public function create(Request $request, PaymentGatewayService $paymentGatewayService)
    {
        $data['payment_gateways'] = $paymentGatewayService->gateway_activations();
        $data['recharge_amount'] = $request->recharge_amount;
        $data['gateway_activations'] = $paymentGatewayService->gateway_active();
        if (auth()->user()->role->type != "customer") {
            return view('wallet::backend.seller.my_wallet_recharge_create', $data);
        } else {
            return view(theme('pages.profile.wallets.my_wallet_recharge_create'), $data);
        }
    }


    public function walletRecharge($amount, $method, $response)
    {
        $this->walletService->walletRecharge($amount, $method, $response);
        LogActivity::successLog('wallet recharge successful.');
    }

    public function store(Request $request)
    {
        session()->put('wallet_recharge', '1');
        if ($request->method == "Stripe") {
            $stripeController = new StripeController;
            $response = $stripeController->stripePost($request->all());
        }
        if ($request->method == "RazorPay") {
            $razorpayController = new RazorpayController;
            $response = $razorpayController->payment($request->all());
        }
        if ($request->method == "Paypal") {
            $paypalController = new PayPalController;
            $response = $paypalController->payment($request->all());
        }
        if ($request->method == "Paystack") {
            $paystack = new Paystack(env('PAYSTACK_SECRET'), env('PAYSTACK_PAYMENT_URL'));
            return $paystack->getAuthorizationUrl()->redirectNow();
        }
        if ($request->method == "BankPayment") {
            $bankController = new BankPaymentController;
            $response = $bankController->store($request->all());
        }
        if ($request->method == "PayTm") {
            $paytm = new PaytmController;
            return $paytm->payment($request->all());
        }
        if ($request->method == "Instamojo") {
            $instamojo = new InstamojoController;
            return $instamojo->paymentProcess($request->all());
        }
        if ($request->method == "Midtrans") {
            $midtrans = new MidtransController;
            return $midtrans->paymentProcess($request->all());
        }
        if ($request->method == "PayUMoney") {
            $PayUMoney = new PayUmoneyController;
            return $PayUMoney->payment($request->all());
        }
        if ($request->method == "JazzCash") {
            $JazzCash = new JazzCashController;
            return $JazzCash->paymentProcess($request->all());
        }
        if ($request->method == "flutterwave") {
            $flutterWaveController = new FlutterwaveController;
            return $flutterWaveController->payment($request->all());
        }
        if ($request->method == "Bkash") {
            $data['gateway_id'] = encrypt(15);
            $bkashController = new BkashController();
            $response = $bkashController->bkashSuccess($request->all());
        }

        if ($request->method == "SslCommerz") {

            $post_data = array();
            $post_data['total_amount'] = $request->amount; # You cant not pay less than 10
            $post_data['currency'] = "BDT";
            $post_data['tran_id'] = uniqid(); // tran_id must be unique

            # CUSTOMER INFORMATION
            $post_data['cus_name'] = 'Customer Name';
            $post_data['cus_email'] = 'customer@mail.com';
            $post_data['cus_add1'] = 'Customer Address';
            $post_data['cus_add2'] = "";
            $post_data['cus_city'] = "";
            $post_data['cus_state'] = "";
            $post_data['cus_postcode'] = "";
            $post_data['cus_country'] = "Bangladesh";
            $post_data['cus_phone'] = '8801XXXXXXXXX';
            $post_data['cus_fax'] = "";

            # SHIPMENT INFORMATION
            $post_data['ship_name'] = "Store Test";
            $post_data['ship_add1'] = "Dhaka";
            $post_data['ship_add2'] = "Dhaka";
            $post_data['ship_city'] = "Dhaka";
            $post_data['ship_state'] = "Dhaka";
            $post_data['ship_postcode'] = "1000";
            $post_data['ship_phone'] = "";
            $post_data['ship_country'] = "Bangladesh";

            $post_data['shipping_method'] = "NO";
            $post_data['product_name'] = "Computer";
            $post_data['product_category'] = "Goods";
            $post_data['product_profile'] = "physical-goods";

            # OPTIONAL PARAMETERS
            $post_data['value_a'] = "ref001";
            $post_data['value_b'] = "ref002";
            $post_data['value_c'] = "ref003";
            $post_data['value_d'] = "ref004";

            session(['ssl_payment_type' => $request->type]);
            $sslc = new SslCommerzNotification();
            $payment_options = $sslc->makePayment($post_data);
            $payment_options = \GuzzleHttp\json_decode($payment_options);
            if ($payment_options->status == "success") {
                return Redirect::to($payment_options->data);
            } else {
                return redirect()->route('my-wallet.index', auth()->user()->role->type);

            }
        }

        if ($request->method == "MercadoPago") {
            $mercadoPagoController = new MercadoPagoController();
            $response = $mercadoPagoController->payment($request->all());
            return $response;
        }

        Toastr::success(__('common.successful'), __('common.success'));

        LogActivity::successLog('wallet recharge successful.');
        return redirect()->route('my-wallet.index', auth()->user()->role->type);
    }

    public function offline_recharge_store(Request $request)
    {
        $this->walletService->walletOfflineRecharge($request->all());


        LogActivity::successLog('offline wallet recharge successful.');
        Toastr::success(__('common.successful'), __('common.success'));
        return redirect()->back();
    }

    public function offline_recharge_update(Request $request)
    {
        $this->walletService->walletOfflineRechargeUpdate($request->all());

        LogActivity::successLog('offline wallet recharge update successful.');
        Toastr::success(__('common.updated_successfully'), __('common.success'));
        return redirect()->route('wallet_recharge.offline_index');
    }



    public function recharge_status(Request $request)
    {
        try {
            $wallet = $this->walletService->findById($request->id);
            $wallet->status = $request->status;

            $defaultIncomeAccount = $this->defaultIncomeAccount();

            if ($wallet->save()) {
                if ($wallet->status == 1) {
                    $transactionRepo = new TransactionRepository(new Transaction);
                    $transactionRepo->makeTransaction("Wallet Recharge by customer", "in", $wallet->GatewayName, "wallet_recharge", $defaultIncomeAccount, "Wallet Recharge by customer", $wallet, $wallet->amount, \Carbon\Carbon::now()->format('Y-m-d'), auth()->id(), null, null);
                }


                LogActivity::successLog('wallet recharge status change successful.');
                return 1;
            }
            return 0;
        } catch (\Exception $e) {
            Toastr::error(__('common.operation_failed'));
            LogActivity::errorLog($e->getMessage());
            return 0;
        }
    }

    public function wallet_configuration()
    {
        try {
            $walletConfiguration = $this->walletService->getWalletConfiguration();
            return view('wallet::backend.admin.wallet_configuration', compact('walletConfiguration'));

        } catch (\Exception $e) {
            Toastr::error(__('common.operation_failed'));
            LogActivity::errorLog($e->getMessage());
            return back();
        }
    }

    public function wallet_configuration_update(Request $request)
    {
        try {
            $this->walletService->walletConfigurationUpdate($request);
            Toastr::success(__('common.updated_successfully'), __('common.success'));
            LogActivity::successLog('Wallet configuration updated.');
            return back();
        } catch (\Exception $e) {
            Toastr::error(__('common.operation_failed'));
            LogActivity::errorLog($e->getMessage());
            return back();
        }
    }



}
