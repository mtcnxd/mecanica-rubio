class ServiceItems {
    constructor(rutes) {
        this.rutes = rutes;
    }

    showMessageAlert(message, type = 'success') {
        return Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: 'Aceptar'
        });
    }

    getItems(criteria) {
        $.ajax({
            url: this.rutes.serviceItemsIndex,
            method: "GET",
            data: { criteria: criteria },
            success: (response) => {
                $("#resultListItems").empty();
                $("#resultListItems").show();

                response.data.forEach((item) => {
                    $("#resultListItems").append("<li onClick='serviceItems.selectItem(this)'>" + item + "</li>");
                })
            }
        });
    }

    selectItem(element) {
        let input = document.getElementById('item');
        input.value = element.textContent;
        $("#resultListItems").hide();
    }

    removeItem(id) {
        $.ajax({
            url: this.rutes.serviceItemsDestroy.replace(':id', id),
            method: 'DELETE',
            success: (response) => {
                this.showMessageAlert(response.message)
                    .then(() => {
                        location.reload()
                    });

            }
        });
    }

    addItem(data) {
        $.ajax({
            url: this.rutes.serviceItemsStore,
            method: 'POST',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(data),
            success: (response) => {
                console.log(response);

                if (response.success == false) {
                    serviceItems.showMessageAlert(response.message, 'error');
                    return;
                }

                location.reload();
            }
        });
    }

    setAsCompleted(serviceId) {
        $.ajax({
            url: this.rutes.serviceUpdate.replace(':id', serviceId),
            method: 'PUT',
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify({ id: serviceId }),
            success: (response) => {
                console.log(response);

                if (response.success == false) {
                    serviceItems.showMessageAlert(response.message, 'error');
                    return;
                }

                this.showMessageAlert(response.message)
                    .then(() => {
                        location.reload();
                    });
            }
        });
    }

    getPdf(serviceid) {
        $.ajax({
            url: this.rutes.servicePdf.replace(':id', serviceid),
            method: 'POST',
            data: {
                serviceid: serviceid
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function (response) {
                console.log(response)

                const blob = new Blob([response], { type: 'application/pdf' });
                const url = window.URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.href = url;
                a.download = 'invoice.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            },
            error: function (response) {
                console.log("An error ocurred when creating PDF");
                console.log(response);
            }
        });
    }
}

const serviceItems = new ServiceItems(rutes)

$("#setAsCompleted").on('click', function (event) {
    event.preventDefault();

    const serviceId = $("#service").val();

    if (confirm("El servicio se marcará como pagado. ¿Deseas continuar?")) {
        serviceItems.setAsCompleted(serviceId);
    }
});

$("#labour").on('change', function () {
    if ($(this).prop('checked')) {
        $("#amount").attr('disabled', 'disabled');
        $("#item").attr('disabled', 'disabled');
        $("#supplier").attr('disabled', 'disabled');
    } else {
        $("#amount").removeAttr('disabled');
        $("#item").removeAttr('disabled');
        $("#supplier").removeAttr('disabled');
    }
});

$("#addItemInvoice").on('click', function (event) {
    const data = {
        service: $("#service").val(),
        amount: $("#amount").val(),
        item: $("#item").val(),
        supplier: $("#supplier").val(),
        price: $("#price").val(),
        labour: $("#labour").prop('checked')
    }

    if (data.item.length < 3 && !data.labour) {
        $("#item").focus();
        return;
    }

    serviceItems.addItem(data);
});

$("#item").on('keyup', function () {
    if (this.value.length >= 3) {
        serviceItems.getItems(this.value);
    }
});

$(".removeItem").on('click', function (event) {
    event.preventDefault();
    const id = $(this).data('id');

    serviceItems.removeItem(id);
});

$("#getPdf").on('click', function (event) {
    event.preventDefault();
    const serviceId = $("#service").val();

    serviceItems.getPdf(serviceId);
});