class Payrolls {
    constructor(routes) {
        this.routes = routes;
    }

    selectEmployee(id) {
        $.ajax({
            url: this.routes.employeeSearch.replace(':id', id),
            method: 'GET',
            success: function (response) {
                console.log(response);
                $("#salary").val(response.data.salary);
                $("#email").val(response.data.email);
            }
        })
    }

    addItem(data) {
        $.ajax({
            url: this.routes.itemStore,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: (response) => {
                console.log(response);

                $("#table-body").empty();

                /*
                if (response.data) {
                    $.each(response.data, function (i, item) {
                        $("#table-body").append(
                            '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + item.concept + '</td>' +
                            '<td class="text-end">' + numeral(item.amount).format('$0,000.00') + '</td>' +
                            '<td><a href="#" class="removeButton" id=""><x-feathericon-trash-2 class="table-icon"/></a></td>' +
                            '</tr>'
                        );
                    });
                }
                */
            },
            error: (response) => {
                console.log(response.responseJSON);

                const errors = response.responseJSON.errors;

                $.each(errors, (k, error) => {
                    this.showSwalMessage(error.toString(), 'error');
                });
            }
        }).then(() => {
            location.reload();
        });
    }

    removeItem(id) {
        $.ajax({
            url: this.routes.itemDestroy.replace(':id', id),
            method: 'DELETE',
            success: (response) => {
                console.log(response);
                this.showSwalMessage(response.message, 'success');
            }
        }).then(() => {
            location.reload();
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
    const payrolls = new Payrolls(routes);

    $("#employee").on('change', function (event) {
        event.preventDefault();
        payrolls.selectEmployee(this.value);
    });

    $("#openModal").on('click', function (event) {
        event.preventDefault();
        openModal();
    });

    $(".removeButton").on('click', function (event) {
        event.preventDefault();
        payrolls.removeItem(this.id);
    });

    $('#acceptButton').on('click', function (event) {
        event.preventDefault();

        const data = {
            concept: $("#concept").val(),
            amount: $("#amount").val(),
            employee: $("#employee").val(),
            type: $("#type").val()
        };

        payrolls.addItem(data);

        closeModal();
    });

    $("#closeButton").on('click', function (event) {
        event.preventDefault();
        closeModal();
    });

    $('#overlay').on('click', function (event) {
        event.preventDefault();
        closeModal();
    });

    function openModal() {
        $('#popup').fadeIn();
        $('#overlay').fadeIn();
    }

    function closeModal() {
        $('#popup').fadeOut();
        $('#overlay').fadeOut();
    }

});
