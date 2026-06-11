class Investment {
    constructor(routes) {
        this.routes = routes;
    }

    addItem(data) {
        console.log(data);

        $.ajax({
            url: this.routes.investmentItemStore,
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
            url: this.routes.investmentItemRemove.replace(':id', id),
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
            url: this.routes.investmentItemFiat,
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

    getTrades(table) {
        $.ajax({
            url: this.routes.investmentTrades,
            method: 'GET',
            success: (response) => {
                if (!response.success) {
                    return;
                }

                response.data.forEach((trade) => {
                    table.append("<tr>" +
                        "<td>" + trade.book + "</td>" +
                        "<td>" + trade.major + "</td>" +
                        "<td>" + trade.minor + "</td>" +
                        "<td>" + trade.major_currency + "</td>" +
                        "<td>" + trade.minor_currency + "</td>" +
                        "<td>" + trade.price + "</td>" +
                        "<td>" + trade.fees_amount + "</td>" +
                        "<td>" + trade.created_at + "</td>" +
                        "<td>" +
                        "<button class='add-trade btn btn-primary btn-sm'>Save</button>" +
                        "</td>" +
                        "</tr>");
                })
            },
            error: (response) => {
                console.log(response);
            }
        });
    }

    showSwalMessage(message, type = 'success') {
        return Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar',
        })
    }

}

$(document).ready(function () {
    const investment = new Investment(routes);
    const table = $("#trades tbody")

    investment.getTrades(table);

    $("#trades tbody").on('click', '.add-trade', function () {
        let row = $(this).closest('tr');

        const data = {
            book: row.find("td").eq(0).text(),
            major: row.find("td").eq(1).text(),
            price: row.find("td").eq(5).text(),
            fees_amount: row.find("td").eq(6).text(),
            amount: parseFloat(row.find("td").eq(1).text() - row.find("td").eq(6).text()).toFixed(8)
        }

        investment.addItem(data);
    });

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
});
