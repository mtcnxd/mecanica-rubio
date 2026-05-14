class Investment {

    constructor(rutes) {
        this.rutes = rutes;
    }

    addItem(data) {
        console.log(data);

        $.ajax({
            url: this.rutes.investmentItemStore,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: (response) => {
                console.log(response);
                if (!response.success) {
                    this.showSwalMessage(response.message, 'error');
                }

                this.showSwalMessage(response.message)
                    .then(() => {
                        location.reload();
                    });

            },
            error: (response) => {
                console.log(response);
            }
        })
    }

    removeItem(id) {
        $.ajax({
            url: this.rutes.investmentItemRemove.replace(':id', id),
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ id: id }),
            success: (response) => {
                console.log(response);

                this.showSwalMessage(response.message, response.type)
                    .then(() => {
                        location.reload();
                    });
            }
        });
    }

    updateInstrumentBalance(data) {
        console.log(data);

        $.ajax({
            url: this.rutes.investmentInstrumentUpdate,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: (response) => {
                console.log(response);
                if (!response.success) {
                    this.showSwalMessage(response.message, 'error');
                }

                this.showSwalMessage(response.message);

            },
            error: (response) => {
                console.log(response);
            }
        })
    }

    showSwalMessage(message, type = 'success') {
        return Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar',
        })
    }

}

const investment = new Investment(rutes);

$("#update-fiat-balance").on('click', function (event) {
    event.preventDefault();

    const data = {
        'instrument': $("#investment_instrument").val(),
        'amount': $("#investment-amount").val(),
    };

    investment.updateInstrumentBalance(data);
});

$("#insert-item").on('click', function (event) {
    event.preventDefault();

    const data = {
        book: $("#book").val(),
        amount: $("#amount").val(),
        price: $("#price").val(),
    };

    investment.addItem(data);
});

$(".cancell-trade").on('click', function (event) {
    event.preventDefault();

    const id = $(this).data('id');
    investment.removeItem(id);
});