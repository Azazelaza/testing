if (!localStorage.getItem('token')) {
    location.href = "login.html";
}

function tableRefresh() {
    $.ajax({
        url: 'api/clientes.php',
        type: 'GET',
        data: {
            token: localStorage.getItem('token')
        },
        success: function (res) {
            $("#tabla-clientes tbody > tr").detach();
            let data = JSON.parse(res);
            $.each(data, function (index, cliente) {
                $('#tabla-clientes tbody').append('<tr>' +
                    '<td>' + cliente.cliente_id + '</td>' +
                    '<td>' + cliente.nombre + '</td>' +
                    '<td>' + cliente.apellido + '</td>' +
                    '<td>' + cliente.telefono + '</td>' +
                    '<td>' + cliente.email + '</td>' +
                    '<td>' + cliente.direccion + '</td>' +
                    '<td>' + cliente.ciudad + '</td>' +
                    '<td>' + cliente.estado + '</td>' +
                    '<td>' + cliente.codigo_postal + '</td>' +
                    `<td>
                     <button class="editField" data-obj='${JSON.stringify(cliente)}'>Editar</button>
                     <button class="deleteField" id="${cliente.cliente_id}">Eliminar</button>
                    </td>` +
                    '</tr>');
            });
            actionsTable();
        },
        error: function (xhr, status, error) {
            // Manejar errores de la solicitud AJAX
            console.error('Error en la solicitud AJAX: ' + error);
        }
    });
}
tableRefresh();

function actionsTable() {
    $(document).ready(function () {
        $('.editField').click((item) => {
            let data = JSON.parse(item.target.dataset.obj);
            let inputHidden = $('<input>').attr({
                type: 'hidden',
                id: "objectID",
                name: 'id',
                value: data.cliente_id
            });
            $('#modal').css('display', 'block');
            $('#nombre').val(data.nombre);
            $('#apellido').val(data.apellido);
            $('#email').val(data.email);
            $('#telefono').val(data.telefono);
            $('#direccion').val(data.direccion);
            $('#codigo_postal').val(data.codigo_postal);
            $('#estado').val(data.estado);
            $('#ciudad').val(data.ciudad);
            $('#client-form').append(inputHidden);
            $('#client-form').attr('data-action', 'update')
        })

        $('.deleteField').click((item) => {
            let id = $('.deleteField').attr('id');
            $.ajax({
                type: "DELETE",
                url: "api/clientes.php",
                data: JSON.stringify({
                    id: id
                }),
                contentType: "application/json"
            }).done(() => {
                Toast('Se elimino el registro', 'success');
                closeModal();
                tableRefresh();
            }).fail((err) => {
                console.error('error', err);
                Toast('Verifica los datos', 'error')
            })
        })
    });
}


$('.add-btn').click(() => {
    $('#modal').css('display', 'block');
});

function closeModal() {
    $('#client-form').attr('data-action', 'create')
    $('#modal').css('display', 'none');
    $('#objectID').remove();
    $('#nombre').val('');
    $('#apellido').val('');
    $('#email').val('');
    $('#telefono').val('');
    $('#direccion').val('');
    $('#codigo_postal').val('');
    $('#estado').val('');
    $('#ciudad').val('');
}

$('.close').click(() => {
    closeModal();
});

$(window).click((event) => {
    if (event.target == $('#modal')[0]) {
        closeModal();
    }
});

function Toast(title, icon) {
    Swal.fire({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: icon,
        title: title,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
}

function form(e) {
    e.preventDefault();
    let nombre = $('#nombre').val();
    let apellido = $('#apellido').val();
    let email = $('#email').val();
    let telefono = $('#telefono').val();
    let direccion = $('#direccion').val();
    let codigo_postal = $('#codigo_postal').val();
    let estado = $('#estado').val();
    let ciudad = $('#ciudad').val();
    let datas = {};
    if (nombre === '') {
        Toast('Ingresa el Nombre', 'error')
    }
    if (apellido === '') {
        Toast('Ingresa el Apellido', 'error')
    }
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        Toast('Ingresa el Email correctamente', 'error')
    }
    if (telefono !== '') {
        let telefonoPattern = /^\d{10}$/;
        if (!telefonoPattern.test(telefono)) {
            Toast('Telefono debe de ser de 10 digitos', 'error')
        }
    }
    datas = {
        nombre: nombre,
        apellido: apellido,
        email: email,
        telefono: telefono,
        direccion: direccion,
        codigo_postal: codigo_postal,
        ciudad: ciudad,
        estado: estado
    }
    let action = $('#client-form').data('action');
    console.log(action);
    if (action == 'update') {
        let id = $('#objectID').val();
        datas = { ...datas, id: id };
        $.ajax({
            type: "PUT",
            url: "api/clientes.php",
            data: JSON.stringify(datas),
            contentType: "application/json",
        }).done(() => {
            Toast('Se modifico correctamente', 'success');
            closeModal();
            window.location.reload();
        }).fail((err) => {
            console.error('error', err);
            Toast('Verifica los datos', 'error')
        })
    } else {
        $.ajax({
            type: "POST",
            url: "api/clientes.php",
            data: datas,
            contentType: "application/x-www-form-urlencoded",
        }).done(() => {
            Toast('Se agrego correctamente el cliente', 'success');
            closeModal();
            window.location.reload();
        }).fail((err) => {
            console.error('error', err);
            Toast('Verifica los datos', 'error')
        })

    }
}

$('#client-form').on('submit', (e) => {
    form(e);
})