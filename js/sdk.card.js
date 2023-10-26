const mp = new MercadoPago("TEST-9d92970a-d6fb-4cbc-83e3-7d1af11e939f");

var valor = document.getElementById("totalVALUE").value;


const cardForm = mp.cardForm({
    amount: valor,
    iframe: true,
    form: {
    id: "form-checkout",
    cardNumber: {
        id: "form-checkout__cardNumber",
        placeholder: "Número do cartão",
    },
    expirationDate: {
        id: "form-checkout__expirationDate",
        placeholder: "MM/YY",
    },
    securityCode: {
        id: "form-checkout__securityCode",
        placeholder: "Código de segurança",
    },
    cardholderName: {
        id: "form-checkout__cardholderName",
        placeholder: "Titular do cartão",
    },
    issuer: {
        id: "form-checkout__issuer",
        placeholder: "Banco emissor",
    },
    installments: {
        id: "form-checkout__installments",
        placeholder: "Parcelas",
    },        
    identificationType: {
        id: "form-checkout__identificationType",
        placeholder: "Tipo de documento",
    },
    identificationNumber: {
        id: "form-checkout__identificationNumber",
        placeholder: "Número do documento",
    },
    cardholderEmail: {
        id: "form-checkout__cardholderEmail",
        placeholder: "E-mail",
    },
    },
    callbacks: {
    onFormMounted: error => {
        //if (error) return console.warn("Form Mounted handling error: ", error);
        //console.log("Form mounted");
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


        fetch("http://localhost/e-commerce/ajax/cardPay.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            token,
            issuer_id,
            payment_method_id,
            transaction_amount: Number(amount),
            installments: Number(installments),
            description: "e-commerce pagamento",
            payer: {
            email,
            identification: {
                type: identificationType,
                number: identificationNumber,
            },
            },
        }),
        
        });
    },
    },

    
});

