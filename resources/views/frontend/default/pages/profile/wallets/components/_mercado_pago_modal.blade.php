<div class="modal fade" id="MercadoPagoModal" tabindex="-1" role="dialog" aria-labelledby="mercadoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mercadoModalLabel">{{ __('wallet.mercado_pago_payment') }}</h5>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <section class="send_query bg-white contact_form">
                    <script src="https://sdk.mercadopago.com/js/v2"></script>
                    <script>
                        const mp = new MercadoPago("{{config('mercadopago.public_key')}}");
                    </script>

                    <form id="form-checkout">
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__cardNumber" class="mb-2">{{ __('payment_gatways.card_number') }} <span class="text-danger">*</span></label>
                                <input required  class="primary_input4 form-control mb_20" type="text"  name="cardNumber" id="form-checkout__cardNumber" />
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__cardExpirationDate" class="mb-2">{{ __('payment_gatways.card_expiration_date') }}  <span class="text-danger">*</span> </label>
                                <input required  class="primary_input4 form-control mb_20" type="text"  name="cardExpirationDate" id="form-checkout__cardExpirationDate" />
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__cardholderName" class="mb-2">{{ __('payment_gatways.cardholder_name') }}  <span class="text-danger">*</span> </label>
                                <input required class="primary_input4 form-control mb_20" type="text"  name="cardholderName" id="form-checkout__cardholderName"/>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__cardholderEmail" class="mb-2">{{ __('payment_gatways.cardholder_email') }}  <span class="text-danger">*</span> </label>
                                <input required  class="primary_input4 form-control mb_20" type="email"  name="cardholderEmail" id="form-checkout__cardholderEmail"/>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <label  for="form-checkout__securityCode" class="mb-2">{{ __('payment_gatways.security_code') }}  <span class="text-danger">*</span> </label>
                                <input required  class="primary_input4 form-control mb_20" type="text"  name="securityCode" id="form-checkout__securityCode" />
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__issuer" class="mb-2">{{ __('payment_gatways.issuer') }}  <span class="text-danger">*</span> </label>
                                <select required class="primary_select form-control mb_20" name="issuer" value="rubel" id="form-checkout__issuer"></select>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__identificationType" class="mb-2">{{ __('payment_gatways.identification_type') }}  <span class="text-danger">*</span> </label>
                                <select required class="primary_select form-control mb_20" name="identificationType"  id="form-checkout__identificationType"></select>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__identificationNumber" class="mb-2">{{ __('payment_gatways.identification_number') }}  <span class="text-danger">*</span> </label>
                                <input required class="primary_input4 form-control mb_20" type="text" name="identificationNumber"  id="form-checkout__identificationNumber"/>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <label for="form-checkout__installments" class="mb-2">{{ __('payment_gatways.installments') }}  <span class="text-danger">*</span> </label>
                                <select required class="primary_select form-control mb_20" name="installments" id="form-checkout__installments"></select>

                            </div>
                            <div class="col-xl-6 col-md-6 d-none">
{{--                                <button type="submit" id="form-checkout__submit">Pagar</button>--}}
                                <progress value="0" class="progress-bar">Cargando...</progress>
                            </div>

                            <div class="col-xl-12 col-md-12">
                                <div class="send_query_btn d-flex justify-content-between mt-4">
                                    <button type="button" class="btn_1" data-dismiss="modal">{{ __('common.cancel') }}</button>
                                    <button  class="btn_1" type="submit" id="form-checkout__submit">{{ __('common.submit') }}</button>
                                </div>
                            </div>
                        </div>

                    </form>
                    <script>
                        // Step #3
                        const cardForm = mp.cardForm({
                            amount: "{{$recharge_amount}}",
                            autoMount: true,
                            form: {
                                id: "form-checkout",
                                cardholderName: {
                                    id: "form-checkout__cardholderName",
                                    //placeholder: "cardholderName",
                                    placeholder: "Cardholder Name",
                                },
                                cardholderEmail: {
                                    id: "form-checkout__cardholderEmail",
                                    placeholder: "E-mail",
                                },
                                cardNumber: {
                                    id: "form-checkout__cardNumber",
                                    //placeholder: "Número de la tarjeta",
                                    placeholder: "Card Number",
                                },
                                cardExpirationDate: {
                                    id: "form-checkout__cardExpirationDate",
                                    //placeholder: "Data de vencimiento (MM/YYYY)",
                                    placeholder: "Card Expiration Date (MM/YYYY)",
                                },
                                securityCode: {
                                    id: "form-checkout__securityCode",
                                    //placeholder: "Código de seguridad",
                                    placeholder: "Security Code",
                                },
                                installments: {
                                    id: "form-checkout__installments",
                                    //placeholder: "Cuotas",
                                    placeholder: "Installments",
                                },
                                identificationType: {
                                    id: "form-checkout__identificationType",
                                    //placeholder: "Tipo de documento",
                                    placeholder: "IdentificationType",
                                },
                                identificationNumber: {
                                    id: "form-checkout__identificationNumber",
                                    //placeholder: "Número de documento",
                                    placeholder: "Identification Number",
                                },
                                issuer: {
                                    id: "form-checkout__issuer",
                                    //placeholder: "Banco emisor",
                                    placeholder: "Issuer",
                                },
                            },
                            callbacks: {
                                onFormMounted: error => {
                                    if (error)
                                        return console.warn("Form Mounted handling error: ", error);
                                },
                                onSubmit: event => {
                                    event.preventDefault();
                                    const {
                                        paymentMethodId: payment_method_id,
                                        issuerId: issuer_id,
                                        cardholderEmail: email,
                                        amount,
                                        token,
                                        installments,
                                        identificationNumber,
                                        identificationType,
                                    } = cardForm.getCardFormData();

                                    fetch("{{route('my-wallet.store')}}", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') //customize
                                        },
                                        body: JSON.stringify({
                                            token,
                                            issuer_id,
                                            payment_method_id,
                                            method:'MercadoPago', //customize
                                            payment_type:'wallet_recharge', //customize
                                            transaction_amount: Number(amount),
                                            installments: Number(installments),
                                            description: "Descripción del producto",
                                            payer: {
                                                email,
                                                identification: {
                                                    type: identificationType,
                                                    number: identificationNumber,
                                                },
                                            },
                                        }),
                                    }).then((response) => response.json())
                                        //Then with the data from the response in JSON...
                                        .then((data) => {
                                            $('#pre-loader').hide();
                                            location.replace(data.target_url);
                                        })
                                        .catch((error) => {
                                            $('#pre-loader').hide();
                                        });

                                    $('#MercadoPagoModal').modal('hide');
                                },
                                onFetching: (resource) => {
                                    console.log(resource)
                                    console.log("Fetching resource: ", resource);
                                    // Animate progress bar
                                    const progressBar = document.querySelector(".progress-bar");
                                    progressBar.removeAttribute("value");

                                    return () => {
                                        progressBar.setAttribute("value", "0");
                                    };
                                }
                            },
                        });
                    </script>
                </section>
            </div>
        </div>
    </div>
</div>
